import {Inject} from 'angular2/angular2';
import {Router, Location} from 'angular2/router';

export class Navigation {

	constructor(@Inject(Router) public router: Router, @Inject(Location) public location: Location){
	}

	getItems(container : string = "sidebar") : Array<any> {

		var navigation : Array<any> = window.Minds.navigation;
		var items : Array<any> = navigation[container];
		if(!items)
			return [];

		var path = this.location.path();
		for(var item of items){

			if(path == item.path || (path && path.indexOf(item.path.toLowerCase()) > -1))
				item.active = true;
			else
				item.active = false;

			// a recursive function needs creating here
			// a bit messy and only allows 1 tier
			if(item.submenus){
				for(var subitem of item.submenus){
					var sub_path = subitem.path;
					for(var p in subitem.params){
						if(subitem.params[p])
							sub_path +=  '/' + subitem.params[p];
					}

					if(path && path.indexOf(sub_path.toLowerCase()) > -1)
						subitem.active = true;
					else
						subitem.active = false;
				}
			}
		}
		return items;
	}

}
