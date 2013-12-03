<?php namespace DeSmart\Files\Uploader\Source;

use Illuminate\Filesystem\Filesystem;
use DeSmart\Files\Uploader\SourceInterface;
use Symfony\Component\HttpFoundation\File\MimeType;

class LocalFile implements SourceInterface {

  /**
   * @var string
   */
  protected $filepath;

  /**
   * @var \Illuminate\Filesystem\Filesystem
   */
  protected $filesystem;

  /**
   * @var \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser
   */
  protected $guesser;

  public function __construct($filepath) {
    $this->filepath = realpath($filepath);

    if(false === $this->filepath) {
      throw new \InvalidArgumentException("File does not exist at path {$filepath}");
    }

    $this->setFilesystem(new Filesystem);
    $this->setGuesser(MimeType\MimeTypeGuesser::getInstance());
  }

  public function setGuesser(MimeType\MimeTypeGuesser $guesser) {
    $this->guesser = $guesser;
  }

  public function setFilesystem(Filesystem $filesystem) {
    $this->filesystem = $filesystem;
  }

  public function getExtension() {
    return $this->filesystem->extension($this->filepath);
  }

  public function getName() {
    $name = pathinfo($this->filepath, \PATHINFO_FILENAME);
    $extension = $this->getExtension();

    return preg_replace('/[^a-zA-Z0-9]+/', '_', $name).".{$extension}";
  }

  public function getSize() {
    return $this->filesystem->size($this->filepath);
  }

  public function getMimeType() {
    return $this->guesser->guess($this->filepath);
  }

  public function getFilepath() {
    return $this->filepath;
  }

}
