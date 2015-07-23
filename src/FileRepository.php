<?php namespace DeSmart\Files;

use Illuminate\Database\DatabaseManager;

class FileRepository
{

    /**
     * @var \DeSmart\Files\Model\File
     */
    protected $query;

    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;

    public function __construct(Model\File $query, DatabaseManager $db)
    {
        $this->query = $query;
        $this->db = $db;
    }

    /**
     * @param string $md5Checksum
     * @return \DeSmart\Files\Entity\FileEntity|null
     */
    public function findByChecksum($md5Checksum)
    {

        if (true === empty($md5Checksum)) {
            throw new \InvalidArgumentException('Empty md5_checksum');
        }

        $model = $this->query->where('md5_checksum', $md5Checksum)
            ->first();

        return is_null($model) ? null : $model->toEntity();
    }

    public function save(Entity\FileEntity $file)
    {
        $exists = (null !== $file->getId());
        $model = $this->query->createFromEntity($file);

        if (false === $exists) {
            $model->created_at = date_create();
        }

        $model->save();

        if (false === $exists) {
            $file->setCreatedAt($model->created_at);
            $file->setId($model->getKey());
        }
    }

    /**
     * Checks if file is related to any record
     *
     * @param \DeSmart\Files\Entity\FileEntity $file
     * @return boolean
     */
    public function hasRelatedRecords(Entity\FileEntity $file)
    {
        $count = (int) $this->db->from('file_records')
            ->where('file_id', $file->getId())
            ->count();

        return $count > 0;
    }

    /**
     * Remove file from DB
     *
     * @param \DeSmart\Files\Entity\FileEntity $file
     */
    public function remove(Entity\FileEntity $file)
    {
        $model = $this->query->createFromEntity($file);
        $model->delete();
    }
}
