<?php

namespace Chatter\View\Model;


use Zend\View\Model\ViewModel as ZendViewModel;


class ViewModel extends ZendViewModel
{
	public function setTemplate($template)
	{
		parent::setTemplate('chatter/' . $template);
	}
}