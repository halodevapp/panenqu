<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class ArticleCategory extends Model
{
    use HasFactory, SoftDeletes, HasSlug, LogsActivity;

    protected $guarded = ['id'];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('category_name')
            ->saveSlugsTo('category_slug')
            ->slugsShouldBeNoLongerThan(255);
    }

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
            $eventName = "update data artikel kategori";
        }

        if ($eventName == "created") {
            $logName = $eventName;
            $eventName = "create new artikel kategori";
        }

        if (array_key_exists('deleted_at', $changes['attributes'])) {
            if ($changes['attributes']['deleted_at'] != null) {
                $logName = "deleted";
                $eventName = "delete artikel kategori";
            }
        }

        $activity->log_name = $logName;
        $activity->description = $eventName;
    }
}
