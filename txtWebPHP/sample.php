<?php
require_once('txtwebphplib.php');
setAppKey('50e4a7cb-4188-4904-82ab-fef93888c199');
setPubKey('1783e972-640b-43c5-99b1-2708d556e64c');
startApp();
if (!isMobileValid())		// Use this fuction call for checking whether the request is from authentic mobile or not.
{
	if (!strcmp(getMessage(),"mylocation"))	//checking your location
	{
		addText(getUserSetLocation());
		addLink('http://ec2-23-23-201-210.compute-1.amazonaws.com/libtest/', "more use cases" );
	}
	else if (!strcmp(getMessage(),"geolocation"))	//checking user's geolocation 
	{
		addText(getGeoLocation());
		addLink('http://ec2-23-23-201-210.compute-1.amazonaws.com/libtest/', "more use cases" );
	}
	else if (!strcmp(getMessage(),"push"))	//sending a sample push message
	{
		startPushMessage();
		addPushText("Hello friend");
		addPushLink("www.google.com","google");
		endPushMessage();
		pushMessage(getMobile());
		addText("You will shortly receive a push message :)");
		addLink('http://ec2-23-23-201-210.compute-1.amazonaws.com/libtest/', "more use cases" );
	}
	else
	{
	addText('Please provide a proper message');
	addText("'@phplib push' for push message");
	addText("'@phplib geolocaton' for your geolocation");
	addText("'@phplib mylocation' for your userSet location ");
	

	}
}
else
{
	addText("Request is invalid Please send a request via phone");
}

endApp();

?>
