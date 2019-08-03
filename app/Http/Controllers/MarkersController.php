<?php

namespace App\Http\Controllers;

use App\Marker;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\Geometry;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class MarkersController extends Controller
{

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'district' => 'required',
            'district' => 'required',
            'marker_type_id' => 'required|exists:marker_types,id',
        ]);

        $geoAttr = $request->validate([
            'lat' => 'required',
            'long' => 'required'
        ]);

        $attributes['created_by'] = auth()->id();
        $attributes['geometry'] = new Point($geoAttr['lat'], $geoAttr['long']);
        $marker = Marker::create($attributes);
        return response()->json($marker, 201);
    }
    /**
     * Returns all markers as a geojson feed
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoJson(Request $request)
    {
        $markers = Marker::all();

        $features = [];

        foreach ($markers as $marker) {
            $data = $marker->geometry->toJson();

            $geo = Geometry::jsonUnserialize(json_decode($data));

            $features[] = new Feature($geo, ['name' => $marker->name]);
        }

        $collection = new FeatureCollection($features);

        return response()->json($collection)->setCallback('geo_callback');
    }

    /**
     * Returns the closes neighbors to a given cordinate
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function neighbors(Request $request)
    {
        //        $lat = 73.4255247;
        //        $lon = -0.2971667;

        $lat = $request->get('lat');
        $lon = $request->get('long');

        if (empty($lat) || empty($lon)) {
            return response()->json(['message' => 'Query paramters :lat and :lon is required.'], 403);
        }

        // todo: this filter option for marker types need to be set

        $markers = Marker::whereRaw("MBRCONTAINS(
        LineString(
            POINT(? + .2 / 111.1, ? + .4 / (111.1 / COS(RADIANS(?)))),
            POINT(? - .2 / 111.1, ? - .4 / (111.1 / COS(RADIANS(?))))),
            geometry
        )", [$lon, $lat, $lon, $lon, $lat, $lon])
            ->orderByRaw("GLength(LineString(geometry, POINT(?, ?))) asc", [$lon, $lat])
            ->take(20)
            ->get();


        $features = [];

        foreach ($markers as $marker) {
            $data = $marker->geometry->toJson();

            $geo = Geometry::jsonUnserialize(json_decode($data));

            $features[] = new Feature($geo, ['name' => $marker->name]);
        }

        $collection = new FeatureCollection($features);

        return response()->json($collection);
    }

    public function locations(Request $request)
    {
        $keyword = $request->get('keyword');
        $markerCollections = Marker::where('name', 'like', $keyword . '%')->limit(10)->get()->map(function ($marker) {
            $data = $marker->geometry->toJson();
            $geo = Geometry::jsonUnserialize(json_decode($data));
            return new Feature($geo, [
                'name' => $marker->name,
                'address' => $marker->address,
                'district' => $marker->district,
                'type' => $marker->marker_type,
                'content' => $marker->content,
                'photos' => $marker->photos,
                'verified' => $marker->verified_at != null
            ]);
        });
        return response()->json($markerCollections);
    }
}
