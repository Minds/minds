export class Sidebar{
	open(){
		var self = this;
		var drawer : any = document.getElementsByClassName('mdl-layout__drawer')[0];
		drawer.classList.toggle("is-visible");

		//we have a delay so we don't close after click
		setTimeout(() => {
			var listener = (e) => {
				drawer.classList.toggle("is-visible");
				document.removeEventListener('click', listener);
			};
			document.addEventListener("click", listener);
		}, 300);
	}
	close(){
		var drawer : any = document.getElementsByClassName('mdl-layout__drawer')[0];
		drawer.classList.toggle("is-visible");
	}
}
