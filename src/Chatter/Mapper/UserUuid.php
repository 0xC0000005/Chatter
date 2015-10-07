<?php  

namespace Chatter\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use ZfcBase\Mapper\AbstractDbMapper;

class UserUuid extends AbstractDbMapper
{
    protected $tableName    = 'user_uuid';

    public function genUuid($userId, $type)
    {
		$uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

		$entity = [
			'user_id' => $userId,
			'uuid'   => $uuid,
			'type'	 => $type
		];

		parent::insert($entity, $this->tableName, null);

		return $uuid;
    }
    
    public function getUuid($uuid)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('uuid', $uuid);

		$select = new Select();
		$select->from($this->tableName);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }
    
    public function deleteUuid($uuid)
    {
		$where = ['uuid' => $uuid];
        $result = parent::delete($where, $this->tableName);
        return $result;
    }
}

