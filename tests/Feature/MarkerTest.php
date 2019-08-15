<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
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

        $response = $this->postJson(
            'api/markers',
            $attributes
        );

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'type',
            'geometry' => [
                'type',
                'coordinates',
            ],
            'properties'
        ]);
    }

    /** @test */
    function it_validates_the_request_marker()
    {
        $this->passportSignIn();

        $this->postJson(
            'api/markers',
            factory(Marker::class)->raw(['name' => ''])
        )->assertJsonValidationErrors(['name']);


        $this->postJson(
            'api/markers',
            factory(Marker::class)->raw(['district' => ''])
        )->assertJsonValidationErrors(['district']);


        $this->postJson(
            'api/markers',
            factory(Marker::class)->raw(['address' => ''])
        )->assertJsonValidationErrors(['address']);

        $this->postJson(
            'api/markers',
            factory(Marker::class)->raw(['lat' => ''])
        )->assertJsonValidationErrors(['lat']);

        $this->postJson(
            'api/markers',
            factory(Marker::class)->raw(['long' => ''])
        )->assertJsonValidationErrors(['long']);
    }

    /** @test */
    public function it_returns_error_if_not_authenticated()
    {
        $markerType = factory(MarkerType::class)->create();
        $attributes = [
            'name' => 'Kedeyre Miskiy',
            'address' => 'Someplace',
            'lat' => 3.4,
            'long' =>  1.2,
            'district' => 'Dhadimagu',
            'marker_type_id' => $markerType->id
        ];

        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);

        $response = $this->postJson('api/markers', $attributes);

        $response->assertStatus(401);
    }
}
