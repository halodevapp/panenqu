<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class CustomerCategory extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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
            $eventName = "update data customer kategori";
        }

        if ($eventName == "created") {
            $logName = $eventName;
            $eventName = "create new customer kategori";
        }

        if (array_key_exists('deleted_at', $changes['attributes'])) {
            if ($changes['attributes']['deleted_at'] != null) {
                $logName = "deleted";
                $eventName = "delete customer kategori";
            }
        }

        $activity->log_name = $logName;
        $activity->description = $eventName;
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, "customer_category");
    }
}
