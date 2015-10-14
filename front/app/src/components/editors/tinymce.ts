import { Component, View, CORE_DIRECTIVES, EventEmitter } from 'angular2/angular2';
import { Client } from "src/services/api";

declare var tinymce;

@Component({
  selector: 'minds-tinymce',
  properties: [ '_content: content' ],
  events: [ 'update: content' ]
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
	         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
	         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
	         "save table directionality emoticons template paste autoresize"
	   ],
      setup: (ed) => {

        this.editor = ed;
        ed.on('ExecCommand', (e) => {
          this.update.next(ed.getContent());
        });

        ed.on('change', (e) => {
          this.update.next(ed.getContent());
        });

        ed.on('keyup', (e) => {
          this.update.next(ed.getContent());
        });

      }
    });
  }

  onDestroy(){
     tinymce.remove('minds-tinymce > textarea');
  }

  set _content(value : string){
    if (value){
      this.editor.setContent(value);
    }
    else this.editor.setContent("");
  }

}
