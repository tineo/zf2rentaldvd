<?php

define( "DB_SERVER",    getenv('OPENSHIFT_MYSQL_DB_HOST') );
define( "DB_USER",      getenv('OPENSHIFT_MYSQL_DB_USERNAME') );
define( "DB_PASSWORD",  getenv('OPENSHIFT_MYSQL_DB_PASSWORD') );
define( "DB_DATABASE",  getenv('OPENSHIFT_APP_NAME') );


return array (
	'doctrine' => array (
		'connection' => array (
			'orm_default'=> array (
				'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
					'params' => array(
						'host'     => DB_SERVER,
						'user'     => DB_USER,
						'password' => DB_PASSWORD,
						'dbname'   => DB_DATABASE,
                        'mapping_types' => array("enum" =>"string")
				)
			),
		),
		'driver' => array (
			'_entity' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				//'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../module/Application/src/Application/Entity'
                ),
            ),

			'orm_default' => array (
				'drivers' => array (
					'Application\Entity' => '_entity',
				) 
			) 
		) 
	),



);
