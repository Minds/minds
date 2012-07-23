qx.$$packageData['8']={"locales":{},"resources":{},"translations":{}};
qx.Part.$$notifyLoad("8", function() {
(function(){var a="qx.ui.virtual.core.ILayer";
qx.Interface.define(a,{members:{fullUpdate:function(b,c,d,e){this.assertArgumentsCount(arguments,6,6);
this.assertPositiveInteger(b);
this.assertPositiveInteger(c);
this.assertArray(d);
this.assertArray(e);
},updateLayerWindow:function(f,g,h,i){this.assertArgumentsCount(arguments,6,6);
this.assertPositiveInteger(f);
this.assertPositiveInteger(g);
this.assertArray(h);
this.assertArray(i);
},updateLayerData:function(){}}});
})();
(function(){var b="qx.ui.virtual.layer.Abstract",a="abstract";
qx.Class.define(b,{extend:qx.ui.core.Widget,type:a,implement:[qx.ui.virtual.core.ILayer],construct:function(){qx.ui.core.Widget.call(this);
this.__B={};
},properties:{anonymous:{refine:true,init:true}},members:{__B:null,__C:null,__D:null,__E:null,__F:null,__G:null,getFirstRow:function(){return this.__D;
},getFirstColumn:function(){return this.__E;
},getRowSizes:function(){return this.__F||[];
},getColumnSizes:function(){return this.__G||[];
},syncWidget:function(){if(!this.getContentElement().getDomElement()){return;
}
if(this.__B.fullUpdate||this.__B.updateLayerWindow&&this.__B.updateLayerData){this._fullUpdate.apply(this,this.__C);
}else if(this.__B.updateLayerWindow){this._updateLayerWindow.apply(this,this.__C);
}else if(this.__B.updateLayerData&&this.__F){this._updateLayerData();
}
if(this.__B.fullUpdate||this.__B.updateLayerWindow){var c=this.__C;
this.__D=c[0];
this.__E=c[1];
this.__F=c[2];
this.__G=c[3];
}this.__B={};
},_updateLayerData:function(){this._fullUpdate(this.__D,this.__E,this.__F,this.__G);
},_fullUpdate:function(d,e,f,g){throw new Error("Abstract method '_fullUpdate' called!");
},_updateLayerWindow:function(h,i,j,k){this._fullUpdate(h,i,j,k);
},updateLayerData:function(){this.__B.updateLayerData=true;
qx.ui.core.queue.Widget.add(this);
},fullUpdate:function(l,m,n,o){this.__C=arguments;
this.__B.fullUpdate=true;
qx.ui.core.queue.Widget.add(this);
},updateLayerWindow:function(p,q,r,s){this.__C=arguments;
this.__B.updateLayerWindow=true;
qx.ui.core.queue.Widget.add(this);
}},destruct:function(){this.__B=this.__C=this.__F=this.__G=null;
}});
})();
(function(){var f="cell.empty",e="cell.column",d="updated",c="cell.row",b="qx.event.type.Event",a="qx.ui.virtual.layer.WidgetCell";
qx.Class.define(a,{extend:qx.ui.virtual.layer.Abstract,include:[qx.ui.core.MChildrenHandling],construct:function(g){qx.ui.virtual.layer.Abstract.call(this);
this.setZIndex(2);
this._cellProvider=g;
this.__bh=[];
},properties:{anonymous:{refine:true,init:false}},events:{updated:b},members:{__bh:null,getRenderedCellWidget:function(h,j){var k=this.getColumnSizes().length;
var p=this.getRowSizes().length;
var o=this.getFirstRow();
var n=this.getFirstColumn();

if(h<o||h>=o+p||j<n||j>=n+k){return null;
}var m=(j-n)+(h-o)*k;
var l=this._getChildren()[m];

if(l.getUserData(f)){return null;
}else{return l;
}},_getSpacer:function(){var q=this.__bh.pop();

if(!q){q=new qx.ui.core.Spacer();
q.setUserData(f,1);
}return q;
},_activateNotEmptyChild:function(r){var s=qx.ui.core.FocusHandler.getInstance().getActiveWidget();
if(s==r||qx.ui.core.Widget.contains(r,s)){var t=this._getChildren();

for(var i=t.length-1;i>=0;i--){if(!t[i].getUserData(f)){t[i].activate();
break;
}}}},_fullUpdate:function(u,v,w,z){var B=this._cellProvider;
var F=this._getChildren();

for(var i=0;i<F.length;i++){var D=F[i];

if(D.getUserData(f)){this.__bh.push(D);
}else{this._activateNotEmptyChild(D);
B.poolCellWidget(D);
}}this._removeAll();
var top=0;
var G=0;

for(var y=0;y<w.length;y++){for(var x=0;x<z.length;x++){var E=u+y;
var C=v+x;
var A=B.getCellWidget(E,C)||this._getSpacer();
A.setUserBounds(G,top,z[x],w[y]);
A.setUserData(c,E);
A.setUserData(e,C);
this._add(A);
G+=z[x];
}top+=w[y];
G=0;
}this.fireEvent(d);
},_updateLayerWindow:function(H,I,J,K){var W=H+J.length-1;
var N=I+K.length-1;
var S={firstRow:Math.max(H,this.getFirstRow()),lastRow:Math.min(W,this._lastRow),firstColumn:Math.max(I,this.getFirstColumn()),lastColumn:Math.min(N,this._lastColumn)};
this._lastColumn=N;
this._lastRow=W;

if(S.firstRow>S.lastRow||S.firstColumn>S.lastColumn){return this._fullUpdate(H,I,J,K);
}var X=this._getChildren();
var L=this.getColumnSizes().length;
var U=[];
var R={};

for(var Y=H;Y<=W;Y++){U[Y]=[];

for(var Q=I;Q<=N;Q++){if(Y>=S.firstRow&&Y<=S.lastRow&&Q>=S.firstColumn&&Q<=S.lastColumn){var x=Q-this.getFirstColumn();
var y=Y-this.getFirstRow();
var M=y*L+x;
U[Y][Q]=X[M];
R[M]=true;
}}}var T=this._cellProvider;
var X=this._getChildren();

for(var i=0;i<X.length;i++){if(!R[i]){var V=X[i];

if(V.getUserData(f)){this.__bh.push(V);
}else{this._activateNotEmptyChild(V);
T.poolCellWidget(V);
}}}this._removeAll();
var top=0;
var O=0;

for(var y=0;y<J.length;y++){for(var x=0;x<K.length;x++){var Y=H+y;
var Q=I+x;
var P=U[Y][Q]||T.getCellWidget(Y,Q)||this._getSpacer();
P.setUserBounds(O,top,K[x],J[y]);
P.setUserData(c,Y);
P.setUserData(e,Q);
this._add(P);
O+=K[x];
}top+=J[y];
O=0;
}this.fireEvent(d);
}},destruct:function(){var ba=this._getChildren();

for(var i=0;i<ba.length;i++){ba[i].dispose();
}this._cellProvider=this.__bh=null;
}});
})();
(function(){var g="scrollY",f="update",d="scrollX",c="pane",b="os.scrollBarOverlayed",a="qx.ui.virtual.core.Scroller";
qx.Class.define(a,{extend:qx.ui.core.scroll.AbstractScrollArea,construct:function(h,i,j,k){qx.ui.core.scroll.AbstractScrollArea.call(this);
this.__bs=new qx.ui.virtual.core.Pane(h,i,j,k);
this.__bs.addListener(f,this._computeScrollbars,this);
this.__bs.addListener(d,this._onScrollPaneX,this);
this.__bs.addListener(g,this._onScrollPaneY,this);

if(qx.core.Environment.get(b)){this._add(this.__bs,{edge:0});
}else{this._add(this.__bs,{row:0,column:0});
}},properties:{width:{refine:true,init:null},height:{refine:true,init:null}},members:{__bs:null,getPane:function(){return this.__bs;
},_createChildControlImpl:function(l,m){if(l==c){return this.__bs;
}else{return qx.ui.core.scroll.AbstractScrollArea.prototype._createChildControlImpl.call(this,l);
}},getItemTop:function(n){throw new Error("The method 'getItemTop' is not implemented!");
},getItemBottom:function(o){throw new Error("The method 'getItemBottom' is not implemented!");
},getItemLeft:function(p){throw new Error("The method 'getItemLeft' is not implemented!");
},getItemRight:function(q){throw new Error("The method 'getItemRight' is not implemented!");
},_onScrollBarX:function(e){this.__bs.setScrollX(e.getData());
},_onScrollBarY:function(e){this.__bs.setScrollY(e.getData());
}},destruct:function(){this.__bs.dispose();
this.__bs=null;
}});
})();
(function(){var p="Boolean",o="change",n="single",m="changeSelection",l="one",k="qx.ui.virtual.selection.MModel",j="qx.data.Array",h="multi",g="selected",f="_applySelection",b="_applyDragSelection",d="_applyQuickSelection",c="_applySelectionMode",a="additive";
qx.Mixin.define(k,{construct:function(){this._initSelectionManager();
this.__bt=new qx.data.Array();
this.initSelection(this.__bt);
},properties:{selection:{check:j,event:m,apply:f,nullable:false,deferredInit:true},selectionMode:{check:[n,h,a,l],init:n,apply:c},dragSelection:{check:p,init:false,apply:b},quickSelection:{check:p,init:false,apply:d}},members:{_manager:null,__bu:false,__bv:false,__bt:null,_initSelectionManager:function(){var self=this;
var q={isItemSelectable:function(r){return self._provider.isSelectable(r);
},styleSelectable:function(s,t,u){if(t!=g){return;
}
if(u){self._provider.styleSelectabled(s);
}else{self._provider.styleUnselectabled(s);
}}};
this._manager=new qx.ui.virtual.selection.Row(this.getPane(),q);
this._manager.attachMouseEvents(this.getPane());
this._manager.attachKeyEvents(this);
this._manager.addListener(m,this._onManagerChangeSelection,this);
},_updateSelection:function(){if(this._manager==null){return;
}this._onChangeSelection();
},_applySelection:function(v,w){v.addListener(o,this._onChangeSelection,this);

if(w!=null){w.removeListener(o,this._onChangeSelection,this);
}this._onChangeSelection();
},_applySelectionMode:function(x,y){this._manager.setMode(x);
},_applyDragSelection:function(z,A){this._manager.setDrag(z);
},_applyQuickSelection:function(B,C){this._manager.setQuick(B);
},_onChangeSelection:function(e){if(this.__bv==true){return;
}this.__bu=true;
var E=this.getSelection();
var G=[];

for(var i=0;i<E.getLength();i++){var F=E.getItem(i);
var H=this._getSelectables();
var D=-1;

if(H!=null){D=H.indexOf(F);
}var I=this._reverseLookup(D);

if(I>=0){G.push(I);
}}
if(this._beforeApplySelection!=null&&qx.lang.Type.isFunction(this._beforeApplySelection)){this._beforeApplySelection(G);
}
try{this._manager.replaceSelection(G);
}catch(e){this._manager.selectItem(G[G.length-1]);
}this.__bw();

if(this._afterApplySelection!=null&&qx.lang.Type.isFunction(this._afterApplySelection)){this._afterApplySelection();
}this.__bu=false;
},_onManagerChangeSelection:function(e){if(this.__bu==true){return;
}this.__bv=true;
this.__bw();
this.__bv=false;
},__bw:function(){if(this.__by()){return;
}var J=this._manager.getSelection();
var K=[];

for(var i=0;i<J.length;i++){K.push(this._getDataFromRow(J[i]));
}this.__bx(K);
},__bx:function(L){var M=this.getSelection();

if(L.length>0){var O=[0,M.getLength()];
O=O.concat(L);
var N=M.splice.apply(M,O);
N.dispose();
}else{M.removeAll();
}},__by:function(){var Q=this.getSelection();
var S=this._manager.getSelection();

if(Q.getLength()!==S.length){return false;
}
for(var i=0;i<Q.getLength();i++){var R=Q.getItem(i);
var T=this._getSelectables();
var P=-1;

if(T!=null){P=T.indexOf(R);
}var U=this._reverseLookup(P);

if(U!==S[i]){return false;
}}return true;
},_applyDefaultSelection:function(){if(this._manager!=null){this._manager._applyDefaultSelection();
}}},destruct:function(){this._manager.dispose();
this._manager=null;

if(this.__bt){this.__bt.dispose();
}}});
})();
(function(){var p="String",o="qx.data.Array",n="change",m="row-layer",j="group",h="changeModel",g="resize",f="_applyLabelOptions",d="_applyLabelPath",c="_applyGroupLabelOptions",E="filter",D="Integer",C="_applyModel",B="changeGroups",A="Boolean",z="sorter",y="_applyIconPath",x="_applyDelegate",w="changeDelegate",v="???",t="_applyRowHeight",u="virtual-list",r="_applyGroupLabelPath",s="_applyIconOptions",q="qx.ui.list.List";
qx.Class.define(q,{extend:qx.ui.virtual.core.Scroller,include:[qx.ui.virtual.selection.MModel],construct:function(F){qx.ui.virtual.core.Scroller.call(this,0,1,20,100);
this._init();
this.__k=new qx.data.Array();
this.initGroups(this.__k);

if(F!=null){this.initModel(F);
}this.initItemHeight();
},properties:{appearance:{refine:true,init:u},focusable:{refine:true,init:true},width:{refine:true,init:100},height:{refine:true,init:200},model:{check:o,apply:C,event:h,nullable:true,deferredInit:true},itemHeight:{check:D,init:25,apply:t,themeable:true},labelPath:{check:p,apply:d,nullable:true},iconPath:{check:p,apply:y,nullable:true},groupLabelPath:{check:p,apply:r,nullable:true},labelOptions:{apply:f,nullable:true},iconOptions:{apply:s,nullable:true},groupLabelOptions:{apply:c,nullable:true},delegate:{apply:x,event:w,init:null,nullable:true},autoGrouping:{check:A,init:true},groups:{check:o,event:B,nullable:false,deferredInit:true}},members:{_background:null,_provider:null,_layer:null,__l:null,__m:null,__n:null,__o:false,__p:false,__q:false,__k:null,refresh:function(){this.__s();
},_createChildControlImpl:function(G,H){var I;

switch(G){case m:I=new qx.ui.virtual.layer.Row(null,null);
break;
}return I||qx.ui.virtual.core.Scroller.prototype._createChildControlImpl.call(this,G);
},_init:function(){this._provider=new qx.ui.list.provider.WidgetProvider(this);
this.__l=[];
this.__m=[];
this.__n={};
this.__o=false;
this.__p=false;
this.__q=false;
this.getPane().addListener(g,this._onResize,this);
this._initBackground();
this._initLayer();
},_initBackground:function(){this._background=this.getChildControl(m);
this.getPane().addLayer(this._background);
},_initLayer:function(){this._layer=this._provider.createLayer();
this.getPane().addLayer(this._layer);
},_getDataFromRow:function(J){var L=null;
var K=this.getModel();

if(K==null){return null;
}
if(this._isGroup(J)){L=this.getGroups().getItem(this._lookupGroup(J));
}else{L=K.getItem(this._lookup(J));
}
if(L!=null){return L;
}else{return null;
}},_getLookupTable:function(){return this.__l;
},_lookup:function(M){return this.__l[M];
},_lookupGroup:function(N){return this.__m.indexOf(N);
},_reverseLookup:function(O){if(O<0){return -1;
}return this.__l.indexOf(O);
},_isGroup:function(P){return this._lookup(P)==-1;
},_getSelectables:function(){return this.getModel();
},_applyModel:function(Q,R){if(Q!=null){Q.addListener(n,this._onModelChange,this);
}
if(R!=null){R.removeListener(n,this._onModelChange,this);
}this._provider.removeBindings();
this._onModelChange();
},_applyRowHeight:function(S,T){this.getPane().getRowConfig().setDefaultItemSize(S);
},_applyLabelPath:function(U,V){this._provider.setLabelPath(U);
},_applyIconPath:function(W,X){this._provider.setIconPath(W);
},_applyGroupLabelPath:function(Y,ba){this._provider.setGroupLabelPath(Y);
},_applyLabelOptions:function(bb,bc){this._provider.setLabelOptions(bb);
},_applyIconOptions:function(bd,be){this._provider.setIconOptions(bd);
},_applyGroupLabelOptions:function(bf,bg){this._provider.setGroupLabelOptions(bf);
},_applyDelegate:function(bh,bi){this._provider.setDelegate(bh);
this.__s();
},_onResize:function(e){this.getPane().getColumnConfig().setItemSize(0,e.getData().width);
},_onModelChange:function(e){this.__s();
this._applyDefaultSelection();
},__r:function(){this.getPane().getRowConfig().setItemCount(this.__l.length);
this.getPane().fullUpdate();
},__s:function(){this.__l=[];
this.__m=[];
this.__n={};

if(this.isAutoGrouping()){this.getGroups().removeAll();
}var bj=this.getModel();

if(bj!=null){this._runDelegateFilter(bj);
this._runDelegateSorter(bj);
this._runDelegateGroup(bj);
}this._updateSelection();
this.__r();
},_runDelegateFilter:function(bk){var bl=qx.util.Delegate.getMethod(this.getDelegate(),E);

for(var i=0,l=bk.length;i<l;++i){if(bl==null||bl(bk.getItem(i))){this.__l.push(i);
}}},_runDelegateSorter:function(bm){if(this.__l.length==0){return;
}var bn=qx.util.Delegate.getMethod(this.getDelegate(),z);

if(bn!=null){this.__l.sort(function(a,b){return bn(bm.getItem(a),bm.getItem(b));
});
}},_runDelegateGroup:function(bo){var bs=qx.util.Delegate.getMethod(this.getDelegate(),j);

if(bs!=null){for(var i=0,l=this.__l.length;i<l;++i){var bp=this.__l[i];
var br=this.getModel().getItem(bp);
var bq=bs(br);
this.__t(bq,bp);
}this.__l=this.__u();
}},__t:function(bt,bu){if(bt==null){this.__q=true;
bt=v;
}var name=this.__v(bt);

if(this.__n[name]==null){this.__n[name]=[];

if(this.isAutoGrouping()){this.getGroups().push(bt);
}}this.__n[name].push(bu);
},__u:function(){this.__w();
var by=[];
var bA=0;
var bw=this.getGroups();

for(var i=0;i<bw.getLength();i++){var bv=bw.getItem(i);
by.push(-1);
this.__m.push(bA);
bA++;
var bz=this.__v(bv);
var bx=this.__n[bz];

if(bx!=null){for(var k=0;k<bx.length;k++){by.push(bx[k]);
bA++;
}}}return by;
},__v:function(bB){var name=null;

if(!qx.lang.Type.isString(bB)){var bC=this.getGroups().indexOf(bB);
this.__p=true;
name=j;

if(bC==-1){name+=this.getGroups().getLength();
}else{name+=bC;
}}else{this.__o=true;
var name=bB;
}return name;
},__w:function(){if(this.__p&&this.__q||this.__p&&this.__o){throw new Error("GroupingTypeError: You can't mix 'Objects' and 'Strings' as"+" group identifier!");
}}},destruct:function(){this._background.dispose();
this._provider.dispose();
this._layer.dispose();
this._background=this._provider=this._layer=this.__l=this.__m=this.__n=null;

if(this.__k){this.__k.dispose();
}}});
})();
(function(){var a="qx.ui.virtual.core.IWidgetCellProvider";
qx.Interface.define(a,{members:{getCellWidget:function(b,c){},poolCellWidget:function(d){}}});
})();
(function(){var e="change",d="qx.event.type.Event",c="qx.ui.virtual.core.Axis";
qx.Class.define(c,{extend:qx.core.Object,construct:function(f,g){qx.core.Object.call(this);
this.itemCount=g;
this.defaultItemSize=f;
this.customSizes={};
},events:{"change":d},members:{__bo:null,getDefaultItemSize:function(){return this.defaultItemSize;
},setDefaultItemSize:function(h){if(this.defaultItemSize!==h){this.defaultItemSize=h;
this.__bo=null;
this.fireNonBubblingEvent(e);
}},getItemCount:function(){return this.itemCount;
},setItemCount:function(j){if(this.itemCount!==j){this.itemCount=j;
this.__bo=null;
this.fireNonBubblingEvent(e);
}},setItemSize:function(k,l){if(this.customSizes[k]==l){return;
}
if(l===null){delete this.customSizes[k];
}else{this.customSizes[k]=l;
}this.__bo=null;
this.fireNonBubblingEvent(e);
},getItemSize:function(m){return this.customSizes[m]||this.defaultItemSize;
},resetItemSizes:function(){this.customSizes={};
this.__bo=null;
this.fireNonBubblingEvent(e);
},__bp:function(){if(this.__bo){return this.__bo;
}var p=this.defaultItemSize;
var w=this.itemCount;
var r=[];

for(var t in this.customSizes){var n=parseInt(t,10);

if(n<w){r.push(n);
}}
if(r.length==0){var s=[{startIndex:0,endIndex:w-1,firstItemSize:p,rangeStart:0,rangeEnd:w*p-1}];
this.__bo=s;
return s;
}r.sort(function(a,b){return a>b?1:-1;
});
var s=[];
var o=0;

for(var i=0;i<r.length;i++){var n=r[i];

if(n>=w){break;
}var v=this.customSizes[n];
var q=n*p+o;
o+=v-p;
s[i]={startIndex:n,firstItemSize:v,rangeStart:q};

if(i>0){s[i-1].rangeEnd=q-1;
s[i-1].endIndex=n-1;
}}if(s[0].rangeStart>0){s.unshift({startIndex:0,endIndex:s[0].startIndex-1,firstItemSize:p,rangeStart:0,rangeEnd:s[0].rangeStart-1});
}var x=s[s.length-1];
var u=(w-x.startIndex-1)*p;
x.rangeEnd=x.rangeStart+x.firstItemSize+u-1;
x.endIndex=w-1;
this.__bo=s;
return s;
},__bq:function(y){var z=this.__bo||this.__bp();
var A=0;
var C=z.length-1;
while(true){var D=A+((C-A)>>1);
var B=z[D];

if(B.rangeEnd<y){A=D+1;
}else if(B.rangeStart>y){C=D-1;
}else{return B;
}}},getItemAtPosition:function(E){if(E<0||E>=this.getTotalSize()){return null;
}var G=this.__bq(E);
var I=G.rangeStart;
var F=G.startIndex;
var J=G.firstItemSize;

if(I+J>E){return {index:F,offset:E-I};
}else{var H=this.defaultItemSize;
return {index:F+1+Math.floor((E-I-J)/H),offset:(E-I-J)%H};
}},__br:function(K){var L=this.__bo||this.__bp();
var M=0;
var O=L.length-1;
while(true){var P=M+((O-M)>>1);
var N=L[P];

if(N.endIndex<K){M=P+1;
}else if(N.startIndex>K){O=P-1;
}else{return N;
}}},getItemPosition:function(Q){if(Q<0||Q>=this.itemCount){return null;
}var R=this.__br(Q);

if(R.startIndex==Q){return R.rangeStart;
}else{return R.rangeStart+R.firstItemSize+(Q-R.startIndex-1)*this.defaultItemSize;
}},getTotalSize:function(){var S=this.__bo||this.__bp();
return S[S.length-1].rangeEnd+1;
},getItemSizes:function(T,U){var V=this.customSizes;
var Y=this.defaultItemSize;
var X=0;
var W=[];
var i=0;

while(X<U){var ba=V[T++]||Y;
X+=ba;
W[i++]=ba;

if(T>=this.itemCount){break;
}}return W;
}},destruct:function(){this.customSizes=this.__bo=null;
}});
})();
(function(){var a="qx.ui.virtual.cell.IWidgetCell";
qx.Interface.define(a,{members:{getCellWidget:function(b,c){},pool:function(d){},updateStates:function(e,f){},updateData:function(g,h){}}});
})();
(function(){var d="Color",c="_applyColorOdd",b="_applyColorEven",a="qx.ui.virtual.layer.AbstractBackground";
qx.Class.define(a,{extend:qx.ui.virtual.layer.Abstract,construct:function(e,f){qx.ui.virtual.layer.Abstract.call(this);

if(e){this.setColorEven(e);
}
if(f){this.setColorOdd(f);
}this.__bi={};
this.__bj={};
},properties:{colorEven:{nullable:true,check:d,apply:b,themeable:true},colorOdd:{nullable:true,check:d,apply:c,themeable:true}},members:{__bk:null,__bl:null,__bi:null,__bj:null,setColor:function(g,h){if(h){this.__bi[g]=qx.theme.manager.Color.getInstance().resolve(h);
}else{delete (this.__bi[g]);
}},clearCustomColors:function(){this.__bi={};
this.updateLayerData();
},getColor:function(i){var j=this.__bi[i];

if(j){return j;
}else{return i%2==0?this.__bk:this.__bl;
}},_applyColorEven:function(k,l){if(k){this.__bk=qx.theme.manager.Color.getInstance().resolve(k);
}else{this.__bk=null;
}this.updateLayerData();
},_applyColorOdd:function(m,n){if(m){this.__bl=qx.theme.manager.Color.getInstance().resolve(m);
}else{this.__bl=null;
}this.updateLayerData();
},setBackground:function(o,p){if(p){this.__bj[o]=qx.theme.manager.Decoration.getInstance().resolve(p);
}else{delete (this.__bj[o]);
}this.updateLayerData();
},getBackground:function(q){return this.__bj[q];
}},destruct:function(){this.__bi=this.__bj=null;
}});
})();
(function(){var q="px;",p="left: 0;",o="</div>",n="top:",m="position: absolute;",k="<div style='",j="'>",h="background-color:",g="",f="qx.ui.virtual.layer.Row",c="block",e="width:",d="height:",b="row-layer",a="none";
qx.Class.define(f,{extend:qx.ui.virtual.layer.AbstractBackground,properties:{appearance:{refine:true,init:b}},members:{_fullUpdate:function(r,s,t,u){var B=[];
var A=qx.lang.Array.sum(u);
var C=[];
var top=0;
var E=r;
var x=0;

for(var y=0;y<t.length;y++){var D=this.getBackground(E);

if(D){C.push({childIndex:x,decorator:D,width:A,height:t[y]});
B.push(k,m,p,n,top,q,j,D.getMarkup(),o);
x++;
}else{var z=this.getColor(E);

if(z){B.push(k,m,p,n,top,q,d,t[y],q,e,A,q,h,z,j,o);
x++;
}}top+=t[y];
E+=1;
}var v=this.getContentElement().getDomElement();
v.style.display=a;
v.innerHTML=B.join(g);
for(var i=0,l=C.length;i<l;i++){var w=C[i];
w.decorator.resize(v.childNodes[w.childIndex].firstChild,w.width,w.height);
}v.style.display=c;
this._width=A;
},_updateLayerWindow:function(F,G,H,I){if(F!==this.getFirstRow()||H.length!==this.getRowSizes().length||this._width<qx.lang.Array.sum(I)){this._fullUpdate(F,G,H,I);
}},setColor:function(J,K){qx.ui.virtual.layer.AbstractBackground.prototype.setColor.call(this,J,K);

if(this.__bz(J)){this.updateLayerData();
}},setBackground:function(L,M){qx.ui.virtual.layer.AbstractBackground.prototype.setBackground.call(this,L,M);

if(this.__bz(L)){this.updateLayerData();
}},__bz:function(N){var P=this.getFirstRow();
var O=P+this.getRowSizes().length-1;
return N>=P&&N<=O;
}}});
})();
(function(){var d="cell.states",c="created",b="qx.ui.virtual.cell.AbstractWidget",a="qx.event.type.Data";
qx.Class.define(b,{extend:qx.core.Object,implement:[qx.ui.virtual.cell.IWidgetCell],construct:function(){qx.core.Object.call(this);
this.__bm=[];
},events:{"created":a},members:{__bm:null,_createWidget:function(){throw new Error("abstract method call");
},updateData:function(e,f){throw new Error("abstract method call");
},updateStates:function(g,h){var k=g.getUserData(d);
if(k){var i=h||{};

for(var j in k){if(!i[j]){g.removeState(j);
}}}else{k={};
}if(h){for(var j in h){if(!k.state){g.addState(j);
}}}g.setUserData(d,h);
},getCellWidget:function(l,m){var n=this.__bn();
this.updateStates(n,m);
this.updateData(n,l);
return n;
},pool:function(o){this.__bm.push(o);
},_cleanupPool:function(){var p=this.__bm.pop();

while(p){p.destroy();
p=this.__bm.pop();
}},__bn:function(){var q=this.__bm.pop();

if(q==null){q=this._createWidget();
this.fireDataEvent(c,q);
}return q;
}},destruct:function(){this._cleanupPool();
this.__bm=null;
}});
})();
(function(){var b="_applyDelegate",a="qx.ui.virtual.cell.WidgetCell";
qx.Class.define(a,{extend:qx.ui.virtual.cell.AbstractWidget,properties:{delegate:{apply:b,init:null,nullable:true}},members:{_applyDelegate:function(c,d){this._cleanupPool();
},_createWidget:function(){var e=this.getDelegate();

if(e!=null&&e.createWidget!=null){return e.createWidget();
}else{return new qx.ui.core.Widget();
}},updateData:function(f,g){for(var h in g){if(qx.Class.hasProperty(f.constructor,h)){qx.util.PropertyUtil.setUserValue(f,h,g[h]);
}else{throw new Error("Can't update data! The key '"+h+"' is not a Property!");
}}}}});
})();
(function(){var a="qx.ui.list.provider.IListProvider";
qx.Interface.define(a,{members:{createLayer:function(){},createItemRenderer:function(){},createGroupRenderer:function(){},styleSelectabled:function(b){},styleUnselectabled:function(c){},isSelectable:function(d){},setLabelPath:function(e){},setIconPath:function(f){},setLabelOptions:function(g){},setIconOptions:function(h){},setDelegate:function(i){},removeBindings:function(){}}});
})();
(function(){var o="cell.type",n="String",m="",l="]",k="group",j="model[",i="groups[",h="model",g="changeDelegate",f="label",b="qx.ui.list.core.MWidgetController",d="icon",c="value",a=".";
qx.Mixin.define(b,{construct:function(){this.__x=[];
},properties:{labelPath:{check:n,nullable:true},iconPath:{check:n,nullable:true},groupLabelPath:{check:n,nullable:true},labelOptions:{nullable:true},iconOptions:{nullable:true},groupLabelOptions:{nullable:true},delegate:{event:g,init:null,nullable:true}},members:{__x:null,bindDefaultProperties:function(p,q){if(p.getUserData(o)!=k){this.bindProperty(m,h,null,p,q);
this.bindProperty(this.getLabelPath(),f,this.getLabelOptions(),p,q);

if(this.getIconPath()!=null){this.bindProperty(this.getIconPath(),d,this.getIconOptions(),p,q);
}}else{this.bindProperty(this.getGroupLabelPath(),c,this.getGroupLabelOptions(),p,q);
}},bindProperty:function(r,s,t,u,v){var x=u.getUserData(o);
var w=this.__y(v,r,x);
var y=this._list.bind(w,u,s,t);
this.__z(u,y);
},bindPropertyReverse:function(z,A,B,C,D){var F=C.getUserData(o);
var E=this.__y(D,z,F);
var G=C.bind(A,this._list,E,B);
this.__z(C,G);
},removeBindings:function(){while(this.__x.length>0){var H=this.__x.pop();
this._removeBindingsFrom(H);
}},_configureItem:function(I){var J=this.getDelegate();

if(J!=null&&J.configureItem!=null){J.configureItem(I);
}},_configureGroupItem:function(K){var L=this.getDelegate();

if(L!=null&&L.configureGroupItem!=null){L.configureGroupItem(K);
}},_bindItem:function(M,N){var O=this.getDelegate();

if(O!=null&&O.bindItem!=null){O.bindItem(this,M,N);
}else{this.bindDefaultProperties(M,N);
}},_bindGroupItem:function(P,Q){var R=this.getDelegate();

if(R!=null&&R.bindGroupItem!=null){R.bindGroupItem(this,P,Q);
}else{this.bindDefaultProperties(P,Q);
}},_removeBindingsFrom:function(S){var T=this.__A(S);

while(T.length>0){var U=T.pop();

try{this._list.removeBinding(U);
}catch(e){S.removeBinding(U);
}}
if(qx.lang.Array.contains(this.__x,S)){qx.lang.Array.remove(this.__x,S);
}},__y:function(V,W,X){var Y=j+V+l;

if(X==k){Y=i+V+l;
}
if(W!=null&&W!=m){Y+=a+W;
}return Y;
},__z:function(ba,bb){var bc=this.__A(ba);

if(!qx.lang.Array.contains(bc,bb)){bc.push(bb);
}
if(!qx.lang.Array.contains(this.__x,ba)){this.__x.push(ba);
}},__A:function(bd){var be=bd.getUserData("BindingIds");

if(be==null){be=[];
bd.setUserData("BindingIds",be);
}return be;
}},destruct:function(){this.__x=null;
}});
})();
(function(){var j="cell.type",i="created",h="item",g="group",f="changeDelegate",e="qx.ui.list.provider.WidgetProvider",d="createItem",c="group-item",b="onPool",a="createGroupItem";
qx.Class.define(e,{extend:qx.core.Object,implement:[qx.ui.virtual.core.IWidgetCellProvider,qx.ui.list.provider.IListProvider],include:[qx.ui.list.core.MWidgetController],construct:function(k){qx.core.Object.call(this);
this._list=k;
this._itemRenderer=this.createItemRenderer();
this._groupRenderer=this.createGroupRenderer();
this._itemRenderer.addListener(i,this._onItemCreated,this);
this._groupRenderer.addListener(i,this._onGroupItemCreated,this);
this._list.addListener(f,this._onChangeDelegate,this);
},members:{_itemRenderer:null,_groupRenderer:null,getCellWidget:function(l,m){var n=null;

if(!this._list._isGroup(l)){n=this._itemRenderer.getCellWidget();
n.setUserData(j,h);
this._bindItem(n,this._list._lookup(l));

if(this._list._manager.isItemSelected(l)){this._styleSelectabled(n);
}else{this._styleUnselectabled(n);
}}else{n=this._groupRenderer.getCellWidget();
n.setUserData(j,g);
this._bindGroupItem(n,this._list._lookupGroup(l));
}return n;
},poolCellWidget:function(o){this._removeBindingsFrom(o);

if(o.getUserData(j)==h){this._itemRenderer.pool(o);
}else if(o.getUserData(j)==g){this._groupRenderer.pool(o);
}this._onPool(o);
},createLayer:function(){return new qx.ui.virtual.layer.WidgetCell(this);
},createItemRenderer:function(){var p=qx.util.Delegate.getMethod(this.getDelegate(),d);

if(p==null){p=function(){return new qx.ui.form.ListItem();
};
}var q=new qx.ui.virtual.cell.WidgetCell();
q.setDelegate({createWidget:p});
return q;
},createGroupRenderer:function(){var r=qx.util.Delegate.getMethod(this.getDelegate(),a);

if(r==null){r=function(){var t=new qx.ui.basic.Label();
t.setAppearance(c);
return t;
};
}var s=new qx.ui.virtual.cell.WidgetCell();
s.setDelegate({createWidget:r});
return s;
},styleSelectabled:function(u){var v=this.__i(u);
this._styleSelectabled(v);
},styleUnselectabled:function(w){var x=this.__i(w);
this._styleUnselectabled(x);
},isSelectable:function(y){if(this._list._isGroup(y)){return false;
}var z=this._list._layer.getRenderedCellWidget(y,0);

if(z!=null){return z.isEnabled();
}else{return true;
}},_styleSelectabled:function(A){this.__j(A,{selected:1});
},_styleUnselectabled:function(B){this.__j(B,{});
},_onPool:function(C){var D=qx.util.Delegate.getMethod(this.getDelegate(),b);

if(D!=null){D(C);
}},_onItemCreated:function(event){var E=event.getData();
this._configureItem(E);
},_onGroupItemCreated:function(event){var F=event.getData();
this._configureGroupItem(F);
},_onChangeDelegate:function(event){this._itemRenderer.dispose();
this._itemRenderer=this.createItemRenderer();
this._itemRenderer.addListener(i,this._onItemCreated,this);
this._groupRenderer.dispose();
this._groupRenderer=this.createGroupRenderer();
this._groupRenderer.addListener(i,this._onGroupItemCreated,this);
this.removeBindings();
this._list.getPane().fullUpdate();
},__i:function(G){return this._list._layer.getRenderedCellWidget(G,0);
},__j:function(H,I){if(H==null){return;
}this._itemRenderer.updateStates(H,I);
}},destruct:function(){this._itemRenderer.dispose();
this._groupRenderer.dispose();
this._itemRenderer=this._groupRenderer=null;
}});
})();
(function(){var i="mouseup",h="mousedown",g="losecapture",f="mouseover",e="mousemove",d="removeItem",c="keypress",b="addItem",a="qx.ui.virtual.selection.Abstract";
qx.Class.define(a,{extend:qx.ui.core.selection.Abstract,construct:function(j,k){qx.ui.core.selection.Abstract.call(this);
this._pane=j;
this._delegate=k||{};
},members:{_isSelectable:function(l){return this._delegate.isItemSelectable?this._delegate.isItemSelectable(l):true;
},_styleSelectable:function(m,n,o){if(this._delegate.styleSelectable){this._delegate.styleSelectable(m,n,o);
}},attachMouseEvents:function(){var p=this._pane.getContainerElement();
p.addListener(h,this.handleMouseDown,this);
p.addListener(i,this.handleMouseUp,this);
p.addListener(f,this.handleMouseOver,this);
p.addListener(e,this.handleMouseMove,this);
p.addListener(g,this.handleLoseCapture,this);
},detatchMouseEvents:function(){var q=this._pane.getContainerElement();
q.removeListener(h,this.handleMouseDown,this);
q.removeListener(i,this.handleMouseUp,this);
q.removeListener(f,this.handleMouseOver,this);
q.removeListener(e,this.handleMouseMove,this);
q.removeListener(g,this.handleLoseCapture,this);
},attachKeyEvents:function(r){r.addListener(c,this.handleKeyPress,this);
},detachKeyEvents:function(s){s.removeListener(c,this.handleKeyPress,this);
},attachListEvents:function(t){t.addListener(b,this.handleAddItem,this);
t.addListener(d,this.handleRemoveItem,this);
},detachListEvents:function(u){u.removeListener(b,this.handleAddItem,this);
u.removeListener(d,this.handleRemoveItem,this);
},_capture:function(){this._pane.capture();
},_releaseCapture:function(){this._pane.releaseCapture();
},_getScroll:function(){return {left:this._pane.getScrollX(),top:this._pane.getScrollY()};
},_scrollBy:function(v,w){this._pane.setScrollX(this._pane.getScrollX()+v);
this._pane.setScrollY(this._pane.getScrollY()+w);
},_getLocation:function(){var x=this._pane.getContentElement().getDomElement();
return x?qx.bom.element.Location.get(x):null;
},_getDimension:function(){return this._pane.getInnerSize();
}},destruct:function(){this._pane=this._delegate=null;
}});
})();
(function(){var b="Integer",a="qx.ui.virtual.core.CellEvent";
qx.Class.define(a,{extend:qx.event.type.Mouse,properties:{row:{check:b,nullable:true},column:{check:b,nullable:true}},members:{init:function(c,d,e,f){d.clone(this);
this.setBubbles(false);
this.setRow(e);
this.setColumn(f);
}}});
})();
(function(){var v="appear",u="qx.ui.virtual.core.CellEvent",t="change",s="qx.event.type.Data",r="qx.ui.virtual.core.Pane",q="resize",p="click",o="update",n="scrollX",m="dblclick",d="contextmenu",l="__P",h="cellClick",c="qx.event.type.Event",b="scrollY",g="__I",f="__H",j="cellDblclick",a="cellContextmenu",k="__O";
qx.Class.define(r,{extend:qx.ui.core.Widget,construct:function(w,x,y,z){qx.ui.core.Widget.call(this);
this.__H=new qx.ui.virtual.core.Axis(y,w);
this.__I=new qx.ui.virtual.core.Axis(z,x);
this.__J=0;
this.__K=0;
this.__L=0;
this.__M=0;
this.__N={};
this.__B={};
this.__O=new qx.ui.container.Composite();
this.__O.setUserBounds(0,0,0,0);
this._add(this.__O);
this.__P=[];
this.__H.addListener(t,this.fullUpdate,this);
this.__I.addListener(t,this.fullUpdate,this);
this.addListener(q,this._onResize,this);
this.addListenerOnce(v,this._onAppear,this);
this.addListener(p,this._onClick,this);
this.addListener(m,this._onDblclick,this);
this.addListener(d,this._onContextmenu,this);
},events:{cellClick:u,cellContextmenu:u,cellDblclick:u,update:c,scrollX:s,scrollY:s},properties:{width:{refine:true,init:400},height:{refine:true,init:300}},members:{__H:null,__I:null,__J:null,__K:null,__L:null,__M:null,__N:null,__B:null,__O:null,__P:null,__Q:null,__G:null,__F:null,getRowConfig:function(){return this.__H;
},getColumnConfig:function(){return this.__I;
},getChildren:function(){return [this.__O];
},addLayer:function(A){this.__P.push(A);
A.setUserBounds(0,0,0,0);
this.__O.add(A);
},getLayers:function(){return this.__P;
},getVisibleLayers:function(){var B=[];

for(var i=0;i<this.__P.length;i++){var C=this.__P[i];

if(C.isVisible()){B.push(C);
}}return B;
},getScrollMaxX:function(){var D=this.getInnerSize();

if(D){return Math.max(0,this.__I.getTotalSize()-D.width);
}return 0;
},getScrollMaxY:function(){var E=this.getInnerSize();

if(E){return Math.max(0,this.__H.getTotalSize()-E.height);
}return 0;
},setScrollY:function(F){var G=this.getScrollMaxY();

if(F<0){F=0;
}else if(F>G){F=G;
}
if(this.__J!==F){var H=this.__J;
this.__J=F;
this._deferredUpdateScrollPosition();
this.fireDataEvent(b,F,H);
}},getScrollY:function(){return this.__J;
},setScrollX:function(I){var J=this.getScrollMaxX();

if(I<0){I=0;
}else if(I>J){I=J;
}
if(I!==this.__K){var K=this.__K;
this.__K=I;
this._deferredUpdateScrollPosition();
this.fireDataEvent(n,I,K);
}},getScrollX:function(){return this.__K;
},getScrollSize:function(){return {width:this.__I.getTotalSize(),height:this.__H.getTotalSize()};
},scrollRowIntoView:function(L){var O=this.getBounds();

if(!O){this.addListenerOnce(v,function(){qx.event.Timer.once(function(){this.scrollRowIntoView(L);
},this,0);
},this);
return;
}var P=this.__H.getItemPosition(L);
var N=P+this.__H.getItemSize(L);
var M=this.getScrollY();

if(P<M){this.setScrollY(P);
}else if(N>M+O.height){this.setScrollY(N-O.height);
}},scrollColumnIntoView:function(Q){var T=this.getBounds();

if(!T){this.addListenerOnce(v,function(){qx.event.Timer.once(function(){this.scrollColumnIntoView(Q);
},this,0);
},this);
return;
}var S=this.__I.getItemPosition(Q);
var R=S+this.__I.getItemSize(Q);
var U=this.getScrollX();

if(S<U){this.setScrollX(S);
}else if(R>U+T.width){this.setScrollX(R-T.width);
}},scrollCellIntoView:function(V,W){var X=this.getBounds();

if(!X){this.addListenerOnce(v,function(){qx.event.Timer.once(function(){this.scrollCellIntoView(V,W);
},this,0);
},this);
return;
}this.scrollColumnIntoView(V);
this.scrollRowIntoView(W);
},getCellAtPosition:function(Y,ba){var bb,bc;
var bd=this.getContentLocation();

if(!bd||ba<bd.top||ba>=bd.bottom||Y<bd.left||Y>=bd.right){return null;
}bb=this.__H.getItemAtPosition(this.getScrollY()+ba-bd.top);
bc=this.__I.getItemAtPosition(this.getScrollX()+Y-bd.left);

if(!bb||!bc){return null;
}return {row:bb.index,column:bc.index};
},prefetchX:function(be,bf,bg,bh){var bi=this.getVisibleLayers();

if(bi.length==0){return;
}var bk=this.getBounds();

if(!bk){return;
}var bl=this.__K+bk.width;
var bm=this.__M-bl;

if(this.__K-this.__N.left<Math.min(this.__K,be)||this.__N.right-bl<Math.min(bm,bg)){var bn=Math.min(this.__K,bf);
var bj=Math.min(bm,bh);
this._setLayerWindow(bi,this.__K-bn,this.__J,bk.width+bn+bj,bk.height,false);
}},prefetchY:function(bo,bp,bq,br){var bs=this.getVisibleLayers();

if(bs.length==0){return;
}var bv=this.getBounds();

if(!bv){return;
}var bt=this.__J+bv.height;
var bu=this.__L-bt;

if(this.__J-this.__N.top<Math.min(this.__J,bo)||this.__N.bottom-bt<Math.min(bu,bq)){var bx=Math.min(this.__J,bp);
var bw=Math.min(bu,br);
this._setLayerWindow(bs,this.__K,this.__J-bx,bv.width,bv.height+bx+bw,false);
}},_onResize:function(){if(this.getContainerElement().getDomElement()){this.__Q=true;
this._updateScrollPosition();
this.__Q=null;
this.fireEvent(o);
}},_onAppear:function(){this.fullUpdate();
},_onClick:function(e){this.__R(e,h);
},_onContextmenu:function(e){this.__R(e,a);
},_onDblclick:function(e){this.__R(e,j);
},__R:function(e,by){var bz=this.getCellAtPosition(e.getDocumentLeft(),e.getDocumentTop());

if(!bz){return;
}this.fireNonBubblingEvent(by,qx.ui.virtual.core.CellEvent,[this,e,bz.row,bz.column]);
},syncWidget:function(){if(this.__B._fullUpdate){this._fullUpdate();
}else if(this.__B._updateScrollPosition){this._updateScrollPosition();
}this.__B={};
},_setLayerWindow:function(bA,bB,top,bC,bD,bE){var bJ=this.__H.getItemAtPosition(top);

if(bJ){var bL=bJ.index;
var bP=this.__H.getItemSizes(bL,bD+bJ.offset);
var bK=qx.lang.Array.sum(bP);
var bR=top-bJ.offset;
var bO=top-bJ.offset+bK;
}else{var bL=0;
var bP=[];
var bK=0;
var bR=0;
var bO=0;
}var bN=this.__I.getItemAtPosition(bB);

if(bN){var bH=bN.index;
var bG=this.__I.getItemSizes(bH,bC+bN.offset);
var bM=qx.lang.Array.sum(bG);
var bQ=bB-bN.offset;
var bI=bB-bN.offset+bM;
}else{var bH=0;
var bG=[];
var bM=0;
var bQ=0;
var bI=0;
}this.__N={top:bR,bottom:bO,left:bQ,right:bI};
this.__O.setUserBounds(this.__N.left-this.__K,this.__N.top-this.__J,bM,bK);
this.__G=bG;
this.__F=bP;

for(var i=0;i<this.__P.length;i++){var bF=this.__P[i];
bF.setUserBounds(0,0,bM,bK);

if(bE){bF.fullUpdate(bL,bH,bP,bG);
}else{bF.updateLayerWindow(bL,bH,bP,bG);
}}},__S:function(){if(this.__Q){return;
}var bS=this.getScrollSize();

if(this.__L!==bS.height||this.__M!==bS.width){this.__L=bS.height;
this.__M=bS.width;
this.fireEvent("update");
}},fullUpdate:function(){this.__B._fullUpdate=1;
qx.ui.core.queue.Widget.add(this);
},isUpdatePending:function(){return !!this.__B._fullUpdate;
},_fullUpdate:function(){var bT=this.getVisibleLayers();

if(bT.length==0){this.__S();
return;
}var bU=this.getBounds();

if(!bU){return ;
}this._setLayerWindow(bT,this.__K,this.__J,bU.width,bU.height,true);
this.__S();
},_deferredUpdateScrollPosition:function(){this.__B._updateScrollPosition=1;
qx.ui.core.queue.Widget.add(this);
},_updateScrollPosition:function(){var bV=this.getVisibleLayers();

if(bV.length==0){this.__S();
return;
}var bX=this.getBounds();

if(!bX){return ;
}var bW={top:this.__J,bottom:this.__J+bX.height,left:this.__K,right:this.__K+bX.width};

if(this.__N.top<=bW.top&&this.__N.bottom>=bW.bottom&&this.__N.left<=bW.left&&this.__N.right>=bW.right){this.__O.setUserBounds(this.__N.left-bW.left,this.__N.top-bW.top,this.__N.right-this.__N.left,this.__N.bottom-this.__N.top);
}else{this._setLayerWindow(bV,this.__K,this.__J,bX.width,bX.height,false);
}this.__S();
}},destruct:function(){this._disposeArray(l);
this._disposeObjects(f,g,k);
this.__N=this.__B=this.__G=this.__F=null;
}});
})();
(function(){var s="cellOver",r="number",q="headerOver",p="Boolean",o="",n="string",m="mousemove",l="</b>: ",k="_applyShowCellToolTip",j="_applyShowHeaderToolTip",d="_prevOverCol",i="<br>",g="westorelggman.table.ToolTipTable",c="_prevOverRow",b="<b>",f="_tableToolTip",e="object",h="qx.ui.table.pane.CellEvent",a="qx.event.type.Data";
qx.Class.define(g,{extend:qx.ui.table.Table,construct:function(t,u){qx.ui.table.Table.call(this,t,u);
this.addListener(s,this._onCellOver,this);
this.addListener(q,this._onHeaderOver,this);
this.addListener(m,this._onMouseMove,this);
},events:{"cellOver":a,"headerOver":h},properties:{showCellToolTip:{init:false,nullable:false,check:p,apply:k},showHeaderToolTip:{init:false,nullable:false,check:p,apply:j}},members:{_tableToolTip:null,_prevOverCol:-1,_prevOverRow:-1,_applyShowCellToolTip:function(v,w){if(v){this._setToolTip();
}else{if(this._tableToolTip&&this.getToolTip()===this._tableToolTip&&!this.isShowHeaderToolTip()){this.setToolTip(null);
}}},_setToolTip:function(){if(!this._tableToolTip){this._tableToolTip=new qx.ui.tooltip.ToolTip();
this._tableToolTip.setRich(true);
this._tableToolTip.setHideTimeout(40000);
this._tableToolTip.setShowTimeout(50);
this._tableToolTip.setMaxWidth(500);
}this.setToolTip(this._tableToolTip);
},_refreshToolTip:function(x){this._hideToolTip();

if(typeof (x)===n&&x.length>0){this._tableToolTip.setLabel(x);
this.setToolTip(this._tableToolTip);
qx.ui.tooltip.Manager.getInstance().setCurrent(this._tableToolTip);
}},_hideToolTip:function(){this.setToolTip(null);
qx.ui.tooltip.Manager.getInstance().setCurrent(null);
},cellExists:function(y,z){var A=this.getTableModel();
return (typeof (z)===r&&typeof (y)===r&&0<=z&&z<A.getRowCount()&&0<=y&&y<A.getColumnCount());
},getCellToolTipLabel:function(B,C){var F=o;

if(this.cellExists(B,C)){var D=this.getTableModel().getValue(2,C);

if(typeof (D)===n){F=D;
}else if(typeof (D)===e){for(var E in D){F+=b+E+l+D[E]+i;
}}}return F;
},getHeaderToolTipLabel:function(G){return o;
},_onCellOver:function(event){var H=event.getData();

if(this.isShowCellToolTip()){this._refreshToolTip(this.getCellToolTipLabel(H.column,H.row));
}else{this._hideToolTip();
}},getMetaDataForRow:function(I){return this.getTableModel().getValue(2,I);
},_onHeaderOver:function(event){if(this.isShowHeaderToolTip()){this._refreshToolTip(this.getHeaderToolTipLabel(event.getColumn()));
}else{this._hideToolTip();
}},_onMouseMove:function(event){var M=event.getDocumentLeft();
var N=event.getDocumentTop();
var K=this.getTablePaneScrollerAtPageX(M);

if(!K){return;
}var P=K._getRowForPagePos(M,N);
var O=K._getColumnForPageX(M);

if(O!=this._prevOverCol||P!=this._prevOverRow){var J=event.getTarget();
if(!(J instanceof qx.ui.table.pane.Pane)&&!(J instanceof qx.ui.basic.Atom)){this._hideToolTip();
}var L;
if(typeof (P)===r&&P>-1){if(J instanceof qx.ui.table.pane.Pane&&this.hasListener(s)){this.fireDataEvent(s,{row:P,column:O});
}}this._prevOverCol=O;
this._prevOverRow=P;
}}},destruct:function(){this.removeListener(s,this._onCellOver,this);
this.removeListener(q,this._onHeaderOver,this);
this.removeListener(m,this._onMouseMove,this);
this._disposeFields(d,c);
this._disposeObjects(f);
}});
})();
(function(){var k="execute",j="",i="]",h="[",g="Enter",f="westorelggman.Elggobjects",d='',c="access_id",b="indicator_waitanim",a="You can buy the professional version at\n",O="string",N="Delete Metakey",M="change",L="<a href='http://community-software-24.com/westorElggMan-professional.html' target='_blank'>",K="westorelggman/edit-delete.png",J="Sorry, this function is only available in professional version.\n",I='hidden',H="Pro",G="dataChanged",F="But you can enter the GUID manually in the input field.",r="mymenu",s='Please doubleclick at a value to edit it. Hover above a line to get more infos or click right in the line, if you want to remove ',p="multi",q=". . .",n="appear",o="Value",l="Search",m="Key",t='the key from metadata of that entity.<br><b>You should know what you do. Do you have backups for worst case?</b>',u="getObject",x="keypress",w="\n",z="label",y="GUID",B="This Entity has more children! [See WestorElggMan Pro]",A=" - Doubleclick cell to edit the value",v="String",E="cellContextmenu",D="http://community-software-24.com/westorElggMan-professional.html</a>",C="__zz";
qx.Class.define(f,{extend:qx.ui.container.Composite,construct:function(P){if(!P){this.debug("rpc object must have been specified in construct");
return;
}this.__T=P;
this.__zt=new qx.io.remote.Rpc();
this.__zt.setTimeout(50000);
this.__zt.setUrl(ElggMan_service_url);
this.__zt.setServiceName(ElggMan_service_name+H);
this.__zt.setCrossDomain(false);
qx.ui.container.Composite.call(this);
this.__a();
},events:{},properties:{startGuid:{init:null,nullable:true,check:v,apply:C}},members:{__T:null,__U:"Entities in ownership of ID: ",__yt:null,__zu:null,__V:null,__zv:null,__a:function(){this.setLayout(new qx.ui.layout.VBox(10));
var Y=new qx.ui.container.Composite();
Y.setLayout(new qx.ui.layout.HBox(10));
var S=this.__V=new qx.ui.form.TextField();
S.setMaxWidth(150);
S.setPlaceholder(y);
Y.add(S);
var ba=this.__zv=new qx.ui.form.Button(l);
ba.addListener(k,this.__bg,this);
S.addListener(x,function(e){if(e.getKeyIdentifier()==g){ba.fireEvent(k);
}});
Y.add(ba);
this.__W=new qx.ui.basic.Image();
this.__W.setAppearance(b);
this.__W.setVisibility(I);
Y.add(this.__W);
this.add(Y);
var T=this.__X=new qx.ui.container.Composite(new qx.ui.layout.HBox(10));
this.add(T);
var V=this.__Y=new qx.ui.table.model.Simple();
var U=o;
V.setColumnEditable(1,true);
U+=A;
V.addListener(G,function(event){if(!(event instanceof qx.event.type.Data)){return;
}var be=event.getData();

if(be.firstColumn==0){return;
}var bd=V.getValue(0,be.firstRow);
var bc=V.getValue(be.firstColumn,be.firstRow);
this.__bf(bd,bc);
},this);
V.setColumns([m,U,j]);
var X=this.__ba=new westorelggman.table.ToolTipTable(V);
X.set({decorator:null,height:270,width:600,columnVisibilityButtonVisible:false,showCellFocusIndicator:true,ContextMenu:this.__zx(),showCellToolTip:true});
this.__X.add(X);
var W=this.__bb=X.getTableColumnModel();

with(W){setColumnWidth(0,200);
setColumnWidth(1,380);
setColumnWidth(2,0);
setColumnVisible(2,false);
W.setDataCellRenderer(0,new qx.ui.table.cellrenderer.Html());
}var bb=new qx.ui.container.Composite(new qx.ui.layout.VBox(5));
this.__bc=new qx.ui.basic.Label(this.__U);
bb.add(this.__bc);
this.__bd=new qx.ui.list.List().set({selectionMode:p,height:232,labelPath:z});
this.__bd.getSelection().addListener(M,function(e){var bg=this.__bd.getSelection();
if(bg.getLength()==1){var bf=bg.getItem(0).getGuid();
this.__bg(bf);
}},this);
bb.add(this.__bd);
this.__X.add(bb,{flex:1});
var Q=this.__be=new qx.ui.basic.Label();
var R=[s,t].join(d);
Q.set({rich:true,wrap:true,value:R});
this.add(Q);
X.addListener(E,function(e){this.__zu=e.getRow();
},this);
},__bf:function(bh,bi){var self;
{this.__zy();
this.__bg();
};
},__bg:function(bj){var self=this;
var bk;

if(bj instanceof qx.event.type.Event){bk=this.__V.getValue();
this.__bc.setValue(this.__U+h+bk+i);
this.__bd.setModel(new qx.data.Array());
}else{bk=bj?bj:this.__V.getValue();
}this.__T.callAsync(function(bl,bm,bn){if(bm!=null){elggmanMainContainer.alert("Async("+bn+") exception: "+bm);
}else{if(bl.err){elggmanMainContainer.alert(bl.err);
}else{self.__yt=bk;
self.__Y.setData(bl.data);

if(bl.ownerships.length){{var bo=bl.ownerships;

if(bo.length>5){bo.splice(5);
bo[5]={label:q,guid:null};
bo[6]={label:B,guid:null};
}var bp=qx.data.marshal.Json.createModel(bo);
};
self.__bc.setValue(self.__U+h+self.__V.getValue()+i);
self.__bd.setModel(bp);
}}}},u,bk);
},__zw:function(){var bq,self;
{this.__zy();
};
},__zx:function(){var br=new qx.ui.menu.Menu();
br.setAppearance(r);
br.addListener(n,this.__zl,this);
var bs=this.__zC=new qx.ui.menu.Button(N,K);
bs.addListener(k,this.__zw,this);
br.add(bs);
return br;
},__zl:function(){var bt=this.__ba.getMetaDataForRow(this.__zu);

if(typeof bt[c]==O){this.__zC.setEnabled(true);
}else{this.__zC.setEnabled(false);
}},__zy:function(bu){{var bv=[J,a,L,D,bu?w+bu:j].join(j);
elggmanMainContainer.alert(bv,"Sorry, action not possible","warning");
};
},__zz:function(bw,bx){{this.__zy(F);
};
}}});
})();
(function(){var c="qx.ui.virtual.selection.Row",b="above",a="under";
qx.Class.define(c,{extend:qx.ui.virtual.selection.Abstract,members:{_getItemCount:function(){return this._pane.getRowConfig().getItemCount();
},_getSelectableFromMouseEvent:function(event){var d=this._pane.getCellAtPosition(event.getDocumentLeft(),event.getDocumentTop());

if(!d){return null;
}return this._isSelectable(d.row)?d.row:null;
},getSelectables:function(e){var f=[];

for(var i=0,l=this._getItemCount();i<l;i++){if(this._isSelectable(i)){f.push(i);
}}return f;
},_getSelectableRange:function(g,h){var j=[];
var m=Math.min(g,h);
var k=Math.max(g,h);

for(var i=m;i<=k;i++){if(this._isSelectable(i)){j.push(i);
}}return j;
},_getFirstSelectable:function(){var n=this._getItemCount();

for(var i=0;i<n;i++){if(this._isSelectable(i)){return i;
}}return null;
},_getLastSelectable:function(){var o=this._getItemCount();

for(var i=o-1;i>=0;i--){if(this._isSelectable(i)){return i;
}}return null;
},_getRelatedSelectable:function(p,q){if(q==b){var s=p-1;
var r=0;
var t=-1;
}else if(q==a){var s=p+1;
var r=this._getItemCount()-1;
var t=1;
}else{return null;
}
for(var i=s;i!==r+t;i+=t){if(this._isSelectable(i)){return i;
}}return null;
},_getPage:function(u,v){if(v){return this._getFirstSelectable();
}else{return this._getLastSelectable();
}},_selectableToHashCode:function(w){return w;
},_scrollItemIntoView:function(x){this._pane.scrollRowIntoView(x);
},_getSelectableLocationX:function(y){return {left:0,right:this._pane.getColumnConfig().getTotalSize()-1};
},_getSelectableLocationY:function(z){var C=this._pane.getRowConfig();
var B=C.getItemPosition(z);
var A=B+C.getItemSize(z)-1;
return {top:B,bottom:A};
}}});
})();
(function(){var a="qx.util.Delegate";
qx.Class.define(a,{statics:{getMethod:function(b,c){if(qx.util.Delegate.containsMethod(b,c)){return qx.lang.Function.bind(b[c],b);
}return null;
},containsMethod:function(d,e){var f=qx.lang.Type;

if(f.isObject(d)){return f.isFunction(d[e]);
}return false;
}}});
})();

});