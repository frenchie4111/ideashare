<html>
<head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#testbutton").click(function(event) {
		event.preventDefault();
		var current_id = 2;
		$.post("children",{
			id: current_id
		}, 
		function(xml) {
			var str = "";
			$( xml ).find( "idea" ).each( function(){
				str += $( this ).find( "ideatext" ).text() + " author:" + $( this ).find( "author" ).text() + "<br/>";
			});
			$("#testdiv").html( str );
		});
	});
});
</script>
</head>
<body>
	<input type="submit" id="testbutton" value="click" />
	<div id="testdiv"></div>
</body>
</html>