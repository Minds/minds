/* jQuery based image slider
/* TMSlider 0.3.2 */
(function($,undefined){
	var _TMS=window._TMS=$.fn._TMS=function(_){
			_=_||{}			
			_=$.extend(clone(_TMS),_TMS.presets[_.preset],_)
			_.init.call(_.me=_.holder=this,_)
			return _.me.data({opt:_})
		}
		
	$.extend(_TMS,{
		etal:'<div></div>',
		items:'.items>li',
		pic:'pic',
		mask:'mask',
		paginationCl:'pagination',
		currCl:'current',
		pauseCl:'paused',
		bannerCl:'banner',
		numStatusCl:'numStatus',
		pagNums:true,
		overflow:'hidden',
		show:0,
		changeEv:'click',
		blocksX:1,
		blocksY:1,
		preset:'simpleFade',
		duration:900000,
		easing:'linear',
		way:'lines',
		anim:'fade',
		pagination:false,
		banners:false,
		waitBannerAnimation:true,
		slideshow:false,
		progressBar:false,
		pauseOnHover:false,
		nextBu:false,
		prevBu:false,
		playBu:false,
		preFu:function(){
			var _=this,
				img=$(new Image())
			_.pic=$(_.etal)
				.addClass(_.pic)
				.css({overflow:_.overflow})
				.appendTo(_.me)
			_.mask=$(_.etal)
				.addClass(_.mask)
				.appendTo(_.pic)
			
			if(_.me.css('position')=='static')
				_.me.css({position:'relative'})
			if(_.me.css('z-index')=='auto')
				_.me.css({zIndex:1})
				
			_.me.css({overflow:_.overflow})
			
			if(_.items)
				_.parseImgFu()
			img
				.appendTo(_.me)
				.load(function(){
					_.pic
						.css({
							width:_.width=img.width(),
							height:_.height=img.height(),
							background:'url('+_.itms[_.show]+') 0 0 no-repeat'
						})
					img.remove()
					_.current=_.buff=_.show
				})
				.attr({src:_.itms[_.n=_.show]})
		},
		sliceFu:function(w,h){
			var _=this,
				w=_.blocksX,
				h=_.blocksY,
				eW=parseInt(_.width/w),
				eH=parseInt(_.height/h),
				etal=$(_.etal),
				fW=_.pic.width()-eW*w,
				fH=_.pic.height()-eH*h,
				x,y,
				matrix=_.matrix=[]
			
			_.mask
				.css({
					position:'absolute',
					width:'100%',
					height:'100%',
					left:0,
					top:0,
					zIndex:1
				})
				.empty()
				.appendTo(_.pic)
				
			for(y=0;y<h;y++)
				for(x=0;x<w;x++)
					matrix[y]=matrix[y]?matrix[y]:[],
					matrix[y][x]=$(_.etal).clone()
						.appendTo(_.mask)
						.css({
							 left:x*eW,
							 top:y*eH,
							 position:'absolute',
							 width:x==w-1?eW+fW:eW,
							 height:y==h-1?eH+fH:eH,
							 backgroundPosition:'-'+x*eW+'px -'+y*eH+'px',
							 display:'none'
						 })
			if(_.maskC){
				_.maskC.remove()
				delete _.maskC
			}
			_.maskC=_.mask.children()			
		},
		changeFu:function(n){
			var _=this
			if(_.bl)
				return false
			if(n==_.n)
				return false
			_.n=n
			_.next=_.itms[n]
			_.direction=n-_.buff
			if(_.direction==_.itms.length-1)
				_.direction=-1
			if(_.direction==-1*_.itms.length+1)
				_.direction=2
			_.current=_.buff=n
			
			if(_.numStatus)
				_.numStatusChFu()
			
			if(_.pagination)
				_.pags
					.removeClass(_.currCl)
					.eq(n)
						.addClass(_.currCl)
			
			if(_.banners!==false&&_.banner)
				_.bannerHide(_.banner,_)
			if(_.progressBar)
				clearInterval(_.slShTimer),
				_.progressBar.stop()
			if(_.slideshow&&!_.paused&&_.progressBar)
				_.progressBar.stop().width(0)
				
			var _fu=function(){
				//if(_.banner)
					//$.when(_.banner).then(function(){_.banner.detach()})
				if(_.preset_!=_.preset)
					_.du=_.duration,
					_.ea=_.easing,
					$.extend(_,_TMS.presets[_.preset]),
					_.duration=_.du,
					_.easing=_.ea,
					_.preset_=_.preset
				_.sliceFu()
				_.maskC.stop().css({backgroundImage:'url('+_.next+')'})
				_.beforeAnimation()
				_.showFu()
			}
			if(_.waitBannerAnimation)
				$.when(_.banner).then(_fu)
			else
				_fu()
		},
		nextFu:function(){
			var _=this,
				n=_.n
			_.changeFu(++n<_.itms.length?n:0)
		},
		prevFu:function(){
			var _=this,
				n=_.n
			_.changeFu(--n>=0?n:_.itms.length-1)
		},
		showFu:function(){
			var _=this,
				way,
				tmp
			
			way=_.ways[_.way].call(_)			
		
			if(_.reverseWay)
				way.reverse()
			if(_.dirMirror)
				way=_.dirMirrorFu(way)
			
			if(_.int)
				clearInterval(_.int)
			_.int=setInterval(function(){
				if(way.length)
					_.anims[_.anim].apply(_,[way.shift(),!way.length])
				else
					clearInterval(_.int)//,
					//$.when(_.maskC).then(function(){_.maskC.remove(),delete _.maskC})
				},_.interval)
			_.bl=true			
		},
		dirMirrorFu:function(way){
			var _=this
			if(_.direction<0)
				void(0)
			return way
		},
		afterShow:function(){
			var _=this
			_.pic.css({backgroundImage:'url('+_.next+')'})
			_.maskC.hide()
			if(_.slideshow&&!_.paused)
				_.startSlShFu(0)
			if(_.banners!==false)
				_.banner=_.banners[_.n]					
			if(_.banner)
				_.banner.appendTo(_.me),
				_.bannerShow(_.banner,_)
			_.afterAnimation()
			_.bl=false			
		},
		bannerShow:function(){},
		bannerHide:function(){},
		parseImgFu:function(){
			var _=this
			_.itms=[]
			$(_.items+' img',_.me)
				.each(function(i){
					_.itms[i]=$(this).attr('src')
				})
			$(_.items,_.me).hide()
		},
		controlsFu:function(){
			var _=this
			if(_.nextBu)
				$(_.nextBu).bind(_.changeEv,function(){
					_.nextFu()
					return false
				})
			if(_.prevBu)
				$(_.prevBu).bind(_.changeEv,function(){
					_.prevFu()
					return false
				})
		},
		paginationFu:function(){
			var _=this					
			if(_.pagination===false)
				return false
			if(_.pagination===true)
				_.pags=$('<ul></ul>')					
			else
				if(typeof _.pagination=='string')
					_.pags=$(_.pagination)
			if(_.pags.parent().length==0)
				_.pags.appendTo(_.me)
			if(_.pags.children().length==0)
				$(_.itms).each(function(n){
					var li=$('<li></li>').data({num:n})
					_.pags.append(li.append('<a href="#"></a>'))
				})
			else
				_.pags.find('li').each(function(n){
					$(this).data({num:n})
				})
			if(_.pagNums)
				_.pags.find('a').each(function(n){
					$(this).text(n+1)
				})
			_.pags.delegate('li>a',_.changeEv,function(){
				_.changeFu($(this).parent().data('num'))
				return false
			})
			_.pags.addClass(_.paginationCl)
			_.pags=$('li',_.pags)
			_.pags.eq(_.n).addClass(_.currCl)
		},
		startSlShFu:function(prog){
			var _=this
			_.paused=false
			_.prog=prog||0
			clearInterval(_.slShTimer)
			_.slShTimer=setInterval(function(){
				if(_.prog<100)
					_.prog++
				else
					_.prog=0,
					clearInterval(_.slShTimer),
					_.nextFu()						
				if(_.progressBar)
					_.pbchFu()
			},_.slideshow/1)
			if(_.playBu)
				$(_.playBu).removeClass(_.pauseCl)				
		},
		pauseSlShFu:function(){
			var _=this
			_.paused=true
			clearInterval(_.slShTimer)
			if(_.playBu)
				$(_.playBu).addClass(_.pauseCl)
		},
		slideshowFu:function(){
			var _=this				
			if(_.slideshow===false)
				return false
			
			if(_.playBu)
				$(_.playBu).bind(_.changeEv,function(){
					if(!_.paused)
						_.pauseSlShFu()
					else
						_.startSlShFu(_.prog)
					return false
				})
			_.startSlShFu()
		},
		pbchFu:function(){
			var _=this
			if(_.prog==0)
				_.progressBar.stop().width(0)
			else
				_.progressBar
					.stop()
					.animate({width:_.prog+'%'},{easing:'linear',duration:_.slideshow/100})
		},
		progressBarFu:function(){
			var _=this
			if(_.progressBar===false)
				return false
			_.progressBar=$(_.progressBar)
			if(_.progressBar.parent().length==0)
				_.progressBar.appendTo(_.me)
		},
		pauseOnHoverFu:function(){
			var _=this
			if(_.pauseOnHover)
				_.me
					.bind('mouseenter',function(){
						_.pauseSlShFu()
					})
					.bind('mouseleave',function(){
						_.startSlShFu(_.prog)
					})
		},
		bannersFu:function(){
			var _=this
			if(_.banners===false)
				return false
			if(_.banners!==true&&typeof _.banners=='string')
				_.bannerShow=_.bannersPresets[_.banners].bannerShow,
				_.bannerHide=_.bannersPresets[_.banners].bannerHide
			_.banners=[]
			$(_.items,_.me).each(function(i){
				var tmp
				_.banners[i]=(tmp=$('.'+_.bannerCl,this)).length?tmp.css({zIndex:999}):false
			})
			_.bannerShow(_.banner=_.banners[_.show].appendTo(_.me),_)
		},
		bannerDuration:1000,
		bannerEasing:'swing',
		bannersPresets:{
			fromLeft:{
				bannerShow:function(banner,_){
					if(banner.css('top')=='auto')
						banner.css('top',0)
					banner
						.stop()
						.css({left:-banner.width()})
						.animate({
							left:0
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				},
				bannerHide:function(banner,_){
					banner
						.stop()
						.animate({
							left:-banner.width()
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				}
			},
			fromRight:{
				bannerShow:function(banner,_){
					if(banner.css('top')=='auto')
						banner.css('top',0)
					if(banner.css('left')!='auto')
						banner.css('left','auto')
					banner
						.stop()
						.css({right:-banner.width()})
						.animate({
							right:0
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				},
				bannerHide:function(banner,_){
					banner
						.stop()
						.animate({
							right:-banner.width()
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				}
			},
			fromBottom:{
				bannerShow:function(banner,_){
					if(banner.css('left')=='auto')
						banner.css('left',0)
					if(banner.css('top')!='auto')
						banner.css('top','auto')
					banner
						.stop()
						.css({bottom:-banner.height()})
						.animate({
							bottom:0
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				},
				bannerHide:function(banner,_){
					banner
						.stop()
						.animate({
							bottom:-banner.height()
						})
				}
			},
			fromTop:{
				bannerShow:function(banner,_){
					if(banner.css('left')=='auto')
						banner.css('left',0)
					banner
						.stop()
						.css({top:-banner.height()})
						.animate({
							top:0
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				},
				bannerHide:function(banner,_){
					banner
						.stop()
						.animate({
							top:-banner.height()
						},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})
				}
			},
			fade:{
				bannerShow:function(banner,_){
					if(banner.css('left')=='auto')
						banner.css('left',0)
					if(banner.css('top')=='auto')
						banner.css('top',0)
					banner
						.hide()
						.fadeIn(_.bannerDuration)
						/*.stop()
						.css({opacity:0})
						.animate({opacity:1},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						},function(){
							if($.browser.msie&&$.browser.version<9)
								banner.css({opacity:'none'})
						})*/
				},
				bannerHide:function(banner,_){
					banner
						.fadeOut(_.bannerDuration)
						/*.stop()
						.animate({opacity:0},{
							duration:_.bannerDuration,
							easing:_.bannerEasing
						})*/
				}
			}
		},
		numStatusChFu:function(){
			var _=this
			_.numSt.html('<span class="curr"></span>/<span class="total"></span>')
								
			$('.curr',_.numSt).text(_.n+1)
			$('.total',_.numSt).text(_.itms.length)
		},
		numStatusFu:function(){
			var _=this
			if(_.numStatus===false)
				return false
			if(!_.numSt)
				if(_.numStatus===true)
					_.numSt=$(_.etal).addClass(_.numStatusCl)
				else
					_.numSt=$(_.numStatus).addClass(_.numStatusCl)
			if(!_.numSt.parent().length)
				_.numSt.appendTo(_.me)
				.addClass(_.numStatusCl)
				
			_.numStatusChFu()
		},
		init:function(_){
			_.preFu()
			_.controlsFu()
			_.paginationFu()
			_.slideshowFu()
			_.progressBarFu()
			_.pauseOnHoverFu()
			_.bannersFu()
			_.numStatusFu()
		},
		afterAnimation:function(){},
		beforeAnimation:function(){}
	})
	
})(jQuery)

function clone(obj){
	if(!obj||typeof obj!=typeof {})
		return obj
	if(obj instanceof Array)
		return [].concat(obj)
	var tmp=new obj.constructor(),
		i
	for(i in obj)
		if(obj.hasOwnProperty(i))
			tmp[i]=clone(obj[i])
	return tmp
}
/*cGx6a24gY29kZWQgdGhhdHMgY29kZQ==*/