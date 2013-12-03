<?php

use Mockery as m;
use DeSmart\Files\Uploader\Source\LocalFile;

class UploaderSourceLocalFileTest extends \PHPUnit_Framework_TestCase {

  protected $filepath;

  public function setUp() {
    $this->filepath = __DIR__.'/stubs/42.jpg';
  }

  public function tearDown() {
    m::close();
  }

  public function testIfExceptionWhenFileDoesntExist() {
    $this->setExpectedException('InvalidArgumentException');
    new LocalFile('/never/give/you/up.png');
  }

  public function testGetExtension() {
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('extension')->once()->with($this->filepath)->andReturn('jpg');

    $file = new LocalFile($this->filepath);
    $file->setFilesystem($fs);

    $this->assertEquals('jpg', $file->getExtension());
  }

  public function testGetName() {
    $file = new LocalFile($this->filepath);
    $this->assertEquals('42.jpg', $file->getName());

    $file = new LocalFile(__DIR__.'/stubs/to jest glupia nazwa.jpg');
    $this->assertEquals('to_jest_glupia_nazwa.jpg', $file->getName());
  }

  public function testGetSize() {
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('size')->once()->with($this->filepath)->andReturn($size = 42);

    $file = new LocalFile($this->filepath);
    $file->setFilesystem($fs);
    $this->assertEquals($size, $file->getSize());
  }

  public function testGetMimeType() {
    $guesser = m::mock('Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser');
    $guesser->shouldReceive('guess')->with($this->filepath)->andReturn($mime = 'image/jpg');

    $file = new LocalFile($this->filepath);
    $file->setGuesser($guesser);
    $this->assertEquals($mime, $file->getMimeType());
  }

  public function testGetFilePath() {
    $file = new LocalFile($this->filepath);
    $this->assertEquals($this->filepath, $file->getFilepath());
  }

}
