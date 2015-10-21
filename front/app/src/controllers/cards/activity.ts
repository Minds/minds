import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES, EventEmitter, ElementRef} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { AutoGrow } from 'src/directives/autogrow';
import { Remind } from './remind';
import { BUTTON_COMPONENTS } from 'src/components/buttons';
import { MindsVideo } from 'src/components/video';
import { Boost } from 'src/controllers/newsfeed/boost';
import { Comments } from 'src/controllers/comments/comments';
import { TagsPipe } from 'src/pipes/tags';
import { TagsLinks } from 'src/directives/tags';
import { ScrollFactory } from 'src/services/ux/scroll';

@Component({
  selector: 'minds-activity',
  viewProviders: [ Client ],
  inputs: ['object'],
  outputs: [ '_delete: delete']
})
@View({
  templateUrl: 'templates/cards/activity.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, BUTTON_COMPONENTS, Boost, Comments, Material, AutoGrow, Remind, RouterLink, TagsLinks, MindsVideo ],
  pipes: [ TagsPipe ]
})

export class Activity {

  activity : any;
  menuToggle : boolean = false;
  commentsToggle : boolean = false;
  session = SessionFactory.build();
  scroll = ScrollFactory.build();
  showBoostOptions : boolean = false;
  type : string;
  element : any;
  visible : boolean = false;

  editing : boolean = false;

  _delete: EventEmitter = new EventEmitter();
  scroll_listener;

	constructor(public client: Client, _element: ElementRef){
    this.element = _element.nativeElement;
    this.isVisible();
	}

  set object(value: any) {
    if(!value)
      return;
    this.activity = value;
  }

  save(){
    console.log('trying to save your changes to the server', this.activity);
    this.editing = false;
    this.client.post('api/v1/newsfeed/' + this.activity.guid, this.activity)
      .then((response : any) => {

      });
  }

  delete(){
    this.client.delete('api/v1/newsfeed/'+this.activity.guid);
    this._delete.next(true);
  }

  openMenu(){
    this.menuToggle = !this.menuToggle;
    console.log(this.menuToggle);
  }

  openComments(){
    this.commentsToggle = !this.commentsToggle;
  }

  showBoost(){
      this.showBoostOptions = !this.showBoostOptions;
  }

  isVisible(){
    this.scroll_listener = this.scroll.listen((view) => {
      if(this.element.offsetTop - view.height <= view.top && !this.visible){
        //stop listening
        this.scroll.unListen(this.scroll_listener);
        //make visible
        this.visible = true;
        //update the analytics
        this.client.put('api/v1/newsfeed/' + this.activity.guid + '/view');
      }
    });
    this.scroll.fire();
  }

  onDestruct(){
    this.scroll.unListen(this.scroll_listener);
  }
}
