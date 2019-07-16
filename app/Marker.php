<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Marker extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'name',
        'address',
        'type',
        'geometry',
        'district',
    ];

    protected $spatialFields = [
        'geometry'
    ];
}
