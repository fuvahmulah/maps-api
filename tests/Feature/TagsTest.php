<?php

namespace Tests\Feature;

use App\MarkerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Tags\Tag;
use Tests\TestCase;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    protected function createTags()
    {
        Tag::create(['name' => 'school']);
        Tag::create(['name' => 'government building']);
        Tag::create(['name' => 'guest house']);
    }

    /** @test */
    public function it_creates_tags_if_tag_does_not_exists()
    {
        $this->passportSignIn();

        $markerType = factory(MarkerType::class)->create();

        $attributes = [
            'name' => 'Kedeyre Miskiy',
            'address' => 'Someplace',
            'lat' => 3.4,
            'long' =>  1.2,
            'district' => 'Dhadimagu',
            'marker_type_id' => $markerType->id,
            'tags' => ['mosque']
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

        $tag = Tag::findFromString("mosque");

        $this->assertNotNull($tag);
    }

    /** @test */
    public function it_returns_valid_response_on_tags_index()
    {
        $this->passportSignIn();

        $this->createTags();


        $response = $this->get('/api/tags');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                [
                'id',
                'name' => [
                    'en'
                ],
                'slug' => [
                    'en'
                ],
                'type',
                'order_column'
                ]
            ]
        ]);

        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_returns_valid_response_on_tags_search()
    {
        $this->passportSignIn();

        $this->createTags();

        $response = $this->get('/api/tags/government');

        $response->assertStatus(200);

        $response->assertJsonStructure([
//            'data' => [
                [
                    'id',
                    'name' => [
                        'en'
                    ],
                    'slug' => [
                        'en'
                    ],
                    'type',
                    'order_column'
                ]
//            ]
        ]);

        $response->assertJsonCount(1);
    }
}
