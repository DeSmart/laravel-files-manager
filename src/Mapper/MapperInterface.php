<?php namespace DeSmart\Files\Mapper;

use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\Source\FileSourceInterface;

interface MapperInterface
{

    /**
     * Map entity data
     *
     * @param \DeSmart\Files\Source\FileSourceInterface
     * @param \DeSmart\Files\Entity\FileEntity
     */
    public function map(FileSourceInterface $file, FileEntity $entity);
}
