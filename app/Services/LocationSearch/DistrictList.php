<?php

namespace App\Services\LocationSearch;

use App\Models\District;

class DistrictList implements LocationSearch
{

    static public function getDistrictButtons(): array
    {
        $districts = District::all();

        $districtButtons = [];
        foreach ($districts as $district) {
            $districtButtons[] = [$district->title];
        }

        return $districtButtons;
    }

    static function getSearchResult($location): array
    {
        return District::where('title', 'like', '%' . $location . '%')->firstOr(function () {
            return [];
        })->toArray();
    }

}
