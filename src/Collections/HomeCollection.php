<?php

namespace Collections;

use Sabre\DAV\Collection;
use Sabre\DAV\Auth\Plugin as AuthPlugin;
use Sabre\DAV\FS\Directory;

class HomeCollection extends Collection
{
    private $plugin;
    private $userPath;


    public function __construct(AuthPlugin $authPlugin, string $userPath)
    {
        $this->plugin = $authPlugin;
        $this->userPath = $userPath;
    }

    public function getChildren(): array
    {
        $principal = $this->plugin->getCurrentPrincipal();
        $username = explode("/", $principal)[1];
        $path = $this->userPath;

        if (!is_dir($path.$username)) {
            mkdir($path.$username, 0777 , true);
        }

        return [new Directory($path.$username, $username)];
    }

    public function getName(): string
    {
        return "Home";
    }
}
