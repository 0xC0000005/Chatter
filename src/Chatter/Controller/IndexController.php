<?php

namespace Chatter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $forumService;

    public function indexAction()
    {
        $this->getForumService();

		$viewModel = new ViewModel();

		$forumId = $this->params()->fromQuery('id', false);
		if ($forumId) {
			$forums = $this->forumService->getThreads($forumId);
			$overView = $this->forumService->getForumPathInfo($forumId);
			$viewModel->setTemplate("forum/forum/subforum");
		} else {
			$forums = $this->forumService->getForums();
			$viewModel->setVariable('baseName', $this->getServiceLocator()->get('chatter_module_options')->getForumBaseName());
			$overView = $this->forumService->getGlobalForumInfo();
			
			$viewModel->setTemplate("forum/forum/index");
		}

        $viewModel->setVariable('forums', $forums);
		$viewModel->setVariable('overView', $overView);
	
        return $viewModel;
    }

    private function getForumService()
    {
		$this->forumService = $this->getServiceLocator()->get('chatter_forum_service');
    }

    private function getUserService()
    {
		$this->userService = $this->getServiceLocator()->get('chatter_user_service');
		return $this->userService;
    }
}
