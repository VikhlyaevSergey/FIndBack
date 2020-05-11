<?php

namespace Tests\Feature\Users\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * выход пользователя
     * валидные данные
     * успешный запрос
     */
    public function testValid()
    {
        $user = $this->createUser();

        $response = $this->request($user);

        $this->assertResponseSuccess($response);
    }

    /**
     * выход пользователя
     * неавторизированный юзер
     * ошибка запроса 401
     */
    public function testUnauthorized()
    {
        $response = $this->request();

        $this->assertResponseUnauthorized($response);
    }

    /**
     * запрос на выход из профиля
     *
     * @param User|null $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function request(User $user = NULL)
    {
        return $this->apiRequest('POST', '/api/v1/users/logout', [], $user);
    }
}
