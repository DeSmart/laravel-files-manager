<?php namespace DeSmart\Files;

use DeSmart\Support\Uuid;
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

    public function __construct($query, DatabaseManager $db)
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

    public function insert(Entity\FileEntity $file)
    {
        $file->setId(Uuid::generateUuid());
        $file->setCreatedAt(date_create());

        $model = $this->query->createFromEntity($file);
        $model->exists = false;
        $model->save();

        return $file;
    }

    public function update(Entity\FileEntity $file)
    {
        $model = $this->query->createFromEntity($file);
        $model->save();

        return $file;
    }

    /**
     * Checks if file is related to any record
     *
     * @param \DeSmart\Files\Entity\FileEntity $file
     * @return boolean
     */
    public function hasRelatedRecords(Entity\FileEntity $file)
    {
        $count = (int) $this->db->table('file_records')
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
