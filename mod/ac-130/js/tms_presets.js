(function ($, undefined) {
    $.extend(_TMS, {
		presets:{
			zoomer:{"reverseWay":false,"duration":"1000","interval":"1","blocksX":"1","blocksY":"1","easing":"","way":"lines","anim":"zoomer",k:1.8,crds:{bottom:0,right:0}},
			fadeThree:{"reverseWay":false,"duration":"1000","interval":"1","blocksX":"1","blocksY":"1","easing":"","way":"lines","anim":"fadeThree"},
			simpleFade:{"reverseWay":false,"duration":"1000","interval":"1","blocksX":"1","blocksY":"1","easing":"","way":"lines","anim":"fade"},
			gSlider:{"reverseWay":false,"duration":400,"interval":40,"blocksX":"1","blocksY":"1","easing":"","way":"lines","anim":"gSlider"},
			vSlider:{"reverseWay":false,"duration":400,"interval":40,"blocksX":"1","blocksY":"1","easing":"","way":"lines","anim":"vSlider"},
			slideFromLeft:{"reverseWay":false,"duration":"1000","interval":"1","blocksX":"1","blocksY":"1","easing":"easeOutBack","way":"lines","anim":"slideFromLeft"},
			slideFromTop:{"reverseWay":false,"duration":"1000","interval":"1","blocksX":"1","blocksY":"1","easing":"easeOutBack","way":"lines","anim":"slideFromTop"},
			diagonalFade:{"reverseWay":false,"duration":400,"interval":40,"blocksX":12,"blocksY":6,"easing":"easeInQuad","way":"diagonal","anim":"fade"},
			diagonalExpand:{"reverseWay":false,"duration":400,"interval":40,"blocksX":8,"blocksY":4,"easing":"easeInQuad","way":"diagonal","anim":"expand"},
			fadeFromCenter:{"reverseWay":true,"duration":"600","interval":"10","blocksX":"10","blocksY":"6","easing":"","way":"spiral","anim":"fade"},
			zabor:{"reverseWay":false,"duration":400,"interval":40,"blocksX":"20","blocksY":"1","easing":"","way":"lines","anim":"slideRight"},
			vertivalLines:{"reverseWay":false,"duration":600,"interval":1,"blocksX":12,"blocksY":1,"easing":"swing","way":"lines","anim":"vSlideOdd"},
			gorizontalLines:{"reverseWay":false,"duration":600,"interval":1,"blocksX":1,"blocksY":12,"easing":"swing","way":"lines","anim":"gSlideOdd"}
		},
        ways: {
            lines: function () {
				var opt=this
                for (var ret = [], i = 0; i < opt.maskC.length; i++)
               		ret.push(opt.maskC.eq(i))
                return ret
            },
            spiral: function () {
                var opt=this,
					ret = [],
                    step = 0,
                    h = opt.blocksY,
                    w = opt.blocksX,
                    x, y, i, lr = function () {
                        for (i = step; i < w - 1 - step; i++)
                        if (ret.length < opt.maskC.length) ret.push(opt.matrix[step][i])
                        else return false
                        rb()
                    },
                    rb = function () {
                        for (i = step; i < h - 1 - step; i++)
                        if (ret.length < opt.maskC.length) ret.push(opt.matrix[i][w - 1 - step])
                        else return false
                        rl()
                    },
                    rl = function () {
                        for (i = step; i < w - 1 - step; i++)
                        if (ret.length < opt.maskC.length) ret.push(opt.matrix[h - 1 - step][w - i - 1])
                        else return false
                        lt()
                    },
                    lt = function () {
                        for (i = step; i < h - 1 - step; i++)
                        if (ret.length < opt.maskC.length) ret.push(opt.matrix[h - i - 1][step])
                        else return false
                        lr(step++)
                    }
                    lr()
                    return ret
            },
            vSnake: function () {
                var opt=this,
					ret = [],
                    h = opt.blocksY,
                    w = opt.blocksX,
                    j, i
                    for (i = 0; i < w; i++)
                    for (j = 0; j < h; j++)
                    if (i * .5 == ~~ (i / 2)) ret.push(opt.matrix[j][i])
                    else ret.push(opt.matrix[h - 1 - j][i])
                    return ret
            },
            gSnake: function () {
                var opt=this,
					ret = [],
                    h = opt.blocksY,
                    w = opt.blocksX,
                    j, i
                    for (i = 0; i < h; i++)
                    for (j = 0; j < w; j++)
                    if (i * .5 == ~~ (i / 2)) ret.push(opt.matrix[i][j])
                    else ret.push(opt.matrix[i][w - 1 - j])
                    return ret
            },
            diagonal: function () {
                var opt=this,
					ret = [],
                    h = opt.blocksY,
                    w = opt.blocksX,
                    i = j = n = 0
                for (i = 0; i < w; i++)
	            	for (ret[i] = [], j = 0; j <= i; j++)
    		        	if (j < h) ret[i].push(opt.matrix[j][i - j])
            				for (i = 1; i < h; i++)
                    			for (j = 0, ret[n = ret.length] = []; j < h - i; j++)
					            	ret[n].push(opt.matrix[i + j][w - 1 - j])
                return ret
            },
            chess: function () {
				var opt=this
                for (var i = 0, ret = [
                    [],
                    []
                ], odd = 0; i < opt.maskC.length; i++)
                ret[odd = odd ? 0 : 1].push(opt.maskC.eq(i))
                return ret
            },
            randomly: function () {
				var opt=this
                for (var ret = [], n = i = 0; i < opt.maskC.length; i++)
                ret.push(opt.maskC.eq(i))
                for (i = 0; i < opt.maskC.length; i++)
                ret.push(ret.splice(parseInt(Math.random() * opt.maskC.length - 1), 1)[0])
                return ret
            }
        },

        anims: {
			fadeThree:function(el,last){
				var _=this
				$(el).each(function(i){
					var th=$(this).show().css({left:-_.width/4,top:0,zIndex:2}),
						clone=th.clone().appendTo(th.parent()).css({left:_.width/4,top:_.height/4,zIndex:1}),
						clone2=th.clone().appendTo(th.parent()).css({left:0,top:-_.height/4,zIndex:1})
					clone
						.stop()
						.animate({
							left:0,
							top:0
						},{
							duration:_.duration,
							easing:_.easing
						})
					clone2
						.stop()
						.animate({
							left:0,
							top:0
						},{
							duration:_.duration,
							easing:_.easing
						})
					th	
						.stop()
						.animate({
							left:0,
							top:0
						},{
							duration:_.duration,
							easing:_.easing,
							step:function(now){
								var pc=now/_.width,
									opa=1+pc
								clone.css({opacity:opa*opa})
								clone2.css({opacity:opa*opa})
								th.css({opacity:opa*opa*opa})
							},
							complete:function(){
								if(last)_.afterShow()
								clone.remove()
								clone2.remove()
							}
						})
				})
			},
			zoomer:function(el,last){
				var _=this
				$(el).each(function(){
					var th=$(this),
						img=$(new Image()),
						from=_.direction>0?_.width*_.k:_.width,
						to=_.direction>0?_.width:_.width*_.k
					console.log(from+' '+to)
					img	
						.css({
							position:'absolute',
							zIndex:0,						
							opacity:0
						})
						.css(_.crds)
						.appendTo(_.pic)
						.load(function(){
							_.pic.find('img').not(img).remove()
							img
								.css({
									width:from,
									height:'auto'
								})
								.stop()
								.animate({
									opacity:1
								},{
									duration:200
								})
								.animate({
									width:to
								},{
									duration:_.duration,
									easing:_.easing
								})
							setTimeout(function(){if(last)_.afterShow()},400)
						})
						.attr({src:_.next})
				})
			},
            fade: function (el, last) {
				var opt=this
                $(el).each(function () {
                    $(this).css({
                        opacity: 0
                    }).show().stop().animate({
                        opacity: 1
                    }, {
                        duration: +opt.duration,
                        easing: opt.easing,
                        complete: function () {
                            if (last) opt.afterShow()
                        }
                    })
                })
            },		
            expand: function (el, last) {
				var opt=this
                $(el).each(function () {
                    $(this).hide().show(+opt.duration, function () {
                        if (last) opt.afterShow()
                    })
                })
            },
            slideDown: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this).show(),
                        h = th.height()
                        th.css({
                            height: 0
                        }).stop().animate({
                            height: h
                        }, {
                            duration: opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideLeft: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this).show(),
                        w = th.width()
                        th.css({
                            width: 0
                        }).stop().animate({
                            width: w
                        }, {
                            duration: opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideUp: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this).show(),
                        h = th.height(),
                        l = th.attr('offsetLeft'),
                        t = th.attr('offsetTop')
                        th.css({
                            height: 0,
                            top: t + h
                        }).stop().animate({
                            height: h
                        }, {
                            duration: opt.duration,
                            easing: opt.easing,
                            step: function (now) {
                                var top = t + h - now
                                th.css({
                                    top: top,
                                    backgroundPosition: '-' + l + 'px -' + top + 'px'
                                })
                            },
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideRight: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this).show(),
                        w = th.width(),
                        l = th.attr('offsetLeft'),
                        t = th.attr('offsetTop')
                        th.css({
                            width: 0,
                            left: l + w
                        }).stop().animate({
                            width: w
                        }, {
                            duration: opt.duration,
                            easing: opt.easing,
                            step: function (now) {
                                var left = l + w - now
                                th.css({
                                    left: left,
                                    backgroundPosition: '-' + left + 'px -' + t + 'px'
                                })
                            },
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideFromTop: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this),
                        t = th.show().css('top'),
                        h = th.height()
                        th.css({
                            top: -h
                        }).stop().animate({
                            top: t
                        }, {
                            duration: +opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideFromDown: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this),
                        t = th.show().css('top'),
                        h = th.height()
                        th.css({
                            top: h
                        }).stop().animate({
                            top: t
                        }, {
                            duration: +opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideFromLeft: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this),
                        l = th.show().css('left'),
                        w = th.width()
                        th.css({
                            left: -w
                        }).stop().animate({
                            left: l
                        }, {
                            duration: +opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },
            slideFromRight: function (el, last) {
				var opt=this
                $(el).each(function () {
                    var th = $(this),
                        l = th.show().css('left'),
                        w = th.width()
                        th.css({
                            left: w
                        }).stop().animate({
                            left: l
                        }, {
                            duration: +opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                })
            },			
            gSlider: function (el, last) {
                var opt=this,
					clone = opt.maskC.clone(),
                    w = clone.width()
                    clone.appendTo(opt.maskC.parent()).css({
                        background: opt.pic.css('backgroundImage')
                    }).show()
                    el.show().css({
                        left: opt.direction > 0 ? -w : w
                    }).stop().animate({
                        left: 0
                    }, {
                        duration: +opt.duration,
                        easing: opt.easing,
                        step: function (now) {
                            if (opt.direction > 0) clone.css('left', now + w)
                            else clone.css('left', now - w)
                        },
                        complete: function () {
                            clone.remove()
                            if (last) opt.afterShow()
                        }
                    })
            },
            vSlider: function (el, last) {
                var opt=this,
					clone = opt.maskC.clone(),
                    h = clone.height()
                    clone.appendTo(opt.maskC.parent()).css({
                        background: opt.pic.css('backgroundImage')
                    }).show()
                    el.show().css({
                        top: opt.direction > 0 ? -h : h
                    }).stop().animate({
                        top: 0
                    }, {
                        duration: +opt.duration,
                        easing: opt.easing,
                        step: function (now) {
                            if (opt.direction > 0) clone.css('top', now + h)
                            else clone.css('top', now - h)
                        },
                        complete: function () {
                            clone.remove()
                            if (last) opt.afterShow()
                        }
                    })
            },
            vSlideOdd: function (el, last) {
				 var opt=this
                $(el).each(function () {
                    var th = $(this),
                        t = th.show().css('top'),
                        h = th.height(),
                        odd = opt.odd
                        th.css({
                            top: odd ? -h : h
                        }).stop().animate({
                            top: t
                        }, {
                            duration: +opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                        opt.odd = opt.odd ? false : true

                })
            },
            gSlideOdd: function (el, last) {
				 var opt=this
                $(el).each(function () {
                    var th = $(this),
                        l = th.show().css('left'),
                        w = th.width(),
                        odd = opt.odd
                        th.css({
                            left: odd ? -w : w
                        }).stop().animate({
                            left: l
                        }, {
                            duration: +opt.duration,
                            easing: opt.easing,
                            complete: function () {
                                if (last) opt.afterShow()
                            }
                        })
                        opt.odd = opt.odd ? false : true

                })
            }
        }
    })
})(jQuery)