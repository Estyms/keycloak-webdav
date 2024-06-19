<?php

use Collections\HomeCollection;
use Collections\RolesCollection;
use Principal\RolesBackend;
use Sabre\DAV;
use Dotenv\Dotenv;

// The autoloader
require 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$redis_client = new Redis();
$redis_client->connect($_ENV['redis_host'], intval($_ENV['redis_port']));

$principalBackend = new RolesBackend();

// Set Auth
$authBackend = new Keycloak\KeycloakAuth($redis_client, $principalBackend,$_ENV['client_id'], $_ENV['client_secret'], $_ENV['keycloak_token_url'] );
$authBackend->setRealm($_ENV['realm']);
$authPlugin = new DAV\Auth\Plugin($authBackend);

$path = $_ENV['users_path'];

// The server object is responsible for making sense out of the WebDAV protocol
$server = new DAV\Server([new HomeCollection($authPlugin, $path), new RolesCollection($principalBackend, $path)]);

// If your server is not on your webroot, make sure the following line has the
// correct information
$server->setBaseUri($_ENV['base_uri']);

// The lock manager is responsible for making sure users don't overwrite
// each others changes.
$lockBackend = new DAV\Locks\Backend\File('data/locks');
$lockPlugin = new DAV\Locks\Plugin($lockBackend);
$server->addPlugin($lockPlugin);


$principalPlugin = new Sabre\DAVACL\Plugin($principalBackend);

$server->addPlugin($authPlugin);
$server->addPlugin($principalPlugin);
$server->addPlugin(new DAV\Browser\Plugin());
$server->addPlugin(new DAV\PartialUpdate\Plugin());

$server->addPlugin(
    new DAV\Sync\Plugin()
);

// All we need to do now, is to fire up the server
$server->start();
