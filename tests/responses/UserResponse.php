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
                    'image_url',
                    'image_url_mini',
                    'image_url_medium',
                    'image_url_max',
                ],
                'profileContactsBlock' => [
                    'emails',
                    'phoneNumbers',
                ],
                'places'               => [
                    '*' => [
                        'id',
                        'name',
                        'addressText',
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
