import { Component, View, NgFor, NgIf, Inject, formDirectives} from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { SessionFactory } from '../../services/session';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { Activity } from 'src/controllers/newsfeed/activity';

@Component({
  selector: 'minds-discovery',
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/discovery/discovery.html',
  directives: [ NgFor, NgIf, Material, formDirectives, InfiniteScroll ]
})

export class Discovery {

  constructor(public client: Client){}

}
