<?php

namespace Chatter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AccountController extends AbstractActionController
{
    protected $accountService;
    protected $userService;

    public function indexAction()
    {
		$this->getAccountService();

		$viewModel = new ViewModel();

		$userId = $this->zfcUserAuthentication()->hasIdentity() ? $this->zfcUserAuthentication()->getIdentity()->getId() : false;

		if ($userId){
			if ($this->getRequest()->isPost()){
				$this->accountService->saveSettings($this->params()->fromPost(), $userId);
			}
			$settings = $this->accountService->getSettings($userId);
			$viewModel->setVariable('userid', $userId);
			$viewModel->setVariable('settings', $settings);
        } else {
            $this->redirect()->toUrl("/user/login");
        }


        $viewModel->setTemplate("forum/account/index");
	
        return $viewModel;
    }
	
    public function profileAction()
    {
		$this->getUserService();
		$viewModel = new ViewModel();
		$username = $this->params()->fromQuery('user', false);

		$user = $this->userService->getUserByUsername($username);

		if ($user == null) {
			$this->redirect()->toUrl("/forum");
		}

		$viewModel->setVariable('user', $user);
        $viewModel->setTemplate("forum/account/profile");
	
        return $viewModel;
    }

    public function resetAction()
    {
		$viewModel = new ViewModel();
		$this->getUserService();
		
		$email = $this->params()->fromPost("email", false);
		if ($email){
			$viewModel->setTemplate("forum/account/reset");
			$this->userService->sendPasswordEmail($email);
			$viewModel->setVariable($submitted, true);
		} else {
			$uuid = $this->params()->fromPost("uuid", false);
			$password = $this->params()->fromPost("password", false);
			if ($uuid && $password){
				$viewModel->setTemplate("forum/account/savedpass");
			$this->userService->resetPassword($uuid, $password);
			} else {
				$uuid = $this->params()->fromQuery("uuid", false);
				if ($uuid) {
					$viewModel->setVariable("uuid", $uuid);
					$viewModel->setTemplate("forum/account/newpass");
				} else {
					$viewModel->setTemplate("forum/account/reset");
				}
			}
		}
	
        return $viewModel;
    }
    

    private function getAccountService()
    {
		$this->accountService = $this->getServiceLocator()->get('chatter_account_service');
    }

    private function getUserService()
    {
		$this->userService = $this->getServiceLocator()->get('chatter_user_service');
    }
}
