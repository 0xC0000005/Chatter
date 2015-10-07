<?php

namespace Chatter\Service;

use Zend\Crypt\Password\Bcrypt;

class User
{
    protected $userMapper;
    protected $userUuidMapper;
    protected $emailContentMapper;

    protected $accessLevels = [
	'banned' => 0,
	'member' => 1,
	'admin' => 2,
	'staff' => 3,
	'superuser' => 4
    ];
    
    public function getAccessLevel($userId = null, $minAccess = null)
    {
		if ($userId == null || $userId == false || !is_int($userId)){
			return false;
		}
        $result = $this->userMapper->getUser($userId);
		
		$key = 4;
		foreach($this->accessLevels as $l => $v){
			if ($minAccess == $l){
				$key = $v;
			}
		}
		
		$level = $result[0]['role'];
		
		if ($minAccess == null) {
			return $level;
		}
		
		if ($this->accessLevels[$level] >= $this->accessLevels[$minAccess]){
			return true;
		} else {
			return false;
		}

    }

    public function getUserByUsername($username)
    {
		$result = $this->userMapper->getUserByUsername($username)[0];
        
        $result['post_signature'] = $this->sanitiser->buildTags($result['post_signature']);
        $result['date_joined'] = date('d-m-Y',strtotime($result['date_joined']));
        
        $joined = strtotime($result['date_joined']);
        $today = time();
        $diff = $today - $joined;
        
        $result['post_per_day'] = round($result['post_count'] / ceil($diff/(60*60*24)),2);

		return $result;
    }

    public function sendPasswordEmail($email)
    {
		$user = $this->userMapper->getUserByEmail($email)[0];
		if ($user['user_id'] != null){
			$uuid = $this->userUuidMapper->genUuid($user['user_id'], "reset_password");

			$mailContent = $this->emailContentMapper->getEmailContent("reset_password")[0]['content'];
			$content = str_replace("__X__", $uuid, $mailContent);
			$headers = 'From: Chatter' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			mail($email, "Password Reset Request", $content, $headers);
		}
    }

    public function resetPassword($uuid, $password)
    {
		$userId = $this->userUuidMapper->getUuid($uuid)[0]['user_id'];

		$bcrypt = new Bcrypt;
        $bcrypt->setCost(14);

		$pass = $bcrypt->create($password);

		$this->userMapper->updatePassword($userId, $pass);

		$uuid = $this->userUuidMapper->deleteUuid($uuid);
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

    public function setUserUuidMapper($mapper)
    {
        $this->userUuidMapper = $mapper;
        return $this;
    }

    public function setEmailContentMapper($mapper)
    {
        $this->emailContentMapper = $mapper;
        return $this;
    }

    public function setSanitiser($service)
    {
        $this->sanitiser = $service;
        return $this;
    }
}

