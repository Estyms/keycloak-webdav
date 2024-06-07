<?php

namespace Principal;

use Sabre\DAVACL\PrincipalBackend\AbstractBackend;

class CustomBackend extends AbstractBackend
{

    public function getPrincipalsByPrefix($prefixPath)
    {
        // TODO: Implement getPrincipalsByPrefix() method.
    }

    public function getPrincipalByPath($path)
    {
        // TODO: Implement getPrincipalByPath() method.
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
        // TODO: Implement getGroupMembership() method.
    }

    public function setGroupMemberSet($principal, array $members)
    {
        // TODO: Implement setGroupMemberSet() method.
    }
}