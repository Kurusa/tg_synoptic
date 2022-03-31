<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model {

    protected $table = 'districts';

    protected $fillable = [
        'title',
        'selected_title',
    ];

    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(City::class);
    }

}
