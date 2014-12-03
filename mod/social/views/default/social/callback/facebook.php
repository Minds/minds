<script>
	//remove the old link
	var facebook = window.opener.document.getElementById("social-facebook");
	facebook.remove();
	
	//now add the checkbox
	var div = window.opener.document.getElementById("social-selection");
	var checkbox = document.createElement('input');
	checkbox.type = "checkbox";
	checkbox.name = "social_triggers[facebook]";
	checkbox.value = "selected";
	checkbox.id = "social-facebook";

	var label = document.createElement('label')
	label.htmlFor = "id";
	label.className = 'entypo';
	label.innerHTML = '&#62221;';
 
	div.appendChild(checkbox);
	div.appendChild(label);
	window.close();
</script>
