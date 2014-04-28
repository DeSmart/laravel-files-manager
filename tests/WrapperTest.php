<?php

use DeSmart\Files\Wrapper;
use Mockery as m;
use DeSmart\Files\Model\File;

class WrapperTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Wrapper
   */
  protected $wrapper;

  public function setUp() {
    $file = new File();
    $file['path'] = 'images/srpr/';
    $file['original_name'] = 'logo.png';

    $config = m::mock('Illuminate\Config\Repository');
    $config->shouldReceive('get')->once()->with('app.dms_url')->andReturn('http://dms.local.com');

    $this->wrapper = new Wrapper($file, $config);
  }

  public function tearDown() {
    m::close();
  }

  public function testResize() {
    $resize = $this->wrapper->resize(51, 49);

    $this->assertEquals($resize->toString(), "http://dms.local.com/ResizeImage/images/srpr/51,49,100,3,_logo.png");
  }

  public function testQuality() {
    $quality = $this->wrapper->quality(39);

    $this->assertEquals($quality->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,39,3,_logo.png");
  }

  public function testCrop() {
    $this->wrapper->crop();

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,crop-1_logo.png");
  }

  public function testSharpen() {
    $this->wrapper->sharpen();

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,sharpen-1_logo.png");
  }

  public function testKeepRatio() {
    $this->wrapper->keepRatio();

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,keepratio-1_logo.png");
  }

  public function testMaxWh() {
    $this->wrapper->maxWh();

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,maxwh-1_logo.png");
  }

  public function testResizeAndCrop() {
    $resize = $this->wrapper->resize(51, 49);
    $resize->crop();

    $this->assertEquals($resize->toString(), "http://dms.local.com/ResizeImage/images/srpr/51,49,100,3,crop-1_logo.png");
  }

  public function testKeepRatioAndSharpen() {
    $this->wrapper->keepRatio();
    $this->wrapper->sharpen();

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,sharpen-1;keepratio-1_logo.png");
  }

  public function testKeepRatioAndCropAndSharpenAndMaxWh() {
    $this->wrapper->keepRatio();
    $this->wrapper->crop();
    $this->wrapper->sharpen();
    $this->wrapper->maxWh();

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,crop-1;sharpen-1;keepratio-1;maxwh-1_logo.png");
  }

  public function testCropAndSharpenAndMaxWhAndResizeAndQuality() {
    $this->wrapper->crop();
    $this->wrapper->sharpen();
    $this->wrapper->maxWh();
    $this->wrapper->resize(19, 81);
    $this->wrapper->quality(15);

    $this->assertEquals($this->wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/19,81,15,3,crop-1;sharpen-1;maxwh-1_logo.png");
  }

}
