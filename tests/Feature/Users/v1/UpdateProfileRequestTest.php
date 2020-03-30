<?php

namespace Tests\Feature\Users\v1;

use App\Components\Image\ImageHelper;
use App\Components\Phone as ComponentsPhone;
use App\Models\Email;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\Responses\UserResponse;
use Tests\TestCase;

class UpdateProfileRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * регистрация пользователя
     * валидные данные
     * успешный запрос, пользователь зарегистрировался
     */
    public function testValid()
    {
        $user = $this->createUser();
        $data = $this->getData();

        $response = $this->request($data, $user);

        $this->assertResponseSuccess($response)->assertJsonStructure(
            [
                'response' => UserResponse::response(),
            ]);

        $userId = $response->json('response.profile.id');

        $this->assertDatabaseHas('users', ['id' => $userId, 'fullName' => $data['fullName']]);

        foreach ($data['phones'] as $phone) {
            $this->assertDatabaseHas(
                'phones', [
                'user_id' => $userId,
                'phone'   => ComponentsPhone::create($phone),
            ]);
        }

        foreach ($data['emails'] as $email) {
            $this->assertDatabaseHas('emails', ['user_id' => $userId, 'email' => $email]);
        }

        foreach ($data['places'] as $place) {
            $this->assertDatabaseHas('places', ['user_id' => $userId] + $place);
        }

        $image = $response->json('response.profile.profileMainBlock.image');
        $path  = ImageHelper::makeOriginal($image)->getPath();

        Storage::disk('test')->assertExists($path);
    }

    /**
     * регистрация пользователя
     * невалидные данные
     * ошибка запроса 422
     */
    public function testInvalid()
    {
        $user = $this->createUser();
        $data = $this->getData();

        $this->invalidData(
            $data, function ($data) use ($user) {
            $response = $this->request($data, $user);
            $this->assertResponseInvalid($response);
        });
    }

    /**
     * регистрация пользователя
     * неподтвержденный телефон
     * ошибка запроса 400
     */
    public function testNoneUniqueEmail()
    {
        $data = $this->getData();
        $user = factory(User::class)->create();
        $user->emails()->save(factory(Email::class)->make(['email' => Arr::first($data['emails'])]));
        $user = $this->createUser();

        $response = $this->request($data, $user);

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
        $image = UploadedFile::fake()->image('test.jpg');

        return [
            'fullName' => $this->faker()->name,
            'phones'   => [(string)$this->faker()->numberBetween(10000000000, 99999999999)],
            'emails'   => [$this->faker()->unique()->email],
            'places'   => [
                [
                    'name'      => $this->faker()->word,
                    'latitude'  => $this->faker()->latitude,
                    'longitude' => $this->faker()->longitude,
                ],
                [
                    'name'      => $this->faker()->word,
                    'latitude'  => $this->faker()->latitude,
                    'longitude' => $this->faker()->longitude,
                ],
            ],
            'image'    => $image,
        ];
    }

    /**
     * запрос регистрации
     *
     * @param array     $data
     *
     * @param User|null $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function request($data = [], User $user = NULL)
    {
        return $this->apiRequest('PUT', '/api/v1/users/profile', $data, $user);
    }
}
