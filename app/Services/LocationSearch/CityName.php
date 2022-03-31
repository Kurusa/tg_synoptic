<?php

namespace App\Services\LocationSearch;

use App\Models\City;

class CityName implements LocationSearch
{

    static function getSearchResult($location): array
    {
        $suitableCities = City::where('title', 'like', '%' . $location . '%')->get();

        $cities = [];
        if ($suitableCities->count()) {
            foreach ($suitableCities as $city) {
                $cities[] = [
                    $city->title . ', ' . $city->district->title . ' область'
                ];
            }
        }

        return $cities;
    }

}
