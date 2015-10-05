import { Directive, ViewContainerRef, ProtoViewRef, Type, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

import { MaterialTextfield } from './material/text-field';
import { MaterialUpload } from './material/upload';

@Directive({
  selector: '[mdl]',
  properties: ['mdl']
})

export class Material{
  constructor(@Inject(ViewContainerRef) viewContainer: ViewContainerRef) {
    //MaterialService.rebuild();
    MaterialService.updateElement(viewContainer.element.nativeElement);
  }
}

export const MDL_DIRECTIVES: Type[] = [Material, MaterialTextfield, MaterialUpload];
