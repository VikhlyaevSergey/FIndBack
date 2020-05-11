<?php

namespace App\Http\Controllers;

use App\Http\Requests\v1\UpdateProfileRequest;
use App\Models\User;
use App\Components\Phone;
use App\Components\AuthCode;
use App\Exceptions\ApiException;
use App\Http\Requests\v1\UserLoginRequest;
use App\Http\Requests\v1\UserRegisterRequest;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Authorization
     *
     * Если кода еще нет и его нужно отправить, в запрос кладем только телефон, вернется пустой ответ.
     * Если уже есть код, его отправляем вместе с телефоном, если он верен, вернется профиль пользователя и токен.
     * Если профиля пользователя еще не существует придет ответ 200 с телом action: register
     * Если код неверен, ошибка 400.
     *
     * @param UserLoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     * @bodyParam phone string required
     * @bodyParam code integer
     */
    public function login(UserLoginRequest $request)
    {
        $auth  = new AuthCode($request->input('phone'));
        $phone = Phone::create($request->input('phone'));

        if (!$request->has('code')) {
            $auth->send();

            return responseApi()->get();
        }

        // for testing
        if ($phone === Phone::create(Arr::first(AuthCode::TEST_PHONES)) && $request->input('code') === AuthCode::STATIC_CODE) {
            $user  = User::byPhone($phone)->first();
            $token = $user->createToken($phone . ' access_token')->accessToken;

            return responseApi(
                [
                    'token' => $token,
                    'id'    => $user->id,
                ])->get();
        }

        // for testing
        if ($phone === Phone::create(Arr::last(AuthCode::TEST_PHONES)) && $request->input('code') === AuthCode::STATIC_CODE) {
            return responseApi(['action' => 'register'])->get();
        }

        if (!$auth->check($request->input('code'))) {
            throw new ApiException('Неверный код подтверждения', 400);
        }

        $user = User::byPhone($phone)->first();

        if (!$user) {
            $user = User::create();
            $user->phones()->create(['phone' => $phone]);
        }

        if (!$user->last_login) {
            return responseApi(['action' => 'register'])->get();
        }

        $token = $user->createToken($phone . ' access_token')->accessToken;

        $user->last_login = now();
        $user->save();

        return responseApi(
            [
                'token' => $token,
                'id'    => $user->id,
            ])->get();
    }

    /**
     * Register
     *
     * @param UserRegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function register(UserRegisterRequest $request)
    {
        $phone = Phone::create($request->input('phone'));
        $user  = User::byPhone($phone)->first();

        if (!$user) {
            throw new ApiException('Телефон не подтвержден', 400);
        }

        $userAttributes = $request->except(['email', 'places']);

        if ($request->has('email')) {
            $user->emails()->firstOrCreate(['email' => $request->input('email')]);
        }

        if ($request->has('places')) {
            $user->places()->createMany($request->input('places'));
        }

        $user->update($userAttributes + ['last_login' => now()]);

        $token = $user->createToken($phone . ' access_token')->accessToken;

        return responseApi(['profile' => $user->getProfile(), 'token' => $token])->get();
    }

    /**
     * Update profile
     *
     * @param UserRegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $userAttributes = $request->except(['emails', 'places', 'phones']);
        $user           = user();

        if ($request->has('emails')) {
            foreach ($request->input('emails') as $email) {
                $user->emails()->firstOrCreate(['email' => $email]);
            }
        }

        if ($request->has('places')) {
            foreach ($request->input('places') as $place) {
                $user->places()->firstOrCreate($place);
            }
        }

        if ($request->has('phones')) {
            foreach ($request->input('phones') as $phone) {
                $user->phones()->firstOrCreate(['phone' => Phone::create($phone)]);
            }
        }

        $user->update($userAttributes);

        return responseApi(['profile' => $user->getProfile()])->get();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        return responseApi(['profile' => user()->getProfile()])->get();
    }

    /**
     * выход из профиля
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function logout()
    {
        if (user()->token()) {
            user()->token()->delete();
        }

        return responseApi()->get();
    }
}
