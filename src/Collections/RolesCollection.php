<?php

namespace Collections;

use Principal\RolesBackend;
use Sabre\DAV\Collection;
use Sabre\DAV\FSExt\Directory as SabreDirectory;

class RolesCollection extends Collection {
    
    private RolesBackend $principalBackend;
    private string $dataPath;

    public function __construct(RolesBackend $principal_backend, string $dataPath)
    {
        $this->dataPath = $dataPath; 
        $this->principalBackend = $principal_backend;
    }

    public function getChildren() : array { 
        $path = $this->dataPath;
        $dirs = [];
        foreach ($this->principalBackend->roles as $role) { 
            if (!is_dir($path . '/groups/' . $role)){
                mkdir($path . '/groups/' . $role, 0777, true);
            }
            $dirs[] = new SabreDirectory($path . '/groups/' . $role, $role);
        }

        return $dirs;
    } 

    public function getName() : string
    {
        return "Groups";
    }

}
