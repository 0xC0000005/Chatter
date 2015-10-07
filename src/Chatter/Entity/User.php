<?php

namespace Chatter\Entity;

use Zend\Form\Annotation;
use ZfcUser\Entity\User as zfcuser;

/**
 * Description of UserEntity
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 * @Annotation\Name("user")
 * @Annotation\Attributes({"data-dojo-type": "dijit/form/Form"})
 *
 * @author 0xC0000005
 */
class User extends zfcuser
{
    protected $user_id;
    protected $username;
    protected $email;
    protected $display_name;
    protected $password;
    protected $date_joined;
    protected $role = "member";
    protected $title = "newbie";
    protected $avatar;
    protected $post_signature;
    protected $state;
    
    CONST ROLE_ADMIN = "admin";
    CONST ROLE_STAFF = "staff";
    CONST ROLE_SUPERUSER = "superuser";

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getDisplayName()
    {
        return $this->display_name;
    }

    public function setDisplayName($displayName)
    {
        $this->display_name = $displayName;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getDateJoined()
    {
        return $this->date_joined;
    }

    public function setDateJoined($dateJoined)
    {
        $this->date_joined = $dateJoined;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getPostSignature()
    {
        return $this->post_signature;
    }

    public function setPostSignature($sig)
    {
        $this->post_signature = $sig;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
}

