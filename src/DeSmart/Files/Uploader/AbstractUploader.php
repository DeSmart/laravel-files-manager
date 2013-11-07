<?php namespace DeSmart\Files\Uploader;

use DeSmart\Files\Model;
use DeSmart\Files\UploaderInterface;

abstract class AbstractUploader implements UploaderInterface {

  /**
   * @var \DeSmart\Files\Model\File
   */
  protected $model;

  /**
   * Generuje sciezke do pliku
   *
   * Zwracana sciezka konczy sie separatorem ('/')
   *
   * @return string
   */
  public function generatePath() {
    $parts = str_split(sha1(uniqid()), 2);

    return join('/', array_slice($parts, 0, 2)).'/';
  }

  /**
   * @param \DeSmart\Files\Model\File $model
   */
  public function setModel(Model\File $model) {
    $this->model = $model;
  }

  /**
   * @return \DeSmart\Files\Model\File
   */
  public function getModel() {
    return $this->model ?: new Model\File;
  }

}
