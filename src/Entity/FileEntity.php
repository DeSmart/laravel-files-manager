<?php namespace DeSmart\Files\Entity;

class FileEntity
{

    protected $id;

    protected $name;

    protected $path;

    protected $size;

    protected $createdAt;

    protected $md5Checksum;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setSize($size)
    {
        $this->size = (int) $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setCreatedAt($date)
    {

        if (true === $date instanceof \DateTimeInterface) {
            $this->createdAt = (new \DateTimeImmutable)->setTimestamp($date->getTimestamp());
        } else {
            $this->createdAt = is_null($date) ? null : new \DateTimeImmutable($date);
        }
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getMd5Checksum()
    {
        return $this->md5Checksum;
    }
    
    /**
     * @param string $md5Checksum
     */
    public function setMd5Checksum($md5Checksum)
    {
        $this->md5Checksum = $md5Checksum;
    }
}
