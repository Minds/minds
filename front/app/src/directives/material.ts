import { Directive, ViewContainerRef, ProtoViewRef, Type } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

import { MaterialTextfield } from './material/text-field';

@Directive({
  selector: '[mdl]',
  properties: ['mdl']
})

export class Material{
  constructor(viewContainer: ViewContainerRef) {
    //MaterialService.rebuild();
    MaterialService.updateElement(viewContainer.element.nativeElement);
  }
}

export const MDL_DIRECTIVES: Type[] = [Material, MaterialTextfield];
