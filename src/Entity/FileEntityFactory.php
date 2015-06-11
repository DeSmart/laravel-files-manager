<?php namespace DeSmart\Files\Entity;

use DeSmart\Files\FileSource\FileSourceInterface;

class FileEntityFactory
{

    /**
     * Creates entity from file source
     *
     * @param \DeSmart\Files\FileSource\FileSourceInterface
     * @return \DeSmart\Files\Entity\FileEntity
     */
    public function createFromFileSource(FileSourceInterface $file)
    {
        $entity = new FileEntity;
        $entity->setSize($file->getSize());
        $entity->setName($file->getName());
        $entity->setMd5Checksum($file->getMd5Checksum());

        return $entity;
    }
}
