import { Component, View, Inject } from 'angular2/angular2';
import { Router } from 'angular2/router';
import { Material } from 'src/directives/material';

@Component({
})
@View({
  templateUrl: 'templates/homepage.html',
  directives: [ Material ]
})

export class Homepage {

}
