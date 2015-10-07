<?php

namespace Chatter\Service;


use Zend\ServiceManager\ServiceLocatorAwareInterface;

class Forum
{
    protected $forumMapper;
    protected $threadMapper;
    protected $postMapper;
    protected $userMapper;
    protected $userService;
    protected $zfcUserService;

    
    
    public function getForums()
    {
        $result = $this->forumMapper->getForums();

		$arr = array();

		if (sizeof($result) > 0) {
			foreach($result as $res) {
				$forum = array();
				$forum['id'] = $res['id'];
				$forum['title'] = $res['title'];
				$forum['description'] = $res['description'];

				if ($res['thread_count'] < 1){
				$res['thread_count'] = "0"; 
					}
				
				$forum['thread_count'] = $res['thread_count'];

				$datetime = strtotime($res['last_post_date']);
				$dateUf = date('d-m-Y',$datetime);
				$date = date('d-m-Y H:i',$datetime);
				if ($dateUf == date('d-m-Y')){
				$date = "today " . date('H:i',$datetime);
				} else if ($dateUf == date('d-m-Y',time() - (24 * 60 * 60))) {
					$date = "yesterday " . date('H:i',$datetime);
				} else {
				$date = date('d M Y',$datetime);
						}
				
				$forum['category'] = $res['category'];
				$forum['last_thread_id'] = $res['last_thread_id'];
				$forum['last_thread'] = $res['last_thread'];
				$forum['last_date'] = $date;
				$forum['last_poster'] = $res['last_poster'];

				if ($res['minimum_access'] == "member" || 
						($this->zfcUserService->hasIdentity() == true && 
							$this->userService->getAccessLevel($this->zfcUserService->getIdentity()->getId(), $res['minimum_access']))
				){
					if ($arr[$res['category_id']] == null) {
						$arr[$res['category_id']] = array();
					}
					array_push($arr[$res['category_id']], $forum);
				}
			}
		}
		return $arr;
    }

    public function getThreads($forumId, $pageNo = 1)
    {
        $result = $this->threadMapper->getThreadsByDate($forumId, $pageNo);

		$sticky = array();
		$other = array();

		$forum = $this->forumMapper->getForum($forumId)[0];

		foreach($result as $res) {
			
			$thread = array();

			$thread['id'] = $res['id'];
			$thread['title'] = $res['title'];
			$thread['creator'] = $res['creator'];
			$thread['view_count'] = ($res['view_count']-1);
			$thread['post_count'] = ($res['post_count']-1);
			$thread['sticky'] = $res['sticky'];

			$datetime = strtotime($res['last_post_date']);
			$dateUf = date('d-m-Y',$datetime);
			$date = date('d-m-Y H:i',$datetime);
			if ($dateUf == date('d-m-Y')){
			$date = "today " . date('H:i',$datetime);
			} else if ($dateUf == date('d-m-Y',time() - (24 * 60 * 60))) {
			$date = "yesterday " . date('H:i',$datetime);
			}
			
			$thread['last_poster'] = $res['last_poster'];
			$thread['last_date'] = $date;
			
			if (($this->zfcUserService->hasIdentity() && $this->userService->getAccessLevel($this->zfcUserService->getIdentity()->getId(), $forum['minimum_access'])) || $forum['minimum_access'] == "member"){
				if ($thread['sticky']){
					array_push($sticky, $thread);
				} else {
					array_push($other, $thread);
				}
			}
		}

		$arr = array_merge($sticky, $other);
		return $arr;
    }

    public function getForumFromThread($threadId)
    {
		return $this->threadMapper->getThread($threadId)[0]['forum_id'];
    }
	
    public function getForumPathInfo($forumId, $threadId = null)
    {
		$result = $this->forumMapper->getForum($forumId)[0];
		if ($threadId){
			$thread = $this->threadMapper->getThread($threadId)[0];
				$result['thread_id'] = $thread['id'];
				$result['thread_title'] = $thread['title'];
		}	
		return $result;
    }

    public function getGlobalForumInfo()
    {
		$result = $this->forumMapper->getGlobalInfo();
		return $result;
    }



    public function setForumMapper($mapper)
    {
        $this->forumMapper = $mapper;
        return $this;
    }

    public function setThreadMapper($mapper)
    {
        $this->threadMapper = $mapper;
        return $this;
    }

    public function setCommentMapper($mapper)
    {
        $this->postMapper = $mapper;
        return $this;
    }

    public function setUserMapper($mapper)
    {
        $this->userMapper = $mapper;
        return $this;
    }

    public function setUserService($service)
    {
        $this->userService = $service;
        return $this;
    }

    public function setzfcUserService($service)
    {
        $this->zfcUserService = $service;
        return $this;
    }


}

