<?php namespace DeSmart\Files\Mapper;

use DeSmart\Files\Entity\FileEntity;

interface MapperInterface
{

    /**
     * Map entity data
     *
     * @param \DeSmart\Files\Entity\FileEntity
     */
    public function map(FileEntity $entity);
}
