import { Component, View, CORE_DIRECTIVES, EventEmitter } from 'angular2/angular2';
import { Client } from "src/services/api";

declare var tinymce;

@Component({
  selector: 'minds-tinymce',
  properties: [ '_content: content' ],
  events: [ 'update: contentChange' ]
})
@View({
  template: `
    <textarea>{{content}}</textarea>
  `,
  directives: [ CORE_DIRECTIVES ]
})

export class MindsTinymce {

  editor : any;
  content : string = "";
  update = new EventEmitter();

  constructor(public client : Client) {
    this.init();
  }

  init(){
    var self = this;
    tinymce.init({
      selector:'minds-tinymce > textarea',
      format: 'raw',
      menubar: false,
      toolbar: "undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media",
      statusbar: false,
      plugins: [
	         "advlist autolink link image lists preview hr anchor pagebreak",
	         "media nonbreaking",
	         "table directionality autoresize"
	    ],
      setup: (ed) => {

        this.editor = ed;
        ed.on('ExecCommand', (e) => {
          this.update.next(ed.getContent());
        });

        ed.on('keyup', (e) => {
          this.update.next(ed.getContent());
        });

      }
    });
  }

  onDestroy(){
   if(tinymce)
    tinymce.remove('minds-tinymce > textarea');
  }

  set _content(value : string){
    new Promise((resolve, reject) => {
      if(this.editor)
        resolve(value);
    })
    .then((value : string) => {
      this.editor.setContent(value);
    });
  }

}
