<?php 

use Mockery as m;
use DeSmart\Files\Model\File;
use DeSmart\Files\Uploader\DmsUploader;

class UploaderDmsUploaderTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    m::getConfiguration()->allowMockingNonExistentMethods(false);
  }

  public function tearDown() {
    m::close();
  }

  public function testUploadProcessWhenSuccess() {
    $base_url = 'http://foodboxdms.local.com';
    $guzzle = m::mock('Guzzle\Http\Client');
    $request = m::mock('Guzzle\Http\Message\RequestInterface');
    $response = m::mock('Guzzle\Http\Message\Response');
    $source = m::mock('DeSmart\Files\Uploader\SourceInterface');
    $model = m::mock('DeSmart\Files\Model\File');

    $uploader = $this->getMock('DeSmart\Files\Uploader\DmsUploader', array('generatePath'), array($base_url));
    $uploader->expects($this->once())->method('generatePath')->will($this->returnValue($path = 'ab/cd/'));
    $uploader->setGuzzle($guzzle);
    $uploader->setModel($model);

    $source->shouldReceive('getFilepath')->once()->andReturn($file_path = '/tmp/foo');
    $source->shouldReceive('getName')->once()->andReturn($name = 'test.jpg');
    $guzzle->shouldReceive('post')->with($base_url.'/mod/dmsFiles2Records/RemoteGetFile/', null, array(
        'file_info' => '@'.$file_path,
        'dest_file_name' => $name,
        'dest_path' => $path,
      ))
      ->andReturn($request);
    $request->shouldReceive('send')->once()->andReturn($response);
    $response->shouldReceive('isError')->once()->andReturn(false);
    $model->shouldReceive('createFromUpload')->once()->with($source, $path)->andReturn($model);

    $this->assertEquals($model, $uploader->upload($source));
    $this->assertEquals($response, $uploader->getResponse());
  }

  public function testUploadProcessOnError() {
    $guzzle = m::mock('Guzzle\Http\Client');
    $request = m::mock('Guzzle\Http\Message\RequestInterface');
    $response = m::mock('Guzzle\Http\Message\Response');
    $source = m::mock('DeSmart\Files\Uploader\SourceInterface');

    $uploader = $this->getMock('DeSmart\Files\Uploader\DmsUploader', array('generatePath'), array('http://foodboxdms.local.com'));
    $uploader->expects($this->once())->method('generatePath')->will($this->returnValue($path = 'ab/cd/'));
    $uploader->setGuzzle($guzzle);

    $source->shouldReceive('getFilepath')->once()->andReturn($file_path = '/tmp/foo');
    $source->shouldReceive('getName')->once()->andReturn($name = 'test.jpg');
    $guzzle->shouldReceive('post')->andReturn($request);
    $request->shouldReceive('send')->once()->andReturn($response);
    $response->shouldReceive('isError')->once()->andReturn(true);

    $this->assertNull($uploader->upload($source));
    $this->assertEquals($response, $uploader->getResponse());
  }

}
