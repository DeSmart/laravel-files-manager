<?php namespace DeSmart\Files\FileSource;

use Illuminate\Contracts\Filesystem\Filesystem as Storage;

interface FileSourceInterface
{

    /**
     * Return the name of the file
     *
     * @return string
     */
    public function getName();

    /**
     * Get file size
     *
     * @return integer size in bytes
     */
    public function getSize();

    /**
     * Save file to given location
     *
     * @param \Illuminate\Contracts\Filesystem\Filesystem $storage
     * @param string $destinationPath
     */
    public function save(Storage $storage, $destinationPath);

    /**
     * Return the md5 checksum of file
     *
     * @return string
     */
    public function getMd5Checksum();
}
