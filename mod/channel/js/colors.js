function setElementColor(element, type, color){

	if(type == "background-color") type = "backgroundColor";
	
	$(element).css(type, color);
	
}