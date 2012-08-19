<?php

/*
txtWeb Php library. 
This code is written for helping out txtWeb developers.
this is the first release. 
You are free to make changes to this functions and share with all. 
Your suggestions are welcome.


By

Vihang Gosavi,
IIT, Bombay.
vihangtycoon@gmail.com

*/

function startApp($appkey)
{
echo "<html>           
      <head>           
          <meta name=\"txtweb-appkey\" content=\"$appkey\">
      </head>        
     <body>";
echo "<br>";
}

function endApp()
{
echo "</body></html>";
}



function addlink ($link,$text)
{
echo "<a href='$link' , class='txtweb-menu-for'>$text</a><br>";
}


function addText($text)
{
	echo "$text <br>";
}


?>