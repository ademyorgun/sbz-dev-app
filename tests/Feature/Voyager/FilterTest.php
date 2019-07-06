<?php

namespace Tests\Feature\Voyager;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class FilterTest extends TestCase
{

    public function test_a_user_can_filter_resutls() {
        $user = factory(User::class)->make();
        $data = [
            'title' => 'test',
            'content' => 'test',
        ];

        $response = $this->actingAs($user)->post('/products/filter', $data);
        $response->assertStatus(200);
        // $response->assertJson($data);
    }
}
