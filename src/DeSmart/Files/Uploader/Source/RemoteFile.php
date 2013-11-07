<?php namespace DeSmart\Files\Uploader\Source;

use Guzzle\Http\Client;
use Illuminate\Filesystem\Filesystem;
use DeSmart\Files\Uploader\SourceInterface;
use Symfony\Component\HttpFoundation\File\MimeType;

class RemoteFile implements SourceInterface {

  /**
   * @var \Illuminate\Filesystem\Filesystem
   */
  protected $filesystem;

  protected $url;

  /**
   * @var \Symfony\Component\HttpFoundation\File\MimeType
   */
  protected $guesser;

  /**
   * @var \Guzzle\Http\Client
   */
  protected $guzzle;

  /**
   * @var \Guzzle\Http\Message\Response
   */
  protected $response;

  /**
   * @var string
   */
  protected $filepath;

  public function __construct($url) {
    $this->url = $url;

    $this->setFilesystem(new Filesystem);
    $this->setGuesser(new MimeType\MimeTypeExtensionGuesser);
    $this->setGuzzle(new Client);
  }

  public function __destruct() {

    if(null !== $this->filepath) {
      $this->filesystem->delete($this->filepath);
    }
  }

  public function setGuzzle(Client $guzzle) {
    $this->guzzle = $guzzle;
  }

  public function setGuesser(MimeType\ExtensionGuesserInterface $guesser) {
    $this->guesser = $guesser;
  }

  public function setFilesystem(Filesystem $filesystem) {
    $this->filesystem = $filesystem;
  }

  public function getExtension() {

    if(null === $this->response) {
      $this->download();
    }

    return $this->guesser->guess($this->response->getContentType());
  }

  public function getName() {
    $name = pathinfo($this->url, \PATHINFO_FILENAME);

    return preg_replace('/[^a-zA-Z0-9]+/', '_', $name);
  }

  public function getSize() {

    if(null === $this->response) {
      $this->download();
    }

    return $this->response->getBody()
      ->getSize();
  }

  public function getMimeType() {

    if(null === $this->response) {
      $this->download();
    }

    return $this->response->getContentType();
  }

  public function getFilepath() {

    if(null === $this->response) {
      $this->download();
    }

    if(null !== $this->filepath) {
      return $this->filepath;
    }

    $this->filepath = tempnam(sys_get_temp_dir(), 'remote');
    $this->filesystem->put($this->filepath, $this->response->getBody(true));

    return $this->filepath;
  }

  protected function download() {

    if(null !== $this->response) {
      return;
    }

    $request = $this->guzzle->get($this->url);
    $this->response = $request->getResponse();
  }

}
