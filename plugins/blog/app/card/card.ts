import { Component, Inject } from '@angular/core';

import { Client } from '../../../services/api';
import { SessionFactory } from '../../../services/session';
import { MindsBlogListResponse } from '../../../interfaces/responses';
import { AttachmentService } from '../../../services/attachment';
import { ACCESS } from '../../../services/list-options';


@Component({
  moduleId: module.id,
  selector: 'minds-card-blog',

  inputs: ['_blog : object'],
  templateUrl: 'card.html'
})

export class BlogCard {

  minds;
  blog;
  session = SessionFactory.build();
  access = ACCESS;

  constructor(public attachment: AttachmentService) {
    this.minds = window.Minds;
  }

  set _blog(value: any) {
    this.blog = value;
  }

}
