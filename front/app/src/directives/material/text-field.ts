import { Directive, ViewContainerRef, ProtoViewRef } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[mdl-textfield]',
  properties: ['mdlTextfield'],
  host : {
    '(change)': 'change()'
  }
})

export class MaterialTextfield{

  constructor(viewContainer: ViewContainerRef) {

    MaterialService.updateElement(viewContainer.element.nativeElement);
    setTimeout(() => {
      if(viewContainer.element.nativeElement.MaterialTextfield)
        viewContainer.element.nativeElement.MaterialTextfield.change();
    }, 500);

  }

  change(){
    console.log('textfield changed');
  }

}
