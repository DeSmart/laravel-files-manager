<?php namespace DeSmart\Files\Mapper;

use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\FileSource\FileSourceInterface;

interface MapperInterface
{

    /**
     * Map entity data
     *
     * @param FileEntity $entity
     * @param FileSourceInterface $fileSource
     */
    public function map(FileEntity $entity, FileSourceInterface $fileSource);
}
