<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Marker;
use App\MarkerType;

class MarkerTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    function it_saves_a_valid_marker_request()
    {
        $this->passportSignIn();

        $markerType = factory(MarkerType::class)->create();
        $attributes = [
            'name' => 'Kedeyre Miskiy',
            'address' => 'Someplace',
            'lat' => 3.4,
            'long' =>  1.2,
            'district' => 'Dhadimagu',
            'marker_type_id' => $markerType->id
        ];

        // $this->withoutExceptionHandling();

        $this->post(
            'api/markers',
            $attributes
        )->assertStatus(201);
    }



    /** @test */
    function it_validates_the_request_marker()
    {
        $this->passportSignIn();

        $this->post(
            'api/markers',
            factory(Marker::class)->raw(['name' => ''])
        )->assertSessionHasErrors(['name'], $format = null, $errorBag = 'default');


        $this->post(
            'api/markers',
            factory(Marker::class)->raw(['district' => ''])
        )->assertSessionHasErrors(['district'], $format = null, $errorBag = 'default');


        $this->post(
            'api/markers',
            factory(Marker::class)->raw(['address' => ''])
        )->assertSessionHasErrors(['address'], $format = null, $errorBag = 'default');

        $this->post(
            'api/markers',
            factory(Marker::class)->raw(['lat' => ''])
        )->assertSessionHasErrors(['lat'], $format = null, $errorBag = 'default');

        $this->post(
            'api/markers',
            factory(Marker::class)->raw(['long' => ''])
        )->assertSessionHasErrors(['long'], $format = null, $errorBag = 'default');
    }
}
