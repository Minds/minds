import { Component, View, NgFor, NgIf, FORM_DIRECTIVES} from 'angular2/angular2';
import { Router } from 'angular2/router';
import { Client, Upload } from 'src/services/api';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsActivityObject } from 'src/interfaces/entities';

@Component({
  selector: 'minds-search-bar',
  viewBindings: [ Client ],
  host : {
    '(keyup)' : 'keyup($event)'
  }
})
@View({
  template: '<div class="mdl-textfield mdl-js-textfield"> \
        <i class="material-icons">search</i> \
        <input class="mdl-textfield__input" type="text" id="search" [(ng-model)]="q"/> \
        <label class="mdl-textfield__label" for="search"> \
        </label> \
    </div>',
  directives: [ NgFor, NgIf, Material, FORM_DIRECTIVES ]
})

export class SearchBar {

  q : string = "";

  constructor(public router : Router){
  }

  search(){
    this.router.navigate('search?q=' + this.q);
  }

  keyup(e){
    if(e.keyCode == 13)
      this.search();
  }

}
