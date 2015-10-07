<?php

namespace Chatter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PostController extends AbstractActionController
{
    protected $postService;

    public function indexAction()
    {
        $this->getForumService();

		$viewModel = new ViewModel();

			$postId = $this->params()->fromQuery('id', false);

		if ($postId !== false) {
			$post = $this->forumService->getPost($postId);
				$viewModel->setVariable('post', $post);
			$viewModel->setTemplate("forum/post/index");
		} else {
			$viewModel->setTemplate("forum/post/not_found");
		}
	
        return $viewModel;
    }


    public function newAction()
    {
		$this->getPostService();
        $viewModel = new ViewModel();
	
        $threadId = $this->params()->fromQuery('thread', false);
		$userId = $this->zfcUserAuthentication()->hasIdentity() ? $this->zfcUserAuthentication()->getIdentity()->getId() : false;
		$message = $this->params()->fromQuery('message', false);

		if (!$threadId){
			$threadId = $this->params()->fromPost('thread', false);
		}
		if (!$message){
			$message = $this->params()->fromPost('message', false);
		}

		if ($threadId == false || !$userId || $message == false){
			if ($threadId > 0){
					$this->redirect()->toUrl("/forum/thread?id=" . $threadId);
				} else {
					$this->redirect()->toUrl("/forum");
				}
		} else {
			$postId = $this->postService->addPost($threadId, $userId, $message);

			$this->redirect()->toUrl("/forum/thread?id=" . $threadId);		
		}

        return $viewModel;
    }

    public function advancedAction()
    {
		$viewModel = new ViewModel();

		$threadId = $this->params()->fromQuery('thread', false);

		$viewModel->setVariable('threadId', $threadId);
		$viewModel->setTemplate("forum/post/new");
			
		return $viewModel;
    }

    public function editAction()
    {
		$this->getPostService();
        $viewModel = new ViewModel();
	
        $postId = $this->params()->fromPost('id', false);
		$userId = $this->zfcUserAuthentication()->hasIdentity() ? $this->zfcUserAuthentication()->getIdentity()->getId() : false;
	
		if (!$postId){
			$postId = $this->params()->fromQuery('id', false);
			$post = $this->postService->getPost($postId);
		
			if ($post['user_id'] != $userId){
				$this->redirect()->toUrl("/forum");
			} else {
				$viewModel->setVariable('postId', $post['id']);
				$viewModel->setVariable('postContent', $post['content']);
				$viewModel->setTemplate("forum/post/edit");
			}
		} else {
			$content = $this->params()->fromPost('message', false);

			if ($postId && $content){
				$this->postService->updatePost($postId, $content);
			}
			$post = $this->postService->getPost($postId);
			
			$this->redirect()->toUrl("/forum/thread?id=" . $post['thread_id']);
		}

        return $viewModel;
    }


    private function getPostService()
    {
		$this->postService = $this->getServiceLocator()->get('chatter_post_service');
    }
}
