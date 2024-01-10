<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use App\Models\User; // Certifique-se de importar o modelo User
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

class UserTest extends \Tests\TestCase
{
    use RefreshDatabase; // Use RefreshDatabase para reiniciar o banco de dados entre os testes

    /**A basic unit test example.*/
    public function testCreation(): void{
    
        $this->withoutMiddleware(); //Desabilita os middlewares de autenticação

        $faker = Faker::create(); //Gera uma instância do Faker para criar dados falsos

        //Array que guarda dados que serão usados para criar um usuário
        $payload = [
            'name' => $faker->firstName,
            'email' => $faker->email,
            'password' => $faker->password,
        ];

        $response = $this->json('post', '/users', $payload); //Faz a requisição POST

        $response->assertStatus(Response::HTTP_CREATED); //Compara se o status retornado é igual ao status de HTTP_CREATED(201)

        $this->assertDatabaseHas('users', ['id' => $response->json()["user"]["id"]]); //Conferindo se o usuário realmente foi para a base de dados
    }

    public function testRead(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $id = User::all()->first()->id; //Pega o id do primeiro usuário do banco de dados

        $response = $this->json('GET', "/users/{$id}"); //Faz a requisição

        $response->assertStatus(200); //Compara se os status são ambos 200(Requisição bem sucedida)
       
        //Estrutura esperada do objeto json
        $expectedStructure = [
            'user' => [
                'id',
                'name',
                'email',
                //'password'
            ]
            ];
        
        //Verifica se a estrutura está correta e é realmente a de um usuário
        $response->assertJsonStructure($expectedStructure);      
    }

    public function testUpdate(): void
    {
        $user = User::factory()->create(); //Cria um usuário para teste
        
        $this->withoutMiddleware();
        
        $id = $user->id;

        $faker = Faker::create();

        //Dados de atualização
        $payload = [
            'name' => $faker->firstName,
            'email' => $faker->email,
            //'password' => $faker->password,
        ];

        $response = $this->json('put', "/users/{$id}", $payload); //Faz a requisição update
        
        $response->assertStatus(201); //Assegura o status 201
        
        //Garante que o objeto retornado tem essa estrutura
        $expectedStructure = [
        'user' => [
            'id',
            'name',
            'email',
            //'password'
           ]
        ];
        
        $response->assertJsonStructure($expectedStructure); 
        
        //Verifica se os dados do payload batem com o que foi atualizado passando payload
        $response
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('user.id', $id)
                 ->where('user.name', $payload["name"])
                 ->where('user.email', $payload["email"])
                //  ->where('user.password', bcrypt($payload["password"]))
        );
    }

    public function testDelete(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $id = $user->id;

        $response = $this->json('delete', "/users/{$id}");  //Requisição de delete

        $response->assertStatus(204); //Status 204 (Tudo ok, sem content a retornar)

        $this->assertDatabaseMissing('users', ['id' => $id]); //Verifica se realmente não é possivel achar o id do usuário deletado
    }

}
