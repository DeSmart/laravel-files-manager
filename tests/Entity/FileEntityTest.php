<?php 

namespace test\DeSmart\Files\Entity;

use DeSmart\Files\Entity\FileEntity;

class FileEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testSettingId()
    {
        $entity = new FileEntity;
        $entity->setId(1);

        $this->assertEquals(1, $entity->getId());
    }

    public function testSettingName()
    {
        $entity = new FileEntity;
        $entity->setName('foo');

        $this->assertEquals('foo', $entity->getName());
    }

    public function testSettingPath()
    {
        $entity = new FileEntity;
        $entity->setPath($path = 'ab/cd/efg.png');

        $this->assertEquals($path, $entity->getPath());
    }

    public function testSettingSize()
    {
        $entity = new FileEntity;
        $entity->setSize(1234);

        $this->assertEquals(1234, $entity->getSize());
    }

    public function testSettingSizeConvertsItToInteger()
    {
        $entity = new FileEntity;
        $entity->setSize('1234');

        $this->assertSame(1234, $entity->getSize());
    }

    public function testSettingCreatedAtFromString()
    {
        $date = '2014-01-01 12:00';
        $entity = new FileEntity;
        $entity->setCreatedAt($date);

        $this->assertEquals(new \DateTimeImmutable($date), $entity->getCreatedAt());
    }

    public function testSettingCreatedAtFromDateTimeInterface()
    {
        $date = '2014-01-01 12:00';
        $entity = new FileEntity;
        $entity->setCreatedAt(date_create($date));

        $this->assertEquals(new \DateTimeImmutable($date), $entity->getCreatedAt());
    }
}
