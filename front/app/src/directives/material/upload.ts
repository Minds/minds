import { Directive, ViewContainerRef, ProtoViewRef } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[mdl-upload]',
  properties: ['mdlUpload', 'progress']
})

export class MaterialUpload{

  element : any;

  constructor(viewContainer: ViewContainerRef) {
    this.element = viewContainer.element.nativeElement;
    MaterialService.updateElement(viewContainer.element.nativeElement);


  }

  set progress(value : number){
    console.log(value, this.element);
    this.element.MaterialProgress.setProgress(value);
  }
}
