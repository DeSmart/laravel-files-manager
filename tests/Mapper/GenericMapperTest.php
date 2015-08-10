<?php

namespace test\DeSmart\Files\Mapper;

use Prophecy\Argument;
use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\Mapper\GenericMapper;
use DeSmart\Files\FileSource\FileSourceInterface;
use Illuminate\Contracts\Filesystem\Filesystem as Storage;

class GenericMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testSettingPath()
    {
        $entity = new FileEntity;
        $entity->setName($name = 'Foo.jpg');

        $source = $this->prophesize(FileSourceInterface::class);

        $storage = $this->getMockBuilder(Storage::class)
            ->getMock();

        $storage->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo.jpg'))
            ->willReturn(false);

        $mapper = new GenericMapper($storage);
        $mapper->map($entity, $source->reveal());

        $this->assertRegExp('#^\w{2}/\w{2}/Foo.jpg$#', $entity->getPath());
    }

    public function testSettingPathForExistingFile()
    {
        $entity = new FileEntity;
        $entity->setName('Foo.jpg');

        $source = $this->prophesize(FileSourceInterface::class);

        $storage = $this->getMockBuilder(Storage::class)
            ->getMock();

        $storage->expects($this->at(0))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo.jpg'))
            ->willReturn(true);

        $storage->expects($this->at(1))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo-%x.jpg'))
            ->willReturn(true);

        $mapper = new GenericMapper($storage);
        $mapper->map($entity, $source->reveal());

        $this->assertRegExp('#^\w{2}/\w{2}/Foo-[a-f0-9]+\.jpg#', $entity->getPath());
    }

    public function testEscapingFilename()
    {
        $entity = new FileEntity;
        $entity->setName($name = 'Foo Bar.jpg');
        $escapedName = 'Foo-Bar.jpg';

        $source = $this->prophesize(FileSourceInterface::class);

        $storage = $this->prophesize(Storage::class);
        $storage->exists(Argument::any())->willReturn(false);

        $mapper = new GenericMapper($storage->reveal());
        $mapper->map($entity, $source->reveal());

        $this->assertRegExp('#^\w{2}/\w{2}/'.$escapedName.'$#', $entity->getPath());
    }

    public function testEscapingExistingFileName()
    {
        $entity = new FileEntity;
        $entity->setName('Foo Bar.jpg');

        $source = $this->prophesize(FileSourceInterface::class);

        $storage = $this->getMockBuilder(Storage::class)
            ->getMock();

        $storage->expects($this->at(0))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo-Bar.jpg'))
            ->willReturn(true);

        $storage->expects($this->at(1))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo-Bar-%x.jpg'))
            ->willReturn(true);

        $mapper = new GenericMapper($storage);
        $mapper->map($entity, $source->reveal());

        $this->assertRegExp('#^\w{2}/\w{2}/Foo-Bar-[a-f0-9]+\.jpg#', $entity->getPath());
    }
}
