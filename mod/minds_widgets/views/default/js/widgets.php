<?php if(0){?><script><?php } ?>
/*
 * Renders a minds news post n an external site
 */

var posts = document.getElementsByClassName('minds-post');

var index;
for (index = 0; index < posts.length; ++index) {
    post = posts[index];
   
   iframe = document.createElement('iframe');
   iframe.id = 'post-'+post.getAttribute("data-guid");
   iframe.setAttribute('style', 'border:0;');
   iframe.src = "<?php echo elgg_get_site_url(); ?>news/"+post.getAttribute("data-guid")+"?async=true";
   iframe.width = 640;
   iframe.height=420; //auto depending on iframe
    
 //  iframe.height = iframe.contentWindow.document .body.scrollHeight;

   // newwidth=iframe.contentWindow.document .body.scrollWidth;
   post.appendChild(iframe);
 ready(iframe.id);
   /**
    * @todo continuously check the height
    */
   iframe.onload = function(){
	   setInterval(function(){
			id = 'post-'+post.getAttribute("data-guid");
			document.getElementById(id).height =document.getElementById(id).contentWindow.document .body.scrollHeight;
			document.getElementById(id).width =document.getElementById(id).contentWindow.document .body.scrollWidth;
	   }, 1000);
   };
}


/**
 * Allow for commenting to take place
 */
 function ready(id){
	frame = document.getElementById(id).contentWindow.document;
	var comments = frame.querySelectorAll(".comments-input");
	if(comments[0]){
		comments[0].onkeypress = function(e){
			if(e.keyCode == 13){
				e.preventDefault();
				var form = comments[0].parentNode.parentNode.parentNode.parentNode;
				var list = form.parentNode.children[1].children[0]; //@todo this may not be the most efficient or reliable way
				
				
				var data = new FormData();
				data.append('comment',comments[0].value);
				
				xmlhttp=new XMLHttpRequest();
				xmlhttp.onreadystatechange=function(){
					if(xmlhttp.readyState ==4 && xmlhttp.status == 200){
				   		console.log(xmlhttp.response);
				   		comments[0].value = '';
				   		c = frame.createElement('li');
				   		c.innerHTML = xmlhttp.response;
				   		list.appendChild(c);
				    }
			    }
			    xmlhttp.open("POST",form.getAttribute('action'), true);
			    xmlhttp.send(data);
			   
			   	list.appendChild		
			}
		}
	}
}