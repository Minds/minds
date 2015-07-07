import {Component, View} from 'angular2/angular2';


@Component({
  selector: 'minds-capture'
})
@View({
  template: 'this is capture'
})

export class Capture {
	constructor(){
		console.log("this is the capture");
	}
}