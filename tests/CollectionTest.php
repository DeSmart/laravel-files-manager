<?php

use DeSmart\Files\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase {

  public function testPopDefaultFile() {
    $collection = new Collection(array(
      (object) array('id' => 1, 'is_default' => 0),
      $default = (object) array('id' => 2, 'is_default' => 1),
      (object) array('id' => 3, 'is_default' => 0),
    ));

    $expected = array(
      (object) array('id' => 1, 'is_default' => 0),
      (object) array('id' => 3, 'is_default' => 0),
    );

    $this->assertEquals($default, $collection->popDefaultFile());
    $this->assertEquals($expected, $collection->all());
  }

  public function testPopDefualtFileReturnsFirstFile() {
    $collection = new Collection(array(
      (object) array('id' => 1, 'is_default' => 0),
      $default = (object) array('id' => 2, 'is_default' => 1),
      (object) array('id' => 3, 'is_default' => 1),
      (object) array('id' => 4, 'is_default' => 0),
    ));

    $expected = array(
      (object) array('id' => 1, 'is_default' => 0),
      (object) array('id' => 3, 'is_default' => 1),
      (object) array('id' => 4, 'is_default' => 0),
    );

    $this->assertEquals($default, $collection->popDefaultFile());
    $this->assertEquals($expected, $collection->all());
  }

  public function testPopDefaultFileReturnNull() {
    $collection = new Collection($expected = array(
      (object) array('id' => 1, 'is_default' => 0),
      (object) array('id' => 2, 'is_default' => 0),
      (object) array('id' => 3, 'is_default' => 0),
    ));

    $this->assertNull($collection->popDefaultFile());
    $this->assertEquals($expected, $collection->all());
  }

}
