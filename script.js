if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function() 
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}
function sendAddonBox( id )
{
	captcha_response = $("#div"+id).find( "#recaptcha_response_field" ).val();
	captcha_quiz = $("#div"+id).find( "#recaptcha_challenge_field" ).val();
	idea = $("#div"+id).find( "#ideabox" ).val();
	$.post("submitajax.php",{
		recaptcha_challenge_field: captcha_quiz,
		recaptcha_response_field: captcha_response,
		parent: id,
		idea: idea,
		hidden: 1
	}, 
	function(xml) {
		if( xml.trim() == "done" )
		{
			hideAddonBox( id );
		}
		else
		{
			str = "Error:<br/>";
			str += xml;
			$("#div"+id).find( ".ideaaddonbox" ).html( str );
		}
	});
}
function showAddonBox( id )
{
	if( window.openinkling != -1 )
	{
		hideAddonBox( openinkling, true );
	}
	str = "";

	str += "<textarea rows=4 cols=200 id=\"ideabox\"></textarea><br/>";
	str += "<button onclick='sendAddonBox("+id+")'>Submit</button>"
	str += "<br/>";
	str += "";
	
	str += "<span id=\"recaptcha_div\"></span>"

	$("#div"+id).find( "#ideaaddonbox" + id ).attr("class", "ideaaddonbox");
	$("#div"+id).find( "#ideaaddonbox" + id ).html(str);

	Recaptcha.create("6Ld5TtoSAAAAABwreXK5D-N-Bithq46CwCwyvWlu", 
	"recaptcha_div", { 
   		theme: "red"
	});

	$("#div"+id).find( "#ideaaddontext" + id ).html("Close");
	$("#div"+id).find( "#ideaaddontext" + id ).attr("onclick", "hideAddonBox("+id+")");
	window.openinkling = id;
}
function hideAddonBox(id, instant)
{
	instant = typeof instant !== 'undefined' ? instant : false;
	if( instant )
	{
		$("#div"+id).find( "#ideaaddonbox" + id ).attr("class", "");
		$("#div"+id).find( "#ideaaddonbox" + id ).html("");
		$("#div"+id).find( "#ideaaddontext" + id ).html("Add An Inkling");
		$("#div"+id).find( "#ideaaddontext" + id ).attr("onclick", "showAddonBox("+id+")");
	}
	else
	{
		$("#div"+id).find( "#ideaaddonbox" + id ).attr("class", "");
		$("#div"+id).find( "#ideaaddonbox" + id ).html("");
		$("#div"+id).find( "#ideaaddontext" + id ).html("Add An Inkling");
		$("#div"+id).find( "#ideaaddontext" + id ).attr("onclick", "showAddonBox("+id+")");
	}
}
function expandChildren(id, invert)
{
	var current_id = id;
	$.post("children.php",{
		id: current_id
	}, 
	function(xml) {
		$("#div"+current_id).find( ".showchildren" ).attr( "onclick", "hideChildren(" + id + ",'" + invert + "')" );
		$("#div"+current_id).find( ".showchildren" ).html("Hide Inklings");
		$("#div"+current_id).find( ".children" ).html( prepareChild( xml, invert ) );
	});
}
function hideChildren(id, invert)
{
	current_id = id;
	$("#div"+current_id).find(".children").html("");
	$("#div"+current_id).find( ".showchildren" ).attr( "onclick", "expandChildren(" + id + ", '" + invert + "')" );
	$("#div"+current_id).find( ".showchildren" ).html("Show Inklings");
}
function prepareChild(xml, invert)
{
	var str = "";
	$( xml ).find( "idea" ).each( function(){
		id = $( this ).find( "id" ).text().trim()
		if( invert == 'true' )
		{
			str += "<div class='invertedidea' id='div" + id + "'>";
		}
		else
		{
			str += "<div class='idea' id='div" + id + "'>";
		}
		str += "<table width=100%>";
		str += "<tr>";
		str += "<td style='vertical-align:text-top;' width=100% height=100%>";
		if( invert == 'true' )
		{
			str += "<div class='invertedideatext'>";
		}
		else
		{
			str += "<div class='ideatext'>";	
		}
		str += "<a>"+ $( this ).find( "ideatext" ).text().trim() +"</a>";
		str += "</div>"
		if( invert == 'true' )
		{
			str += "<div class='invertedideabottom'>";
		}
		else
		{
			str += "<div class='ideabottom'>";	
		}
		if( $( this ).find( "childrencount" ).text().trim() != "0" )
		{
			str += "<div class='ideabottomchildren'>";
			if( invert == 'true' )
			{
				str += "<a class='showchildren' id='" + id + "' onclick=\"expandChildren(\'" + id + "\', 'false')\">Show Inlkings</a>";
			}
			else
			{
				str += "<a class='showchildren' id='" + id + "' onclick=\"expandChildren(\'" + id + "\', 'true')\">Show Inklings</a>";	
			}
			str += "</div>";
		}
		str += "<span class='ideaaddon' id='ideaddon" + id + "''><a class='ideaaddontext' id='ideaaddontext" + id + "' onclick='showAddonBox(" + id + ")'>Add An Inkling</a></span>";
		str += "<div class='boxes'> <div id='ideaaddonbox" + id + "'></div> <div class='children'></div> </div>";
		if( invert == 'true' )
		{
			str += "<div class='invertedideabottomauthor'>";
		}
		else
		{
			str += "<div class='ideabottomauthor'>";	
		}
		str += "<a>" + $( this ).find( "author" ).text().trim() + "</a>";
		str += "</div>";
		str += "</div>";
		str += "</td></tr>";
		str += "</table>";
		str += "</div>";
		str += "\n";
	});

	return str;
}

$(document).ready(function(e) {
	window.openinkling = -1;
	$(".up").click(function(event) {
		event.preventDefault();
		var current_id = $(this)[0].id;
		$.post("vote.php",{
			id: current_id,
			vote: 'up'
		}, 
		function(xml) {
			$("#r"+current_id).html( xml );
		});
		$("body").find("#div"+current_id).find("#u"+current_id).html( "<a>Up</a>" );
		$("body").find("#div"+current_id).find("#d"+current_id).html( "<a>Down</a>" );
	});
	$(".down").click(function(event) {
		event.preventDefault();
		var current_id = $(this)[0].id;
		$.post("vote.php",{
			id: current_id,
			vote: 'down'
		}, 
		function(xml) {
			$("#r"+current_id).html( xml );
		});	
		$("body").find("#div"+current_id).find("#u"+current_id).html( "<a>Up</a>" );
		$("body").find("#div"+current_id).find("#d"+current_id).html( "<a>Down</a>" );
	});
	
});