<?php

namespace test\DeSmart\Files\Model;

use DeSmart\Files\Model\File;
use DeSmart\Files\Entity\FileEntity;

class FileTest extends \PHPUnit_Framework_TestCase
{

    public function testCreatingFromEntity()
    {
        $entity = new FileEntity;
        $entity->setId(123);
        $entity->setName('Foo.jpg');
        $entity->setPath('ab/cd/foo.png');
        $entity->setSize(1000);
        $entity->setCreatedAt(null);
        $entity->setMd5Checksum(md5(time()));

        $model = (new File)->createFromEntity($entity);

        $this->assertInstanceOf(File::class, $model);
        $this->assertTrue($model->exists);
        $this->assertEquals($model->id, $entity->getId());
        $this->assertEquals($model->name, $entity->getName());
        $this->assertEquals($model->path, $entity->getPath());
        $this->assertEquals($model->size, $entity->getSize());
        $this->assertEquals($model->created_at, $entity->getCreatedAt());
        $this->assertEquals($model->md5_checksum, $entity->getMd5Checksum());
    }

    public function testCreatingFromEntityWithoutIdReturnsNonExistingModel()
    {
        $entity = new FileEntity;
        $model = (new File)->createFromEntity($entity);

        $this->assertFalse($model->exists);
    }

    public function testModelDoesNotHaveTimestamps()
    {
        $this->assertFalse((new File)->timestamps);
    }

    public function testConvertingToEntity()
    {
        $model = new File;
        $model->id = 1;
        $model->name = 'Foo.jpg';
        $model->path = 'ab/cd/Foo.jpg';
        $model->size = 1000;
        $model->md5_checksum = md5(time());
        $model->created_at = null;

        $entity = $model->toEntity();

        $this->assertInstanceOf(FileEntity::class, $entity);
        $this->assertEquals(1, $entity->getId());
        $this->assertEquals('Foo.jpg', $entity->getName());
        $this->assertEquals('ab/cd/Foo.jpg', $entity->getPath());
        $this->assertEquals(1000, $entity->getSize());
        $this->assertEquals($model->md5_checksum, $entity->getMd5Checksum());
        $this->assertNull($entity->getCreatedAt());
    }
}
