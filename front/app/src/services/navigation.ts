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
			if(this.router.lastNavigationAttempt == item.path)
				item.active = true;
			else
				item.active = false;
		}
		return items;
	}

}
