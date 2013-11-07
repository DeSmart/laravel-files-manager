<?php namespace DeSmart\Files\Model;

class File extends \Eloquent {

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

}
