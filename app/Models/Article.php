<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class Article extends Model
{
    use HasFactory, SoftDeletes, HasSlug, LogsActivity;

    const MEDIA_CATEGORY_THUMBNAIL = 'ARTICLE_THUMBNAIL';
    const MEDIA_CATEGORY_BANNER = 'ARTICLE_BANNER';

    protected $guarded = ['id'];

    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logName = 'default';
    protected static $logOnlyDirty = true;

    public function tapActivity(Activity $activity, string $eventName)
    {
        $changes = $activity->changes();
        $logName = self::$logName;

        if ($eventName == "updated") {
            $logName = $eventName;
            $eventName = "update data article";

            if (array_key_exists('publish_date', $changes['attributes']) || array_key_exists('old', $changes['attributes'])) {
                if ($changes['attributes']['publish_date'] == null && $changes['old']['publish_date'] != null) {
                    $eventName = "unpublish article";
                } else {
                    $eventName = "publish article";
                }
            }
        }

        if ($eventName == "created") {
            $logName = $eventName;
            $eventName = "create new article";

            if ($changes['attributes']['publish_date'] != null) {
                $eventName = "publish article";
            } else {
                $eventName = "create draft article";
            }
        }

        if (array_key_exists('deleted_at', $changes['attributes'])) {
            if ($changes['attributes']['deleted_at'] != null) {
                $logName = "deleted";
                $eventName = "delete data article";
            }
        }

        $activity->log_name = $logName;
        $activity->description = $eventName;
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('article_title')
            ->saveSlugsTo('article_slug')
            ->slugsShouldBeNoLongerThan(255);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('publish_date');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category')->withDefault([
            'category_name' => ''
        ]);
    }

    public function thumbnail()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_CATEGORY_THUMBNAIL);
    }

    public function banner()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model_category', self::MEDIA_CATEGORY_BANNER);
    }

    public function related()
    {
        return $this->hasMany(Article::class, 'article_category', 'article_category')->limit(5)->orderBy('publish_date', 'desc');
    }
}
