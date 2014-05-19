<?php

use Mockery as m;
use DeSmart\Files\Uploader\Source\UploadedFile;

class UploaderSourceUploadedFileTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    m::getConfiguration()->allowMockingNonExistentMethods(false);
  }

  public function tearDown() {
    m::close();
  }

  public function testGetExtension() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('guessExtension')->once()->andReturn($ext = 'jpg');

    $files = new UploadedFile($mock);
    $this->assertEquals($ext, $files->getExtension());
  }

  public function testGetExtensionWhenGuessFailed() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('guessExtension')->once()->andReturn(null);
    $mock->shouldReceive('getExtension')->once()->andReturn($ext = 'jpg');

    $files = new UploadedFile($mock);
    $this->assertEquals($ext, $files->getExtension());
  }

  public function testGetName() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('getClientOriginalName')->once()->andReturn('foobar.txt');
    $mock->shouldReceive('guessExtension')->once()->andReturn('jpg');

    $files = new UploadedFile($mock);
    $this->assertEquals('foobar.jpg', $files->getName());
  }

  public function testGetNameWithInvalidCharacters() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('getClientOriginalName')->once()->andReturn('foo bar-bar#yolo##swag');
    $mock->shouldReceive('guessExtension')->once()->andReturn('jpg');

    $files = new UploadedFile($mock);
    $this->assertEquals('foo_bar_bar_yolo_swag.jpg', $files->getName());
  }

  public function testGetSize() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('getSize')->once()->andReturn($size = 123);

    $files = new UploadedFile($mock);
    $this->assertEquals($size, $files->getSize());
  }

  public function testGetMimeType() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('getMimeType')->once()->andReturn($mime = 'test/plain');

    $files = new UploadedFile($mock);
    $this->assertEquals($mime, $files->getMimeType());
  }

  public function testGetFilepath() {
    $mock = m::mock('Symfony\Component\HttpFoundation\File\UploadedFile');
    $mock->shouldReceive('getPathname')->once()->andReturn($path = 'foo');

    $files = new UploadedFile($mock);
    $this->assertEquals($path, $files->getFilepath());
  }

}
