<?php namespace DeSmart\Files\Uploader;

interface SourceInterface {

  /**
   * Zwraca rozszerzenie pliku.
   *
   * Rozszerzenie bedzie w lowercase.
   *
   * @return string
   */
  function getExtension();

  /**
   * Zwraca nazwe pliku (razem z rozszerzeniem)
   *
   * Nazwa powinna byc eskejpowana.
   *
   * @return string
   */
  function getName();

  /**
   * Zwraca informacje o rozmiarze pliku (w bajtach)
   *
   * @return integer
   */
  function getSize();

  /**
   * @return string
   */
  function getMimeType();

  /**
   * Zwraca sciezke do pliku zrodlowego
   *
   * @return string
   */
  function getFilepath();

}
