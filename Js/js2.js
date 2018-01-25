function cityInfo(x_y, Async)
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	if( Async )
	{
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
  			{
				//TU DAVAS KOD CO SA MA VYKONAT V PRIPADE ASYNCHRONOUS
				$("#local-info").html(xmlhttp.responseText);
  			}
		}
	}
  
	xmlhttp.open("GET","script.php?script=cityInfo&xy=" + x_y, Async);
	xmlhttp.send();
	
	if( !Async )
	{
		if( xmlhttp.readyState==4 && xmlhttp.status==200 )
  		{
			//TU DAVAS KOD CO SA MA VYKONAT V PRIPADE SYNCHRONOUS
			//$("#myID").html(xmlhttp.responseText);
			$("#local-info").html(xmlhttp.responseText);
  		}
	}
}

$(document).ready(function(){
	$(".map_img").click(function(){
		var x_y = $(this).attr("id");
		cityInfo(x_y, true);
	});
});
