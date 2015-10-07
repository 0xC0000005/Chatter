<?php

namespace Chatter\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $zendAdapter = array( 100 => 'Zend\Db\Adapter\Main' );
    /**
     * @var array
     */
    protected $forumBaseName = array( 100 => 'New Chatter Forum' );

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
     * Set forum base name
     *
     * @param array $forumBaseName
     * @return ModuleOptions
     */
    public function setForumBaseName($forumBaseName)
    {
        $this->forumBaseName = $forumBaseName;
        return $this;
    }

    /**
     * Get forum base name
     *
     * @return array
     */
    public function getForumBaseName()
    {
        return $this->forumBaseName;
    }
}
