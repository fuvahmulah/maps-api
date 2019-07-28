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
        'geometry',
        'district',
        'marker_type_id',
        'created_by'
    ];

    protected $spatialFields = [
        'geometry'
    ];

    protected $dates = [
        'verified_at',
    ];
}
