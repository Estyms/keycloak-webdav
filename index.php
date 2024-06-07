<?php

use Collections\HomeCollection;
use Sabre\DAV;

// The autoloader
require 'vendor/autoload.php';

$aclPlugin = new \Sabre\DAVACL\Plugin();


// Set Auth
$authBackend = new Keycloak\KeycloakAuth($aclPlugin,$_ENV['client_id'], $_ENV['client_secret'], $_ENV['keycloak_token_url'] );
$authBackend->setRealm($_ENV['realm']);
$authPlugin = new DAV\Auth\Plugin($authBackend);


// The server object is responsible for making sense out of the WebDAV protocol
$server = new DAV\Server([new HomeCollection($authPlugin, $_ENV['users_path'])]);

// If your server is not on your webroot, make sure the following line has the
// correct information
$server->setBaseUri($_ENV['base_uri']);

// The lock manager is responsible for making sure users don't overwrite
// each others changes.
$lockBackend = new DAV\Locks\Backend\File('data/locks');
$lockPlugin = new DAV\Locks\Plugin($lockBackend);
$server->addPlugin($lockPlugin);



// This ensures that we get a pretty index in the browser, but it is
// optional.
$server->addPlugin(new DAV\Browser\Plugin());
$server->addPlugin($authPlugin);
$server->addPlugin($aclPlugin);

// All we need to do now, is to fire up the server
$server->start();