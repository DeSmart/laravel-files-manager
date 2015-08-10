<?php

namespace test\DeSmart\Files;

use Prophecy\Argument;
use Ggd\Files\Model\File;
use Ggd\Files\Entity\FileEntity;
use DeSmart\Files\FileRepository;
use Illuminate\Database\DatabaseManager;

class FileRepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testInsertingEntity()
    {
        $entity = new FileEntity;
        $model = $this->prophesize(File::class);
        $model->save()->shouldBeCalled();

        $query = $this->prophesize(File::class);
        $query->createFromEntity($entity)->willReturn($model);

        $db = $this->prophesize(DatabaseManager::class);

        $repository = new FileRepository($query->reveal(), $db->reveal());
        $repository->insert($entity);

        $this->assertRegExp('/^[0-9a-f]{32}$/', $entity->getId());
        $this->assertEquals(date_create()->getTimestamp(), $entity->getCreatedAt()->getTimestamp());
    }

    public function testUpdatingEntity()
    {
        $entity = new FileEntity;
        $entity->setId('file-1');

        $model = $this->prophesize(File::class);
        $model->save()->shouldBeCalled();

        $query = $this->prophesize(File::class);
        $query->createFromEntity($entity)->willReturn($model);

        $db = $this->prophesize(DatabaseManager::class);

        $repository = new FileRepository($query->reveal(), $db->reveal());
        $repository->update($entity);

        $this->assertEquals('file-1', $entity->getId());
    }
}
