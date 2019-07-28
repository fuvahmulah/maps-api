<?php

use App\Marker;
use App\MarkerType;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $dir = resource_path('data/FOC_Address/');


        DB::table('markers')->truncate();
        DB::table('marker_types')->truncate();

        $markerType = MarkerType::create([
            'name' => 'House',
            'created_by' => 1
        ]);
        DB::table('users')->truncate();

        $districts = ['Dhadimagu', 'Dhiguvaandu', 'Hoadhadu', 'Maadhadu', 'Miskiymagu', 'Malegan', 'Funaadu', 'Dhoodigan'];

        foreach ($districts as $district) {

            $district_file = $dir . $district . '.geojson';
            $data = json_decode(file_get_contents($district_file), true);
            foreach ($data['features'] as $feature) {

                $this->command->info('adding address ' . $feature['properties']['Name']);

                $geo = \GeoJson\Geometry\Geometry::jsonUnserialize($feature['geometry']);

                $point = \Grimzy\LaravelMysqlSpatial\Types\Geometry::fromJson($geo);
                //                $point = \Grimzy\LaravelMysqlSpatial\Types\Geometry::fromJson(json_encode($feature['geometry']));

                Marker::create([
                    'district' => $district,
                    'name' => $feature['properties']['Name'],
                    'address' => $feature['properties']['Name'],
                    'geometry' => $point,
                    'marker_type_id' => $markerType->id,
                    'created_by' => 1,
                    'verified_at' => now(),
                    'verified_by' => 1
                ]);
            }
        }


        User::create([
            'name' => 'My Self',
            'email' => 'myself@myself.com',
            'username' => 'mysqlf',
            'password' => Hash::make('secret')
        ]);
    }
}
