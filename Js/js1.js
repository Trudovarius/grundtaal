//registracia rozbalovaci panel naspodku stranky index hned pod loginom, najprv obrazok, po kliknuti sa objavi formular
$(document).ready(function(){
	var a = "\
		<form action='registered.php' method='post'>\
    	<input  name='Name' type='text' value='Name' />\
   		<br/>\
    	<input name='Password' type='password' value='Password' />\
    	<br/>\
    	<input name='Email' type='text' value='E-mail' />\
    	<br/>\
    	<input type='submit' value='Register' />\
    	</form>";
	$("#register_img").click(function(){
		$("#registration").html(a);
	});
});

/*
//mapa okolitych dedin, oznacovanie mysou
$(document).ready(function(){
	//ak myskou klikne na obrazok nastavy sa border 2px sedy
	$(".map_img").click(function(){
		$(this).css({
			border: '2px solid #252525',
			width : '92px',
			height: '92px'
			});
			var field = this;
	});
	if(field != this){
		$(field).css({
			border: '0px',
			width : '96px',
			height: '96px'
		});
	}
});
*/