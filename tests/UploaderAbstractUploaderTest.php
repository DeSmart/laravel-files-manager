<?php

use DeSmart\Files\Uploader\AbstractUploader;

class UploaderAbstractUploaderTest extends \PHPUnit_Framework_TestCase {

  public function testGeneratePath() {
    $mock = $this->getMockForAbstractClass('DeSmart\Files\Uploader\AbstractUploader');
    $this->assertRegExp('/^\w{2}\/\w{2}\/$/', $p = $mock->generatePath());
  }

}
