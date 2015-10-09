<?php

namespace Chatter;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Hydrator\ObjectProperty;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $em = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($em);
		
		$sm = $e->getApplication()->getServiceManager();
		$headLink = $sm->get('viewhelpermanager')->get('headLink');
		$headLink->appendStylesheet('/chatter/css/chatter.css');
		$headScript = $sm->get('viewhelpermanager')->get('headScript');
		$headScript->appendFile('https://ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojo/dojo.js', 'text/javascript', []);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
			'factories' => array(
				'chatter_module_options' => function ($sl) {
					$config = $sl->get('Config');
					return new Options\ModuleOptions(isset($config['chatter']) ? $config['chatter'] : array());
				},
				'chatter_text_sanitiser' => function ($sl) {
					return new Sanitiser\TextSanitiser;
				},
				/*Services*/
				'chatter_forum_service' => function ($sl) {
					$service = new Service\Forum;
					$service->setModuleOptions($sl->get('chatter_module_options'));
					
					$postMapper = $sl->get('chatter_post_mapper');
					$service->setCommentMapper($postMapper);
					$forumMapper = $sl->get('chatter_forum_mapper');
					$service->setForumMapper($forumMapper);
					$threadMapper = $sl->get('chatter_thread_mapper');
					$service->setThreadMapper($threadMapper);
					$userService = $sl->get('chatter_user_service');
					$service->setUserService($userService);
					$zfcUserService = $sl->get('zfcuser_auth_service');
					$service->setzfcUserService($zfcUserService);
					return $service;
				},
				'chatter_thread_service' => function ($sl) {
					$service = new Service\Thread;

					$threadMapper = $sl->get('chatter_thread_mapper');
					$service->setThreadMapper($threadMapper);				
					$postMapper = $sl->get('chatter_post_mapper');
					$service->setPostMapper($postMapper);

					return $service;
				},
				'chatter_post_service' => function ($sl) {
					$service = new Service\Post;

					$postMapper = $sl->get('chatter_post_mapper');
					$service->setPostMapper($postMapper);
					$threadMapper = $sl->get('chatter_thread_mapper');
					$service->setThreadMapper($threadMapper);
					$zfcUserService = $sl->get('zfcuser_auth_service');
					$service->setzfcUserService($zfcUserService);
					$sanitiser = $sl->get('chatter_text_sanitiser');
					$service->setSanitiser($sanitiser);

					return $service;
				},
				'chatter_account_service' => function ($sl) {
					$service = new Service\Account;
						
					$userMapper = $sl->get('chatter_user_mapper');
					$service->setUserMapper($userMapper);

					return $service;
				},
				'chatter_user_service' => function ($sl) {
					$service = new Service\User;

					$userMapper = $sl->get('chatter_user_mapper');
					$service->setUserMapper($userMapper);
					$userUuidMapper = $sl->get('chatter_useruuid_mapper');
					$service->setUserUuidMapper($userUuidMapper);
					$emailContentMapper = $sl->get('chatter_emailcontent_mapper');
					$service->setEmailContentMapper($emailContentMapper);
					$sanitiser = $sl->get('chatter_text_sanitiser');
					$service->setSanitiser($sanitiser);

					return $service;
				},
				/*Mappers*/
				'chatter_forum_mapper' => function ($sl) {
					$config = $sl->get('chatter_module_options');
						
					$mapper = new Mapper\Forum;
					$mapper->setDbAdapter($sl->get($config->getZendDbAdapter()));
					$mapper->setHydrator(new ObjectProperty);
					$mapper->setEntityPrototype(new \stdClass);
					return $mapper;
				},
				'chatter_thread_mapper' => function ($sl) {
					$config = $sl->get('chatter_module_options');
						
					$mapper = new Mapper\Thread;
					$mapper->setDbAdapter($sl->get($config->getZendDbAdapter()));
					$mapper->setHydrator(new ObjectProperty);
					$mapper->setEntityPrototype(new \stdClass);
					return $mapper;
				},
				'chatter_post_mapper' => function ($sl) {
					$config = $sl->get('chatter_module_options');
						
					$mapper = new Mapper\Post;
					$mapper->setDbAdapter($sl->get($config->getZendDbAdapter()));
					$mapper->setHydrator(new ObjectProperty);
					$mapper->setEntityPrototype(new \stdClass);
					return $mapper;
				},
				'chatter_user_mapper' => function ($sl) {
					$config = $sl->get('chatter_module_options');
						
					$mapper = new Mapper\User;
					$mapper->setDbAdapter($sl->get($config->getZendDbAdapter()));
					$mapper->setHydrator(new ObjectProperty);
					$mapper->setEntityPrototype(new \stdClass);
					return $mapper;
				},
				'chatter_useruuid_mapper' => function ($sl) {
					$config = $sl->get('chatter_module_options');
						
					$mapper = new Mapper\UserUuid;
					$mapper->setDbAdapter($sl->get($config->getZendDbAdapter()));
					$mapper->setHydrator(new ObjectProperty);
					$mapper->setEntityPrototype(new \stdClass);
					return $mapper;
				},
				'chatter_emailcontent_mapper' => function ($sl) {
					$config = $sl->get('chatter_module_options');
						
					$mapper = new Mapper\EmailContent;
					$mapper->setDbAdapter($sl->get($config->getZendDbAdapter()));
					$mapper->setHydrator(new ObjectProperty);
					$mapper->setEntityPrototype(new \stdClass);
					return $mapper;
				},
			),
		);
    }
}
