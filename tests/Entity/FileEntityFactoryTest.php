<?php

namespace test\DeSmart\Files\Entity;

use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\Entity\FileEntityFactory;
use DeSmart\Files\FileSource\FileSourceInterface;

class FileEntityFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatingFromFileSource()
    {
        $source = $this->prophesize(FileSourceInterface::class);
        $source->getName()->willReturn($name = 'Foo.jpg');
        $source->getSize()->willReturn($size = 10000);
        $source->getMd5Checksum()->willReturn($md5Sum = md5(time()));

        $factory = new FileEntityFactory;
        $entity = $factory->createFromFileSource($source->reveal());

        $this->assertInstanceOf(FileEntity::class, $entity);
        $this->assertEquals($name, $entity->getName());
        $this->assertEquals($size, $entity->getSize());
        $this->assertEquals($md5Sum, $entity->getMd5Checksum());
    }
}
