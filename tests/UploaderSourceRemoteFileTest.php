<?php

use Mockery as m;
use DeSmart\Files\Uploader\Source\RemoteFile;

class UploaderSourceRemoteFileTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    m::getConfiguration()->allowMockingNonExistentMethods(false);
  }

  public function tearDown() {
    m::close();
  }

  public function testGetExtensionProcess() {
    $source = new RemoteFile($url = 'https://i.chzbgr.com/maxW500/7887742208/h9FA24865/');
    $response = m::mock('Guzzle\Http\Message\Response');
    $request = m::mock('Guzzle\Http\Message\RequestInterface');
    $guzzle = m::mock('Guzzle\Http\Client');
    $guesser = m::mock('Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesserInterface');

    $response->shouldReceive('getContentType')->once()->andReturn($mime = 'image/jpeg');
    $request->shouldReceive('send')->once()->andReturn($response);
    $guzzle->shouldReceive('get')->once()->with($url)->andReturn($request);
    $guesser->shouldReceive('guess')->once()->with($mime)->andReturn($ext = 'jpeg');

    $source->setGuzzle($guzzle);
    $source->setGuesser($guesser);

    $this->assertEquals($ext, $source->getExtension());
  }

  /**
   * @dataProvider getNameProvider
   */
  public function testGetName($url, $guessedExtension, $expected) {
    $source = $this->getMock('DeSmart\Files\Uploader\Source\RemoteFile', array('getExtension'), array($url));
    $source->expects($this->once())->method('getExtension')->will($this->returnValue($guessedExtension));

    $this->assertEquals($expected, $source->getName());
  }

  public function getNameProvider() {
    return array(
      array('http://wp.pl/foo/bar/baz.jpg', 'jpg', 'baz.jpg'),
      array('http://wp.pl/foobaz.jpg', 'jpg', 'foobaz.jpg'),
      array('https://i.chzbgr.com/maxW500/7887742208/h9FA24865/', 'jpg', 'h9FA24865.jpg'),
      array('https://i.chzbgr.com/maxW500/7887742208/h9 FA2++4865/', 'gif', 'h9_FA2_4865.gif'),
      array('https://i.chzbgr.com/maxW500/7887742208/h9 FA2+4865/', 'gif', 'h9_FA2_4865.gif'),
    );
  }

  public function testGetSizeProcess() {
    $source = new RemoteFile($url = 'https://i.chzbgr.com/maxW500/7887742208/h9FA24865/');
    $body = m::mock('Guzzle\Http\EntityBodyInterface');
    $response = m::mock('Guzzle\Http\Message\Response');
    $request = m::mock('Guzzle\Http\Message\RequestInterface');
    $guzzle = m::mock('Guzzle\Http\Client');

    $body->shouldReceive('getSize')->once()->andReturn($size = 1235231);
    $response->shouldReceive('getBody')->once()->andReturn($body);
    $request->shouldReceive('send')->once()->andReturn($response);
    $guzzle->shouldReceive('get')->once()->with($url)->andReturn($request);

    $source->setGuzzle($guzzle);

    $this->assertEquals($size, $source->getSize());
  }

  public function testGetMimeTypeProcess() {
    $source = new RemoteFile($url = 'https://i.chzbgr.com/maxW500/7887742208/h9FA24865/');
    $response = m::mock('Guzzle\Http\Message\Response');
    $request = m::mock('Guzzle\Http\Message\RequestInterface');
    $guzzle = m::mock('Guzzle\Http\Client');

    $response->shouldReceive('getContentType')->once()->andReturn($mime = 'image/jpeg');
    $request->shouldReceive('send')->once()->andReturn($response);
    $guzzle->shouldReceive('get')->once()->with($url)->andReturn($request);

    $source->setGuzzle($guzzle);

    $this->assertEquals($mime, $source->getMimeType());
  }

  public function testGetFilepathProcess() {
    $source = new RemoteFile($url = 'https://i.chzbgr.com/maxW500/7887742208/h9FA24865/');
    $response = m::mock('Guzzle\Http\Message\Response');
    $request = m::mock('Guzzle\Http\Message\RequestInterface');
    $guzzle = m::mock('Guzzle\Http\Client');
    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $file_name = null;
    $matcher = function($name) use (& $file_name) {

      if(true === is_string($name)) {
        $file_name = $name;
        return true;
      }

      return false;
    };

    $response->shouldReceive('getBody')->once()->with(true)->andReturn($body = 'lulz');
    $request->shouldReceive('send')->once()->andReturn($response);
    $guzzle->shouldReceive('get')->once()->with($url)->andReturn($request);
    $fs->shouldReceive('put')->once()->with(m::on($matcher), $body);

    $source->setGuzzle($guzzle);
    $source->setFilesystem($fs);

    // to musi byc wywolane przed assertEquals
    // w przeciwnym razie $file_name bedzie NULL
    $path = $source->getFilepath();

    $this->assertEquals($file_name, $path);
    $this->assertNotNull($file_name);

    // dla pewnosci, ze plik nie zostal ponownie zapisany
    $this->assertEquals($file_name, $path);

    return array($source, $file_name);
  }

  /**
   * @depends testGetFilepathProcess
   */
  public function testDestructorRemovesFile(array $stack) {
    list($source, $file_name) = $stack;

    $fs = m::mock('Illuminate\Filesystem\Filesystem');
    $fs->shouldReceive('delete')->once()->with($file_name);

    $source->setFilesystem($fs);
    $source->__destruct();
  }

}
