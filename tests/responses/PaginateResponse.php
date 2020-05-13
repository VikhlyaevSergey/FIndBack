<?php

namespace Tests\Responses;

use InvalidArgumentException;

class PaginateResponse
{
    /**
     * @var Responseable
     */
    protected $item;

    /**
     * ListPaginateResponse constructor.
     *
     * @param $item
     */
    public function __construct(Responseable $item)
    {
        $this->item = new $item;
    }

    /**
     * @return array
     */
    public function response(): array
    {
        return [
            'data'  => [
                '*' => $this->item->response(),
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta'  => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ];
    }
}
