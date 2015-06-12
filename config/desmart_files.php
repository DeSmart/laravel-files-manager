<?php

use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\Mapper\GenericMapper;

return [

    /**
     * Default storage used to store files
     *
     * @see http://laravel.com/docs/5.0/filesystem#basic-usage
     * @var string
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

    /**
     * Name of file entity class which should be returned by manager
     *
     * @var string|null
     */
    'file_entity_class' => null,
];
