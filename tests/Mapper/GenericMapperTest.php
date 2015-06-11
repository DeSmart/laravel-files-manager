<?php

namespace test\DeSmart\Files\Mapper;

use Prophecy\Argument;
use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\Mapper\GenericMapper;
use Illuminate\Contracts\Filesystem\Filesystem;

class GenericMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testSettingPath()
    {
        $entity = new FileEntity;
        $entity->setName($name = 'Foo.jpg');

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->getMock();

        $filesystem->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo.jpg'))
            ->willReturn(false);

        $mapper = new GenericMapper($filesystem);
        $mapper->map($entity);

        $this->assertRegExp('#^\w{2}/\w{2}/Foo.jpg$#', $entity->getPath());
    }

    public function testSettingPathForExistingFile()
    {
        $entity = new FileEntity;
        $entity->setName('Foo.jpg');

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->getMock();

        $filesystem->expects($this->at(0))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo.jpg'))
            ->willReturn(true);

        $filesystem->expects($this->at(1))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo-%x.jpg'))
            ->willReturn(true);

        $mapper = new GenericMapper($filesystem);
        $mapper->map($entity);

        $this->assertRegExp('#^\w{2}/\w{2}/Foo-[a-f0-9]+\.jpg#', $entity->getPath());
    }

    public function testEscapingFilename()
    {
        $entity = new FileEntity;
        $entity->setName($name = 'Foo Bar.jpg');
        $escapedName = 'Foo-Bar.jpg';

        $filesystem = $this->prophesize(Filesystem::class);
        $filesystem->exists(Argument::any())->willReturn(false);

        $mapper = new GenericMapper($filesystem->reveal());
        $mapper->map($entity);

        $this->assertRegExp('#^\w{2}/\w{2}/'.$escapedName.'$#', $entity->getPath());
    }

    public function testEscapingExistingFileName()
    {
        $entity = new FileEntity;
        $entity->setName('Foo Bar.jpg');

        $filesystem = $this->getMockBuilder(Filesystem::class)
            ->getMock();

        $filesystem->expects($this->at(0))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo-Bar.jpg'))
            ->willReturn(true);

        $filesystem->expects($this->at(1))
            ->method('exists')
            ->with($this->matches('%c%c/%c%c/Foo-Bar-%x.jpg'))
            ->willReturn(true);

        $mapper = new GenericMapper($filesystem);
        $mapper->map($entity);

        $this->assertRegExp('#^\w{2}/\w{2}/Foo-Bar-[a-f0-9]+\.jpg#', $entity->getPath());
    }
}
