<?php

namespace Tests\Responses;

interface Responseable
{
    /**
     * an array of the fields
     *
     * @return array
     */
    public static function response(): array;
}