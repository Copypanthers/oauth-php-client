<?php
/*

/oauth2/authorize?client_id={{client_id}}&redirect_uri={{callback_url}}&response_type=code
/oauth2/token?grant_type=authorization_code&code={{code}}&redirect_uri={{callback_url}}&client_id={{client_id}}&client_secret={{client_secret}}
{{domain}}/oauth2/token/?grant_type=authorization_code&code={{auth_code}}&redirect_uri={{callbakc_uri}}&client_id={{client_id}}&client_secret={{client_secret}}
/oauth2/token?client_id={{client_id}}&client_secret={{client_secret}}&grant_type=client_credentials

*/
class crowdpanthersoAuth {
    public $CurlHeaders;
    public $ResponseCode;
    public $Redirect_uri;
 
    private $_AuthorizeUrl = "http://api.crowdpanthers.com/oauth2/authorize";
    private $_AccessTokenUrl = "http://api.crowdpanthers.com/oauth2/token";
    private $_ProfileOfMe = "http://api.crowdpanthers.com/api/v1";

    public function __construct() {
        $this->CurlHeaders = array();
        $this->ResponseCode = 0;
    }
 
    public function RequestAccessCode ($client_id, $redirect_url) {
        return($this->_AuthorizeUrl . "?client_id=" . $client_id . "&response_type=code&redirect_uri=" . $redirect_url);
    }
 
    // Convert an authorization code from an Crowdpanther callback into an access token.
    public function GetAccessToken($client_id, $client_secret, $auth_code) {
        // Init cUrl.
        $r = $this->InitCurl($this->_AccessTokenUrl);
 
        // Add client ID and client secret to the headers.
        curl_setopt($r, CURLOPT_HTTPHEADER, array (
            "Authorization: Basic " . base64_encode($client_id . ":" . $client_secret),
        ));        
 
        // Assemble POST parameters for the request.
        $post_fields = "grant_type=authorization_code&code=".$auth_code."&redirect_uri=".$this->Redirect_uri."&client_id=".$client_id."&client_secret=".$client_secret;

        // Obtain and return the access token from the response.
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_POSTFIELDS, $post_fields);

        $response = curl_exec($r);

        if ($response == false) {
            die("1- curl_exec() failed. Error: " . curl_error($r)."<br />".$response);
        }
 
        //Parse JSON return object.
        return json_decode($response);
    }
 
    private function InitCurl($url) {
        $r = null;
        $r = curl_init();
        curl_setopt($r, CURLOPT_URL, $url);
//        curl_setopt($r, CURLOPT_HEADER, 1);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        return($r);
    }
 
    // A generic function that executes an Crowdpanthers API request. 
    public function ExecRequest($resource = '/me', $access_token, $get_params = array()) {
        switch ($resource) {
            case '/me':
                $url = $this->_ProfileOfMe.$resource;
                break;
            
            default:
                $url = $this->_ProfileOfMe.$resource;
                break;
        }
        // Create request string.
        $get_params['access_token'] = $access_token;
        $full_url = $url."?".http_build_query($get_params);

        $r = $this->InitCurl($full_url);
         $headers = array( 
            "Accept: application/json", 
        ); 
        curl_setopt($r, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($r);

        $res_obj = json_decode($response);
        if ($response == false) {
            die("2- curl_exec() failed. Error: " . curl_error($r)."<br />".print_r($response,true));
        }
 
        //Parse JSON return object.
        return json_decode($response);
    }
}
?>