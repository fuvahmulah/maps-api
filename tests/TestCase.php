<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function passportSignIn($user = null, $scopes = [])
    {
        $user = $user ?: factory(User::class)->create();
        Passport::actingAs($user, $scopes);
        return $user;
    }
}
