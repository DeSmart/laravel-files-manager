<?php namespace DeSmart\Files;

use DeSmart\Files\Uploader\SourceInterface;

interface UploaderInterface {

  function upload(SourceInterface $source);

  function createModel(SourceInterface $source);

}
