<?php
// config
$baseurl          = 'https://domain.de/nextcloud/ocs/v1.php/cloud/users';
$adminName        = 'admin';
$adminPass        = 'geheim';
// userdata
$userName    = "Heinrich";
$userPass    = "geheim2";
$displayname = "Heini";
$group       = 'Old Man';
$address     = "on a big farm";
$phone       = "0815 123 456";
$email       = "heini@farmhouse.de";

// Create a new user
$url       = $baseurl;
$options   = array (CURLOPT_POST => 1,CURLOPT_POSTFIELDS => array('userid'   => "$userName",'password' => $userPass ));
echo "<h3>Create a new user</h3>";
doCurl ($url, $options);

// Add the user to group
$url       = $baseurl . '/' . $userName . '/' . 'groups';
$options   = array (CURLOPT_POST => 1,CURLOPT_POSTFIELDS => array('groupid' => $Group));
echo "<h3>Add the user to group</h3>";
doCurl ($url, $options);

// Add the other attributes to the user
$url       = $baseurl . '/' . $userName;
$otherAtts = array('email', 'displayname', 'phone', 'address');
foreach ($otherAtts as $attribute) {
	if ($$attribute) {
		$options = array (CURLOPT_CUSTOMREQUEST => "PUT", CURLOPT_POSTFIELDS => http_build_query(array('key'   => $attribute,'value' => $$attribute)));
		echo "<h3>Add the ".$attribute." to the user</h3>";
		doCurl ($url, $options);
	}
}

// Send welcome mail
$url       = $baseurl . '/' . $userName . '/welcome';
$options   = array (CURLOPT_POST => 1);
echo "<h3>Send welcome mail</h3>";
doCurl ($url, $options);

// show user information
// $url     = $baseurl . '/' . $userName;
// $options = array (CURLOPT_CUSTOMREQUEST => "GET");
// doCurl ($url, $options);

// FUNCTION: Do the curl call
function doCurl ($url, $options) {
	global $adminName, $adminPass;
	$options = $options + array(CURLOPT_RETURNTRANSFER => true,CURLOPT_USERPWD => $adminName . ":" . $adminPass, CURLOPT_HTTPHEADER => array('OCS-APIRequest:true'),);
  	$options = $options + array(CURLOPT_VERBOSE => FALSE, CURLOPT_HEADER  => FALSE);
	$ch = curl_init($url);
 	curl_setopt_array( $ch, $options);
	$response = curl_exec($ch);
	if($response === false) {echo 'Curl error: ' . curl_error($ch) . "\n";exit(1);}
	curl_close($ch);
	// An error causes an exit
	if (preg_match("~<statuscode>(\d+)</statuscode>~", $response, $matches)) {$responseCode = $matches[1]; }
    if ($responseCode != '100' ) {echo "Error response code; exiting<pre>\n$response\n";}
	else {echo "<xmp>$response\n</xmp><br><br><br>";	}
  // // show user information
  // preg_match("~<email>(.+?)</email>~", $response, $email);
  // echo "\n Email: ".$email[1];
  // $xml = simplexml_load_string($response);
  // echo "\nGruppen: ".$xml->data->groups->element[0];
  // // echo $xml->data->groups->element[0];
	echo "<pre>";
	print_r($options);
	echo "</pre>";
	return;
}

?>
