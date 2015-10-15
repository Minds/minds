import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { SessionFactory } from 'src/services/session';
import { Client } from "src/services/api";


@Component({
  selector: 'minds-button-feature',
  inputs: ['_object: object'],
  host: {
    '(click)': 'feature()'
  }
})
@View({
  template: `
    <button class="" [ng-class]="{'selected': object.featured_id || object.featured }">
      <i class="material-icons">star</i>
      <counter *ng-if="object.reminds > 0">{{object.reminds}}</counter>
    </button>
  `,
  directives: [CORE_DIRECTIVES]
})

export class FeatureButton {

  object;
  session = SessionFactory.build();

  constructor(public client : Client) {
  }

  set _object(value : any){
    this.object = value;
  }

  feature(){
    var self = this;

    if (this.object.featured)
      return this.unFeature();

    this.object.featured = true;

    this.client.put('api/v1/admin/feature/' + this.object.guid, {})
      .then((response : any) => {

      })
      .catch((e) => {
        this.object.featured = false;
      });
  }

  unFeature(){
    var self = this;
    this.object.featured = false;
    this.object.featured_id = null;
    this.client.delete('api/v1/admin/feature/' + this.object.guid, {})
      .then((response : any) => {

      })
      .catch((e) => {
        this.object.featured = true;
      });
  }

}
