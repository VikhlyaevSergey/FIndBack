<?php

namespace Tests\Feature\Users\v1;

use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePlaceRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * удалить email
     * валидный запрос
     * успешное удаление
     */
    public function testValid()
    {
        $user  = $this->createUser();
        $place = $user->places()->save(factory(Place::class)->make());

        $response = $this->request(['place' => $place->id], $user);

        $this->assertResponseSuccess($response);
        $this->assertCount(0, $user->places()->get());
    }

    /**
     * удалить email
     * невалидный запрос
     * ошибка запроса 400
     */
    public function testInvalid()
    {
        $user  = $this->createUser();
        $place = $user->places()->save(factory(Place::class)->make());

        // пустой запрос
        $response = $this->request([], $user);
        $this->assertResponseInvalid($response);

        // не существующиее место
        $response = $this->request(['place' => $place->id + 9999], $user);
        $this->assertResponseInvalid($response);

        // невалидное место
        $response = $this->request(['place' => 'invalid'], $user);
        $this->assertResponseInvalid($response);
    }

    /**
     * удалить email
     * неавторизованный запрос
     * ошибка запроса 401
     */
    public function testUnauthorized()
    {
        $place = factory(Place::class)->create();

        $response = $this->request(['place' => $place->id]);

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
        return $this->apiRequest('DELETE', '/api/v1/users/places', $data, $user);
    }
}
