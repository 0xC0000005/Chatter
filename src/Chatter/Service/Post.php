<?php

namespace Chatter\Service;

class Post
{
    protected $threadMapper;
    protected $postMapper;
    protected $zfcUserService;
    protected $sanitiser;
    

    public function addPost($threadId, $userId, $content)
    {
        $result = $this->postMapper->savePost($threadId, $userId, strip_tags($content));
		$this->threadMapper->updateThread($threadId);

		return $result;
    }

    public function getPost($postId)
    {
        $result = $this->postMapper->getPost($postId)[0];

		return $result;
    }

    public function updatePost($postId, $content)
    {
        $result = $this->postMapper->updatePost($postId, strip_tags($content));

		return $result;
    }


    public function formatPostsforThread($posts)
    {
		$arr = array();
		foreach($posts as $res) {
			$post = [];
			$post['id'] = $res['id']; 
			$post['username'] = $res['username']; 
			$post['title'] = $res['user_title']; 
			$post['avatar'] = $res['user_avatar'];
			$post['post_count'] = $res['user_postcount'];

			$date = date('M Y', strtotime($res['user_joined']));    
			$post['join_date'] = $date;

			$datetime = strtotime($res['date_added']);
			$dateUf = date('d-m-Y',$datetime);
			$date1 = date('d-m-Y H:i',$datetime);
			if ($dateUf == date('d-m-Y')){
				$date1 = "Today " . date('H:i',$datetime);
			} elseif ($dateUf == date('d-m-Y',time() - (24 * 60 * 60))) {
				$date1 = "Yesterday " . date('H:i',$datetime);
			}
			$post['post_date'] = $date1;
			$post['content'] = $this->sanitiser->buildTags($res['content']);
			if ($res['last_updated'] != null) {
				$datetime = strtotime($res['last_updated']);
				$dateUf = date('d-m-Y',$datetime);
				$date2 = date('d-m-Y H:i',$datetime);
				if ($dateUf == date('d-m-Y')){
					$date2 = "today " . date('H:i',$datetime);
				} else if ($dateUf == date('d-m-Y',time() - (24 * 60 * 60))) {
					$date2 = "yesterday " . date('H:i',$datetime);
				}
				$post['last_updated'] = $date2;
			}
				
			$post['signature'] = $this->sanitiser->buildTags($res['user_signature']);

			array_push($arr, $post);
		}
			
		return $arr;
    }

    public function setThreadMapper($mapper)
    {
        $this->threadMapper = $mapper;
        return $this;
    }

    public function setPostMapper($mapper)
    {
        $this->postMapper = $mapper;
        return $this;
    }

    public function setzfcUserService($service)
    {
        $this->zfcUserService = $service;
        return $this;
    }
    
    public function setSanitiser($service)
    {
        $this->sanitiser = $service;
        return $this;
    }

}

