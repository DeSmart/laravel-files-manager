<?php namespace DeSmart\Files\Uploader;

use Guzzle\Http\Client;
use DeSmart\Files\Model;
use DeSmart\Files\Uploader\SourceInterface;

class DmsUploader extends AbstractUploader {

  protected $dmsUrl;

  /**
   * @var \Guzzle\Http\Client
   */
  protected $guzzle;

  /**
   * Odpowiedz zwrocona z ostatnio wykonanego uploadu
   *
   * @var \Guzzle\Http\Message\Response
   */
  protected $response;

  public function __construct($dmsUrl = null) {
    $this->dmsUrl = $dmsUrl ?: \Config::get('app.dms_url');
    $this->setGuzzle(new Client);
  }

  public function setGuzzle(Client $guzzle) {
    $this->guzzle = $guzzle;
  }
 
  public function upload(SourceInterface $source) {
    $path = $this->generatePath();
    $request = $this->guzzle->post($this->dmsUrl.'/mod/dmsFiles2Records/RemoteGetFile/', null, array(
      'file_info' => '@'.$source->getFilepath(),
      'dest_file_name' => $source->getName(),
      'dest_path' => $path,
    ));

    $this->response = $request->send();

    if(false === $this->response->isError()) {
      return $this->getModel()->createFromUpload($source, $path);
    }

    return null;
  }

  public function getResponse() {
    return $this->response;
  }

}

