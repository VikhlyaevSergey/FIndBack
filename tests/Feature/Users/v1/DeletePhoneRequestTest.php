<?php

namespace Tests\Feature\Users\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePhoneRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * удалить телефон
     * валидный запрос
     * успешное удаление
     */
    public function testValid()
    {
        $user = $this->createUser();

        $response = $this->request(['phone' => $user->phones()->first()->phone], $user);

        $this->assertResponseSuccess($response);
        $this->assertCount(0, $user->phones()->get());
    }

    /**
     * удалить телефон
     * невалидный запрос
     * ошибка запроса 400
     */
    public function testInvalid()
    {
        $user = $this->createUser();

        // пустой запрос
        $response = $this->request([], $user);
        $this->assertResponseInvalid($response);

        // не существующий телефон
        $response = $this->request(['phone' => $this->faker->numberBetween(1000000000, 9999999999)], $user);
        $this->assertResponseInvalid($response);

        // невалидный телефон
        $response = $this->request(['phone' => 'invalid'], $user);
        $this->assertResponseInvalid($response);
    }

    /**
     * удалить телефон
     * неавторизованный запрос
     * ошибка запроса 401
     */
    public function testUnauthorized()
    {
        $response = $this->request(['phone' => $this->faker->numberBetween(1000000000, 9999999999)]);

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
        return $this->apiRequest('DELETE', '/api/v1/users/phones', $data, $user);
    }
}
