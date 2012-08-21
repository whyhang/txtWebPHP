<?php
require_once('txtwebphplib.php');
setAppKey('YOUR-APP-KEY');
startApp();
addText("Hello all");
print getUserSetLocation();
addLink("www.facebook.com","Homepage");
endApp();



?>
