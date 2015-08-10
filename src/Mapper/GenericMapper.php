<?php namespace DeSmart\Files\Mapper;

use DeSmart\Files\Entity\FileEntity;
use DeSmart\Files\FileSource\FileSourceInterface;
use Illuminate\Contracts\Filesystem\Filesystem as Storage;

class GenericMapper implements MapperInterface
{

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function map(FileEntity $file, FileSourceInterface $fileSource)
    {
        $dir = $this->generateDirname($file);
        $path = $this->generatePath($dir, $file);

        $file->setPath($path);
    }

    protected function generatePath($dir, FileEntity $file)
    {
        $pathinfo = pathinfo($file->getName());
        $fileName = preg_replace('/\W+/', '-', $pathinfo['filename']);
        $path = sprintf(
            '%s/%s.%s',
            $dir,
            $fileName,
            $pathinfo['extension']
        );

        while (true === $this->storage->exists($path)) {
            $path = sprintf(
                '%s/%s-%s.%s',
                $dir,
                $fileName,
                $this->generateHash(),
                $pathinfo['extension']
            );
        }

        return $path;
    }

    protected function generateHash()
    {
        return substr(
            sha1(time().uniqid()),
            0,
            8
        );
    }

    protected function generateDirname(FileEntity $entity)
    {
        $hash = sha1(time().$entity->getName());
        $splitted = str_split($hash, 2);

        return join(
            '/',
            array_slice($splitted, 0, 2)
        );
    }
}
