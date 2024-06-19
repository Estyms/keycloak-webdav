<?php

namespace Collections;

use Sabre\DAV\Collection;
use Sabre\DAV\Auth\Plugin as AuthPlugin;
use Sabre\DAV\FS\Directory;

class HomeCollection extends Collection
{
    private $plugin;
    private $dataPath;


    public function __construct(AuthPlugin $authPlugin, string $dataPath)
    {
        $this->plugin = $authPlugin;
        $this->dataPath = $dataPath;
    }

    public function getChildren(): array
    {
        $principal = $this->plugin->getCurrentPrincipal();
        $username = explode("/", $principal)[1];
        $path = $this->dataPath;

        if (!is_dir($path . '/users/' . $username)) {
            mkdir($path . '/users/' . $username, 0777 , true);
        }

        return [new Directory($path . '/users/' . $username, $username)];
    }

    public function getName(): string
    {
        return "Home";
    }
}
