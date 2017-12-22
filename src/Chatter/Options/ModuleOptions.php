<?php

namespace Chatter\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $zendAdapter = 'Zend\Db\Adapter\Main';
    /**
     * @var array
     */
    protected $forumBaseName = 'New Chatter Forum';
    /**
     * @var array
     */
    protected $chatterEmailSendoutAddress = 'chatter@example.com';

    /**
     * Set zend db adapters
     *
     * @param array $zendAdapter
     * @return ModuleOptions
     */
    public function setZendDbAdapter($zendAdapter)
    {
        $this->zendAdapter = $zendAdapter;
        return $this;
    }

    /**
     * Get zend db adapters
     *
     * @return array
     */
    public function getZendDbAdapter()
    {
        return $this->zendAdapter;
    }
    
    /**
     * Set chatter root name
     *
     * @param array $forumBaseName
     * @return ModuleOptions
     */
    public function setChatterRootName($forumBaseName)
    {
        $this->forumBaseName = $forumBaseName;
        return $this;
    }

    /**
     * Get chatter root name
     *
     * @return array
     */
    public function getChatterRootName()
    {
        return $this->forumBaseName;
    }
    
    /**
     * Set chatter email sendout address
     *
     * @param array $chatterEmailSendoutAddress
     * @return ModuleOptions
     */
    public function setChatterEmailSendoutAddress($chatterEmailSendoutAddress)
    {
        $this->chatterEmailSendoutAddress = $chatterEmailSendoutAddress;
        return $this;
    }

    /**
     * Get chatter email sendout address
     *
     * @return array
     */
    public function getChatterEmailSendoutAddress()
    {
        return $this->chatterEmailSendoutAddress;
    }
}
