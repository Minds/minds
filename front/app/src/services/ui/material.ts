export class Material{
	static rebuild(){
		window.componentHandler.upgradeDom();
	}
	static updateElement(element : any){
		window.componentHandler.upgradeElement(element);
	}
}
