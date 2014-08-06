Application Demo Zend Framework 2
=======================

It was deployed here: https://www.openshift.com/ ( Openshift )


This application uses

    Packages:

        "zendframework/zendframework": "2.3.*",
        "doctrine/doctrine-orm-module": "0.*",
        "zendframework/zend-developer-tools": "dev-master"

Installation
------------

    git clone git://github.com/tineo/rentaldvd.git --recursive
    cd rentaldvd/
    php composer.phar update

    #Deploy database with Doctrine Mapping
    php vendor/bin/doctrine-module orm:schema-tool:update --force

    Edit config/autoload/database.global.php for your own database configuration.



Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/rentaldvd/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/rentaldvd/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
