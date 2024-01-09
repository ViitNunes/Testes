<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use App\Models\User; // Certifique-se de importar o modelo User
use Illuminate\Http\Response;

class UserTest extends \Tests\TestCase
{
    //use RefreshDatabase; // Use RefreshDatabase para reiniciar o banco de dados entre os testes

    /**A basic unit test example.*/
    public function testCreation(): void{
    
        $this->withoutMiddleware(); // Desabilita temporariamente os middlewares de autenticação // Desativa temporariamente os redirecionamentos

        $faker = Faker::create();

        $payload = [
            'name' => $faker->firstName,
            'email' => $faker->email,
            'password' => $faker->password,
        ];

        $response = $this->json('post', '/users', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testRead(): void
    {
        $this->withoutMiddleware();
        $id = 1;

        $response = $this->json('GET', "/users/{$id}");
        $response->assertStatus(200);
       // dd($response->json());
       $expectedStructure = [
        'user' => [
            'id',
            'name',
            'email',
            'password'
           ]
        ];

        $response->assertJsonStructure($expectedStructure);      
    }

    public function testUpdate(): void
    {
        $user = User::factory(1)->create();
        
        $this->withoutMiddleware();
        $id = 1;

        $faker = Faker::create();

        $payload = [
            'name' => $faker->firstName,
            'email' => $faker->email,
            'password' => $faker->password,
        ];

        $response = $this->json('put', "/users/{$id}", $payload);
        $response->assertStatus(201);
        $expectedStructure = [
        'user' => [
            'id',
            'name',
            'email',
            'password'
           ]
        ];

        $response->assertJsonStructure($expectedStructure); 
        //$this->assertNotEquals($payload, json_decode($response->json), "o valor nao foi alterado");
        // $response
        // ->assertJson(fn (AssertableJson $json) =>
        //     $json->where('id', $id)
        //          ->where('name', $payload['name'])
        //          ->where('email', fn (string $email) => str($email)->is($payload['email']))
        //          ->where('password', $payload['password'])
        // );
    }

    // public function testDelete(): void
    // {
    //     $user = User::factory()->create(); // Cria um usuário para ser deletado
    //     $id = $user->id;

    //     $this->withoutMiddleware();

    //     $response = $this->json('delete', "/users/{$id}");
    //     $response->assertStatus(Response::HTTP_NO_CONTENT);

    // // Verifica se o usuário foi removido do banco de dados
    //     $this->assertNull(User::find($id));

    // }


}
