<?php

use DeSmart\Files\Mapper\GenericMapper;

return [

    /**
     * Default storage used to store files
     *
     * @see http://laravel.com/docs/5.0/filesystem#basic-usage
     */
    'storage_disk' => 'upload',

    /**
     * File entity mappers
     *
     * This mappers will be used to model entity data before storing it on filesystem and database
     *
     * @var \DeSmart\Files\Mapper\MapperInterface[]
     */
    'mappers' => [
        GenericMapper::class,
    ],
];
