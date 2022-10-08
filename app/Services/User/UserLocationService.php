<?php

namespace App\Services\User;

use App\Models\User\UserLocation;
use App\Services\Base\BaseService;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

/**
 * Class UserLocationService.
 */
class UserLocationService
{
    protected $base;
    public function __construct(BaseService $base){
        $this->base = $base;
    }

    public function userLocation(): UserLocation
    {
        return new UserLocation();
    }

    public function userLocationWithRelations()
    {
        return $this->userLocation()->with('user');
    }

    public function userLocationById($id){
        return $this->userLocationWithRelations()->findOrFail($id);
    }

    public function userLocationByUserId($userId){
        return $this->userLocationWithRelations()
            ->where('user_id', $userId)->first();
    }

    public function getLocationFromUserId($userId)
    {
        // Check if user location exists in database, if not get from ip
        $userLocation = $this->userLocation()->where('user_id', $userId)->first();
        if($userLocation){
            $location = $userLocation;
        }else{
            $location = $this->addUserLocationFromIp($userId);
        }
        return $location;
    }

    public function addUserLocationFromIp($userId){
        $ip = $this->base->getIp();
        $ipLocations = Location::get($ip);
        $ipLocationsArray = [];
        if(is_object($ipLocations)){
            foreach($ipLocations as $key => $value){
                $ipLocationsArray[$key] = $value;
            }
        }
        $ipLocationsArray['user_id'] = $userId;
        $ipLocationsArray['country_name'] = $ipLocations->countryName;
        $ipLocationsArray['country_code'] = $ipLocations->countryCode;
        $ipLocationsArray['city_name'] = $ipLocations->cityName;
        $ipLocationsArray['zip_code'] = $ipLocations->zipCode;

        return $this->userLocation()->create($ipLocationsArray);
    }

    public function updateUserLocationFromIp($userId)
    {
        $ip = $this->base->getIp();
        $ipLocations = Location::get($ip);
        $userLocation = $this->userLocationByUserId($userId);
        if($userLocation){
            $userLocation->update([
                'ip' => $ip,
                'country_name' => $ipLocations->countryName ?? null,
                'country_code' => $ipLocations->countryCode ?? null,
                'city_name' => $ipLocations->cityName ?? null,
                'zip_code' => $ipLocations->zipCode ?? null,
                'latitude' => $ipLocations->latitude ?? null,
                'longitude' => $ipLocations->longitude ?? null,
            ]);
        }

        return $userLocation;
    }
}
