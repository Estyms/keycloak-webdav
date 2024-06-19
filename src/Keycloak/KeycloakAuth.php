<?php

namespace Keycloak;

use Principal\RolesBackend;
use Sabre\DAV\Auth\Backend\AbstractBasic;

class KeycloakAuth extends AbstractBasic
{

    private RolesBackend $principal_backend;
    private string $client_id;
    private string $client_secret;
    private string $keycloakTokenUrl;

    public function __construct(RolesBackend $principal_backend, string $client_id, 
	string $client_secret, string $keycloakTokenUrl)
    {
        $this->principal_backend = $principal_backend;
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

        $body = curl_exec($curl);

        $err = curl_error($curl);

        $data = curl_getinfo($curl);


        curl_close($curl);

        if ($err || $data['http_code'] != 200) {
            return false;
        } else {
	        $x = json_decode($body);
	        $user_data = json_decode(base64_decode(explode(".",$x->access_token)[1]), true);
            
            $resource_data = $user_data["resource_access"];
            if (is_null($resource_data) || !array_key_exists($this->client_id, $resource_data)) return true;

            $client_data = $resource_data[$this->client_id];
            if (is_null($client_data)) return true;

            $roles = $client_data["roles"];
            if (is_null($client_data)) return true;

            $this->principal_backend->setPrincipals($roles);

            return true;
        }

    }
}
