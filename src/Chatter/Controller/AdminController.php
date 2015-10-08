<?php

namespace Chatter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    protected $userService;
    
    public function indexAction()
    {
		$viewModel = new ViewModel();
        $this->hasAccess();
        
        $viewModel->setTemplate("chatter/admin/index");

        return $viewModel;
    }
    
    private function hasAccess()
    {
        $this->getUserService();
        $userId = $this->zfcUserAuthentication()->hasIdentity() ? $this->zfcUserAuthentication()->getIdentity()->getId() : false;
        if (!$userId){
            $this->redirect()->toUrl("/forum");
        } else if (!$this->userService->getAccessLevel($userId, "admin")){
            $this->redirect()->toUrl("/forum");
        }
    }
    
    private function getUserService()
    {
		$this->userService = $this->getServiceLocator()->get('chatter_user_service');
    }
}
