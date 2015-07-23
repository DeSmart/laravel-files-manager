<?php namespace DeSmart\Files\Entity;

use DeSmart\Support\Uuid;
use DeSmart\Files\FileSource\FileSourceInterface;

class FileEntityFactory
{

    /**
     * @var string
     */
    protected $entityClassName;

    public function __construct($entityClassName = null)
    {
        $this->setEntityClassName($entityClassName);
    }

    /**
     * Sets name of file entity class which will be hydrated by factory
     *
     * @param string $entityClassName
     * @throws \InvalidArgumentException
     */
    protected function setEntityClassName($entityClassName = null)
    {

        if (null !== $entityClassName && false === is_a($entityClassName, FileEntity::class, true)) {
            throw new \InvalidArgumentException('Entity must be subclass of '.FileEntity::class);
        }

        $this->entityClassName = $entityClassName ?: FileEntity::class;
    }

    /**
     * Creates entity from file source
     *
     * @param \DeSmart\Files\FileSource\FileSourceInterface
     * @return \DeSmart\Files\Entity\FileEntity
     */
    public function createFromFileSource(FileSourceInterface $file)
    {
        $entity = new $this->entityClassName;
        $entity->setId(Uuid::generateUuid());
        $entity->setSize($file->getSize());
        $entity->setName($file->getName());
        $entity->setMd5Checksum($file->getMd5Checksum());

        return $entity;
    }
}
