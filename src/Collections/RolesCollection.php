<?php

namespace Collections;

use Principal\RolesBackend;
use Sabre\DAV\Collection;
use Sabre\DAV\FSExt\Directory as SabreDirectory;

class RolesCollection extends Collection {
    
    private RolesBackend $principalBackend;
    private $dataRoot;

    public function __construct(RolesBackend $principal_backend, string $dataRoot)
    {
        $this->$dataRoot = $dataRoot; 
        $this->principalBackend = $principal_backend;
    }

    public function getChildren() : array { 
        $path = $this->dataRoot;
        $dirs = [];
        foreach ($this->principalBackend->roles as $role) { 
            if (!is_dir($path . 'public/' . $role)){
                mkdir($path . 'public/' . $role, 0777, true);
            }
            $dirs[] = new SabreDirectory($path . 'public/' . $role, $role);
        }

        return $dirs;
    } 

    public function getName() : string
    {
        return "Groups";
    }

}
