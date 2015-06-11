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
}
