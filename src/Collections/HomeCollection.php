<?php

namespace Collections;

use Sabre\DAV\Collection;
use Sabre\DAV\Auth\Plugin as AuthPlugin;
use Sabre\DAV\FS\Directory;

class HomeCollection extends Collection
{
    private $plugin;
    private $userPath;


    public function __construct(AuthPlugin $authPlugin, $userPath)
    {
        $this->plugin = $authPlugin;
        $this->userPath = $userPath;
    }

    public function getChildren()
    {
        $principal = $this->plugin->getCurrentPrincipal();
        $username = explode("/", $principal)[1];
        $path = $this->userPath;

        if (!is_dir($path.$username)) {
            mkdir($path.$username, 0777 , true);
        }

        return [new Directory($path.$username, $username)];
    }

    public function getName()
    {
        return "Home";
    }
}