<?php namespace DeSmart\Files;

use DeSmart\Files\Uploader\SourceInterface;

interface UploaderInterface {

  /**
   * Zapisz plik.
   *
   * Jesli operacja sie powiedzie zostanie zwrocona instancja modelu.
   *
   * @param \DeSmart\Files\Uploader\SourceInterface
   * @return \DeSmart\Files\Model\File|null
   */
  function upload(SourceInterface $source);

}
