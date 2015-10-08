<?php  

namespace Chatter\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use ZfcBase\Mapper\AbstractDbMapper;

class Post extends AbstractDbMapper
{
    protected $tableName    = 'post';

    public function getPostsByDate($threadId, $pageNo = 1)
    {
        $selectPost = new Select();
		$selectPost->from('post');
		$selectPost->group('user_id');
		$selectPost->columns([
            'user_id',
            'postcount' => new Expression('COUNT(user_id)'),
 	]);
 
	$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('thread_id', $threadId);

		$select = new Select();
		$select->from($this->tableName);
		$select->order('date_added ASC');
		$select->join(['po' => $selectPost], 'post.user_id = po.user_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER);
		$select->join('user', 'user.user_id = post.user_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER);
		$select->limit(20);
		$select->offset(($pageNo-1)*20);
		$select->columns(
	    [
			'id',
            'date_added',
            'content',
            'last_updated',
            'username'    => new Expression('user.username'),
            'user_title'  => new Expression('user.title'),
            'user_avatar' => new Expression('user.avatar'),
            'user_signature' => new Expression('user.post_signature'),
            'user_joined' => new Expression('user.date_joined'),
			'user_postcount' => new Expression('po.postcount')
            ],
            false
        );
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }
	
    public function getPostCountForThread($threadId)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('thread_id', $threadId);

		$select = new Select();
		$select->from($this->tableName);
		$select->columns([ 'count' => new Expression('COUNT(*)')]);
			
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }


    public function savePost($threadId, $userId, $message)
    {
		$date = date('Y/m/d H:i:s');
		$entity = [
			'thread_id' => $threadId,
			'user_id' => $userId,
			'content' => $message,
			'date_added' => $date
		];
		
		try {
			$result = parent::insert($entity, $this->tableName, null);
		} catch(Exception $e) {
			return false;
		}

		$e2 = ['date_updated' => $date];

		$predicate = new Predicate(null, Predicate::OP_AND);
		$predicate->equalTo('id', $threadId);

		try {
			$result = parent::update($e2, $predicate, 'thread', null);
		} catch(Exception $e) {
			return false;
		}
		
		return $result; 
    }

    public function getPost($postId)
    {
		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('id', $postId);

        $select = new Select();
		$select->from($this->tableName);
			
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }

    public function updatePost($postId, $content)
    {
		$entity = [
			'content' => $content,
			'last_updated' => date('Y/m/d H:i:s')
		];

		$predicate = new Predicate(null, Predicate::OP_AND);
		$predicate->equalTo('id', $postId);
		
		try {
			$result = parent::update($entity, $predicate, $this->tableName, null);
		} catch(Exception $e) {
			return false;
		}
		return true;
    }
}

