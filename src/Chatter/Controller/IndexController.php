<?php

namespace Chatter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Chatter\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $forumService;

    public function indexAction()
    {
        $this->getForumService();

		$viewModel = new ViewModel();
		$viewModel->setTemplate("forum/index");

		$forumId = $this->params()->fromQuery('id', false);	
		
		if ($forumId) {
			$forums = $this->forumService->getThreads($forumId);
			$viewModel->setTemplate("forum/subforum");
		} else {
			$forums = $this->forumService->getForums();
		}

		$viewModel->setVariable('navbar', $this->forumService->getForumHeaderData($forumId));
		$viewModel->setVariable('forums', $forums);
	
        return $viewModel;
    }

    private function getForumService()
    {
		$this->forumService = $this->getServiceLocator()->get('chatter_forum_service');
    }
}
