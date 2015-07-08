import {Component, View} from 'angular2/angular2';
import {RouterLink} from 'angular2/router';

@Component({
  selector: 'minds-navigation'
})
@View({
  templateUrl: 'templates/components/navigation.html',
  directives: [RouterLink]
})

export class Navigation { 
	constructor(){ }
}