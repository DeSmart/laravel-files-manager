<?php

namespace test\DeSmart\Files;

use Prophecy\Argument;
use DeSmart\Files\Model\File;
use DeSmart\Files\FileRepository;
use DeSmart\Files\Entity\FileEntity;

class FileRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSaving()
    {
        $entity = new FileEntity;

        $createdAt = date_create();
        $model = $this->prophesize(File::class);
        $model->save()->shouldBeCalled();
        $model->getKey()->willReturn(12);
        $model->getAttribute('created_at')->willReturn($createdAt);
        $model->setAttribute('created_at', Argument::type('\DateTime'))->shouldBeCalled();

        $query = $this->prophesize(File::class);
        $query->createFromEntity($entity)->willReturn($model);

        $repository = new FileRepository($query->reveal());
        $repository->save($entity);

        $this->assertEquals(12, $entity->getId());
        $this->assertEquals($createdAt->getTimestamp(), $entity->getCreatedAt()->getTimestamp());
    }

    public function testSavingExistingEntity()
    {
        $entity = new FileEntity;
        $entity->setId(1);

        $model = $this->prophesize(File::class);
        $model->getKey()->willReturn(123);
        $model->save()->shouldBeCalled();

        $query = $this->prophesize(File::class);
        $query->createFromEntity($entity)->willReturn($model);

        $repository = new FileRepository($query->reveal());
        $repository->save($entity);

        $this->assertEquals(1, $entity->getId());
    }
}
