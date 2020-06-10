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
                'profileMainBlock'     => [
                    'id',
                    'fullName',
                    'date',
                    'image',
                ],
                'profileContactsBlock' => [
                    'emails',
                    'phoneNumbers',
                ],
                'places'               => [
                    '*' => [
                        'id',
                        'name',
                        'point' => [
                            'latitude',
                            'longitude',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'profileFavouriteLossObjects',
                'profileLossObjects',
            ],
        ];
    }
}
