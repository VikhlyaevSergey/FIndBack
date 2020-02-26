<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Components\Phone;
use App\Components\AuthCode;
use App\Exceptions\ApiException;
use App\Components\Image\ImageHelper;
use App\Http\Requests\v1\UserLoginRequest;
use App\Http\Requests\v1\UserRegisterRequest;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Exception\NotReadableException;

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
        $auth = new AuthCode($request->input('phone'));

        if (!$request->has('code')) {
            $auth->send();

            return responseApi()->get();
        }

        if (!$auth->check($request->input('code'))) {
            throw new ApiException('Неверный код подтверждения', 400);
        }

        $phone = Phone::create($request->input('phone'));
        $user  = User::byPhone($phone)->first();

        if (!$user) {
            $user = User::create();
            $user->phones()->create(['phone' => $phone]);

            return responseApi([
                'action' => 'register',
            ])->get();
        }

        $token = $user->createToken($phone . ' access_token')->accessToken;

        return responseApi([
            'token' => $token,
            'id'  => $user->id,
        ])->get();
    }

    /**
     * Register
     *
     * @param UserRegisterRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $user = User::byPhone($request->input('phone'))->first();

        if (!$user) {
            throw new ApiException('Телефон не подтвержден', 400);
        }

        $userAttributes = $request->only(['fullName']);

        if ($request->has('image')) {
            $image = $request->input('image') ?: $request->file('image');

            try {
                $image = ImageHelper::make($image, 'avatars');
                [$file] = $image->saveAllSizes();

                $userAttributes['image'] = $file;
            } catch (NotReadableException $e) {
                $error = ValidationException::withMessages(['image' => 'Неверный тип картинки']);
                throw $error;
            }
        }

        if ($request->has('email')) {
            $user->emails()->firstOrCreate(['email' => $request->input('email')]);
        }

        if ($request->has('places')) {
            $user->places()->createMany($request->input('places'));
        }

        $user->update($userAttributes);

        return responseApi(['profile' => $user->getProfile()])->get();
    }
}
