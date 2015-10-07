<?php

namespace Chatter\Service;

class Thread
{
    protected $threadMapper;
    protected $postMapper;
    

    public function incrementViews($threadId)
    {
         $this->threadMapper->incrementViews($threadId);
    }

    public function createThread($forumId, $userId, $title, $message, $sticky)
    {
		$result = $this->threadMapper->saveThread($forumId, $userId, $title, $sticky);
		$threadId = $result->getGeneratedValue();
		$this->postMapper->savePost($threadId, $userId, $message);
		return $threadId;
    }

    public function getThread($threadId)
    {
		$result = $this->threadMapper->getThread($threadId);

		return $result[0];
    }
    
    public function deleteThread($id)
    {
        $this->threadMapper->deleteThread($id);
    }
	
    public function getPosts($threadId, $pageNo = 1)
    {
        $result = $this->postMapper->getPostsByDate($threadId, $pageNo);
		return $result;
    }
	
    public function getPaginater($threadId)
    {
        $response = $this->postMapper->getPostCountForThread($threadId)[0]['count'];
        return $response;
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

}

