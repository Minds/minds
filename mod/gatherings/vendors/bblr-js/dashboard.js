var b = box_by_user(user_id);
b.draggable({
    containment: "document",
    scroll: false,
    stack: ".br-person-box"
});
var vc = makeVideoController({
    user_id: user_id,
    rootElement: b.find("#avatar_parent"),
    showBackground: show_avatar,
    showControl: function (ctrl, show, enabled) {
        id = null;
        switch (ctrl) {
        case "start":
            id = "#start_webcam";
            break;
        case "stop":
            id = "#stop_webcam";
            break;
        case "view":
            id = "#start_video";
            break;
        case "unview":
            id = "#stop_video";
            break;
        case "mute":
            id = "#mute";
            break;
        case "unmute":
            id = "#unmute";
            break;
        case "video_on":
            id = "#video_on";
            break;
        case "video_off":
            id = "#video_off";
            break
        }
        if (!id || !(id = b.find(id)).length) return;
        show ? id.show() : id.hide();
        id.button(enabled !== false ? "enable" : "disable")
    },
    updateBroadcasterIndicators: function (indicators) {
        var rtitle = "";
        if (indicators.md.video === false) rtitle += '<i class="icon2-pause-circled" style="font-size: 0.9em;"></i> ';
        if (indicators.md.audio === false) rtitle += '<i class="icon2-mic-off" style="font-size: 0.9em"> </i> ';
        if (indicators.broadcasting) {
            if (indicators.peerCount) {
                rtitle += indicators.peerCount.toString() + " viewer";
                if (indicators.peerCount > 1) rtitle += "s"
            }
        }
        b.find("#" + rtitle_id).html(rtitle)
    },
    autoStartViewer: true,
    useWebRTC: BRDashboard.conference_access_config && BRDashboard.conference_access_config.peer_to_peer
});

function show_avatar(show) {
    if (show === undefined) show = !vc.isVideoOn();
    if (show) {
        var av = $j(b.hasClass("br-person-big-box") ? "#avatar_large" : "#avatar_medium", b);
        img = $j("img", av);
        if (img.prop("src")) img.show().next().hide();
        else img.hide().next().show();
        av.show()
    } else $j("#avatar_medium, #avatar_large", b).hide()
}
function avatar_icon_online(online) {
    var icon = "icon2-phone";
    if (online) {
        icon = "icon2-user";
        var iid = BRDashboard.invitee_id_by_user[user_id],
            i = BRDashboard.invitees[iid];
        if (iid && i && i.role === "Host") icon = "icon2-magic"
    }
    b.find("#avatar_parent i.icon").removeClass("icon2-user icon2-phone").addClass(icon)
}
function audio_action(action) {
    var d = b.data("data").mids,
        mids = [];
    for (var m in d) if (d.hasOwnProperty(m)) mids.push(m);
    if (mids.length) BRCommands.conferenceIdsAction(mids, action)
}
function resize(to_big) {
    show_avatar(false);
    if (to_big) {
        b.removeClass("br-person-box").addClass("br-person-big-box");
        b.find("#small").show().next().hide()
    } else {
        b.removeClass("br-person-big-box").addClass("br-person-box");
        b.find("#small").hide().next().show()
    }
    show_avatar(undefined)
}
b.find("#mute").parent().buttonset();
b.find("#talking").button({
    label: '<i class=""></i>'
}).show().next().button({
    label: '<i class="icon2-pause-circled"></i>'
}).click(function () {
    vc.mediaChannelAction("video_off")
}).next().button({
    label: '<i class="icon2-play-circled"></i>'
}).click(function () {
    vc.mediaChannelAction("video_on")
}).next().button({
    label: '<i class="icon2-mic-off"></i>'
}).click(function () {
    vc.mediaChannelAction("mute", audio_action)
}).next().button({
    label: '<i class="icon2-mic-1"></i>'
}).click(function () {
    vc.mediaChannelAction("unmute", audio_action)
}).next().button({
    label: '<i class="icon icon2-videocam"></i>'
}).click(function () {
    vc.startBroadcast()
}).next().button({
    label: '<i class="icon icon2-stop"></i>'
}).click(function () {
    vc.stopBroadcast()
}).next().button({
    label: '<i class="icon icon2-eye-1"></i>'
}).click(function () {
    vc.startView()
}).next().button({
    label: '<i class="icon icon2-eye-off-1"></i>'
}).click(function () {
    vc.stopView()
}).next().button({
    label: '<i class="icon icon2-resize-small"></i>'
}).click(function () {
    resize(false)
}).next().button({
    label: '<i class="icon icon2-resize-full"></i>'
}).click(function () {
    resize(true)
}).next().button({
    label: '<!--<i class="icon2-check-empty"></i>-->'
}).click(function () {}).next();
b.find(".ui-button-text").css({
    padding: "0.4em",
    height: "16px",
    width: "16px"
});
b.find("button").hide();
b.find("#talking,#select").show();
b.find("#small").next().show();
show_avatar(true);
var lmut_store = {};

function lmut(listening, mute, talking, is_host) {
    if (typeof listening !== "undefined") lmut_store.listening = listening;
    if (typeof mute !== "undefined") lmut_store.mute = mute;
    if (typeof talking !== "undefined") lmut_store.talking = talking;
    if (typeof is_host !== "undefined") lmut_store.is_host = is_host;
    b.find("#talking i").removeClass("icon2-volume-off icon2-volume-up");
    b.find("#mute").hide().next().hide();
    if (lmut_store.listening) {
        var nc = "";
        if (lmut_store.talking) nc = "icon2-volume-up";
        else if (lmut_store.mute) nc = "icon2-volume-off";
        if (nc.length) b.find("#talking i").addClass(nc);
        var sel = b.find("#mute");
        if (lmut_store.mute) sel = sel.next();
        sel.show().button(BR._api.context.user_id == user_id || lmut_store.is_host ? "enable" : "disable")
    }
}
BRDashboard.subscribe(function (o) {
    if (o.updated.is_host) lmut(undefined, undefined, undefined, BR._api.context.is_host)
}, "room_context");
lmut(false, false, false, BR._api.context.is_host);
b.data("lmut", lmut);
b.data("cb", function (verb, key, value) {
    switch (verb) {
    case "src":
        if (b.is(":visible")) {
            b.find("#" + key + "_img").prop("src", value);
            show_avatar(undefined)
        } else deferred_src[key] = value;
        break
    }
});
b.data("box_function", function (o) {
    switch (o.command) {
    case "hide":
        avatar_icon_online(o.data._online);
        b.hide();
        break;
    case "show":
        if (deferred_src) {
            deferred_src.avatar_medium && b.find("#avatar_medium_img").prop("src", deferred_src.avatar_medium);
            deferred_src.avatar_large && b.find("#avatar_large_img").prop("src", deferred_src.avatar_large);
            deferred_src = {};
            show_avatar(undefined)
        }
        avatar_icon_online(o.data._online);
        b.show();
    case "update":
        if (o.data.dtmf !== o.old_data.dtmf) var dtmf_tmp = parseInt(o.data.dtmf) || 0;
        if (dtmf_tmp > 0) dtmf_tmp = "&#x246" + (dtmf_tmp - 1).toString();
        else dtmf_tmp = "";
        b.find("#select span").html('<span style="font-weight: bold; font-size: 1.1em;">' + dtmf_tmp + "</span>");
        if (o.data._online !== o.old_data._online) {
            avatar_icon_online(o.data._online)
        }
        if (BRDashboard.connection_id) {
            if (BRDashboard.connection_id in o.data.connection_ids) {
                vc.setMe()
            }
            if (o.data._online !== o.old_data._online && o.id) b.find("#" + title_id).html(BRWidgets.user_name(o.data.connection_ids, o.id))
        }
        break
    }
    if (!o.old_data._listeners && o.data._listeners) lmut(true, undefined, undefined, undefined);
    else if (o.old_data._listeners && !o.data._listeners) lmut(false, undefined, undefined, undefined)
});
return b
}
BRDashboard.subscribe(function (o) {
    var b = box_by_user(o.idx);
    if (b.data("data") && "connection_ids" in b.data("data") && typeof o.attr != "undefined") b.find(s(o.idx, "title")).html(BRWidgets.user_name(b.data("data")["connection_ids"], o.idx));
    if (o.idx && o.attr && o.value) {
        if (o.attr === "avatar_medium" || o.attr === "avatar_large") b.data("cb")("src", o.attr, o.value)
    }
}, "users");
BRDashboard.subscribe(function (o) {
    var b = box_by_listener(o.mid);
    if (b.length && o.command == "attr" && typeof o.attrs["mute"] !== undefined) b.data("lmut")(undefined, o.attrs.mute ? true : false, undefined, undefined)
}, "listener");
BRDashboard.subscribe(function (o) {
    var b = box_by_listener(o.mid);
    if (!b.length) return;
    b.data("lmut")(undefined, undefined, typeof o.value === "undefined" ? false : true, undefined)
}, "talking");
BRDashboard.subscribe(function (o) {
    var b = box_by_user(o.data["user_id"]);
    switch (o.command) {
    case "add":
        b = make_box(o.data["user_id"]);
        break;
    case "del":
        break
    }
    if (b.length) {
        b.data("data", o.data);
        b.data("box_function")(o)
    }
}, "box")
}, slidesArea: function (sel) {
    var $j = jQuery;
    var have_moved_user_to_slideshows_once = false;
    $j(sel).append("<center>" + BRWidgets._commonToolbar("slides_toolbar") + '<div style="height: 2px;"></div><div id="slide" class="br-z-index-slide"><img id="slide_img" width="800" alt="" class="ui-widget-content" style="cursor: crosshair;"></div></center>');
    $j(sel).append('<div id="cslptr" class="br-z-index-ptr" style="position: absolute; background-color: transparent; color: red; padding: 0; margin: 0; font-size: 2em; display: none; cursor: crosshair; pointer-events: none;">&otimes;</div>');
    var no_pres_text = "-- No Presentation Loaded --";
    var sel_pres_text = "-- Select a Presentation --";
    $j("#slides_toolbar").append('<span class="not_presenting">    <span><a href="#" target="_blank" id="presentation_name">' + no_pres_text + '</a></span>    <span id="page">--</span></span><span class="presenting">    <select id="presentations"><option value="-1">' + sel_pres_text + '</option></select>    <button id="upload_button" title="Upload file...">Upload</button>    <select id="current_page"><option>--</option></select></span><span>    <span>/ </span>    <span id="num_pages">--</span></span><span class="presenting">    <button id="beginning_button" class="page_control goes_forward" title="First">First</button>    <button class="page_control goes_forward" title="Prev">Prev</button>    <button id="show_button"  class="page_control" style="" title="Show Slide"><i class="icon icon2-eye"></i></button>    <button id="hide_button"  class="page_control" style="display: none;" title="Hide Slide"><i class="icon icon2-eye-off"></i></button>    <button class="page_control goes_backward" title="Next">Next</button>    <button class="page_control goes_backward" title="End">End</button>    &nbsp;    <button title="Close Presentation">Close</button></span><span id="presenter" class="not_presenting"></span><button id="make_me_button" class="not_presenting">Make Me Presenter</button>');
    $j("#upload_button").button({
        text: false,
        icons: {
            primary: "ui-icon-circle-arrow-n"
        }
    }).click(BRWidgets._upload);
    var current_page = $j("#current_page");
    BRWidgets.styleSelect(current_page);
    $j("#beginning_button").button({
        text: false,
        icons: {
            primary: "ui-icon-seek-first"
        }
    }).click(function () {
        presenter_set_page(1)
    }).next().button({
        text: false,
        icons: {
            primary: "ui-icon-seek-prev"
        }
    }).click(function () {
        presenter_set_page(-1)
    }).next().button({
        text: true,
        no_icons: {
            primary: "ui-icon-play"
        }
    }).click(function () {
        BRWidgets.presentationController.changePage(current_page.val())
    }).next().button({
        text: true,
        no_icons: {
            primary: "ui-icon-stop"
        }
    }).click(function () {
        BRWidgets.presentationController.changePage(undefined)
    }).next().button({
        text: false,
        icons: {
            primary: "ui-icon-seek-next"
        }
    }).click(function () {
        presenter_set_page(0)
    }).next().button({
        text: false,
        icons: {
            primary: "ui-icon-seek-end"
        }
    }).click(function () {
        presenter_set_page($j("#num_pages").text())
    }).next().button({
        text: false,
        icons: {
            primary: "ui-icon-closethick"
        }
    }).click(function () {
        BRWidgets.presentationController.close()
    }).next();
    $j("#make_me_button").button({
        text: true,
        no_icons: {
            primary: "ui-icon-pencil"
        }
    }).click(function () {
        BRWidgets.presentationController.makeMePresenter()
    });
    var sel_pr = $j("#presentations");
    BRWidgets.styleSelect(sel_pr);
    BRWidgets.enableSelect(sel_pr, false);
    var url;
    var mp_url;

    function update_page_controls() {
        var page = parseInt(current_page.val(), 10) || 0;
        var num_pages = parseInt($j("#num_pages").text(), 10) || 0;
        if (page > 0) {
            $j(sel).find("button.page_control").button("enable");
            if (page == 1) $j(sel).find("button.goes_forward").removeClass("ui-state-focus ui-state-hover").button("disable");
            if (page == $j("#num_pages").text()) $j(sel).find("button.goes_backward").removeClass("ui-state-focus ui-state-hover").button("disable")
        } else $j(sel).find("button.page_control").button("disable")
    }
    function presenter_set_page(new_page_num) {
        if (typeof new_page_num == "string") {
            new_page_num = parseInt(new_page_num, 10);
            if (isNaN(new_page_num)) return
        }
        if (new_page_num < 1) {
            var page = parseInt(current_page.val(), 10);
            if (isNaN(page)) return;
            if (new_page_num == -1) new_page_num = page - 1;
            else if (new_page_num == 0) new_page_num = page + 1;
            else return
        }
        var num_pages = $j("#num_pages").text();
        if (new_page_num < 1 || new_page_num > num_pages) return;
        current_page.val(new_page_num);
        update_page_controls();
        if ($j("#show_button").css("display") == "none") BRWidgets.presentationController.changePage(new_page_num)
    }
    var selImg = $j("#slide_img");
    var selPtr = $j("#cslptr");

    function show_page(page_num) {
        if (page_num && current_page.find('option[value="' + page_num + '"]').length) {
            current_page.val(page_num);
            $j("#page").text(page_num);
            $j("#show_button").css("display", "none");
            $j("#hide_button").css("display", "inline");
            var surl;
            if (mp_url.length) surl = mp_url + "-" + page_num + ".png";
            else surl = url;
            selImg.css("display", "block").attr("src", surl.replace(/^https?:/, ""));
            selPtr.css("display", "block");
            $j(sel).find("button.page_control").button("enable");
            update_page_controls();
            if (!presenting() && !have_moved_user_to_slideshows_once) {
                switch_view("slides");
                have_moved_user_to_slideshows_once = true
            }
            if (ticking === undefined) ticking = setInterval(fnTick, 40)
        } else {
            update_page_controls();
            $j("#show_button").css("display", "inline");
            $j("#hide_button").css("display", "none");
            selImg.css("display", "none");
            selPtr.css("display", "none");
            if (ticking !== undefined) {
                clearInterval(ticking);
                ticking = undefined
            }
        }
    }
    sel_pr.prop("disabled", true).change(function () {
        var selIndex = sel_pr.find(":selected").val();
        BRWidgets.presentationController.changePresentation(selIndex)
    });
    current_page.change(function () {
        presenter_set_page(current_page.val())
    });
    var scaleFactor = 800 / 1e4;
    var pointerXY = {
        x: 0,
        y: 0
    };
    var pointing = false;

    function set_ptr(obj) {
        if (obj) {
            pointerXY = adjustOutPtr(obj)
        } else selPtr.css("display", "none")
    }
    function fnMM(e) {
        lastMMEvent = e;
        lastMMEvent._br_x = Math.round((lastMMEvent.pageX - selImg.offset().left) / scaleFactor), lastMMEvent._br_y = Math.round((lastMMEvent.pageY - selImg.offset().top) / scaleFactor), lastMMEventTime = (new Date).getTime()
    }
    function adjustOutPtr(pair) {
        pair.x *= scaleFactor;
        pair.y *= scaleFactor;
        pair.x += selImg.offset().left - $j("#slide").offset().left - selPtr.width() / 2 + 1;
        pair.y += Math.round(selImg.offset().top) - 0 - 10;
        return pair
    }
    var ticking = undefined;
    var lastPointerSendTime = 0;
    var lastMMEventTime = 0;
    var lastMMEvent = {};
    var xyAtLastSend = {};

    function point() {
        if (!lastMMEventTime) return;
        if (xyAtLastSend.x == lastMMEvent._br_x && xyAtLastSend.y == lastMMEvent._br_y) return;
        else xyAtLastSend = {};
        pointerXY = adjustOutPtr({
            x: lastMMEvent._br_x,
            y: lastMMEvent._br_y
        });
        var now = (new Date).getTime();
        if (now - lastPointerSendTime < 200) return;
        BRWidgets.presentationController.setPointer(lastMMEvent._br_x, lastMMEvent._br_y);
        lastPointerSendTime = now;
        xyAtLastSend = {
            x: lastMMEvent._br_x,
            y: lastMMEvent._br_y
        }
    }
    var deltaXY = {
        x: 0,
        y: 0
    };
    var dampFactor = 4;

    function fnTick() {
        if (pointing) point();
        deltaXY.x += (pointerXY.x - deltaXY.x) / dampFactor;
        deltaXY.y += (pointerXY.y - deltaXY.y) / dampFactor;
        selPtr.css({
            left: deltaXY.x + "px",
            top: deltaXY.y + "px"
        })
    }
    function presenting() {
        return $j(".presenting").css("display") !== "none"
    }
    function startStopPointer() {
        var showingPage = $j("#slide").html().length > 0;
        if (presenting() && showingPage && !pointing) {
            pointing = true;
            $j("#slide_img").bind("mousemove", fnMM)
        }
        if (pointing && !(presenting() && showingPage)) {
            pointing = false;
            $j("#slide_img").unbind("mousemove", fnMM)
        }
    }
    $j(".presenting").css("display", "none");
    BRWidgets.presentationController.onChangePage = function (page_num) {
        show_page(page_num)
    };
    BRWidgets.presentationController.onPresentationChange = function (obj) {
        show_page(0);
        current_page.find("option").remove();
        if (obj && sel_pr.find('option[value="' + obj.presentationIndex + '"]').length) {
            var num_pages = obj.numPages;
            $j("#num_pages").text(num_pages);
            if (num_pages > 0) {
                for (var i = 1; i <= num_pages; i++) {
                    current_page.append('<option value="' + i + '">' + i + "</option>")
                }
                BRWidgets.enableSelect(current_page, true)
            }
            var presentation_name = obj.presentationName;
            sel_pr.val(obj.presentationIndex);
            url = obj.url;
            $j("#presentation_name").text(BRWidgets._crop(presentation_name));
            $j("#presentation_name").attr("href", url);
            $j("#presentation_name").attr("title", "Download " + presentation_name);
            if (obj.multipage) mp_url = url.replace(/\.([^\/]*)\?\d*$/, "_$1");
            else mp_url = "";
            update_page_controls()
        } else {
            $j("#num_pages").text("--");
            $j("#presentation_name").text(no_pres_text);
            $j("#page").text("");
            current_page.append("<option>--</option>");
            BRWidgets.enableSelect(current_page, false);
            sel_pr.val("-1");
            url = "";
            mp_url = "";
            update_page_controls()
        }
    };
    BRWidgets.presentationController.onPresenterChange = function (name, me) {
        if (!name.length) $j("#presenter").text("");
        else $j("#presenter").html(" <em>Presenter:</em> " + name + " ");
        if (name.length && me) {
            $j(".not_presenting").css("display", "none");
            $j(".presenting").css("display", "inline")
        } else {
            $j(".not_presenting").css("display", "inline");
            $j(".presenting").css("display", "none")
        }
    };
    BRWidgets.presentationController.onAddPresentation = function (idx, name) {
        sel_pr.append('<option value="' + idx + '">' + BRWidgets._crop(name) + "</option>");
        BRWidgets.enableSelect(sel_pr, true)
    };
    BRWidgets.presentationController.onRemovePresentation = function (idx) {
        sel_pr.find('option[value="' + idx + '"]').remove()
    };
    BRWidgets.presentationController.onSetPointer = function (obj) {
        set_ptr(obj)
    };
    BRWidgets.presentationController.onCheckPointer = function () {
        startStopPointer()
    }
},
changeCenterView: function (view) {
    var $j = jQuery;
    $j("#center_slides").css("display", "none");
    $j("#center_people").css("display", "none");
    $j("#center_list").css("display", "none");
    $j("#center_" + view).css("display", "block")
},
content_participantSummary: function (id, selector) {
    var accStr = '<div id="' + selector + '" style="overflow: hidden;">';
    accStr += '<div><table id="right_listeners_table"></table></div>';
    accStr += '<div><table id="' + id + '_right_online_table"></table></div>';
    accStr += "</div>";
    return accStr
},
participantSummary: function (id, selector) {
    var $j = jQuery;

    function modified() {
        BRWidgets._modified($j(selector).prev().data("key", id + "_participants"))
    }
    var t = $j("#" + id + "_right_online_table");
    t.jqGrid({
        datatype: "local",
        colModel: [{
            name: "id",
            hidden: true,
            sorttype: "int"
        }, {
            name: "full_name",
            label: "Online"
        }, {
            name: "user_id",
            hidden: true,
            sorttype: "int"
        }],
        width: 175
    });

    function fixup_name(connection_id, user_id) {
        var cids = {};
        cids[connection_id] = true;
        t.jqGrid("setRowData", connection_id, {
            full_name: BRWidgets.user_name(cids, user_id)
        })
    }
    BRDashboard.subscribe(function (o) {
        switch (o.command) {
        case "mod":
            if (!t.jqGrid("getInd", o.connection_id)) t.jqGrid("addRowData", o.connection_id, {
                id: o.connection_id,
                user_id: o.user_id
            });
            fixup_name(o.connection_id, o.user_id);
            break;
        case "del":
            t.jqGrid("delRowData", o.connection_id);
            break
        }
        modified()
    }, "online");
    BRDashboard.subscribe(function (o) {
        if (o.attr != "name" && o.attr != "last_name") return;
        var rows = t.jqGrid("getRowData");
        for (var i = 0; i < rows.length; i++) {
            if (o.id == rows[i].user_id) fixup_name(rows[i].id, o.id)
        }
    }, "users");

    function callers_change() {
        if (BRDashboard.listeners.length) $j("button.need1caller").button("enable");
        else $j("button.need1caller").button("disable");
        modified()
    }
    BRDashboard.subscribe(callers_change, "listener");
    callers_change()
},
content_files: function (id, selector) {
    var accStr = '<div id="' + selector + '">';
    accStr += '<div><table id="' + id + '_files"></table></div>';
    accStr += '    <div style="height: 5px"></div>    <fieldset id="' + id + '_fieldset" class="ui-widget ui-widget-content"><legend id="' + id + '_selected" class="ui-widget-header ui-corner-all"></legend>        <div id="' + id + '_fields"></div>        <div style="float: left;"><button id="' + id + '_download" style="width: 75px;" title="Open file"><a id="' + id + '_url" target="_blank" href="">Open</a></button></div>        <div style="float: right;"><button id="' + id + '_delete" style="width: 75px;" title="Delete file">Delete</button></div>        <div style="clear: both;"></div>    </fieldset>    <div style="height: 5px"></div>    <center><button id="' + id + '_upload" style="width: 75px;" title="Upload file...">Upload...</button></center>    ';
    accStr += "</div>";
    return accStr
},
files: function (id, selector) {
    var $j = jQuery;
    var t = $j("#" + id + "_files");
    $j("#" + id + "_download").button({
        text: true,
        icons: {
            primary: "ui-icon-arrowthickstop-1-s"
        }
    });
    $j("#" + id + "_delete").button({
        text: true,
        icons: {
            primary: "ui-icon-trash"
        }
    }).click(function () {
        if (confirm("The selected file will be permanently deleted. Press OK to confirm.")) {
            var rowid = t.jqGrid("getGridParam", "selrow");
            if (!rowid) return;

            function done(data, textStatus, jqXHR) {
                if (rowid == t.jqGrid("getGridParam", "selrow")) $j("#" + id + "_delete").button("enable");
                switch (textStatus) {
                case "success":
                    break;
                case "error":
                    alert("An error occurred deleting file.\n\nYou may not have permission to delete this file.");
                    break
                }
            }
            $j("#" + id + "_delete").button("disable");
            jQuery.ajax({
                url: BR._api.get_host("api") + "/api/v1/files/" + rowid,
                type: "DELETE",
                success: done,
                xhrFields: {
                    withCredentials: true
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    done(errorThrown, textStatus, jqXHR)
                }
            })
        }
    });
    $j("#" + id + "_upload").button({
        text: true,
        icons: {
            primary: "ui-icon-circle-arrow-n"
        }
    }).click(BRWidgets._upload);
    t.jqGrid({
        datatype: "local",
        colModel: [{
            name: "id",
            index: "id",
            hidden: true,
            sorttype: "int"
        }, {
            name: "name",
            label: "Download"
        }, {
            name: "pages",
            label: "Pages",
            width: "50",
            align: "right"
        }, {
            name: "url",
            hidden: true
        }, {
            name: "bucket",
            hidden: true
        }, {
            name: "len",
            hidden: true
        }, {
            name: "size",
            hidden: true
        }, {
            name: "user_id",
            hidden: true
        }],
        onSelectRow: function (rowid, selected) {
            var rd = t.jqGrid("getRowData", rowid);
            $j("#" + id + "_selected").text(BRWidgets._crop(rd.name, 28)).attr("title", rd.name);
            $j("#" + id + "_fieldset").find("button").button("enable");
            $j("#" + id + "_url").attr("href", rd.url);
            var f = "";

            function size(i) {
                var s = ["Bytes", "KB", "MB", "GB", "TB", "PB"];
                var e = Math.floor(Math.log(i) / Math.log(1024));
                return (i / Math.pow(1024, Math.floor(e))).toFixed(2) + " " + s[e]
            }
            if (rd.name) f += '<div style="float:left;width:30px;"><b>Name:</b></div><div style="float:right;width:110px;" title="' + rd.name + '">' + BRWidgets._crop(rd.name, 22) + '</div><div style="clear:both;"></div>';
            if (rd.bucket) f += '<div style="float:left;width:30px;"><b>Bucket:</b></div><div style="float:right;width:110px;">' + rd.bucket + '</div><div style="clear:both;"></div>';
            if (rd.len) f += '<div style="float:left;width:30px;"><b>Length:</b></div><div style="float:right;width:110px;">' + rd.len + '</div><div style="clear:both;"></div>';
            if (rd.size) f += '<div style="float:left;width:30px;"><b>Size:</b></div><div style="float:right;width:110px;">' + size(rd.size) + '</div><div style="clear:both;"></div>';
            $j("#" + id + "_fields").html(f)
        },
        width: 175
    });

    function unselected() {
        $j("#" + id + "_selected").text("No file selected").attr("title", "");
        $j("#" + id + "_download").button("disable");
        $j("#" + id + "_delete").button("disable");
        $j("#" + id + "_fields").html("")
    }
    unselected();

    function modified() {
        BRWidgets._modified($j(selector).prev().data("key", id + "_files"))
    }
    function cb(h) {
        if (h.attr === undefined && h.value === undefined) {
            if (t.jqGrid("getGridParam", "selrow") == h.id) unselected();
            t.jqGrid("delRowData", h.id);
            modified()
        } else {
            if (!t.jqGrid("getInd", h.id)) {
                t.jqGrid("addRowData", h.id, {
                    name: "-"
                });
                modified()
            }
            var tmp;
            switch (h.attr) {
            case "url":
                t.jqGrid("setRowData", h.id, {
                    url: h.value
                });
                break;
            case "name":
                t.jqGrid("setRowData", h.id, {
                    name: h.value
                });
                break;
            case "bucket":
                t.jqGrid("setRowData", h.id, {
                    bucket: h.value
                });
                break;
            case "length":
                t.jqGrid("setRowData", h.id, {
                    len: h.value
                });
                break;
            case "size":
                t.jqGrid("setRowData", h.id, {
                    size: h.value
                });
                break;
            case "slideshow_pages":
                switch (h.value) {
                case undefined:
                    tmp = "???";
                    break;
                case "-1":
                    tmp = '<img src="' + BR._api.get_host("cdn") + '/cdn/v1/c/img/arrows_spinner.gif" alt="Loading...">';
                    break;
                case "0":
                    tmp = "<em>None</em>";
                    break;
                default:
                    tmp = h.value
                };
                t.jqGrid("setRowData", h.id, {
                    pages: tmp
                });
                break;
            default:
                return
            }
            modified()
        }
    }
    BRDashboard.subscribe(cb, "media_files")
},
commonSelectedHandler: function (selector, data) {
    var $j = jQuery;
    var sel = BRDashboard.selectedListeners.length;
    var text = ".";
    var t;
    switch (sel) {
    case 0:
        text = "No participants selected";
        break;
    case 1:
        if (t = BRDashboard.listener_data[data.id]) {
            text = t.name;
            break
        }
    default:
        text = sel.toString(10) + " participants selected"
    }
    $j("#" + selector).text(text);
    return sel
},
dialerMonitor: function (root_selector, selector) {
    var id = BRWidgets.nextId();
    var $j = jQuery;

    function modified() {
        BRWidgets._modified(jQuery(root_selector).prev().data("key", id + "_dialpad"))
    }
    var text = function (msg, token, color) {
            var elem_id = "#" + id + "_" + token + "_status";
            var elem = $j(elem_id);
            if (!elem.length) return;
            elem.html(msg);
            if (color) {
                elem.css("color", color)
            }
        };
    var d = jQuery(selector);
    var timers = [];

    function cb(data) {
        if (!data.token) return;

        function remove(token) {
            jQuery("#" + id + "_" + token + "_line").remove()
        }
        function newLine(token, state, full_number, sec) {
            modified();
            var html = '<div id="' + id + "_" + token + '_line"><button id="' + id + "_" + token + '_hup" title="Hangup"><i class="icon icon2-cancel-circled" stle="color: red;"></i></button><button disabled><span id="' + id + "_" + token + '_status"></span></button></div>';
            d.append(html);
            var l = d.find("#" + id + "_" + token + "_line");
            l.find("button").button();
            l.find(".ui-button-text").css({
                "padding-left": "4px",
                "padding-right": "4px"
            });
            d.find("#" + id + "_" + token + "_hup").click(function () {
                BRCommands.fsHup(token)
            }).removeClass("ui-corner-all").addClass("ui-corner-left").css("margin-right", "0").next().removeClass("ui-corner-all").addClass("ui-corner-right").css("opacity", ".75").css("margin-left", "0");
            text(state + " " + full_number + " <b>" + sec + "</b>", token);
            timers[token] = setInterval(function () {
                text(state + " " + full_number + " <b>" + --sec + "</b>", token);
                if (sec <= 0 && timers[token]) {
                    clearInterval(timers[token]);
                    timers[token] = null;
                    remove(token)
                }
            }, 1e3)
        }
        function clearLine(token, linger) {
            if (timers[token]) {
                clearInterval(timers[token]);
                timers[token] = null
            }
            jQuery("#" + id + "_" + token + "_spinner").html("");
            if (linger) setTimeout(function () {
                remove(token)
            }, linger);
            else remove(token)
        }
        switch (data.state) {
        case "dialing":
            newLine(data.token, "Dial", data.full_number, data.timeout_seconds || 30);
            break;
        case "calling":
            clearLine(data.token, 0);
            newLine(data.token, "Ring", data.full_number, data.timeout_seconds || 30);
            break;
        case "connected":
            text("<b>Connected</b>", data.token, "lime");
            clearLine(data.token, 500);
            break;
        case "cancelled":
            text("<b>Cancelled</b>", data.token, "red");
            clearLine(data.token, 500);
            break
        }
    }
    BRDashboard.subscribe(cb, "dialer")
},
breakoutGroups: function (id, selector) {
    var $j = jQuery;
    $j(selector).find("button").button();
    $j("#" + id + "_move_button").click(function () {
        BRCommands.moveToRoom("delete-me", $(id + "_move"))
    });
    $j("#" + id + "_break_button").click(function () {
        BRCommands.breakOut("delete-me", $(id + "_break"))
    });
    $j("#" + id + "_return_button").click(function () {
        BRCommands.dissolveRooms("delete-me")
    });
    BRWidgets.styleSelect($j("#" + id + "_move"));
    BRWidgets.styleSelect($j("#" + id + "_break"));

    function disenable(count) {
        if (count > 0) {
            $j("#" + id + "_move_button").button("enable");
            BRWidgets.enableSelect($j("#" + id + "_move"), true)
        } else {
            $j("#" + id + "_move_button").button("disable");
            BRWidgets.enableSelect($j("#" + id + "_move"), false)
        }
        if (count > 1) {
            $j("#" + id + "_break_button").button("enable");
            BRWidgets.enableSelect($j("#" + id + "_break"), true)
        } else {
            $j("#" + id + "_break_button").button("disable");
            BRWidgets.enableSelect($j("#" + id + "_break"), false)
        }
    }
    function select(data) {
        disenable(BRWidgets.commonSelectedHandler(id + "_selected", data))
    }
    BRDashboard.subscribe(select, "select_listener");
    select({})
},
dialpad: function (id, selector) {
    var $j = jQuery,
        kp = $j("#" + id + "_keypad", selector),
        ip = kp.find('input[name="number"]');

    function dial() {
        var num = "+" + ip.val();
        BR._api.addParticipant(BRUtils.conferencePath(), {
            name: num
        }, function (e, d) {
            if (e || !d || !d.user || !d.user.id || !d.user.name) return alert(e || "Error creating user for dialout");
            var token = BRUtils.makeDialToken(),
                to = 20;
            BRDashboard.fire({
                type: "dialer",
                state: "dialing",
                full_number: num,
                token: token,
                timeout_seconds: to
            });
            BRUtils.waitForPin(d.user.id, token, to * 1e3, function (pin) {
                if (pin) BRCommands.fsDialout(pin, num, d.user.name, token)
            })
        })
    }
    function dtmf(c) {
        BRCommands.gue("dtmf", c)
    }
    function valid(c) {
        return /^[\d#\*]$/.exec(c) !== null
    }
    kp.find("button").button().click(function (e) {
        var c = $j(this).val();
        switch (c) {
        case "T":
            dial();
            break;
        case "D":
            dtmf(null);
            ip.val(ip.val().substr(0, ip.val().length - 1));
            break;
        case "C":
            dtmf(null);
            ip.val("1");
            break;
        default:
            ip.val(ip.val() + c);
            dtmf(c)
        }
    });
    ip.keypress(function (e) {
        var c = String.fromCharCode(e.charCode);
        if (!valid(c)) return false;
        return true
    }).bind("paste", function (e) {
        setTimeout(function () {
            var o = ip.val(),
                n = "";
            for (var i = 0; i < o.length; i++) if (valid(o.charAt(i))) n += o.charAt(i);
            ip.val(n)
        }, 0)
    });
    BRWidgets.dialerMonitor(selector, "#call_monitor");

    function updateDialpadAccess() {
        var verb = "disable",
            json = null;
        if (BR._api.context.is_host) {
            verb = "enable"
        } else {
            json = BRDashboard.conference_access_config;
            if (json && json.participants_can_call) verb = "enable"
        }
        $j("#" + id + "_talk").button(verb)
    }
    BRDashboard.subscribe(function (o) {
        if (o.idx !== BR._api.context.conference_id || o.attr !== "access_config") return;
        updateDialpadAccess()
    }, "conferences");
    BRDashboard.subscribe(updateDialpadAccess, "room_context");
    updateDialpadAccess()
},
controls: function (id, selector) {
    var $j = jQuery;
    $j(selector).find("button").button();
    $j("#" + id + "_volume_in").slider({
        min: -4,
        max: 4,
        step: 1,
        slide: function (event, ui) {
            BRCommands.conferenceSelectedAction("volume_in " + ui.value);
            $j("#" + id + "_volume_in_level").val(ui.value);
            $j("#" + id + "_volume_in_level_text").text($j("#" + id + "_volume_in_level option:selected").text())
        }
    });
    $j("#" + id + "_volume_in_level").change(function () {
        var level = $j("#" + id + "_volume_in_level").val();
        BRCommands.conferenceSelectedAction("volume_in " + level);
        $j("#" + id + "_volume_in").slider("value", level)
    });

    function end_call() {
        if (confirm("The conference will end and all callers will be disconnected. Press OK to confirm.")) {
            BRCommands.conferenceAction("hup all")
        }
    }
    $j("#" + id + "_mute").button("option", "label", '<i class="pull-left icon2-mic-off"></i> Mute').click(function () {
        BRCommands.conferenceSelectedAction("mute")
    });
    $j("#" + id + "_unmute").button("option", "label", '<i class="pull-left icon2-mic-1"></i> Unmute').click(function () {
        BRCommands.conferenceSelectedAction("unmute")
    });
    $j("#" + id + "_pa").click(function () {
        BRCommands.conferenceSelectedAction("pa")
    });
    $j("#" + id + "_unpa").click(function () {
        BRCommands.conferenceSelectedAction("unpa")
    });
    $j("#" + id + "_drop").button("option", "icons", {
        primary: "ui-icon-cancel"
    }).click(function () {
        BRCommands.conferenceSelectedAction("hup")
    });
    $j("#" + id + "_lock").button("option", "icons", {
        primary: "ui-icon-locked"
    }).click(function () {
        BRCommands.conferenceAction("lock")
    });
    $j("#" + id + "_unlock").button("option", "icons", {
        primary: "ui-icon-unlocked"
    }).click(function () {
        BRCommands.conferenceAction("unlock")
    }).css("display", "none");
    $j("#" + id + "_start_recording").button("option", "icons", {
        primary: "ui-icon-play"
    }).click(function () {
        BRCommands.conferenceAction("record")
    }).css("display", "none");
    $j("#" + id + "_stop_recording").button("option", "icons", {
        primary: "ui-icon-stop"
    }).click(function () {
        BRCommands.conferenceAction("norecord all")
    });
    $j("#" + id + "_end_call").button("option", "icons", {
        primary: "ui-icon-power"
    }).click(end_call);

    function disenable(enable) {
        var disen = enable ? "enable" : "disable";
        var p = $j("#" + id + "_selected").parent();
        p.find("button").button(disen);
        $j("#" + id + "_volume_in").slider(disen)
    }
    function select(data) {
        disenable(BRWidgets.commonSelectedHandler(id + "_selected", data) > 0)
    }
    BRDashboard.subscribe(select, "select_listener");
    BRDashboard.subscribe(function (o) {
        if (o.on_if_defined == undefined) {
            $j("#" + id + "_lock").css("display", "block");
            $j("#" + id + "_unlock").css("display", "none")
        } else {
            $j("#" + id + "_lock").css("display", "none");
            $j("#" + id + "_unlock").css("display", "block")
        }
    }, "lock");
    BRDashboard.subscribe(function (o) {
        if (o.on_if_defined == undefined) {
            $j("#" + id + "_recording").text("Not recording");
            $j("#" + id + "_start_recording").css("display", "block");
            $j("#" + id + "_stop_recording").css("display", "none")
        } else {
            $j("#" + id + "_recording").text("Recording");
            $j("#" + id + "_start_recording").css("display", "none");
            $j("#" + id + "_stop_recording").css("display", "block")
        }
    }, "recording");
    select({})
},
polling_depreciated: function (id, selector) {
    var $j = jQuery;

    function modified() {
        BRWidgets._modified($j(selector).prev().data("key", id + "_polling"))
    }
    function clear() {
        var len = BRDashboard.listeners.length;
        for (var i = 0; i < len; i++) {
            BRDashboard.fire({
                type: "listener",
                command: "attr",
                mid: BRDashboard.listeners[i],
                attrs: {
                    poll: ""
                }
            })
        }
    }
    $j("#" + id + "_clear_button").button({
        icons: {
            primary: "ui-icon-cancel"
        }
    }).click(clear);

    function update() {
        var votes = {};

        function inc(idx) {
            if (votes[idx]) votes[idx]++;
            else votes[idx] = 1
        }
        function cell(key, idx, value) {
            var elem = $j("#" + id + "_" + key + "_" + idx);
            var old_value = elem.text();
            if (value == old_value) return;
            elem.text(value)
        }
        for (var i in BRDashboard.listener_data) {
            var l = BRDashboard.listener_data[i];
            var v = parseInt(l.poll, 10);
            if (isNaN(v)) v = 0;
            if (v > 0 && v < 10) {
                inc(v);
                inc(10)
            } else {
                inc(0)
            }
        }
        var no_vote = votes[0] ? votes[0] : 0;
        var voted = votes[10] ? votes[10] : 0;
        var total = no_vote + voted;
        for (var i = 0; i < 11; i++) {
            if (votes[i]) {
                if (i > 0 && i < 10 && !$j("#" + id + "_t_" + i).hasClass("bold-text")) $j("#" + id + "_t_" + i).addClass("bold-text");
                cell("c", i, votes[i]);
                cell("p", i, Math.round(votes[i] * 100 / total) + "%");
                if (voted && i) cell("v", i, Math.round(votes[i] * 100 / voted) + "%");
                else cell("v", i, "")
            } else {
                if ($j("#" + id + "_t_" + i).hasClass("bold-text")) $j("#" + id + "_t_" + i).removeClass("bold-text");
                cell("c", i, "");
                cell("p", i, "");
                cell("v", i, "")
            }
        }
    }
    BRDashboard.subscribe(update, "listener");
    clear()
},
polling: function (id, selector) {
    var $j = jQuery,
        distinct_voters = {},
        ballot_boxes = {},
        changed = {};
    $j("#" + id + "_clear_button").button({
        icons: {
            primary: "ui-icon-cancel"
        }
    }).click(clear);

    function modified() {
        BRWidgets._modified($j(selector).prev().data("key", id + "_polling"))