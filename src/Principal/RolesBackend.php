<?php

namespace Principal;

use Sabre\DAVACL\PrincipalBackend\AbstractBackend;

class RolesBackend extends AbstractBackend
{
    public array $roles = [];
    public array $principals = [];

    public function getPrincipalsByPrefix($prefixPath)
    {
       return array_filter(array: $this->principals, callback: function($k) use($prefixPath) {
           return str_starts_with($prefixPath, $k);
       }); 
    }

    public function getPrincipalByPath($path)
    {
        return $path;
    }

    public function updatePrincipal($path, \Sabre\DAV\PropPatch $propPatch)
    {
        // TODO: Implement updatePrincipal() method.
    }

    public function searchPrincipals($prefixPath, array $searchProperties, $test = 'allof')
    {
        // TODO: Implement searchPrincipals() method.
    }

    public function getGroupMemberSet($principal)
    {
        // TODO: Implement getGroupMemberSet() method.
    }

    public function getGroupMembership($principal)
    {
	if (!str_contains("groups")) {
		return $this->principals;
	}
	else return [$principal];
    }

    public function setGroupMemberSet($principal, array $members)
    {
        // TODO: Implement setGroupMemberSet() method.
    }

    public function setPrincipals(array $roles) {
        $this->roles = $roles;
        $this->principals = array_map(array: $roles, callback:  function($r) {
            return "/principals/groups/" . $r;
        });
    }
}
