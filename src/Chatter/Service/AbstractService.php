<?php

namespace Chatter\Service;

class AbstractService
{
	protected $_moduleOptions;
	
	
	
	
	
	public function setModuleOptions($moduleOptions)
	{
		$this->_moduleOptions = $moduleOptions;
	}
}