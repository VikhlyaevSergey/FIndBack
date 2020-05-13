<?php

namespace Tests\Responses;

class LossObjectResponse implements Responseable
{

    /** @inheritDoc */
    public static function response(): array
    {
        return [
            'id',
            'name',
            'info',
            'regime',
            'publishDate',
            'previewImage',
            'isFavourite',
            'objectType',
            'petType',
        ];
    }
}
