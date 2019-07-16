<?php

namespace App\Http\Controllers;

use App\Marker;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\Geometry;
use Illuminate\Http\Request;

class MarkersController extends Controller
{
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
        $lon = $request->get('lon');

        if (empty($lat) || empty($lon)) {
            return response()->json(['message' => 'Query paramters :lat and :lon is required.'], 403);
        }

        // todo: this filter option for marker types need to be set

        $markers = Marker::whereRaw("MBRCONTAINS(
        LineString(
            POINT(? + .2 / 111.1, ? + .4 / (111.1 / COS(RADIANS(?)))),
            POINT(? - .2 / 111.1, ? - .4 / (111.1 / COS(RADIANS(?))))),
            geometry
        )", [$lat, $lon, $lat, $lat, $lon, $lat])
            ->orderByRaw("GLength(LineString(geometry, POINT(?, ?))) asc", [$lat, $lon])
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
}
