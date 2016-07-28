import { Component } from 'angular2/core';
import { Client } from '../../services/api';

@Component({
  selector: 'plugin-{{plugin.lc_name}}',
  template: `

  `
})

export class {{plugin.name}} {

  constructor(private client : Client){

  }

}
