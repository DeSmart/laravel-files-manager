<?php namespace DeSmart\Files\Model;

use DeSmart\Files\Entity\FileEntity;

class File extends \Illuminate\Database\Eloquent\Model
{

    /**
     * Timestamps are disabled for this model
     *
     * In files table we need only `created_at` field.
     * If timestamps = TRUE Laravel will try to set both updated_at and created_at.
     *
     * @var boolean
     */
    public $timestamps = false;

    public $incrementing = false;

    public function createFromEntity(FileEntity $entity)
    {
        $attributes = [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'path' => $entity->getPath(),
            'size' => $entity->getSize(),
            'md5_checksum' => $entity->getMd5Checksum(),
            'created_at' => $entity->getCreatedAt(),
        ];

        $exists = (null !== $entity->getId());

        static::unguard();
        $model = $this->newInstance($attributes, $exists);
        static::reguard();

        return $model;
    }

    /**
     * Convert model to entity
     *
     * @return \DeSmart\Files\Entity\FileEntity
     */
    public function toEntity()
    {
        $entity = new FileEntity;
        $entity->setId($this->getKey());
        $entity->setName($this->name);
        $entity->setPath($this->path);
        $entity->setSize($this->size);
        $entity->setMd5Checksum($this->md5_checksum);
        $entity->setCreatedAt($this->created_at);

        return $entity;
    }
}
