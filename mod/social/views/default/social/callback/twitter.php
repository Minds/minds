<script>
	//remove the old link
	var twitter = window.opener.document.getElementById("social-twitter");
	twitter.remove();
	
	//now add the checkbox
	var div = window.opener.document.getElementById("social-selection");
	var checkbox = document.createElement('input');
	checkbox.type = "checkbox";
	checkbox.name = "social_triggers[twitter]";
	checkbox.value = "selected";
	checkbox.id = "social-facebook";

	var label = document.createElement('label')
	label.htmlFor = "id";
	label.appendChild(document.createTextNode('twitter'));

	div.appendChild(checkbox);
	div.appendChild(label);
	window.close();
</script>