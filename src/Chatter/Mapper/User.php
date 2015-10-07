<?php  

namespace Chatter\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use ZfcBase\Mapper\AbstractDbMapper;

class User extends AbstractDbMapper
{
    protected $tableName    = 'user';

    public function getUser($userId)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('user_id', $userId);

		$select = new Select();
		$select->from($this->tableName);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }

    public function getUserByEmail($email)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('email', $email);

		$select = new Select();
		$select->from($this->tableName);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }

    public function getUserByUsername($username)
    {
        $selectPost = new Select();
		$selectPost->from('post');
		$selectPost->group('user_id');
		$selectPost->columns([
				'user_id',
				'postcount' => new Expression('COUNT(user_id)'),
		]);
        
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('username', $username);

		$select = new Select();
		$select->from($this->tableName);
        
        $select->join(['po' => $selectPost], 'user.user_id = po.user_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER);
		$select->where($predicate);
        $select->columns(
			[
				'user_id' => new Expression('user.user_id'),
				'username',
				'display_name',
				'date_joined',
				'role',
				'title',
				'avatar',
				'post_signature',
				'post_count' => new Expression('po.postcount')
			],
            false
        );
		$result = $this->select($select);

		return $result->toArray();
    }

    public function getSettings($userId)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('user_id', $userId);

		$select = new Select();
		$select->from($this->tableName);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }

    public function saveProfileSettings($params, $userId)
    {
		$where = [];
		$where[] = 'user_id = ' . $userId;

		$entity = [
			'avatar' => $params['avatar'],
			'post_signature' => $params['signature']
		]; 

		$success = parent::update($entity, $where, $this->tableName, null);
		return $success;
    }

    public function updatePassword($userId, $password)
    {
		$where = [];
		$where[] = 'user_id = ' . $userId;

		$entity = [
			'password' => $password
		]; 

		$success = parent::update($entity, $where, $this->tableName, null);
		return $success;
    }
}

