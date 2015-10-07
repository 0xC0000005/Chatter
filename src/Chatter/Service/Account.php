<?php

namespace Chatter\Service;

class Account
{
    protected $userMapper;
    protected $sanitiser;

    public function getSettings($userId)
    {
		$response = $this->userMapper->getSettings($userId);
		return is_array($response) ? $response[0] : $response;
    }

    public function saveSettings($params, $userId)
    {
		switch($params['type'])
			{
			case 'profile':
				$response = $this->userMapper->saveProfileSettings($params, $userId);
				break;

			default:
				break;
		}
		return $response;
    }


    public function setUserMapper($mapper)
    {
        $this->userMapper = $mapper;
        return $this;
    }

    public function setSanitiser($service)
    {
        $this->sanitiser = $service;
        return $this;
    }

}

