import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES, EventEmitter} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { AutoGrow } from 'src/directives/autogrow';
import { BUTTON_COMPONENTS } from 'src/components/buttons';
import { TagsPipe } from 'src/pipes/tags';

@Component({
  selector: 'minds-card-comment',
  viewProviders: [ Client ],
  inputs: ['object', 'parent'],
  outputs: [ '_delete: delete'],
  host: {
    '(keydown.esc)': 'editing = false'
  }
})
@View({
  template: `
  <div class="mdl-card minds-comment minds-block">
    <div class="minds-avatar">
      <a [router-link]="['/Channel', {username: comment.ownerObj.username}]">
        <img src="{{minds.cdn_url}}/icon/{{comment.ownerObj.guid}}/small" class="mdl-shadow--2dp"/>
      </a>
    </div>
    <div class="minds-body">
      <a [router-link]="['/Channel', {username: comment.ownerObj.username}]" class="username mdl-color-text--blue-grey-500">{{comment.ownerObj.name}} @{{comment.ownerObj.username}}</a>
      <span class="mdl-color-text--blue-grey-300">{{comment.time_created * 1000 | date: 'medium'}}</span>
      <p [hidden]="editing" [inner-html]="comment.description | tags"></p>

      <div class="minds-editable-container" *ng-if="editing">
      	<!-- Please not the intentional single way binding for ng-model, we want to be able to cancel our changes -->
      	<textarea class="mdl-card__supporting-text message"
          [ng-model]="comment.description"
          #edit
          [auto-grow]
          (keydown.enter)="comment.description = edit.value; save();"
          (keydown.esc)="editing = false; edit.value = comment.description"
          ></textarea>
        <span>Press ESC to cancel</span>
      </div>

      <div class="mdl-card__menu mdl-color-text--blue-grey-300">
      	<button class="mdl-button minds-more mdl-button--icon" (click)="delete(i)"
          *ng-if="comment.owner_guid == session.getLoggedInUser()?.guid || session.isAdmin() || parent.owner_guid == session.getLoggedInUser()?.guid">
      		<i class="material-icons">delete</i>
      	</button>
        <button class="mdl-button minds-more mdl-button--icon" (click)="editing = !editing"
          *ng-if="comment.owner_guid == session.getLoggedInUser()?.guid || session.isAdmin()">
          <i class="material-icons">edit</i>
        </button>
      </div>
    </div>
  </div>
  `,
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, BUTTON_COMPONENTS, AutoGrow, RouterLink ],
  pipes: [ TagsPipe ]
})

export class CommentCard {

  comment : any;
  editing : boolean = false;
  minds = window.Minds;
  session = SessionFactory.build();


  _delete: EventEmitter = new EventEmitter();

	constructor(public client: Client){
	}

  set object(value: any) {
    if(!value)
      return;
    this.comment = value;
  }

  set _editing(value : boolean){
    this.editing = value;
  }

  save(){
    this.editing = false;
    this.client.post('api/v1/comments/update/' + this.comment.guid, this.comment)
      .then((response : any) => {

      });
  }

  delete(){
    this.client.delete('api/v1/comments/' + this.comment.guid);
    this._delete.next(true);
  }
}
