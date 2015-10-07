<?php

$settings = array(
   /**
    * Base forum name
    *
    * Specify the name of the forum. One might use domain name or site name.
    * Default: New Chatter Forum
    */
    'forum_base_name' => 'New Chatter Forum',

    /**
     * Zend\Db\Adapter\Adapter DI Alias
     *
     * Please specify the DI alias for the configured Zend\Db\Adapter\Adapter
     * instance that Chatter should use.
     */
    'zend_db_adapter' => 'Zend\Db\Adapter\Forum',
);


return array(
    'chatter' => $settings,
    'service_manager' => array(
        'aliases' => array(
            'chatter_zend_db_adapter' => (isset($settings['zend_db_adapter'])) ? $settings['zend_db_adapter']: 'Zend\Db\Adapter\Adapter',
        ),
    ),
);
