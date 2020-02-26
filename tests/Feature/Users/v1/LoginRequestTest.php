<?php

namespace Tests\Feature\Users\v1;

use App\Components\Phone;
use App\Models\Code;
use App\Models\User;
use App\Models\Phone as PhoneModel;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Responses\LoginResponse;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * авторизация
     * отправить код подтверждения
     * успешный запрос
     */
    public function testLoginRequest()
    {
        $phone = '+7(900)800-70-60';

        $response = $this->request(['phone' => $phone]);

        $this->assertResponseSuccess($response);
        $this->assertDatabaseHas('codes', ['phone' => Phone::create($phone)]);
    }

    /**
     * авторизация
     * невалидный телефон
     * ошибка запроса 422
     */
    public function testInvalidLoginRequest()
    {
        $phone    = 'invalid';
        $response = $this->request(['phone' => $phone]);
        $this->assertResponseInvalid($response);

        $phone    = '+7(900)80';
        $response = $this->request(['phone' => $phone]);
        $this->assertResponseInvalid($response);

        // without phone
        $response = $this->request();
        $this->assertResponseInvalid($response);
    }

    /**
     * проверка кода
     * валидный код, новый пользователь
     * успешный запрос, необходима регистрация
     */
    public function testCheckCodeNewUserRequest()
    {
        $phone = '+79999999999';
        $code  = factory(Code::class)->create(['phone' => Phone::create($phone)]);

        $response = $this->request(['phone' => $phone, 'code' => $code->code]);

        $this->assertResponseSuccess($response)->assertJson([
            'response' => [
                'action' => 'register'
            ]
        ]);

        $this->assertDatabaseHas('phones', ['phone' => Phone::create($phone)]);
    }

    /**
     * проверка кода
     * валидный код, существующий пользователь
     * успешный запрос, пользователь создан
     */
    public function testCheckCodeExistUserRequest()
    {
        Artisan::call('passport:install');
        $phone = '+79999999999';
        $user  = factory(User::class)->create();
        $user->phones()->save(factory(PhoneModel::class)->make(['phone' => Phone::create($phone)]));
        $code  = factory(Code::class)->create(['phone' => Phone::create($phone)]);

        $response = $this->request(['phone' => $phone, 'code' => $code->code]);

        $this->assertResponseSuccess($response)->assertJsonStructure([
            'response' => LoginResponse::response()
        ]);

        $this->assertEquals($user->id, $response->json('response.id'));
        $this->assertDatabaseHas('users', ['id' => $response->json('response.id')]);
    }

    /**
     * проверка кода
     * невалидный код
     * ошибка запроса 422
     */
    public function testCheckCodeInvalidRequest()
    {
        $phone = '+79999999999';
        $code  = 'invalid';

        $response = $this->request(['phone' => $phone, 'code' => $code]);

        $this->assertResponseInvalid($response);
    }

    /**
     * проверка кода
     * неверный код код
     * ошибка запроса 400
     */
    public function testCheckCodeWrongRequest()
    {
        $phone = '+79999999999';

        $response = $this->request(['phone' => $phone, 'code' => 7363]);

        $this->assertResponseError($response, 400);
    }

    /**
     * запрос авторизации
     *
     * @param array $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function request($data = [])
    {
        return $this->apiRequest('POST', '/api/v1/login', $data);
    }
}
