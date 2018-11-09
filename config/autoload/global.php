<?php
/**

* Global Configuration Override
*
* You can use this file for overridding configuration values from modules, etc.
* You would place values in here that are agnostic to the environment and not
* sensitive to security.
*
* @NOTE: In practice, this file will typically be INCLUDED in your source
* control, so do not include passwords or other sensitive information in this
* file.
*/
/*
return array(
// ...
);
*/
return array(
    'db' => array(
        'driver' => 'Pdo',
        'host' => 'ec2-54-247-98-162.eu-west-1.compute.amazonaws.com',
        'db' => 'darflnvqfnhgbc',
        'username' => 'qmkdynrsqhxzis',
        'password' => '9d0ec272b0789da713c8ca078e0aa9ef1738775d24f17fc464b939efde0b8d0c',
        'port' => '5432',
// 'dsn' => 'mysql:dbname=wingman;host=wingman-db.my.phpcloud.com',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),	
		/* Moved to Auth module to allow to be replaced by Doctrine or other.
		// added for Authentication and Authorization. Without this each time we have to create a new instance.
		// This code should be moved to a module to allow Doctrine to overwrite it
        'aliases' => array( // !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'my_auth_service',
        ),
        'invokables' => array(
            'my_auth_service' => 'Zend\Authentication\AuthenticationService',
        ),
		*/
    ),
	
	'static_salt' => 'aFGQ475SDsdddaf2342', // was moved from module.config.php here to allow all modules to use it
);
