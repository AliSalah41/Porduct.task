<?php

namespace Tests\Feature;

use App\Http\Requests\ProductRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
    * A basic feature test example.
    */
    public function test_store_creates_product_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); 

        $request = new ProductRequest([
            'name' => 'Test Product',
            'price' => 100,
            'category_id' => 1,
            'quantity' => 10,
        ]);

        $response = $this->postJson(route('products.store'), $request->all());

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Successfully created'
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 100,
            'quantity' => 10,
            'category_id' => 1,
            'owner_id' => $user->id,
        ]);
    }

    public function test_store_fails_to_create_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $request = new ProductRequest([]);

        $response = $this->postJson(route('products.store'), $request->all());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price', 'category_id', 'quantity']);
    }
}
