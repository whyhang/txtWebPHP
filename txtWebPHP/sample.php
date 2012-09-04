<?php
require_once('txtwebphplib.php');
setAppKey('YOUR-APP-KEY-HERE');
setPubKey('YOUR-PUB-KEY-HERE');
startApp();
if (isMobileValid())
{
	if (!strcmp(getMessage(),"mylocation"))
	{
		addText(getUserSetLocation());
	}
	else if (!strcmp(getMessage(),"geolocation"))
	{
		addText(getGeoLocation());
	}
	else if (!strcmp(getMessage(),"push"))
	{
		startPushMessage();
		addPushText("Hello friend");
		addPushLink("www.google.com","google");
		endPushMessage();
		pushMessage(getMobile());
	}
	else
	{
	addText('Please provide a proper message');
	addLink('../libtest/index.php', "usage instruction" );
	}
}
else
{
	addText("Request is invalid Please send a request via phone");
}

endApp();

?>
