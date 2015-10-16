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
    <button class="" [ng-class]="{'selected': isFeatured }">
      <i class="material-icons">star</i>
    </button>
  `,
  directives: [CORE_DIRECTIVES]
})

export class FeatureButton {

  object;
  session = SessionFactory.build();
  isFeatured = false;

  constructor(public client : Client) {
  }

  set _object(value : any){
    if(!value)
      return;
    this.object = value;
    this.isFeatured = value.featured_id || value.featured;
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
