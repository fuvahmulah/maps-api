<?php

namespace App;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Marker extends Model
{
    use SpatialTrait, HasTags;

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

    protected $dates = [
        'verified_at',
    ];
}
