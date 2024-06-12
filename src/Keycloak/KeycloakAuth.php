<?php

namespace Keycloak;

use Sabre\DAV\Auth\Backend\AbstractBasic;
use Sabre\DAVACL\Plugin as AclPlugin;

class KeycloakAuth extends AbstractBasic
{
    private $aclPlugin;
    private $client_id;
    private $client_secret;
    private $keycloakTokenUrl;

    public function __construct(AclPlugin $plugin, string $client_id, string $client_secret, string $keycloakTokenUrl)
    {
        $this->aclPlugin = $plugin;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->keycloakTokenUrl = $keycloakTokenUrl;
    }

    protected function validateUserPass($username, $password) : bool
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->keycloakTokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "grant_type=password&client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&username=".$username."&password=".$password,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded"
            ],
        ]);

        curl_exec($curl);

        $err = curl_error($curl);

        $data = curl_getinfo($curl);

        curl_close($curl);

        if ($err || $data['http_code'] != 200) {
            return false;
        } else {
            return true;
        }

    }
}
