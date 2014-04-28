<?php

use DeSmart\Files\Wrapper;
use Mockery as m;
use DeSmart\Files\Model\File;

class WrapperTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    m::close();
  }

  public function testResize() {
    $wrapper = $this->factory()->resize(51, 49);

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/51,49,100,3,_logo.png");
  }

  public function testQuality() {
    $wrapper = $this->factory()->quality(39);

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,39,3,_logo.png");
  }

  public function testCrop() {
    $wrapper = $this->factory()->crop();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,crop-1_logo.png");
  }

  public function testSharpen() {
    $wrapper = $this->factory('logo.jpg')->sharpen();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,2,sharpen-1_logo.jpg");
  }

  public function testKeepRatio() {
    $wrapper = $this->factory('logo.jpg')->keepRatio();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,2,keepratio-1_logo.jpg");
  }

  public function testMaxWh() {
    $wrapper = $this->factory()->maxWh();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,maxwh-1_logo.png");
  }

  public function testResizeAndCrop() {
    $wrapper = $this->factory()->resize(51, 49);
    $wrapper->crop();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/51,49,100,3,crop-1_logo.png");
  }

  public function testKeepRatioAndSharpen() {
    $wrapper = $this->factory('logo.gif')->keepRatio()
      ->sharpen();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,1,sharpen-1;keepratio-1_logo.gif");
  }

  public function testKeepRatioAndCropAndSharpenAndMaxWh() {
    $wrapper = $this->factory()->keepRatio()
      ->crop()
      ->sharpen()
      ->maxWh();

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,crop-1;sharpen-1;keepratio-1;maxwh-1_logo.png");
  }

  public function testCropAndSharpenAndMaxWhAndResizeAndQuality() {
    $wrapper = $this->factory()->crop()
      ->sharpen()
      ->maxWh()
      ->resize(19, 81)
      ->quality(15);

    $this->assertEquals($wrapper->toString(), "http://dms.local.com/ResizeImage/images/srpr/19,81,15,3,crop-1;sharpen-1;maxwh-1_logo.png");
  }

  /**
   * @param string $originalName
   * @param string $path
   * @return Wrapper
   */
  protected function factory($originalName = 'logo.png', $path = 'images/srpr/') {
    $file = new File(array(), 'http://dms.local.com');
    $file['path'] = $path;
    $file['original_name'] = $originalName;

    return new Wrapper($file, 'http://dms.local.com');
  }

}
