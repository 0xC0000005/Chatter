<?php  

namespace Chatter\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use ZfcBase\Mapper\AbstractDbMapper;

class Thread extends AbstractDbMapper
{
    protected $tableName    = 'thread';

    public function getThreadsByDate($threadId, $pageNo = 1)
    {
		$selectPost = new Select();
		$selectPost->join('user', 'post.user_id = user.user_id', [], $selectPost::JOIN_LEFT . ' ' . $selectPost::JOIN_OUTER);
		$selectPost->from('post')
				->group('thread_id');
		$selectPost->columns([
			'thread_id',
			'count' => new Expression('COUNT(thread_id)'),
			'last_post' => new Expression('MAX(date_added)'),
			'poster' => new Expression('username')
		]);



		$predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('forum_id', $threadId)
                ->equalTo('deleted', '0');

		$select = new Select();
		$select->from($this->tableName);
		$select->order('date_updated DESC');
		$select->limit(20);
		
		$select->join('user', 'thread.user_id = user.user_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER);
		$select->join(['po' => $selectPost], 'thread.id = po.thread_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER);
		$select->columns([
			'id',
			'title',
			'date_added',
			'forum_id',
			'view_count',
			'sticky',
			'post_count'     => new Expression('count'),
			'creator'        => new Expression('username'),
			'last_poster'    => new Expression('poster'),
			'last_post_date' => new Expression('last_post')
		]);
		$select->offset(($pageNo-1)*20);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }
	
    public function getThread($threadId)
    {
        $predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('id', $threadId);

        $select = new Select();
        $select->from($this->tableName);
	
        $select->where($predicate);
        $result = $this->select($select);

        return $result->toArray();
    }

    public function saveThread($forumId, $userId, $title, $sticky)
    {
		$entity = [
			'forum_id' => $forumId,
			'user_id' => $userId,
			'title' => $title,
			'date_added' => date('Y/m/d H:i:s')
		];
			
		if ($sticky){
			$entity['sticky'] = true;
		}
		
		try {
			$result = parent::insert($entity, $this->tableName, null);
		} catch(Exception $e) {
			return false;
		}
		
		return $result; 
    }

    public function incrementViews($threadId)
    {
        $entity = [
			'view_count' => new Expression('view_count + 1')
			];

		$where = ['id = ' . $threadId];

		parent::update($entity, $where, $this->tableName, null);
    }
    

    public function updateThread($threadId)
    {
        $entity = [
			'date_updated' => date('Y/m/d H:i:s')
        ];

		$where = ['id = ' . $threadId];

		parent::update($entity, $where, $this->tableName, null);
    }
	
    public function deleteThread($id)
    {
        $entity = [
			'deleted' => "1"
			];

		$where = ['id = ' . $id];

		parent::update($entity, $where, $this->tableName, null);
    }
}

