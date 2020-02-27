<?php

namespace Tests\Feature\Users\v1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Responses\UserResponse;
use Tests\TestCase;

class GetProfileRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * авторизация
     * отправить код подтверждения
     * успешный запрос
     */
    public function testLoginRequest()
    {
        $user = factory(User::class)->create();

        $response = $this->request($user);

        $this->assertResponseSuccess($response)->assertJsonStructure(
            [
                'response' => UserResponse::response(),
            ]);
    }

    /**
     * авторизация
     * неавторизованный запрос
     * ошибка запроса 401
     */
    public function testInvalidLoginRequest()
    {
        $user = factory(User::class)->create();

        $response = $this->request();

        $this->assertResponseUnauthorized($response);
    }

    /**
     * запрос авторизации
     *
     * @param array $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function request(User $user = NULL)
    {
        return $this->apiRequest('GET', '/api/v1/users/profile', [], $user);
    }
}
