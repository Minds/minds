import { Directive, ViewContainerRef, ProtoViewRef, Inject } from 'angular2/angular2';
import { Material as MaterialService } from "src/services/ui";

@Directive({
  selector: '[mdl-upload]',
  inputs: ['mdlUpload', 'progress']
})

export class MaterialUpload{

  element : any;

  constructor(@Inject(ViewContainerRef) viewContainer: ViewContainerRef) {
    this.element = viewContainer.element.nativeElement;
    MaterialService.updateElement(viewContainer.element.nativeElement);


  }

  set progress(value : number){
    this.element.MaterialProgress.setProgress(value);
  }
}
