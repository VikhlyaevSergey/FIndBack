<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Arr;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @inheritdoc */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[ FileStorageTest::class ])) {
            $this->afterApplicationCreated(
                function () {
                    $this->createPublicTestDirectory();
                });

            $this->beforeApplicationDestroyed(
                function () {
                    $this->deletePublicTestDirectory();
                });
        }

        if (isset($uses[ InitData::class ])) {
            $this->afterApplicationCreated(
                function () {
                    $this->setInitData();
                });
        }
    }

    /**
     * send request to api
     *
     * @param       $method
     * @param       $url
     * @param array $data
     * @param null  $user
     *
     * @param array $header
     *
     * @return TestResponse
     */
    protected function apiRequest($method, $url, $data = [], $user = NULL, $header = [])
    {
        if ($user && $user instanceof User) {
            return $this->actingAs($user, 'api')->json($method, $url, $data, $header);
        } else {
            return $this->json($method, $url, $data, $header);
        }
    }

    /**
     * check that the API request returned a data validation error
     *
     * @param TestResponse $response
     * @param array        $message
     *
     * @return TestResponse
     */
    protected function assertResponseInvalid(TestResponse $response, string $message = NULL)
    {
        $message = $message ? ['message' => $message] : [];

        return $response->assertStatus(200)->assertJson(
            [
                'success'  => false,
                'error'    => 422,
                'response' => NULL,
            ] + $message);
    }

    /**
     * check that the API request returned a 401 error
     *
     * @param TestResponse $response
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertResponseUnauthorized(TestResponse $response)
    {
        return $response->assertStatus(200)->assertExactJson(
            [
                'success'  => false,
                'error'    => 401,
                'message'  => 'Вы не авторизованы',
                'response' => NULL,
            ]);
    }

    /**
     * check that the API request returned a 403 error
     *
     * @param TestResponse $response
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertResponsePermissionDenied(TestResponse $response)
    {
        return $response->assertStatus(200)->assertExactJson(
            [
                'success'  => false,
                'error'    => 403,
                'message'  => 'You do not have permission to access this resource',
                'response' => NULL,
            ]);
    }

    /**
     * check that the API request returned a successful response
     *
     * @param TestResponse $response
     * @param array        $responseData
     *
     * @param array        $message
     *
     * @return TestResponse
     */
    protected function assertResponseSuccess(TestResponse $response, $responseData = NULL, $message = NULL)
    {
        $responseData = $responseData ? ['response' => $responseData] : [];
        $message      = $message ? ['message' => $message] : [];

        return $response->assertStatus(200)->assertJson(
            [
                'success' => true,
                'error'   => NULL,
            ] + $responseData + $message);
    }

    /**
     * check that the API request returned a custom error
     *
     * @param TestResponse $response
     * @param int          $error
     * @param array        $message
     *
     * @return TestResponse
     */
    protected function assertResponseError(TestResponse $response, int $error, string $message = NULL)
    {
        $message = $message ? ['message' => $message] : [];

        return $response->assertStatus(200)->assertJson(
            [
                'success'  => false,
                'error'    => $error,
                'response' => NULL,
            ] + $message);
    }

    /**
     * convert the data to invalid and call the function
     *
     * @param          $data
     * @param callable $callable
     */
    protected function invalidData(array $data, Callable $callable)
    {
        foreach ($data as $param => $value) {
            if (is_string($value)) {
                $value = ['invalid'];
            } else {
                $value = 'invalid';
            }

            $callable([$param => $value] + $data);
        }
    }

    /**
     * to convert the data to non-existent ID and call the function
     *
     * @param          $data
     * @param callable $callable
     */
    protected function noneExistsData(array $data, Callable $callable)
    {
        foreach ($data as $param => $value) {
            if (strpos($param, '_id') === false) {
                continue;
            }

            $callable([$param => $value + 9999] + $data);
        }
    }

    /**
     * run the callback function without the required parameters
     *
     * @param array    $data
     * @param          $required
     * @param callable $callable
     */
    protected function withoutData(array $data, $required, Callable $callable)
    {
        $required = is_array($required) ? $required : [$required];

        foreach ($required as $key) {
            $callable(Arr::except($data, $key));
        }
    }

    /**
     * trim a string
     *
     * @param string $str
     *
     * @return bool|string
     */
    protected function cuteStr(string $str)
    {
        return substr($str, 1, strlen($str) - 2);
    }
}
