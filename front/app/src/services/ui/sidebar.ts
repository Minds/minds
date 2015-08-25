export class Sidebar{
	open(){
		var drawer : any = document.getElementsByClassName('mdl-layout__drawer')[0];
		drawer.style['transform'] = "translateX(0)";
		drawer.style['-webkit-transform'] = "translateX(0)";
		drawer.style['-moz-transform'] = "translateX(0)";
		var self = this;
		//we have a delay so we don't close after click
		setTimeout(() => {
			var listener = (e) => {
				self.close();
				document.removeEventListener('click', listener);
			};
			document.addEventListener("click", listener);
		}, 300);
	}
	close(){
		var drawer : any = document.getElementsByClassName('mdl-layout__drawer')[0];
		drawer.style['transform'] = null;
		drawer.style['-webkit-transform'] = null;
		drawer.style['-moz-transform'] = null;
	}
}
