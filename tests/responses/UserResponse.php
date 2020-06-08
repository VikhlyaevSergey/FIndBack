<?php

namespace Tests\Responses;

class UserResponse implements Responseable
{

    /** @inheritDoc */
    public static function response(): array
    {
        return [
            'profile' => [
                'id',
                'profileMainBlock' => [
                    'id',
                    'fullName',
                    'date',
                    'image'
                ],
                'profileContactsBlock' => [
                    'emails',
                    'phoneNumbers'
                ],
                'places' => [
                    '*' => [
                        'id',
                        'name',
                        'latitude',
                        'longitude'
                    ]
                ],
                'profileFavouriteLossObjects',
                'profileLossObjects'
            ]
        ];
    }
}
