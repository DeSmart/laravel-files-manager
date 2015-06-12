<?php namespace DeSmart\Files;

use DeSmart\Files\Mapper\MapperInterface;
use DeSmart\Files\Entity\FileEntityFactory;
use DeSmart\Files\FileSource\FileSourceInterface;
use Illuminate\Contracts\Filesystem\Filesystem as Storage;

class Manager
{
    
    /**
     * @var \DeSmart\Files\FileRepository
     */
    protected $repository;

    /**
     * @var \DeSmart\Files\Entity\FileEntityFactory
     */
    protected $entityFactory;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * @var \DeSmart\Files\Mapper\MapperInterface[]
     */
    protected $mappers = [];

    public function __construct(FileRepository $repository, FileEntityFactory $entityFactory, Storage $storage)
    {
        $this->repository = $repository;
        $this->entityFactory = $entityFactory;
        $this->storage = $storage;
    }

    /**
     * @param \DeSmart\Files\Mapper\MapperInterface[] $mappers
     */
    public function setMappers(MapperInterface ...$mappers)
    {
        $this->mappers = $mappers;
    }

    /**
     * Store file in filesystem and database
     *
     * If file already exists in database no actions will be performed.
     *
     * @param \DeSmart\Files\FileSource\FileSourceInterface $file
     * @return \DeSmart\Files\Entity\FileEntity
     */
    public function store(FileSourceInterface $file)
    {
        $entity = $this->repository->findByChecksum($file->getMd5Checksum());

        if (null !== $entity) {
            return $entity;
        }

        $entity = $this->entityFactory->createFromFileSource($file);

        foreach ($this->mappers as $mapper) {
            $mapper->map($entity);
        }

        $file->save($this->storage, $entity->getPath());
        $this->repository->save($entity);

        return $entity;
    }
}
