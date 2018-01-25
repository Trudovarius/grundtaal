//funkcia pre pohyb na mape
function move(Async, x, y, name){
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttpFocus=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttpFocus=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if( Async )
	{
		xmlhttpFocus.onreadystatechange=function()
		{
			if (xmlhttpFocus.readyState==4 && xmlhttpFocus.status==200)
  			{
				//TU DAVAS KOD CO SA MA VYKONAT V PRIPADE ASYNCHRONOUS
				$("#navigation").html(xmlhttpFocus.responseText);
				$(".map_img").click(function(){
					var x_y = $(this).attr("id");
					cityInfo(x_y, true);
				});
  			}
		}
	}
	if( !Async )
	{
		if( xmlhttpFocus.readyState==4 && xmlhttpFocus.status==200 )
  		{
			//TU DAVAS KOD CO SA MA VYKONAT V PRIPADE SYNCHRONOUS
			$("#navigation").html(xmlhttpFocus.responseText);
			$(".map_img").click(function(){
			var x_y = $(this).attr("id");
			cityInfo(x_y, true);
			});
  		}
	}
	xmlhttpFocus.open("GET","mapLoad.php?sessionStart=lol&x=" + x + "&y=" + y + "&name=" + name , true);
	xmlhttpFocus.send();
}