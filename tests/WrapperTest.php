<?php

use DeSmart\Files\Wrapper;
use Mockery as m;
use DeSmart\Files\Model\File;

class WrapperTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    m::close();
  }

  public function testResize() {
    $factory = $this->factory()->resize(51, 49);

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/51,49,100,3,_logo.png");
  }

  public function testQuality() {
    $factory = $this->factory()->quality(39);

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,39,3,_logo.png");
  }

  public function testCrop() {
    $factory = $this->factory()->crop();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,crop-1_logo.png");
  }

  public function testSharpen() {
    $factory = $this->factory('logo.jpg')->sharpen();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,2,sharpen-1_logo.jpg");
  }

  public function testKeepRatio() {
    $factory = $this->factory('logo.jpg')->keepRatio();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,2,keepratio-1_logo.jpg");
  }

  public function testMaxWh() {
    $factory = $this->factory()->maxWh();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,maxwh-1_logo.png");
  }

  public function testResizeAndCrop() {
    $factory = $this->factory()->resize(51, 49);
    $factory->crop();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/51,49,100,3,crop-1_logo.png");
  }

  public function testKeepRatioAndSharpen() {
    $factory = $this->factory('logo.gif')->keepRatio()
        ->sharpen();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,1,sharpen-1;keepratio-1_logo.gif");
  }

  public function testKeepRatioAndCropAndSharpenAndMaxWh() {
    $factory = $this->factory()->keepRatio()
      ->crop()
      ->sharpen()
      ->maxWh();

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/0,0,100,3,crop-1;sharpen-1;keepratio-1;maxwh-1_logo.png");
  }

  public function testCropAndSharpenAndMaxWhAndResizeAndQuality() {
    $factory = $this->factory()->crop()
        ->sharpen()
        ->maxWh()
        ->resize(19, 81)
        ->quality(15);

    $this->assertEquals($factory->toString(), "http://dms.local.com/ResizeImage/images/srpr/19,81,15,3,crop-1;sharpen-1;maxwh-1_logo.png");
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
