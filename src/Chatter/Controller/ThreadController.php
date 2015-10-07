<?php

namespace Chatter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ThreadController extends AbstractActionController
{
    protected $forumService;
    protected $postService;

    public function indexAction()
    {
        $this->getForumService();
        $this->getPostService();
        $this->getThreadService();

		$viewModel = new ViewModel();

        $threadId = $this->params()->fromQuery('id', false);
		$pageNo = $this->params()->fromQuery('page', 1);

		if ($threadId){
			$this->threadService->incrementViews($threadId);
			
			$paginater = $this->threadService->getPaginater($threadId);
			$postList = $this->threadService->getPosts($threadId, $pageNo);
			$thread = $this->threadService->getThread($threadId);
			$posts = $this->postService->formatPostsforThread($postList);
			$overView = $this->forumService->getForumPathInfo($this->forumService->getForumFromThread($threadId), $threadId);
		
			$viewModel->setVariable('posts', $posts);
			$viewModel->setVariable('paginater', $paginater);
			$viewModel->setVariable('pageNo', $pageNo);
			$viewModel->setVariable('threadid', $threadId);
			$viewModel->setVariable('overView', $overView);
			$viewModel->setVariable('thread', $thread);
			$viewModel->setTemplate("forum/thread/index");
		} else {
			$this->redirect()->toUrl("/forum");
        }

        return $viewModel;
    }
	
    public function newAction()
    {
		$viewModel = new ViewModel();

		if ($this->getRequest()->isPost())
		{
			$forumId = $this->params()->fromPost('id', false);
			$userId = $this->zfcUserAuthentication()->hasIdentity() ? $this->zfcUserAuthentication()->getIdentity()->getId() : false;
			$title = $this->params()->fromPost('title', false);
			$message = $this->params()->fromPost('message', false);
            $sticky = $this->params()->fromPost('sticky', false); 

			if ($forumId == false || !is_int($userId) || $title == false || $message == false){
				if ($forumId > 0){
				$this->redirect()->toUrl("/forum/?id=" . $forumId);
				} else {
					$this->redirect()->toUrl("/forum");
				}
			} else {
				$this->getThreadService();
				$threadId = $this->threadService->createThread($forumId, $userId, $title, $message, $sticky);
				$this->redirect()->toUrl("/forum/thread?id=" . $threadId);
			}
		}
			
		$forumId = $this->params()->fromQuery('id', false);

		$viewModel->setVariable('forumId', $forumId);
		$viewModel->setTemplate("forum/thread/new");
			
		return $viewModel;
    }
    
    public function deleteAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity() && 
			$this->getUserService->getAccessLevel($this->zfcUserAuthentication()->getIdentity()->getUserId(), "Admin")) 
        {
            $id = $this->params()->fromPost('id', false);
            $redirect = $this->params()->fromQuery('redirect', false);
            $this->getThreadService();
            if ($id){
                $this->threadService->deleteThread($id);
            }
            if ($redirect){
                $this->redirect()->toUrl("/forum/?id=" . $redirect);
            } else {
                $this->redirect()->toUrl("/forum");
            }
        }
        $this->redirect()->toUrl("/forum");
    }

    private function getForumService()
    {
		$this->forumService = $this->getServiceLocator()->get('chatter_forum_service');
    }
    private function getThreadService()
    {
		$this->threadService = $this->getServiceLocator()->get('chatter_thread_service');
    }
    private function getPostService()
    {
		$this->postService = $this->getServiceLocator()->get('chatter_post_service');
    }
    private function getUserService()
    {
		$this->userService = $this->getServiceLocator()->get('chatter_user_service');
		return $this->userService;
    }
}
