<?php namespace DeSmart\Files;

use DeSmart\Files\Mapper\MapperInterface;
use DeSmart\Files\Entity\FileEntityFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use DeSmart\Files\FileSource\FileSourceInterface;

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
    protected $filesystem;

    /**
     * @var \DeSmart\Files\Mapper\MapperInterface[]
     */
    protected $mappers = [];

    public function __construct(FileRepository $repository, FileEntityFactory $entityFactory, Filesystem $filesystem)
    {
        $this->repository = $repository;
        $this->entityFactory = $entityFactory;
        $this->filesystem = $filesystem;
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

        $file->save($this->filesystem, $entity->getPath());
        $this->repository->save($entity);

        return $entity;
    }
}
