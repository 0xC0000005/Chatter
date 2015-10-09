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
			'last_thread' 	 => new Expression('last_thread'),
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
		$selPost = new Select();
		$selPost->from('post')
			->columns(['pc' => new \Zend\Db\Sql\Expression('COUNT(post.id)')]);
				
		$selThread = new Select();
		$selThread->from('thread')
			->columns(['tc' => new \Zend\Db\Sql\Expression('COUNT(thread.id)')]);
				
		$select = new Select();
		$select->from('user')
			->columns(
				[
					'users' 	=> new \Zend\Db\Sql\Expression('COUNT(user.user_id)'),
					'threads' 	=> new \Zend\Db\Sql\Expression('?', [$selThread]),
					'posts' 	=> new \Zend\Db\Sql\Expression('?', [$selPost])
			
				]
			);
			
		$result = $this->select($select)->current();

		return $result;
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
		$select = new Select();
		$select->from('thread')
			->join('post', 'thread.id = post.thread_id AND thread.date_updated = post.date_added', [], 
			$select::JOIN_INNER)
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

