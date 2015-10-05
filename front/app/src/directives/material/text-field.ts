import { Directive, ViewContainerRef, ProtoViewRef, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[mdl-textfield]',
  inputs: ['mdlTextfield'],
  events : [
    '(change): change()'
  ]
})

export class MaterialTextfield{

  constructor(@Inject(ViewContainerRef) viewContainer: ViewContainerRef) {

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
