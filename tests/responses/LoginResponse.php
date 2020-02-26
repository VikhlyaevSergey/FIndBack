<?php

namespace Tests\Responses;

class LoginResponse implements Responseable
{

    /** @inheritDoc */
    public static function response(): array
    {
        return [
            'id',
            'token',
        ];
    }
}