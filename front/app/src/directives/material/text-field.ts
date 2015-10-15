import { Directive, ElementRef, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[mdl-textfield]',
  inputs: ['mdlTextfield'],
  host: {
    "(change)": 'change()'
  }
})

export class MaterialTextfield{

  element : any;

  constructor(_element : ElementRef) {
    this.element = _element.nativeElement;

    MaterialService.updateElement(this.element);

  }

  change(){
  }

}
