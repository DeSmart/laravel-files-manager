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

        $model = (new File)->createFromEntity($entity);

        $this->assertInstanceOf(File::class, $model);
        $this->assertTrue($model->exists);
        $this->assertEquals($model->id, $entity->getId());
        $this->assertEquals($model->name, $entity->getName());
        $this->assertEquals($model->path, $entity->getPath());
        $this->assertEquals($model->size, $entity->getSize());
        $this->assertEquals($model->created_at, $entity->getCreatedAt());
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
}
