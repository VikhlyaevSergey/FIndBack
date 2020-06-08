<?php

namespace Tests\Feature\LossObject\v1;

use App\Models\LossObject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Responses\LossObjectResponse;
use Tests\Responses\PaginateResponse;
use Tests\TestCase;

class GetMyRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * получить список моих объектов
     * валидный запрос
     * успешный ответ
     */
    public function testValid()
    {
        $user      = $this->createUser();
        $myObjects = $user->objects()->saveMany(factory(LossObject::class, 5)->make());
        factory(LossObject::class, 5)->create();

        $response = $this->request($user);

        $this->assertResponseSuccess($response)->assertJsonStructure(
            [
                'response' => (new PaginateResponse(new LossObjectResponse))->response(),
            ]);

        $result = collect($response->json('response.data'));

        $this->assertCount($myObjects->count(), $result);

        foreach ($myObjects as $object) {
            $item = $result->where('id', $object->id);

            $this->assertNotNull($item);
        }
    }

    /**
     * получить список моих объектов
     * неавторизованный запрос
     * успешный ответ
     */
    public function testUnauthorized()
    {
        $user      = $this->createUser();
        $myObjects = $user->objects()->saveMany(factory(LossObject::class, 5)->make());
        factory(LossObject::class, 5)->create();

        $response = $this->request();

        $this->assertResponseUnauthorized($response);
    }

    protected function request(User $user = NULL)
    {
        return $this->apiRequest('GET', '/api/v1/objects/my', [], $user);
    }
}
