import { Directive, ViewContainerRef, ProtoViewRef } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[mdl-textfield]',
  properties: ['mdlTextfield']
})

export class MaterialTextfield{
  constructor(viewContainer: ViewContainerRef) {
    MaterialService.updateElement(viewContainer.element.nativeElement);
    console.log(viewContainer.element.nativeElement.MaterialTextfield);
    setTimeout(() => {
      viewContainer.element.nativeElement.MaterialTextfield.change();
    }, 500);

  }
}
