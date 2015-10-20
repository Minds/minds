import { Component, View, Inject } from 'angular2/angular2';
import { Router } from 'angular2/router';
import { Material } from 'src/directives/material';
import { MindsTitle } from 'src/services/ux/title';

@Component({
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'templates/homepage.html',
  directives: [ Material ]
})

export class Homepage {

  constructor(public title: MindsTitle){
    this.title.setTitle("Home");
  }
}
