<?php

use Mockery as m;
use DeSmart\Files\Model\File;

class ModelFileTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    m::close();
  }

  public function testCreateFromUpload() {
    $source = m::mock('DeSmart\Files\Uploader\SourceInterface');
    $source->shouldReceive('getExtension')->once()->andReturn($ext = 'jpg');
    $source->shouldReceive('getName')->twice()->andReturn($name = 'test.jpg');
    $source->shouldReceive('getSize')->once()->andReturn($size = 123);
    $source->shouldReceive('getMimeType')->once()->andReturn($mime = 'image/jpeg');
    $path = 'ab/cd/';

    $model = File::createFromUpload($source, $path);

    $this->assertInstanceOf('\DeSmart\Files\Model\File', $model);
    $this->assertInstanceOf('DateTime', $model->date);
    $this->assertEquals($ext, $model->extension);
    $this->assertEquals($name, $model->name);
    $this->assertEquals($name, $model->original_name);
    $this->assertEquals($path, $model->path);
    $this->assertEquals($mime, $model->type);
    $this->assertEquals($size, $model->size);
  }

}
