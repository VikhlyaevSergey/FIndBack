<?php

namespace Tests\Feature\Users\v1;

use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteEmailRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * удалить email
     * валидный запрос
     * успешное удаление
     */
    public function testValid()
    {
        $user = $this->createUser();
        $email = $user->emails()->save(factory(Email::class)->make());

        $response = $this->request(['email' => $email->email], $user);

        $this->assertResponseSuccess($response);
        $this->assertCount(0, $user->emails()->get());
    }

    /**
     * удалить email
     * невалидный запрос
     * ошибка запроса 400
     */
    public function testInvalid()
    {
        $user = $this->createUser();
        $email = $user->emails()->save(factory(Email::class)->make());

        // пустой запрос
        $response = $this->request([], $user);
        $this->assertResponseInvalid($response);

        // не существующий email
        $response = $this->request(['email' => $this->faker->email], $user);
        $this->assertResponseInvalid($response);

        // невалидный email
        $response = $this->request(['email' => 'invalid'], $user);
        $this->assertResponseInvalid($response);
    }

    /**
     * удалить email
     * неавторизованный запрос
     * ошибка запроса 401
     */
    public function testUnauthorized()
    {
        $response = $this->request(['email' => $this->faker->email]);

        $this->assertResponseUnauthorized($response);
    }

    /**
     * @param array     $data
     * @param User|NULL $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function request(array $data = [], User $user = NULL)
    {
        return $this->apiRequest('DELETE', '/api/v1/users/emails', $data, $user);
    }
}
