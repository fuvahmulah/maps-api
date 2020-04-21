<?php

namespace App;

use GeoJson\Feature\Feature;
use GeoJson\Geometry\Geometry;
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
        'created_by',
        'tags'
    ];

    protected $spatialFields = [
        'geometry'
    ];

    protected $dates = [
        'verified_at',
    ];

    public function marker_type()
    {
        return $this->belongsTo(MarkerType::class);
    }

    public function content()
    {
        return $this->hasOne(Content::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function toFeature()
    {
        $geo = Geometry::jsonUnserialize(json_decode($this->getAttribute('geometry')->toJson()));

        return new Feature($geo, [
            'name' => $this->getAttribute('name'),
            'address' => $this->getAttribute('address'),
            'district' => $this->getAttribute('district'),
            'type' => $this->getRelationValue('marker_type'),
            'content' => $this->getRelationValue('content'),
            'photos' => $this->getRelationValue('photos'),
        ]);
    }
}
