<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
//use Illuminate\Support\Facades\Hash; 

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_creation(): void
    {
         //Cria dados de teste com o Faker
        $nome = 'Maxuel'; //$this->faker->name();
        $email = 'maxuel@gmail.com'; //$this->faker->email();
        $password = '123456';

         //Faz requisição via POST
         //redirect()->route('user.index')
        $response = $this->post('/users/create', [
            'nome' => $nome,
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertRedirect(); //Valida status code da resposta
    //      //   ->assertSessionHas('message', 'Fullbanner criado com sucesso'); //Valida dados na variável de sessão

    //    // $this->assertDatabaseHas(FullBanner::class, ['title' => $title, 'link' => $link]); //Valida se o item foi cadastrado no banco de dados

    //     /** @var FullBanner $banner */
    //     $banner = FullBanner::query()->where('title', 'like', $title)->first();

    //     $this->assertNotNull($banner->getImage()); //Valida se o mock da imagem foi cadastrado
    }
}
