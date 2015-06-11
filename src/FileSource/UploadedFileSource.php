<?php namespace DeSmart\Files\FileSource;

use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        return pathinfo($name, PATHINFO_FILENAME);
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
    public function save(Filesystem $filesystem, $destinationPath)
    {
        $filesystem->put($destinationPath, file_get_contents($this->file->getPathname()));
    }

    /**
     * @return string
     */
    public function getMd5Checksum()
    {
        return md5_file($this->file->getPathname());
    }
}
