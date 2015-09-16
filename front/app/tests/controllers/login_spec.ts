import {
  AsyncTestCompleter,
  TestComponentBuilder,
  beforeEach,
  ddescribe,
  describe,
  el,
  expect,
  iit,
  inject,
  it,
  xit
} from 'angular2/test';

import {Component, View, ViewMetadata, UrlResolver, bind} from 'angular2/angular2';
import { HTTP_BINDINGS } from 'angular2/http';
import { ROUTER_BINDINGS, ROUTER_DIRECTIVES } from 'angular2/router';

import {DOM} from 'angular2/src/core/dom/dom_adapter';
import { Login as LoginComponent } from 'src/controllers/login';

export function main() {

  describe('Login[component]', () => {
    let builder: TestComponentBuilder;
    beforeEach(inject([TestComponentBuilder], (tcb) => { builder = tcb; }));

    it('should have login visible by default', inject([AsyncTestCompleter], (async) => {
      builder.createAsync(TestApp)
        .then((rootTC) => {
           var Login = rootTC.componentViewChildren[0].componentInstance;
           expect(Login.hideLogin).toEqual(false);
           async.done();
        })
        .catch((e) => {
          console.error(e);
          async.done();
        })
    }));

  });

};

@Component({
  selector: 'test-app',
  bindings: [ ]
})
@View({
  directives: [ LoginComponent, ROUTER_DIRECTIVES ],
  template: `<base href="/" />
    <minds-app>
      <minds-login></minds-login>
    </minds-app>`
})
class TestApp {}
