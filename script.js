/*
 * file: script.js
 * 
 * Javascript code for ideashare, contains all methods to be called for various functionality
 *
 * TODO: Lessen the amount of jquery findelementbyid's that are used. Should  be able to 
 * 		only call $("#div" + id ) once and return using it
 * TODO: Add button listeners in a more efficient way, when creating a new link add it as follows:
 * 		$("<linkhtml>").click( function() { link action });
 *
 * Author: Mike Lyons <mdl0394@gmail.com>
 */

/*
 * Function for creating a string trim function that cleans strings that 
 * are returned from ajax requests
 */
if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function() 
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}

/*
 * function sendAddonBox( id )
 * Submits user input addon box to add and inkling to an idea parent
 *
 * @param - id - The id of the parent idea
 */
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

/*
 * function showAddonBox(id)
 * Shows addon box for adding an inkling to an idea
 * 
 * @param - id - id of parent to add addon box to
 */
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

	Recaptcha.create("6Ld5TtoSAAAAABwreXK5D-N-Bithq46CwCwyvWlu", // Update recaptcha
	"recaptcha_div", { 
   		theme: "red"
	});

	$("#div"+id).find( "#ideaaddontext" + id ).html("Close");
	$("#div"+id).find( "#ideaaddontext" + id ).attr("onclick", "hideAddonBox("+id+")");
	window.openinkling = id;
}

/*
 * function showAddonBox(id)
 * Hides addon box for adding an inkling to an idea
 * 
 * @param - id - id of parent to remove addon box from
 * @param - instant - optional - whether or not it should be animated
 *        note: animation currently unsupported
 */
function hideAddonBox(id, instant)
{
	instant = typeof instant !== 'undefined' ? instant : false; // Make sure instant has a value
	if( instant )
	{
		$("#div"+id).find( "#ideaaddonbox" + id ).attr("class", "");
		$("#div"+id).find( "#ideaaddonbox" + id ).html("");
		$("#div"+id).find( "#ideaaddontext" + id ).html("Add An Inkling");
		$("#div"+id).find( "#ideaaddontext" + id ).attr("onclick", "showAddonBox("+id+")");
	}
	else // Currently animation is unsupported
	{
		$("#div"+id).find( "#ideaaddonbox" + id ).attr("class", "");
		$("#div"+id).find( "#ideaaddonbox" + id ).html("");
		$("#div"+id).find( "#ideaaddontext" + id ).html("Add An Inkling");
		$("#div"+id).find( "#ideaaddontext" + id ).attr("onclick", "showAddonBox("+id+")");
	}
}

/*
 * function expandChildren( id, invert )
 * Retrieves idea children through ajax and then adds it to the idea's html
 *
 * @param - id - the id of the parent id to show the children of
 * @param - invert - the children/parents have 2 colors, this argument tells the function what color to make this ones children
 *                 added to allow for back and forth inverting on nested children
 */
function expandChildren(id, invert)
{
	var current_id = id; // TODO: Add error checking to this parent
	$.post("children.php",{ // Request children of this parent id
		id: current_id
	}, 
	function(xml) {
		$("#div"+current_id).find( ".showchildren" ).attr( "onclick", "hideChildren(" + id + ",'" + invert + "')" );
		$("#div"+current_id).find( ".showchildren" ).html("Hide Inklings");
		$("#div"+current_id).find( ".children" ).html( prepareChild( xml, invert ) );
	});
}

/*
 * function hideChildren( id, invert )
 * Hides the children box of an idea by setting it's child box's html to nothing
 * and then adds the Show Inkling link to the parent and readding the click listener to it
 *
 * @param - id - The parent id to remove the children box from
 * @param - invert - the children/parents have 2 colors, this argument tells the function what color to make this ones children
 *                 added to allow for back and forth inverting on nested children
 *                 This needs to be passed to hideChildren because it needs to be readded to the expand children function call
 *                 in the readded Show Inklings link
 */
function hideChildren(id, invert)
{
	current_id = id;
	$("#div"+current_id).find(".children").html("");
	$("#div"+current_id).find( ".showchildren" ).attr( "onclick", "expandChildren(" + id + ", '" + invert + "')" );
	$("#div"+current_id).find( ".showchildren" ).html("Show Inklings");
}

/*
 * fucntion prepareChild( xml, invert )
 * Takes xml from an ajax query and prepares a child box for showing
 *
 * TODO: Load this html file from an easier to edit template
 *
 * @param - xml - the xml retrieved through ajax that contains the user data
 * @param - invert - he children/parents have 2 colors, this argument tells the function what color to make this ones children
 *                 added to allow for back and forth inverting on nested children
 */
function prepareChild(xml, invert)
{
	var str = ""; // The html code of the new box
	// Loop through each idea in the returned xml file and create a box for each
	// Syntax of searching xml is equivelent to that of using ajax to search html
	$( xml ).find( "idea" ).each( function(){ // For each idea in the xml
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

/*
 * When the page loads add button listeners to both the up and down vote buttons
 */
$(document).ready(function(e) {
	window.openinkling = -1;
	$(".up").click(function(event) {
		event.preventDefault();
		var current_id = $(this)[0].id;
		$.post("vote.php",{ // Send request to vote.php that upvotes this idea
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
		$.post("vote.php",{ // Send request to vote.php that down votes this idea
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