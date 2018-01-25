// JavaScript Document
function Timer(Async){	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttpTimer=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttpTimer=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if( Async )
	{
		xmlhttpTimer.onreadystatechange=function()
		{
			if (xmlhttpTimer.readyState==4 && xmlhttpTimer.status==200)
  			{
				//TU DAVAS KOD CO SA MA VYKONAT V PRIPADE ASYNCHRONOUS
				$("#timer").html(xmlhttpTimer.responseText);
  			}
		}
	}
	if( !Async )
	{
		if( xmlhttpTimer.readyState==4 && xmlhttpTimer.status==200 )
  		{
			//TU DAVAS KOD CO SA MA VYKONAT V PRIPADE SYNCHRONOUS
			$("#timer").html(xmlhttpTimer.responseText);
  		}
	}
	xmlhttpTimer.open("GET","timer.php", true);
	xmlhttpTimer.send();
}