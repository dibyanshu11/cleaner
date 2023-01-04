<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BillingAddress;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'password',
        'image',
        'contact_number',
        'status',
        'email_verified_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'name',
    ];

    protected $with = ['UserDetails'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function UserDetails()

    {
        return $this->hasOne(UserDetails::class, 'user_id', 'id');
    }

    public function cleanerTeam()
    {
        return $this->hasMany(CleanerTeam::class, 'user_id', 'id');
    }

    public function cleanerHours()
    {
        return $this->hasMany(CleanerHours::class, 'users_id', 'id');
    }

    public function cleanerServices()
    {
        return $this->hasMany(CleanerServices::class, 'users_id', 'id');
    }


    public static function getDays()
    {
        return ['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
    }

    public function bankInfo()
    {
        return $this->hasOne(BankInfo::class, 'users_id', 'id');
    }

    public function billing_address()
    {
        return $this->hasOne(BillingAddress::class);
    }

    public function card()
    {
        return $this->hasOne(UserCard::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function hasCleanerSetHisServedLocations()
    {
        $userDetails = $this->UserDetails;
        $result      = $userDetails->serve_center_lat &&  $userDetails->serve_center_lng && $userDetails->serve_radius_in_meters;
        return $result;
    }

    /* Cleaner user function */
    public function isWithInRadius($lat, $lng)
    {

        $distanceInKm = getDistance(
            (float) $lat,
            (float) $lng,
            (float) $this->UserDetails->serve_center_lat,
            (float) $this->UserDetails->serve_center_lng,
        );


        $radiusInKm = convertMeters( $this->UserDetails->serve_radius_in_meters, "km" );
        return $distanceInKm <= $radiusInKm;
    }

    /* Customer user function */
    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'user_id', 'id');
    }

    /* Cleaner user function */

    public function cleanerReviews()
    {
        return $this->hasMany(Review::class, 'cleaner_id');
    }

    /* Customer user function */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /* cleaner user function */

    public function isEligibleForListing()
    {
        if ( $this->hasCleanerSetHisServedLocations() === false ) {
            return false;
        }

        if ( $this->cleanerHours->isEmpty() ){
            return false;
        }

        if ( $this->cleanerServices->where('status', '1')->isEmpty() ){
            return false;
        }

        return true;
    }
}
