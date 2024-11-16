<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_successful_login()
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'), // Breeze expects hashed passwords
        ]);

        // Act: Send a POST request to the login endpoint with correct credentials
        $response = $this->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // Assert: Check that the response redirects to the dashboard or home (or another authenticated route)
        $response->assertRedirect('/dashboard'); // Modify this as needed to match your app's behavior
        $this->assertAuthenticatedAs($user); // Ensure the user is authenticated
    }

    /**
     * Test unsuccessful login attempt due to wrong credentials.
     *
     * @return void
     */
    public function test_unsuccessful_login()
    {
        // Arrange: Create a user
        User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Act: Send a POST request to the login endpoint with incorrect credentials
        $response = $this->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword', // Incorrect password
        ]);

        // Assert: Check that the response returns the login page with errors
        $response->assertRedirect(route('login')); // Should redirect back to login page
        $response->assertSessionHasErrors(['email']); // Assert that an error for email (or password) is present
        $this->assertGuest(); // Ensure that the user is not authenticated
    }

}
