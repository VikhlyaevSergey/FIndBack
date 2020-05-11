<?php

namespace Tests\Feature\Users\v1;

use App\Components\Image\ImageHelper;
use App\Components\Image\ImageSize;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\User;
use App\Models\Email;
use App\Models\Phone;
use Illuminate\Http\UploadedFile;
use Tests\Responses\UserResponse;
use Illuminate\Support\Facades\Storage;
use App\Components\Phone as ComponentsPhone;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * регистрация пользователя
     * валидные данные
     * успешный запрос, пользователь зарегистрировался
     */
    public function testValid()
    {
        Artisan::call('passport:install');
        $data = $this->getData();

        $response = $this->request($data);

        $this->assertResponseSuccess($response)->assertJsonStructure([
            'response' => UserResponse::response()
        ]);

        $userId = $response->json('response.profile.id');

        $this->assertDatabaseHas('users', ['id' => $userId, 'fullName' => $data['fullName']]);
        $this->assertDatabaseHas('phones', ['user_id' => $userId, 'phone' => ComponentsPhone::create($data['phone'])]);
        $this->assertDatabaseHas('emails', ['user_id' => $userId, 'email' => $data['email']]);

        foreach ($data['places'] as $place) {
            $this->assertDatabaseHas('places', ['user_id' => $userId] + $place);
        }

        $image = $response->json('response.profile.profileMainBlock.image');
        $path = ImageHelper::makeOriginal($image)->getPath();

        Storage::disk('test')->assertExists($path);
    }

    /**
     * регистрация пользователя
     * невалидные данные
     * ошибка запроса 422
     */
    public function testInvalid()
    {
        $data = $this->getData();

        $this->withoutData($data, ['fullName', 'phone'], function ($data) {
            $response = $this->request($data);
            $this->assertResponseInvalid($response);
        });

        $this->invalidData($data, function ($data) {
            $response = $this->request($data);
            $this->assertResponseInvalid($response);
        });
    }

    /**
     * регистрация пользователя
     * неподтвержденный телефон
     * ошибка запроса 400
     */
    public function testWrongPhone() {
        $data = $this->getData();
        $data['phone'] = $this->faker()->numberBetween(10000000000, 99999999999);

        $response = $this->request($data);

        $this->assertResponseError($response, 400);
    }

    /**
     * регистрация пользователя
     * неподтвержденный телефон
     * ошибка запроса 400
     */
    public function testNoneUniqueEmail() {
        $data = $this->getData();
        $user = factory(User::class)->create();
        $user->emails()->save(factory(Email::class)->make(['email' => $data['email']]));

        $response = $this->request($data);

        $this->assertResponseInvalid($response);
    }

    /**
     * получить валидные данные
     *
     * @return array
     */
    protected function getData()
    {
        Storage::persistentFake('test');
        $user = $this->createUser();
        $image = UploadedFile::fake()->image('test.jpg');

        return [
            'fullName' => $this->faker()->name,
            'phone' => $user->phones->first()->phone,
            'email' => $this->faker()->email,
            'places' => [
                [
                    'name' => $this->faker()->word,
                    'latitude' => $this->faker()->latitude,
                    'longitude' => $this->faker()->longitude
                ],
                [
                    'name' => $this->faker()->word,
                    'latitude' => $this->faker()->latitude,
                    'longitude' => $this->faker()->longitude
                ]
            ],
            'image' => $image
        ];
    }

    /**
     * запрос регистрации
     *
     * @param array $data
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function request($data = [])
    {
        return $this->apiRequest('POST', '/api/v1/register', $data);
    }
}
