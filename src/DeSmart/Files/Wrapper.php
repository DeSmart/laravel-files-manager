<?php namespace DeSmart\Files;

use DeSmart\Files\Model\File;

/**
 * Prosty skrypt generujacy sciezke do resize image w DMSie
 */
class Wrapper {

  /**
   * @var \DeSmart\Files\Model\File
   */
  protected $file;

  protected $properties = array(
    'width' => 0,
    'height' => 0,
    'type' => 2, // jpg
    'quality' => 100,
    'fx' => array(
      'crop' => null,
      'sharpen' => null,
      'keepratio' => null,
      'maxwh' => null,
    ),
  );

  public function __construct(File $file) {
    $this->file = $file;
    $this->properties['type'] = $this->guessImageType();
  }

  /**
   * @var integer $width
   * @var integer $height
   * @return \DeSmart\Files\Model\File
   */
  public function resize($width, $height) {
    $this->properties = array_merge($this->properties, compact('width', 'height'));

    return $this;
  }

  /**
   * @param integer $quality
   * @return \DeSmart\Files\Model\File
   */
  public function quality($quality) {
    $this->properties['quality'] = $quality;

    return $this;
  }

  /**
   * Crop image
   *
   * @return \DeSmart\Files\Model\File
   */
  public function crop() {
    $this->properties['fx']['crop'] = 1;

    return $this;
  }

  /**
   * Sharpen image
   *
   * @return \DeSmart\Files\Model\File
   */
  public function sharpen() {
    $this->properties['fx']['sharpen'] = 1;

    return $this;
  }

  /**
   * @return \DeSmart\Files\Model\File
   */
  public function keepRatio() {
    $this->properties['fx']['keepratio'] = 1;

    return $this;
  }

  /**
   * Resize if image is greater than expected width or height or do nothing (keep original width and height)
   *
   * @return \DeSmart\Files\Model\File
   */
  public function maxWh() {
    $this->properties['fx']['maxwh'] =  1;

    return $this;
  }

  public function toString() {
    $filter = function($var) {
      return null !== $var;
    };

    extract($this->properties);
    $fx = array_filter($fx, $filter);
    $image_fx = array();

    foreach($fx as $name => $value) {
      $image_fx[] = "{$name}-{$value}";
    }

    return sprintf(
      '%s/ResizeImage/%s%s,%s,%s,%s,%s_%s',
      \Config::get('app.dms_url'),
      $this->file->path,
      $width,
      $height,
      $quality,
      $type,
      join(';', $image_fx),
      $this->file->original_name
    );
  }

  public function __toString() {
    return $this->toString();
  }

  /**
   * Ustawia typ pliku jaki ma byc wygenerowany
   *
   * @return integer
   */
  protected function guessImageType() {
    $extension = pathinfo($this->file->original_name, PATHINFO_EXTENSION);

    if('png' === $extension) {
      return IMAGETYPE_PNG;
    }

    if('gif' === $extension) {
      return IMAGETYPE_GIF;
    }

    return IMAGETYPE_JPEG;
  }

}
