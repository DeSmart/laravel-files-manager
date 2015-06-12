<?php

namespace test\DeSmart\Files;

use DeSmart\Files\Manager;
use DeSmart\Files\FileRepository;
use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\Mapper\MapperInterface;
use DeSmart\Files\Entity\FileEntityFactory;
use DeSmart\Files\FileSource\FileSourceInterface;
use Illuminate\Contracts\Filesystem\Filesystem as Storage;

class ManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testFileStoring()
    {
        $entity = new FileEntity;
        $entity->setPath($path = 'ab/cd/foo.jpg');
        $entity->setMd5Checksum(md5('foo'));

        $factory = $this->prophesize(FileEntityFactory::class);
        $mapper = $this->prophesize(MapperInterface::class);
        $source = $this->prophesize(FileSourceInterface::class);
        $repository = $this->prophesize(FileRepository::class);
        $storage = $this->prophesize(Storage::class);

        $repository->findByChecksum($entity->getMd5Checksum())->willReturn(null);
        $factory->createFromFileSource($source)->willReturn($entity);
        $mapper->map($entity)->shouldBeCalled();
        $repository->save($entity)->shouldBeCalled();
        $source->getMd5Checksum()->willReturn($entity->getMd5Checksum());
        $source->save($storage, $path)->shouldBeCalled();

        $manager = new Manager($repository->reveal(), $factory->reveal(), $storage->reveal());
        $manager->setMappers($mapper->reveal());

        $this->assertSame($entity, $manager->store($source->reveal()));
    }

    public function testStoringExistingFile()
    {
        $entity = new FileEntity;
        $entity->setMd5Checksum(md5('foo'));

        $factory = $this->prophesize(FileEntityFactory::class);
        $mapper = $this->prophesize(MapperInterface::class);
        $source = $this->prophesize(FileSourceInterface::class);
        $repository= $this->prophesize(FileRepository::class);
        $storage = $this->prophesize(Storage::class);

        $source->getMd5Checksum()->shouldBeCalled()->willReturn($entity->getMd5Checksum());
        $repository->findByChecksum($entity->getMd5Checksum())->willReturn($entity);

        $manager = new Manager($repository->reveal(), $factory->reveal(), $storage->reveal());
        $manager->setMappers($mapper->reveal());

        $this->assertSame($entity, $manager->store($source->reveal()));
    }
}
