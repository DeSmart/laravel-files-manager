<?php namespace DeSmart\Files\FileSource;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Contracts\Filesystem\Filesystem as Storage;

class UploadedFileSource implements FileSourceInterface
{

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $name = $this->file->getClientOriginalName();

        if (true === empty($name)) {
            return null;
        }

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->file->getClientSize() ?: 0;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Storage $storage, $destinationPath)
    {
        $storage->put($destinationPath, file_get_contents($this->file->getPathname()));
    }

    /**
     * @return string
     */
    public function getMd5Checksum()
    {
        return md5_file($this->file->getPathname());
    }
}
