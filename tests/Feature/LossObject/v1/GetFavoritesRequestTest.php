<?php

namespace Tests\Feature\LossObject\v1;

use App\Models\LossObject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetFavoritesRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * получить список избранных
     * валидный запрос
     * успешный ответ
     */
    public function testValid()
    {
        $user      = $this->createUser();
        $favorites = $user->favoriteObjects()->saveMany(factory(LossObject::class, 5)->make());
        factory(LossObject::class, 5)->create();

        $response = $this->request($user);

        $this->assertResponseSuccess($response)->assertJsonStructure(
            [
                'response',
            ]);

        $result = collect($response->json('data'));

        foreach ($favorites as $object) {
            $item = $result->where('id', $object->id);

            $this->assertNotNull($item);
        }
    }

    protected function request(User $user = NULL)
    {
        return $this->apiRequest('GET', '/api/v1/objects/favorites', [], $user);
    }
}
