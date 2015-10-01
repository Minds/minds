import { Component, View, NgIf} from 'angular2/angular2';
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';
import { Storage } from 'src/services/storage';
import { Sidebar } from 'src/services/ui/sidebar';
import { SessionFactory } from 'src/services/session';
import { SearchBar } from 'src/controllers/search/bar';
import { TopbarNavigation } from './topbar-navigation';

@Component({
  selector: 'minds-topbar',
  viewBindings: [ Storage, Sidebar ]
})
@View({
  templateUrl: 'templates/components/topbar.html',
  directives: [ NgIf, RouterLink, Material, SearchBar, TopbarNavigation ]
})

export class Topbar{

	session = SessionFactory.build();

	constructor(public storage: Storage, public sidebar : Sidebar){
	}

	/**
	 * Open the navigation
	 */
	openNav(){
		this.sidebar.open();
	}
}
