<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity;

    const USER_ACTIVE = 1;
    const USER_INACTIVE = 0;

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
            $eventName = "update data user";
        }

        if ($eventName == "created") {
            $logName = $eventName;
            $eventName = "create new user";
        }

        if (array_key_exists('deleted_at', $changes['attributes'])) {
            if ($changes['attributes']['deleted_at'] != null) {
                $logName = "deleted";
                $eventName = "delete data user";
            }
        }

        $activity->log_name = $logName;
        $activity->description = $eventName;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserActiveAttribute()
    {
        return self::USER_ACTIVE;
    }

    public function getUserInactiveAttribute()
    {
        return self::USER_INACTIVE;
    }
}
