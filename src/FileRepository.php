<?php namespace DeSmart\Files;

class FileRepository
{

    /**
     * @var \DeSmart\Files\Model\File
     */
    protected $query;

    public function __construct(Model\File $query)
    {
        $this->query = $query;
    }

    /**
     * @param string $md5Checksum
     * @return \DeSmart\Files\Entity\FileEntity|null
     */
    public function findByChecksum($md5Checksum)
    {

        if (true === empty($md5Checksum)) {
            throw new \InvalidArgumentException('Empty md5_checksum');
        }

        $model = $this->query->where('md5_checksum', $md5Checksum)
            ->first();

        return is_null($model) ? null : $model->toEntity();
    }

    public function save(Entity\FileEntity $entity)
    {
        $exists = (null !== $entity->getId());
        $model = $this->query->createFromEntity($entity);

        if (false === $exists) {
            $model->created_at = date_create();
        }

        $model->save();

        if (false === $exists) {
            $entity->setCreatedAt($model->created_at);
            $entity->setId($model->getKey());
        }
    }
}
