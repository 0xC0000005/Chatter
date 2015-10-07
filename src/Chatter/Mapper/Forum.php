<?php  

namespace Chatter\Mapper;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use ZfcBase\Mapper\AbstractDbMapper;

class Forum extends AbstractDbMapper
{
    protected $tableName    = 'forum';

    public function getForums()
    {
		$selectCount = $this->getThreadCounts();
		$selectLatest = $this->getForumLatest();

		$select = new Select();
		$select->from($this->tableName);

		$select->join(
					['count' => $selectCount],
					new Expression('forum.id = count.forum_id'),
					[],
					$select::JOIN_LEFT. ' '. $select::JOIN_OUTER
				)
			->join(
					['latest' => $selectLatest],
					new Expression('forum.id = latest.forum_id'),
					[],
					$select::JOIN_LEFT. ' '. $select::JOIN_OUTER
				)
			->join(
					'forum_category',
					new Expression('forum.forum_category_id = forum_category.id'),
					[],
					$select::JOIN_LEFT. ' '. $select::JOIN_OUTER
				);
		$select->columns([
			'id'             => new Expression('forum.id'),
			'title'          => new Expression('forum.title'),
			'description'    => new Expression('forum.description'),
			'minimum_access' => new Expression('forum.minimum_access'),
			'category_id'    => new Expression('forum_category.id'),
			'category'       => new Expression('forum_category.category'),
			'thread_count'   => new Expression('count_for_forum'),
			'last_poster'    => new Expression('last_post'),
			'last_post_date' => new Expression('latest.date_updated'),
			'last_thread' => new Expression('last_thread'),
			'last_thread_id' => new Expression('last_thread_id')
			], 
			false
		);

		$result = $this->select($select);

		return $result->toArray();
		}
	
    public function getForum($forumId)
    {
        $predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('id', $forumId);
		
		
		$select = new Select();
		$select->from($this->tableName);
		$select->where($predicate);
		$result = $this->select($select);

		return $result->toArray();
    }
	
    public function getGlobalInfo()
    {
		$selectP = new Select();
		$selectP->from('post')
			->columns(['postcount' => new \Zend\Db\Sql\Expression('COUNT(id)')]);
		$r1 = $this->select($selectP)->toArray();
				
		$selectT = new Select();
		$selectT->from('thread')
			->columns(['threadcount' => new \Zend\Db\Sql\Expression('COUNT(id)')]);
		$r2 = $this->select($selectT)->toArray();
			
		$select = new Select();
		$select->from('user')
            ->columns(['usercount' => new \Zend\Db\Sql\Expression('COUNT(id)')]);
		$r3 = $this->select($select)->toArray();
			
		$arr = [];
			
		$arr['postcount'] = $r1[0]['postcount'];
		$arr['threadcount'] = $r2[0]['threadcount'];
		$arr['usercount'] = $r3[0]['usercount'];
		return $arr;
    }



    private function getThreadCounts()
    {
		$select = $this->getSelect('thread')
			->group('forum_id')
            ->columns(
                [
					'forum_id',
					'count_for_forum'    => new Expression('COUNT(forum_id)'),
				]
			);
        
        $predicate = new Predicate(null, Predicate::OP_AND);
        $predicate->equalTo('deleted', "0");
        
        $select->where($predicate);
		return $select;
    }

    private function getForumLatest()
    {
		/*$select = new Select();
		$select->from('post')
			->join('user', 'post.user_id = user.user_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER)
			->join('thread', 'post.thread_id = thread.id AND post.date_added = thread.date_updated', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER)
			->order('date_updated DESC')
			->group('forum_id')
			->columns([
				'forum_id' => new Expression('thread.forum_id'),
				'last_post' => new Expression('user.username'),
				'last_thread'   => new Expression('thread.title'),
				'last_thread_id' => new Expression('thread.id'),
				'date_updated' => new Expression('thread.date_updated')
			]);
			
		return $select;*/
			
		$select->from('thread')
			->join('post', 'thread.id = post.thread_id AND thread.date_updated = post.date_added', [], $select::JOIN_INNER)
			->join('user', 'post.user_id = user.user_id', [], $select::JOIN_LEFT . ' ' . $select::JOIN_OUTER)
			->columns([
				'forum_id' => new Expression('thread.forum_id'),
				'last_post' => new Expression('user.username'),
				'last_thread'   => new Expression('thread.title'),
				'last_thread_id' => new Expression('thread.id'),
				'date_updated' => new Expression('thread.date_updated')
			]);
		return $select;
    }
}

