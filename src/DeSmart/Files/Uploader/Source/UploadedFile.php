<?php namespace DeSmart\Files\Uploader\Source;

use DeSmart\Files\Uploader\SourceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile as File;

class UploadedFile implements SourceInterface {

  /**
   * @var \Symfony\Component\HttpFoundation\File\UploadedFile
   */
  protected $file;

  public function __construct(File $file) {
    $this->file = $file;
  }

  public function getExtension() {
    return $this->file->guessExtension() ?: $this->file->getExtension();
  }

  public function getName() {
    $name = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $this->getExtension();

    return preg_replace('/[^a-zA-Z0-9]+/', '_', $name).".{$extension}";
  }

  public function getSize() {
    return $this->file->getSize();
  }

  public function getMimeType() {
    return $this->file->getMimeType();
  }

  public function getFilepath() {
    return $this->file->getPathname();
  }

}
