<?php namespace DeSmart\Files\Model;

use DeSmart\Files\Uploader\SourceInterface;

class File extends \DeSmart\Model\Model {

  protected $primaryKey = 'id_file';

  protected $table = 'files2records_files';

  protected static $unguarded = true;

  public function fileable() {
    return $this->morphTo();
  }

  public function scopeSort($query) {
    $query->orderBy('sort_order', 'asc');
  }

  public function scopeGroup($query, $groupName, $tableUsed = null) {

    if(null === $tableUsed) {
      $tableUsed = $this->getTable().'.table_used';
    }
    else {
      $tableUsed = sprintf('"%s"', $tableUsed);
    }

    $query->whereRaw(sprintf('id_file_group = (SELECT id_file_group FROM files2records_groups WHERE table_used = %s and unique_name = "%s")', $tableUsed, $groupName));
  }

  /**
   * Get url to original file
   *
   * @return string
   */
  public function getUrl() {
    return sprintf('%s/u/%s%s', \Config::get('app.dms_url'), $this->path, $this->original_name);
  }

  public function setPathAttribute($path) {
    $this->attributes['path'] = rtrim($path, '/').'/';
  }

  public function createFromUpload(SourceInterface $source, $path) {
    $model = new self;
    $model->date = new \DateTime();
    $model->extension = $source->getExtension();
    $model->name = $source->getName();
    $model->original_name = $source->getName();
    $model->path = $path;
    $model->type = $source->getMimeType();
    $model->size = $source->getSize();

    return $model;
  }

}
