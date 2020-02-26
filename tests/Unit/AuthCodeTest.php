<?php

namespace Tests\Unit;

use App\Components\AuthCode;
use App\Components\Phone;
use App\Exceptions\AuthCodeRepeatException;
use App\Models\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class AuthCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * отправка кода
     * валидные данные
     * код отправлен
     *
     * @throws AuthCodeRepeatException
     */
    public function testValidSend()
    {
        $phone    = '89007006050';
        $authCode = new AuthCode($phone);

        $authCode->send();

        $this->assertDatabaseHas('codes', ['phone' => Phone::create($phone)]);
    }

    /**
     * отправка кода
     * невалидный телефон
     * ошибка
     */
    public function testInvalidPhone()
    {
        $phone = 'invalid';

        $this->expectException(InvalidArgumentException::class);

        $authCode = new AuthCode($phone);
    }

    /**
     * отправка кода
     * повторная отправка
     * ошибка отправки
     *
     * @throws AuthCodeRepeatException
     */
    public function testRepeatSend()
    {
        $phone = '89446346324';
        factory(Code::class)->create(['phone' => Phone::create($phone)]);
        $authCode = new AuthCode($phone);

        $this->expectException(AuthCodeRepeatException::class);

        $authCode->send();
    }

    /**
     * отправка кода
     * повторная отправка
     * успешная отправки
     *
     * @throws AuthCodeRepeatException
     */
    public function testRepeatSendAfter2Minutes()
    {
        $phone = '89446346324';
        factory(Code::class)->create(
            [
                'phone'      => Phone::create($phone),
                'created_at' => now()->subMinutes(3),
                'updated_at' => now()->subMinutes(3),
            ]);
        $authCode = new AuthCode($phone);

        $authCode->send();

        $this->assertDatabaseHas('codes', ['phone' => Phone::create($phone)]);
    }

    /**
     * проверка кода
     * разные варианты
     * ожидаемые варианты
     */
    public function testCheck()
    {
        $phone    = '89639530249';
        $code     = 6349;
        $authCode = new AuthCode($phone);

        // valid code
        factory(Code::class)->create(['phone' => Phone::create($phone), 'code' => $code]);
        $this->assertTrue($authCode->check($code));
        $this->assertDatabaseMissing('codes', ['phone' => Phone::create($phone)]);

        // invalid code
        factory(Code::class)->create(['phone' => Phone::create($phone), 'code' => $code]);
        $this->assertFalse($authCode->check(9076));
        $this->assertDatabaseHas('codes', ['phone' => Phone::create($phone)]);

        // after 5 minutes
        $phone    = '89715495781';
        $authCode = new AuthCode($phone);
        factory(Code::class)->create(
            [
                'phone'      => Phone::create($phone),
                'code'       => $code,
                'created_at' => now()->subMinutes(6),
                'updated_at' => now()->subMinutes(6),
            ]);
        $this->assertFalse($authCode->check($code));
        $this->assertDatabaseHas('codes', ['phone' => Phone::create($phone)]);
    }
}
