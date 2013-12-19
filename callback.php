<?php
 
require_once('crowdpanthers-oauth.php');
 
if (!isset($_GET["code"])) {
    die("Require the code parameter to validate!");
}
 
$code = $_GET["code"];
$crowd_auth = new crowdpanthersoAuth();
$json = $crowd_auth->GetAccessToken("4eb1de8bf06b10210e000005", "M5IqdtQdZN8cX741OkBniA", $code);
 
//Output code
echo "Access token is " . $json->data->access_token . "<p/>";
 
?>