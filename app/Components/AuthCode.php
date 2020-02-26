<?php

namespace App\Components;

use App\Exceptions\AuthCodeRepeatException;
use App\Jobs\SendSmsJob;
use App\Models\Code;

class AuthCode
{
    const TEST_PHONES = ['79999999999', '78888888888'];
    const STATIC_CODE = 1111;

    /**
     * @var string
     */
    public $phone;

    /**
     * AuthCode constructor.
     *
     * @param string $phone
     */
    public function __construct(string $phone)
    {
        $this->phone = Phone::createForSMS($phone);
    }

    /**
     * @throws AuthCodeRepeatException
     */
    public function send()
    {
        $repeat = Code::where('created_at', '>=', now()->subMinutes(2))
            ->wherePhone(Phone::create($this->phone))
            ->first();

        if ($repeat) {
            throw new AuthCodeRepeatException();
        }

        if (in_array($this->phone, self::TEST_PHONES) || config('nutnet-laravel-sms.provider') === 'log') {
            $code = self::STATIC_CODE;
        } else {
            $code = rand(1000, 9999);
        }

        SendSmsJob::dispatch($this->phone, "Ваш код: {$code}");

        Code::wherePhone(Phone::create($this->phone))->delete();
        Code::create(['phone' => Phone::create($this->phone), 'code' => $code]);
    }

    /**
     * @param int $code
     *
     * @return bool
     * @throws \Exception
     */
    public function check(int $code): bool
    {
        $code = Code::wherePhone(Phone::create($this->phone))
            ->where('created_at', '>=', now()->subMinutes(5))
            ->whereCode($code)
            ->first();

        if ($code) {
            $code->delete();

            return true;
        }

        return false;
    }
}
