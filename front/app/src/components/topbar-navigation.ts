import { Component, View, CORE_DIRECTIVES, EventEmitter } from 'angular2/angular2';
import { RouterLink } from 'angular2/router';
import { Navigation as NavigationService } from 'src/services/navigation';
import { SessionFactory } from 'src/services/session';

@Component({
  selector: 'minds-topbar-navigation',
  viewBindings: [ NavigationService ]
})
@View({
  template: `
    <nav class="" *ng-if="session.isLoggedIn()">

    	<a *ng-for="#item of navigation.getItems('topbar')" class="mdl-color-text--white"
    		[router-link]="[item.path, item.params]"
    		>
    		<i class="mdl-color-text--blue-grey-500 material-icons" [ng-class]="{'mdl-color-text--blue-grey-500' : true}">{{item.icon}}</i>
        <span id="{{item.name | lowercase}}-counter" class="counter mdl-color-text--green-400" *ng-if="item.extras && item.extras.counter">{{item.extras.counter}}</span>
    	</a>

    </nav>
  `,
  directives: [RouterLink, CORE_DIRECTIVES]
})

export class TopbarNavigation {

	user;
	session = SessionFactory.build();

	constructor(public navigation : NavigationService){
		var self = this;
	}

}
