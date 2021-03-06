<?php

/*
txtWeb Php library. 
This code is written for helping out txtWeb developers.
this is the first release. 
You are free to make changes to this functions and share with all. 
Your suggestions are welcome.



Owner:
Vihang Gosavi,
IIT, Bombay.
email: vihangtycoon@gmail.com
fb : www.fb.com/vihang.gosavi

*/

require_once('class.xhttp.php');

//this function converts an XML response to a tree data-structure with 'tag->tag-value' key-value pair
function xml2assoc($xml) {
        $tree = null;
        while($xml->read())
                switch ($xml->nodeType) {
                        case XMLReader::END_ELEMENT: return $tree;
                        case XMLReader::ELEMENT:
                                                     $node = array('tag' => $xml->name, 'value' => $xml->isEmptyElement ? '' : xml2assoc($xml));
                                                     if($xml->hasAttributes)
                                                             while($xml->moveToNextAttribute())
                                                                     $node['attributes'][$xml->name] = $xml->value;
                                                     $tree[] = $node;
                                                     break;
                        case XMLReader::TEXT:
                        case XMLReader::CDATA:
                                                     $tree .= $xml->value;
                }
        return $tree;
}






function setAppKey($key)	//function to set the application-key.
{
	global $appKey;
	$appKey = $key;
}


function setPubKey($key)	//function to set the publisher-key (optional) only needed if using PUSH.
{
	global $pubKey;
	$pubKey = $key;
}

function startApp()	//starting of any txtWebapplication
{
global $appKey;
echo "<html>           
      <head>           
          <meta name=\"txtweb-appkey\" content=\"$appKey\">
      </head>        
     <body>";
echo "<br>";
}

function endApp()		// function to end a txtWebApp 
{
echo "</body></html>";
}



function addlink ($link,$text) //adding link to the app
{
echo "<a href='$link' , class='txtweb-menu-for'>$text</a><br>";
}


function addText($text)	// adding text to the app	
{
	echo "$text <br>";
}

function getUserSetLocation()	//getting location of the user set by him. ( returns 0 if error )
{
	$mobilehash = $_REQUEST['txtweb-mobile']; 


	$data = array();
	$data['get'] = array(
			'txtweb-mobile' => $mobilehash,
			
			);
	$response = xhttp::fetch("http://api.txtweb.com/v1/location/get", $data);

	
	
	$r = new XMLReader();
	$r->xml($response['body']);
	$res = xml2assoc($r);
	$status = $res[0]['value'][0]['value'][0]['value'];
//	print_r($res);
	if ($status == 0)
	return $res[0]['value'][1]['value'][1]['value'];
	else 
	return 0;
}

function getMessage()
{
	return $_REQUEST['txtweb-message'];
}

function getMobile()
{
	return $_REQUEST['txtweb-mobile'];
}

function getGeoLocation()	//getting geo location of the user ( returns 0 if error )
{
	$mobilehash = $_REQUEST['txtweb-mobile']; 


	$data = array();
	$data['get'] = array(
			'txtweb-mobile' => $mobilehash,
			
			);
	$response = xhttp::fetch("http://api.txtweb.com/v1/location/get", $data);

	
	//Check response from push api
	$r = new XMLReader();
	$r->xml($response['body']);
	$res = xml2assoc($r);
	$status = $res[0]['value'][0]['value'][0]['value'];
//	print_r ($res);
	if ($status == 0)
	return $res[0]['value'][1]['value'][2]['value'][0]['value']." , ".$res[0]['value'][1]['value'][2]['value'][1]['value']." , ".$res[0]['value'][1]['value'][2]['value'][2]['value'];
	else 
	return 0;
	
}

function pushInternal($mobilehash)	// for retry logic.
{
	global $pubKey;
	global $pushMessage;
	
	$data = array();
	$data['post'] = array(
			'txtweb-mobile' => $mobilehash,
			'txtweb-message' => stripslashes($pushMessage),
			'txtweb-pubkey' => $pubKey,
			'txtweb-appkey' => $appKey,
 			);
	$response = xhttp::fetch("http://api.txtweb.com/v1/push", $data);

	
	//Check response from push api
	$r = new XMLReader();
	$r->xml($response['body']);
	$res = xml2assoc($r);
//	print_r($res);

	$status = $res[0]['value'][0]['value'][0]['value'];
	return $status;
}
function pushMessage($mobilehash)	//sends $message to $mobilehash number.
{

	$status = pushInternal($mobilehash);
	

	if ($status == 0) return 1; 
	else if ($status == -1)
	{	
	$status2 = pushInternal($mobilehash); 
	if ($status2 == 0) return 1; 
	else return $status2;
	}
	else 
	{
		return $status;
	}
}


function isMobileValid()			//verifies whether the request is from valid fone or not returns 1/0
{	
	$verifyService_APIURL = "http://api.txtweb.com/v3/verify";
	
	$SUCCESS_CODE = "0"; //the code returned by the Verify Service API on a successful response

	
	//extract the mobile-hash of the user
	$mobilehash = $_REQUEST['txtweb-mobile']; 

	//extract the message from the request
	$message =  $_REQUEST['txtweb-message'];

	//exract the verify-id sent by txtWeb
	$verify_id=$_REQUEST['txtweb-verifyid'];

	//extract the request protocol of the message
	$protocol=$_REQUEST['txtweb-protocol'];


	//prepare the URL parameters that verify service API expects
	$data = array();
	$data['get'] = array(
			'txtweb-mobile' => $mobilehash,
			'txtweb-message' => stripslashes($message),
			'txtweb-verifyid' => $verify_id,
			'txtweb-protocol' => $protocol,
			);

	//make the verify service API call here
	$response = xhttp::fetch($verifyService_APIURL,$data);

	//Parse the XML response from the Verify Service API
	$xmlReader = new XMLReader();
	$xmlReader->xml($response['body']);
	$xmlArray = xml2assoc($xmlReader);

	//get the status code from the API response
	$statusCode = $xmlArray[0]['value'][0]['value'][0]['value'];
	
	if( $statusCode == $SUCCESS_CODE ){
		return 1;
		
	}
	else{
		return 0;
	}

}

function startPushMessage()
{
global $pushMessage;
global $appKey;
$pushMessage= "<html><head><meta name='txtweb-appkey' content='$appKey' /><title>Hello</title></head><body>";
}

function addPushText($txt)
{
	global $pushMessage;
	$pushMessage .= "$txt<br>";

}

function addPushLink($text,$link)
{
	global $pushMessage;
	$pushMessage .= "<a href='$link' , class='txtweb-menu-for'>$text</a><br>";

}

function endPushMessage()
{
	global $pushMessage;
	$pushMessage .= "</body></html>";
//	echo $pushMessage;
}


?>