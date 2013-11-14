<?php namespace DeSmart\Files;

use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection {

  /**
   * Wyszukuje i usuwa z kolekcji plik z flaga is_default = 1.
   *
   * Jesli w kolekcji jest wiecej niz jeden rekord z flaga is_default zostanie zwrocony pierwszy znaleziony.
   *
   * @return \DeSmart\Files\Model\File|null znaleziony rekord
   */
  public function popDefaultFile() {
    $file = null;
    $key = null;

    foreach($this->items as $key => $item) {

      if(1 == $item->is_default) {
        $file = $item;
        break;
      }
    }

    if(null !== $file) {
      array_splice($this->items, $key, 1);
    }

    return $file;
  }

}
