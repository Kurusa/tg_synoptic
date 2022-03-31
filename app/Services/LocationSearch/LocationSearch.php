<?php

namespace App\Services\LocationSearch;

interface LocationSearch
{

    static function getSearchResult($location): array;

}
