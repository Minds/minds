import {Inject} from 'angular2/angular2';
import {Router} from 'angular2/router';

export class Navigation {

	constructor(@Inject(Router) public router: Router){
	}

	getItems() : Array<any> {
		var items : Array<any> = window.Minds.navigation;
		if(!items)
			return [];

		for(var item of items){

			if(this.router.lastNavigationAttempt == item.path || this.router.lastNavigationAttempt.indexOf(item.path) > -1)
				item.active = true;
			else
				item.active = false;

			// a recursive function needs creating here
			// a bit messy and only allows 1 tier
			if(item.submenus){
				for(var subitem of item.submenus){
					var path = subitem.path;
					for(var p in subitem.params){
						path +=  '/' + subitem.params[p];
					}

					if(this.router.lastNavigationAttempt.indexOf(path) > -1)
						subitem.active = true;
					else
						subitem.active = false;
				}
			}
		}
		return items;
	}

}
