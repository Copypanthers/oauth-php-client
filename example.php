<?php
require_once('crowdpanthers-oauth.php');
error_reporting(E_ALL);
 
$crowd_auth = new crowdpanthersoAuth();
$url = $crowd_auth->RequestAccessCode("4eb1de8bf06b10210e000005", "http://yourdomainname.com/elance/callback.php");
 
header("Location: " . $url);
 
?>