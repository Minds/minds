if (typeof JSON !== "object") {
    JSON = {}
}!
function () {
    "use strict";

    function f(n) {
        return n < 10 ? "0" + n : n
    }
    if (typeof Date.prototype.toJSON !== "function") {
        Date.prototype.toJSON = function (key) {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
        };
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function (key) {
            return this.valueOf()
        }
    }
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap, indent, meta = {
            "\b": "\\b",
            "	": "\\t",
            "\n": "\\n",
            "\f": "\\f",
            "\r": "\\r",
            '"': '\\"',
            "\\": "\\\\"
        },
        rep;

    function quote(string) {
        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === "string" ? c : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + string + '"'
    }
    function str(key, holder) {
        var i, k, v, length, mind = gap,
            partial, value = holder[key];
        if (value && typeof value === "object" && typeof value.toJSON === "function") {
            value = value.toJSON(key)
        }
        if (typeof rep === "function") {
            value = rep.call(holder, key, value)
        }
        switch (typeof value) {
        case "string":
            return quote(value);
        case "number":
            return isFinite(value) ? String(value) : "null";
        case "boolean":
        case "null":
            return String(value);
        case "object":
            if (!value) {
                return "null"
            }
            gap += indent;
            partial = [];
            if (Object.prototype.toString.apply(value) === "[object Array]") {
                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || "null"
                }
                v = partial.length === 0 ? "[]" : gap ? "[\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "]" : "[" + partial.join(",") + "]";
                gap = mind;
                return v
            }
            if (rep && typeof rep === "object") {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === "string") {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ": " : ":") + v)
                        }
                    }
                }
            } else {
                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ": " : ":") + v)
                        }
                    }
                }
            }
            v = partial.length === 0 ? "{}" : gap ? "{\n" + gap + partial.join(",\n" + gap) + "\n" + mind + "}" : "{" + partial.join(",") + "}";
            gap = mind;
            return v
        }
    }
    if (typeof JSON.stringify !== "function") {
        JSON.stringify = function (value, replacer, space) {
            var i;
            gap = "";
            indent = "";
            if (typeof space === "number") {
                for (i = 0; i < space; i += 1) {
                    indent += " "
                }
            } else if (typeof space === "string") {
                indent = space
            }
            rep = replacer;
            if (replacer && typeof replacer !== "function" && (typeof replacer !== "object" || typeof replacer.length !== "number")) {
                throw new Error("JSON.stringify")
            }
            return str("", {
                "": value
            })
        }
    }
    if (typeof JSON.parse !== "function") {
        JSON.parse = function (text, reviver) {
            var j;

            function walk(holder, key) {
                var k, v, value = holder[key];
                if (value && typeof value === "object") {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v
                            } else {
                                delete value[k]
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value)
            }
            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
                j = eval("(" + text + ")");
                return typeof reviver === "function" ? walk({
                    "": j
                }, "") : j
            }
            throw new SyntaxError("JSON.parse")
        }
    }
}();
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement) {
        "use strict";
        if (this == null) {
            throw new TypeError
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1
        }
        var n = 0;
        if (arguments.length > 1) {
            n = Number(arguments[1]);
            if (n != n) {
                n = 0
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n))
            }
        }
        if (n >= len) {
            return -1
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k
            }
        }
        return -1
    }
}!
function () {
    typeof window.BR === "undefined" ? window.BR = {
        v1: {}
    } : typeof window.BR.v1 === "undefined" && (window.BR.v1 = {})
}();
!
function () {
    var _xhr_func = null;

    function XHR() {
        this.options = null
    }
    function _jquery_ajax(verb, url, data, fn) {}
    function _xhr(verb, url, data, fn) {
        var x = new XMLHttpRequest;
        if (false) {
            if (verb !== "GET" && verb != "POST") {
                x.setRequestHeader("X-HTTP-Method-Override", verb);
                verb = "POST"
            }
        }
        if (x === null) return fn("No XHR Support");
        try {
            x.open(verb, url, true)
        } catch (e) {
            fn(e, null)
        }
        x.withCredentials = true;
        if (data) x.setRequestHeader("Content-Type", "application/json");
        x.onreadystatechange = function (data) {
            if (x.readyState == 4) {
                var r = null;
                if (x.status != 200) return fn("Remote Error");
                try {
                    r = JSON.parse(x.responseText)
                } catch (e) {
                    return fn("Protocol Error")
                }
                if (r.error) return fn("Remote Error: " + (r.error.text || ""));
                fn(null, r)
            }
        };
        if (data) x.send(JSON.stringify(data));
        else x.send();
        return true
    }
    function _ms_XDomainRequest_no_cookies(verb, url, data, fn) {
        var x = new XDomainRequest;
        x.onload = function () {
            var r = null;
            try {
                r = JSON.parse(x.responseText)
            } catch (e) {
                return fn("Protocol Error")
            }
            fn(null, r)
        };
        x.onerror = function () {
            fn("Error")
        };
        x.onprogress = function () {};
        x.ontimeout = function () {
            fn("Timeout")
        };
        x.open(verb, url);
        if (data) x.send(JSON.stringify(data));
        else x.send()
    }
    function xhr_func() {
        if (_xhr_func) return _xhr_func;
        _xhr_func = function (a, b, c, fn) {
            console.log("No XHR");
            fn("No XHR Support")
        };
        if (false);
        else if (window.XDomainRequest) _xhr_func = _ms_XDomainRequest_no_cookies;
        else if (window.XMLHttpRequest) _xhr_func = _xhr;
        return _xhr_func
    }
    XHR.prototype.init = function (options) {
        this.options = options;
        return this
    };
    XHR.prototype.request = function (verb, url, data, fn) {
        return xhr_func().call(this, verb, url, data, fn)
    };
    window.BR.v1.XHR = function () {
        return new XHR
    }
}();
!
function () {
    var scripts = document.getElementsByTagName("script"),
        src_of_this_src = scripts[scripts.length - 1].src;
    var countries = [{
        code: 1,
        name: "United States"
    }, {
        code: 212,
        name: "Morocco"
    }, {
        code: 33,
        name: "France"
    }, {
        code: 34,
        name: "Spain"
    }, {
        code: 351,
        name: "Portugal"
    }, {
        code: 353,
        name: "Ireland"
    }, {
        code: 39,
        name: "Italy"
    }, {
        code: 44,
        name: "United Kingdom"
    }, {
        code: 49,
        name: "Germany"
    }];
    var config_map = {
        dev: {
            hosts: {
                api: "//api-dev.babelroom.com",
                my: "//my-dev.babelroom.com",
                myapi: "//myapi-dev.babelroom.com",
                live: "//live-dev.babelroom.com",
                page: "//dev.babelroom.com",
                cdn: "//cdn-dev.babelroom.com",
                home: "//staging.babelroom.com",
                video: "//video.babelroom.com:1936"
            }
        },
        staging: {
            hosts: {
                api: "//api-staging.babelroom.com",
                my: "//my-staging.babelroom.com",
                myapi: "//myapi-staging.babelroom.com",
                live: "//live-staging.babelroom.com",
                page: "//rooms-staging.babelroom.com",
                cdn: "//cdn-staging.babelroom.com",
                home: "//staging.babelroom.com",
                video: "//video.babelroom.com:1936"
            }
        },
        prod: {
            hosts: {
                api: "//api.babelroom.com",
                my: "//my.babelroom.com",
                myapi: "//myapi.babelroom.com",
                live: "//live.babelroom.com",
                page: "//bblr.co",
                cdn: "//cdn.babelroom.com",
                home: "//babelroom.com",
                video: "//video.babelroom.com:1936"
            }
        }
    };

    function API() {
        this.options = null;
        this.hosts = null;
        this.stream_channel = undefined;
        this.xhr_channel = undefined;
        this.commands = null;
        this.notify = null;
        this.context = {}
    }
    function _open_stream(fn) {
        if (typeof this.stream_channel !== "undefined") return fn("Stream already opened");
        if (!this.options.streamFactory) return fn("No stream factory");
        this.stream_channel = this.options.streamFactory();
        if (!this.stream_channel) return fn("Failed top open stream");
        this.stream_channel.connect(_get_host.call(this, "live"), fn)
    }
    function _non_stream_call(verb, path, data, fn) {
        if (typeof this.xhr_channel === "undefined") this.xhr_channel = BR.v1.XHR();
        if (!this.xhr_channel) return fn("No communication channel");
        return this.xhr_channel.request(verb, _get_host.call(this, "api") + path, data, fn)
    }
    function _form_call(verb, path, data) {
        var frm = document.getElementById(this.options._dynamic_form_id);
        while (frm.hasChildNodes()) {
            frm.removeChild(frm.lastChild)
        }
        frm.setAttribute("method", "POST");

        function add_input(name, type, value) {
            var e = document.createElement("input");
            e.setAttribute("name", name);
            e.setAttribute("type", type);
            e.setAttribute("value", value);
            frm.appendChild(e)
        }
        add_input("_dynform_method", "hidden", verb);
        add_input("_dynform_path", "hidden", path);
        for (var i in data) if (data.hasOwnProperty(i) && typeof data[i] === "string") add_input(i, "text", data[i]);
        frm.submit()
    }
    function _canonify_cookie_call_options(options) {
        options = options || {};
        if (!options.success_url) options.success_url = window.location.href;
        if (!options.failure_url) options.failure_url = options.success_url;
        if (!options.fnerror) options.fnerror = function (e) {};
        return options
    }
    function _cookie_call(verb, path, data, options, fn) {
        if (!data) data = {};
        data._success_url = options.success_url;
        data._failure_url = options.failure_url;
        return _form_call.call(this, verb, path, data)
    }
    function _cookie_call_response(is_error, e, options) {
        if (!is_error) {
            if (options.fnerror) fnerror(e);
            else window.location.href = options.failure_url
        } else window.location.href = options.success_url
    }
    function _call(verb, path, data, fn) {
        if (this.stream_channel) {
            this.stream_channel.api_call({
                verb: verb,
                path: path,
                data: data
            }, function (e, d) {
                return fn(e, !e && d ? JSON.parse(d) : null)
            });
            return
        }
        return _non_stream_call.call(this, verb, path, data, fn)
    }
    function _parseQueryString(qs) {
        var dict = {},
            match, pl = /\+/g,
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) {
                return decodeURIComponent(s.replace(pl, " "))
            };
        while (match = search.exec(qs.substring(1))) dict[decode(match[1])] = decode(match[2]);
        return dict
    }
    function _orm_shallow_copy(obj) {
        var new_obj = {};
        for (var i in obj) if (obj.hasOwnProperty(i) && i[0] != "_") new_obj[i] = obj[i];
        return new_obj
    }
    function _get_host(name) {
        return this.hosts[name]
    }
    function _addStreamCredential(_this, path, token, fn) {
        _initControllers(_this);

        function stream_authenticate() {
            _this.stream_channel.add_conference_credential(path, token, function (e, d) {
                if (e || !d) return fn(e || "unexpected response");
                fn(e, d)
            })
        }
        if (_this.stream_channel) stream_authenticate();
        else return _open_stream.call(_this, function (error) {
            if (error) return fn(error);
            stream_authenticate()
        })
    }
    function _model(model, arg, fn) {
        var id = 0;
        var qs = "";
        switch (typeof arg) {
        case "number":
            id = arg;
            break;
        case "object":
            id = arg.id;
            if (arg.columns) qs = "?c=" + arg.columns.join(",");
            break;
        default:
            return fn("bad args", null)
        }
        return _call.call(this, "GET", "/api/v1/" + model + "/" + id, null, function (e, d) {
            if (d && d.data) {
                var copy = _orm_shallow_copy(d.data);
                copy._ = {
                    id: id,
                    model: model,
                    orig: d.data
                };
                return fn(e, copy)
            } else return fn(e ? e : "unexpected enveloped format", null)
        })
    }
    function _update(obj, fn) {
        if (typeof obj !== "object" || !obj._ || !obj._.id || !obj._.model || !obj._.orig) return fn("corrupt API record", null);
        var to_save = {};
        var found = false;
        for (var i in obj) if (obj.hasOwnProperty(i) && i[0] != "_" && obj._.orig[i] !== obj[i]) {
            to_save[i] = obj[i];
            found = true
        }
        if (!found) return fn(null, {});
        else return _call.call(this, "PUT", "/api/v1/" + obj._.model + "/" + obj._.id, to_save, function (e, d) {
            if (!e && d) obj._.orig = _orm_shallow_copy(obj);
            fn(e, d)
        })
    }
    function _streamStart(self) {
        var o = self.options;
        _addStreamCredential(self, "/i/" + o.conference_id, o.authentication.token, function (e, d) {
            if (e || !d) return o.onError(e || "Error authorizing stream");
            self.context = d;
            self.commands.start(self, o.onError)
        })
    }
    function _topLevelCopy(o, n) { 
        for (var v in o) if (o.hasOwnProperty(v)) if (typeof n[v] === "undefined") n[v] = o[v]
    }
    function _initControllers(self) { 
        var oc = self.options.controllers; 
        if (!oc) return;
        for (var i = 0; i < oc.length; i++) {
            var c = oc[i],
                dc = BR.v1.controllers[c.type];
            if (!dc) continue;
            c._default = dc;
            _topLevelCopy(dc, c);
            c._api = self;
            c._onInit && c._onInit();
            c.onInit && c.onInit()
        }
    }
    function _init(self, args) {
        var subdomain = src_of_this_src.match(/^(?:http:|https:)\/\/([a-z0-9]+(?:-[a-z0-9]+)*)\.babelroom.com\//i);
        var _config = subdomain && subdomain[1].match("dev") ? config_map.dev : config_map.prod;
        var hosts = null;
        self.defaults = {
            controllers: [],
            onError: function (msg) {
                console && console.log && console.log(msg)
            },
            streamFactory: window.BR.v1.SIO,
            _: 0
        };
        self.options = {};
        var logicContainer = window.BR.v1.logic && window.BR.v1.logic.create();
        if (logicContainer) {
            self.commands = logicContainer.commands;
            self.notify = logicContainer.notify
        }
        _topLevelCopy(self.defaults, self.options);
        if (typeof args === "object") {
            if (typeof args["query_string"] === "string") {
                _topLevelCopy(_parseQueryString(args["query_string"]), args);
                delete args["query_string"]
            }
            for (var key in args) if (args.hasOwnProperty(key)) {
                var val = args[key];
                switch (key) {
                case "env":
                    _config = config_map[val];
                    break;
                case "hosts":
                    hosts = val;
                    break;
                default:
                    self.options[key] = val
                }
            }
        }
        self.hosts = {};
        for (var i in _config.hosts) if (_config.hosts.hasOwnProperty(i)) self.hosts[i] = hosts !== null ? typeof hosts === "object" ? typeof hosts[i] !== "undefined" ? hosts[i] : _config.hosts[i] : hosts : _config.hosts[i];
        return self
    }
    API.prototype.get_host = function (name) {
        return _get_host.call(this, name)
    };
    API.prototype.get_countries = function (fn) {
        fn(null, countries);
        return this
    };
    API.prototype.stream = function (get_or_set_stream) {
        if (typeof get_or_set_stream === "undefined") return this.stream_channel;
        this.stream_channel = get_or_set_stream;
        return this.stream_channel
    };
    API.prototype.addControllers = function (arg) {
        var self = this;

        function addController(c) {
            self.options.controllers.push(c)
        }
        if (arg instanceof Array) {
            for (var i = 0; i < arg.length; i++) addController(arg[i])
        } else addController(arg)
    };
    API.prototype.addStreamCredential = function (path, token, fn) {
        return _addStreamCredential(this, path, token, fn)
    };
    API.prototype.start = function () {
        _streamStart(this);
        return this
    };
    API.prototype.login = function (data, options) {
        options = _canonify_cookie_call_options(options);
        return _cookie_call.call(this, "POST", "/api/v1/login", data, options, function (e, d) {
            _cookie_call_response(e || !d, e, options)
        })
    };
    API.prototype.logout = function (options) {
        options = _canonify_cookie_call_options(options);
        return _cookie_call.call(this, "DELETE", "/api/v1/login", null, options, function (e, d) {
            _cookie_call_response(e, e, options)
        })
    };
    API.prototype.addSelf = function (path, data, options) {
        options = _canonify_cookie_call_options(options);
        return _cookie_call.call(this, "POST", "/api/v1/add_self" + path, data, options, function (e, d) {
            _cookie_call_response(e || !d, e || "Missing response data", options)
        })
    };
    API.prototype.currentUser = function (fn) {
        return _call.call(this, "GET", "/api/v1/login", null, function (e, d) {
            fn(e, d ? d.user : null)
        })
    };
    API.prototype.addParticipant = function (path, data, fn) {
        return _call.call(this, "POST", "/api/v1/add_participant" + path, data, fn)
    };
    API.prototype.users = function (arg, fn) {
        return _model.call(this, "users", arg, fn)
    };
    API.prototype.update = function (obj, fn) {
        return _update.call(this, obj, fn)
    };
    API.prototype.conferences = function (arg, fn) {
        return _model.call(this, "conferences", arg, fn)
    };
    API.prototype._aq = function (data, fn) {
        return _call.call(this, "POST", "/api/v1/_aq", data, fn)
    };
    window.BR.v1.api = {
        create: function (opts) {
            return _init(new API, opts)
        }
    }
}();
!
function () {
    function SIO() {
        this._socket = null;
        this.options = null
    }
    SIO.prototype.init = function (options) {
        this.options = options;
        return this
    };
    SIO.prototype.connect = function (url, fn) {
        var _this = this;
        if (typeof io === "undefined") fn("socket io not present or not initialized");
        else if (!(this._socket = io.connect(url, {
            resource: "sio"
        }))) fn("socket io error connecting to [" + url + "]");
        else {
            this._socket.on("error", function (e) {
                fn(e)
            });
            this._socket.on("connect", function () {
                fn(null)
            })
        }
        return this
    };
    SIO.prototype.add_conference_credential = function (path, token, fn) {
        if (!this._socket) return fn("Not connected");
        this._socket.emit("br_add_conference_credential", JSON.stringify({
            path: path,
            token: token
        }), function (data) {
            if (!data) return fn("unexpected null response");
            if (data.error || !data.data) return fn(data.error || "unexpected null data", null);
            fn(null, data.data)
        })
    };
    SIO.prototype.attach = function (conference_id, connection_salt, user_id, fn) {
        if (!this._socket) return fn("Not connected");
        this._socket.on("message", function (data) {
            fn(null, data)
        });
        this._socket.emit("br_attach", conference_id + "?ld=" + connection_salt + "-" + user_id, function (data) {
            data || fn("Error attaching")
        })
    };
    SIO.prototype.put = function (data, fn) {
        if (!this._socket) return fn("Not connected");
        this._socket.emit("br_put", JSON.stringify(data));
        fn(null)
    };
    SIO.prototype.api_call = function (data, fn) {
        if (!this._socket) fn("Not connected");
        else {
            this._socket.emit("br_api", data, function (result) {
                fn(result ? null : "Error response from server", result)
            })
        }
        return this
    };
    SIO.prototype.destroy = function () {
        if (this._socket) this._socket.disconnect();
        this._socket = null;
        return this
    };
    typeof window.BR === "undefined" ? window.BR = {
        v1: {}
    } : typeof window.BR.v1 === "undefined" && (window.BR.v1 = {});
    window.BR.v1.SIO = function () {
        return typeof io["connect"] === "undefined" ? null : new SIO
    }
}();
!
function () {
    var LogicContainer = function () {
            var br_room_context = null;
            var BRCommands = {
                cid: "",
                last_ts: undefined,
                _stream: null,
                _memberKV: function (idx, value, attr) {
                    switch (attr) {
                    case undefined:
                        BRDashboard.fire({
                            type: "listener",
                            command: value == undefined ? "del" : "add",
                            mid: idx
                        });
                        break;
                    case "mute":
                    case "pa":
                    case "deaf":
                        var o = {};
                        o[attr] = typeof value === "undefined" ? false : true;
                        BRDashboard.fire({
                            type: "listener",
                            command: "attr",
                            mid: idx,
                            attrs: o
                        });
                        break;
                    case "group":
                        if (value == "0") value = "";
                    case "name":
                    case "callerid":
                    case "dialout":
                    case "role":
                    case "poll":
                    case "user_id":
                        var o = {};
                        o[attr] = value;
                        BRDashboard.fire({
                            type: "listener",
                            command: "attr",
                            mid: idx,
                            attrs: o
                        });
                        break;
                    default:
                    }
                },
                _connectionKV: function (id, attr, value) {
                    if (typeof value !== "undefined" || attr) {
                        if (attr) {
                            if (/^video-(\d+)-(.*)$/.exec(attr)) {
                                var uid = RegExp.$1,
                                    rest = RegExp.$2;
                                BRDashboard.fire({
                                    type: "video",
                                    connection_id: id,
                                    user_id: uid,
                                    subkey: rest,
                                    data: value
                                })
                            }
                        } else {
                            if (/^([^-]+)-([^-]+)-(\d+)$/.exec(value)) {
                                var el = RegExp.$1,
                                    cs = RegExp.$2,
                                    uid = RegExp.$3;
                                if (cs == br_room_context.connection_salt) {
                                    BRDashboard.connection_id = id;
                                    BRDashboard.updateRoomContext("is_live", true)
                                }
                                BRDashboard.fire({
                                    type: "online",
                                    command: "mod",
                                    connection_id: id,
                                    estream_label: el,
                                    connection_salt: cs,
                                    user_id: uid
                                })
                            }
                        }
                    } else {
                        BRDashboard.fire({
                            type: "online",
                            command: "del",
                            connection_id: id
                        })
                    }
                },
                _dispatchKV: function (str, ts) {
                    if (str.length < 1) return;
                    var key;
                    var value;
                    if (str.match(/^([^:]*):\s*(.*)$/)) {
                        key = RegExp.$1;
                        value = RegExp.$2
                    } else {
                        key = str;
                        value = undefined
                    }
                    var verb = id = idx = attr = undefined;
                    if (key.match(/^([^-]*)-(.*)$/)) {
                        verb = RegExp.$1;
                        var tmp = RegExp.$2;
                        if (tmp.match(/^([^-]*)-(.*)$/)) {
                            id = RegExp.$1;
                            attr = RegExp.$2
                        } else {
                            id = tmp
                        }
                        var idx = parseInt(id, 10)
                    } else {
                        verb = key
                    }
                    switch (verb) {
                    case "_":
                        BRCommands._connectionKV(id, attr, value);
                        break;
                    case "chat":
                        BRDashboard.fire({
                            type: "chat",
                            ts: ts,
                            data: value
                        });
                        break;
                    case "member":
                        BRCommands._memberKV(idx, value, attr);
                        break;
                    case "talking":
                        BRDashboard.fire({
                            type: "talking",
                            mid: idx,
                            value: value
                        });
                        break;
                    case "lock":
                        BRDashboard.fire({
                            type: "lock",
                            on_if_defined: value
                        });
                        break;
                    case "recording":
                        BRDashboard.fire({
                            type: "recording",
                            on_if_defined: value
                        });
                        break;
                    case "slide":
                        BRDashboard.fire({
                            type: "slide",
                            variable: id,
                            value: value
                        });
                        break;
                    case "command":
                        BRDashboard.fire({
                            type: "command",
                            command: id,
                            value: value
                        });
                        break;
                    case "pin":
                        BRDashboard.fire({
                            type: "pin",
                            pin: id,
                            user_id: value
                        });
                        break;
                    case "gue":
                        BRDashboard.fire({
                            type: "gue",
                            id: id,
                            idx: idx,
                            attr: attr,
                            value: value
                        });
                        break;
                    case "media_files":
                    case "invitations":
                    case "users":
                    case "conferences":
                        BRDashboard.fire({
                            type: verb,
                            id: id,
                            idx: idx,
                            attr: attr,
                            value: value
                        });
                        break
                    }
                },
                _fire: function (data, ts) {
                    var ctrl = data.substring(0, 1);
                    var str = data.substring(1);
                    var arr;
                    switch (ctrl) {
                    case "C":
                        BRDashboard.fire({
                            type: "chat",
                            ts: ts,
                            data: str
                        });
                        break;
                    case "D":
                        break;
                    case "K":
                        BRDashboard.fire({
                            type: "_K",
                            ts: ts,
                            str: str
                        });
                        BRCommands._dispatchKV(str, ts);
                        arr = str.match(/^member-([^:-]+):\s?(\S+)$/);
                        if (arr && arr.length > 2) {
                            BRDashboard.fire({
                                type: "dialer",
                                state: "connected",
                                token: arr[2]
                            })
                        }
                        break;
                    case "R":
                        BRDashboard.fire({
                            type: "_R",
                            ts: ts,
                            str: str
                        });
                        break;
                    default:
                    }
                },
                dispatchData2: function (data) {
                    var ts = undefined;
                    if (/^([a-f0-9]+)(.*)$/.exec(data)) {
                        var tmp = RegExp.$1;
                        if (tmp.length > 4) {
                            BRCommands.last_ts = ts = parseInt(tmp, 16)
                        } else if (BRCommands.last_ts !== undefined) {
                            ts = BRCommands.last_ts + parseInt(tmp, 16)
                        }
                        if (ts !== undefined) ts *= 100;
                        data = RegExp.$2
                    }
                    BRCommands.dispatchData3(data, ts)
                },
                dispatchData3: function (data, ts) {
                    BRCommands._fire(data, ts)
                },
                updateProgress: function (str) {
                    BRDashboard.fire({
                        type: "log",
                        data: str
                    })
                },
                doPUT: function (queue, data, fn) {
                    console.log("depreciated", queue, data, fn)
                },
                put: function (template, args, fn) {
                    BRCommands._stream && BRCommands._stream.put({
                        cid: BRCommands.cid,
                        template: template,
                        args: args
                    }, fn)
                },
                sendChat: function (user_id, user_name, msg, fn) {
                    msg.user_id = user_id;
                    msg.user = user_name;
                    msg = JSON.stringify(msg);
                    BRCommands.put("chat", msg, fn)
                },
                conferenceAction: function (action) {
                    BRCommands.put("conferenceAction", action, function () {
                        BRCommands.updateProgress("Done: " + action)
                    })
                },
                conferenceIdsAction: function (ids, action) {
                    BRCommands.put("conferenceIdsAction", [ids, action], function () {
                        BRCommands.updateProgress("Done: " + action + " " + ids)
                    })
                },
                conferenceSelectedAction: function (action) {
                    ids = BRDashboard.selectedListeners;
                    BRCommands.conferenceIdsAction(ids, action)
                },
                fsDialout: function (pin, full_number, caller_id_name, token) {
                    var fs_number = full_number.replace(/^\+1/, "1").replace(/^\+/, "011");
                    var cmd = pin + ":" + fs_number + ":" + token + ":" + caller_id_name;
                    BRCommands.put("dialCmd", "O" + cmd, function () {
                        BRCommands.updateProgress("Originate done: " + cmd)
                    });
                    BRDashboard.fire({
                        type: "dialer",
                        state: "calling",
                        full_number: full_number,
                        pin: pin,
                        token: token,
                        timeout_seconds: 30
                    })
                },
                fsHup: function (token) {
                    var cmd = token + "::::";
                    BRCommands.put("dialCmd", "H" + cmd, function () {
                        BRCommands.updateProgress("hangup done: " + cmd)
                    });
                    BRDashboard.fire({
                        type: "dialer",
                        state: "cancelled",
                        token: token
                    })
                },
                videoAction: function (subkey, value) {
                    var cmd = "-" + subkey;
                    console.log();
                    if (typeof value !== "undefined") cmd += ":" + value;
                    BRCommands.put("video", {
                        connection_id: BRDashboard.connection_id,
                        uid: br_room_context.user_id,
                        cmd: cmd
                    }, function () {
                        BRCommands.updateProgress("Done - videoAction: " + cmd)
                    })
                },
                gue: function (attr, value) {
                    var sattr = attr ? "-" + attr : "",
                        svalue = value ? ": " + value : "";
                    var h = {
                        idx: br_room_context.user_id,
                        attr: sattr,
                        value: svalue
                    };
                    BRCommands.put("gue", h, function () {
                        BRCommands.updateProgress("Done - gue: " + h.toString())
                    })
                },
                slideAction: function (variable, value) {
                    var cmd = "";
                    if (variable) cmd += "-" + variable;
                    if (value) cmd += ": " + value;
                    BRCommands.put("slide", cmd, function () {
                        BRCommands.updateProgress("Done - slideAction: " + cmd)
                    })
                },
                commandAction: function (command) {
                    var cmd = "";
                    if (command) cmd += "-" + command;
                    cmd += ": " + (new Date).getTime();
                    BRCommands.put("command", cmd, function () {
                        BRCommands.updateProgress("Done - commandAction: " + cmd)
                    })
                },
                clearAction: function (key, attr) {
                    var cmd = key;
                    if (attr) cmd += "-" + attr;
                    BRCommands.put("clear", cmd, function () {
                        BRCommands.updateProgress("Done - clearAction: " + cmd)
                    })
                },
                _doMove: function (to_group, ids) {
                    BRCommands.put("move", [to_group, ids], function () {
                        BRCommands.updateProgress("Done: doMove " + ids)
                    })
                },
                dissolveRooms: function (depreciate_id) {
                    BRCommands._doMove(0, "all")
                },
                moveToRoom: function (depreciated_id, destRoomField) {
                    ids = BRDashboard.selectedListeners.join(" ");
                    BRCommands._doMove(destRoomField.value, ids)
                },
                breakOut: function (depreciate_id, groupField) {
                    var groupCode = groupField.value;
                    if (groupCode < 0) return;

                    function groups_of_x(size, index) {
                        var ways = Math.min(9, Math.round(size / groupCode));
                        if (ways == 0) ways = 1;
                        return index % ways + 1
                    }
                    function x_groups(size, index) {
                        var ways = Math.min(9, groupCode - 100);
                        return index % ways + 1
                    }
                    var fn = groupCode < 100 ? groups_of_x : x_groups;
                    var len = BRDashboard.selectedListeners.length;
                    var groups = {};
                    for (var idx = 0; idx < len; idx++) {
                        var id = BRDashboard.selectedListeners[idx];
                        var assignment = fn(len, idx);
                        if (!groups[assignment]) groups[assignment] = "";
                        groups[assignment] += id + " "
                    }
                    len = groups.length;
                    for (var idx in groups) {
                        BRCommands._doMove(idx, groups[idx])
                    }
                },
                start: function (api_obj, fnerror) {
                    br_room_context = api_obj.context;
                    BRCommands.cid = br_room_context.conference_estream_id;
                    BRDashboard.load();
                    BRCommands._stream = api_obj.stream();
                    BRCommands._stream.attach(BRCommands.cid, br_room_context.connection_salt, br_room_context.user_id, function (e, d) {
                        if (e) fnerror(e);
                        else BRCommands.dispatchData2(d)
                    })
                }
            };
            var BRDashboard = {
                user_map: {},
                invitees: {},
                invitee_id_by_user: {},
                listeners: [],
                listener_data: {},
                selectedListeners: [],
                online_2_user_map: {},
                boxes: {},
                connection_id: null,
                notification_thread_id: undefined,
                notification_dict: {},
                conference_access_config: {},
                list_pre: {},
                list: {},
                list_post: {},
                _subscribe: function (fn, type, priority) {
                    var l;
                    if (priority < 0) {
                        l = this.list_post
                    } else if (priority > 0) {
                        l = this.list_pre
                    } else l = this.list;
                    if (typeof l[type] === "undefined") {
                        l[type] = []
                    }
                    l[type].push(fn)
                },
                subscribe: function (fn, type) {
                    this._subscribe(fn, type, 0)
                },
                _unsubscribe: function (ll, fn) {
                    for (var key in ll) {
                        var l = ll[key],
                            i = l.indexOf(fn);
                        if (i > -1) l.splice(i, 1)
                    }
                },
                unsubscribe: function (fn) {
                    this._unsubscribe(this.list_pre, fn);
                    this._unsubscribe(this.list, fn);
                    this._unsubscribe(this.list_post, fn)
                },
                _fire: function (l, data) {
                    if (!l) return;
                    var max = l.length,
                        i, copy = [];
                    for (i = 0; i < max; i++) copy[i] = l[i];
                    for (i = 0; i < max; i++) copy[i](data)
                },
                fire: function (data) {
                    if (!data) return;
                    this._fire(this.list_pre[data.type], data);
                    this._fire(this.list[data.type], data);
                    this._fire(this.list_post[data.type], data)
                },
                ass: function (assertion) {},
                box: function (user_idx, key, attr, value) {
                    var user_id = user_idx;
                    switch (typeof user_idx) {
                    case "undefined":
                        return false;
                    case "number":
                        user_id = user_idx.toString();
                        break;
                    default:
                        user_idx = parseInt(user_idx, 10)
                    }
                    function presence_count(box) {
                        BRDashboard.ass(box._listeners >= 0);
                        BRDashboard.ass(box._online >= 0);
                        return box._listeners + box._online
                    }
                    var have_box = user_idx in BRDashboard.boxes;
                    var old_presence_count = 0;
                    var new_presence_count = 0;
                    var old_data = {};
                    if (have_box) {
                        var data = BRDashboard.boxes[user_idx];
                        for (var data_key in data) if (data.hasOwnProperty(data_key)) old_data[data_key] = data[data_key];
                        old_presence_count = presence_count(old_data)
                    }
                    if (typeof key === "undefined") {
                        if (have_box) {
                            BRDashboard.fire({
                                type: "box",
                                command: "del",
                                idx: user_idx,
                                id: user_id,
                                data: BRDashboard.boxes[user_idx],
                                old_data: old_data
                            });
                            delete BRDashboard.boxes[user_idx];
                            return
                        }
                    } else {
                        if (!have_box) BRDashboard.boxes[user_idx] = {
                            user_idx: user_idx,
                            user_id: user_id,
                            connection_ids: {},
                            mids: {},
                            _online: 0,
                            _listeners: 0
                        };
                        var b = BRDashboard.boxes[user_idx];
                        if (typeof value === "undefined") {
                            switch (key) {
                            case "connection_id":
                                if (typeof b.connection_ids[attr] !== "undefined") {
                                    delete b.connection_ids[attr];
                                    b._online--
                                };
                                break;
                            case "mid":
                                if (typeof b.mids[attr] !== "undefined") {
                                    delete b.mids[attr];
                                    b._listeners--
                                };
                                break;
                            default:
                                delete BRDashboard.boxes[user_idx][key]
                            }
                        } else {
                            switch (key) {
                            case "connection_id":
                                if (typeof b.connection_ids[attr] === "undefined") {
                                    b._online++
                                };
                                b.connection_ids[attr] = value;
                                break;
                            case "mid":
                                if (typeof b.mids[attr] === "undefined") {
                                    b._listeners++
                                };
                                b.mids[attr] = value;
                                break;
                            default:
                                BRDashboard.boxes[user_idx][key] = value
                            }
                        }
                        if (!have_box) BRDashboard.fire({
                            type: "box",
                            command: "add",
                            idx: user_idx,
                            id: user_id,
                            data: BRDashboard.boxes[user_idx],
                            old_data: old_data
                        })
                    }
                    if (user_idx in BRDashboard.boxes) {
                        new_presence_count = presence_count(BRDashboard.boxes[user_idx]);
                        BRDashboard.boxes[user_idx].presence_count = new_presence_count
                    }
                    var command = "update";
                    if (new_presence_count > 0 && old_presence_count == 0) command = "show";
                    else if (old_presence_count > 0 && new_presence_count == 0) command = "hide";
                    BRDashboard.fire({
                        type: "box",
                        command: command,
                        idx: user_idx,
                        id: user_id,
                        data: BRDashboard.boxes[user_idx],
                        old_data: old_data
                    })
                },
                parseAndSetAccessConfig: function (ac) {
                    BRDashboard.conference_access_config = BRDynamic.readOptions(ac, _br_v1_conference_options)
                },
                load: function () {
                    BRDashboard._subscribe(function (o) {
                        if (o.attr !== undefined || o.value !== undefined) {
                            if (BRDashboard.user_map[o.idx] === undefined) {
                                BRDashboard.user_map[o.idx] = {};
                                BRDashboard.box(o.idx, "user_idx", null, o.idx)
                            }
                            BRDashboard.user_map[o.idx][o.attr] = o.value
                        }
                    }, "users", 1);
                    BRDashboard._subscribe(function (o) {
                        if (o.attr === undefined && o.value === undefined) {
                            delete BRDashboard.user_map[o.idx];
                            BRDashboard.box(o.idx, undefined, null, undefined)
                        }
                    }, "users", -1);
                    BRDashboard._subscribe(function (o) {
                        var idx = BRDashboard.listeners.indexOf(o.mid);
                        if (o.command == "add" && idx == -1) {
                            BRDashboard.listeners.push(o.mid);
                            BRDashboard.listener_data[o.mid] = {}
                        } else if (o.command == "attr" && idx != -1) {
                            if (BRDashboard.listener_data[o.mid].poll !== o.attrs.poll && BRDashboard.listener_data[o.mid].user_id) BRDashboard.box(BRDashboard.listener_data[o.mid].user_id, "dtmf", null, o.attrs.poll);
                            jQuery.extend(BRDashboard.listener_data[o.mid], o.attrs);
                            if ("user_id" in o.attrs) BRDashboard.box(o.attrs["user_id"], "mid", o.mid, true)
                        }
                    }, "listener", 1);
                    BRDashboard._subscribe(function (o) {
                        var idx = BRDashboard.listeners.indexOf(o.mid);
                        if (o.command == "del" && idx != -1) {
                            if ("user_id" in BRDashboard.listener_data[o.mid]) BRDashboard.box(BRDashboard.listener_data[o.mid].user_id, "mid", o.mid, undefined);
                            BRDashboard.listeners.splice(idx, 1);
                            delete BRDashboard.listener_data[o.mid]
                        }
                    }, "listener", -1);
                    BRDashboard._subscribe(function (o) {
                        if (o.command === "mod") {
                            BRDashboard.online_2_user_map[o.connection_id] = o.user_id;
                            BRDashboard.box(o.user_id, "connection_id", o.connection_id, true)
                        }
                    }, "online", 1);
                    BRDashboard._subscribe(function (o) {
                        if (o.command === "del") {
                            if (BRDashboard.online_2_user_map[o.connection_id]) {
                                BRDashboard.box(BRDashboard.online_2_user_map[o.connection_id], "connection_id", o.connection_id, undefined);
                                delete BRDashboard.online_2_user_map[o.connection_id]
                            }
                        }
                    }, "online", -1);
                    BRDashboard._subscribe(function (o) {
                        if (o.command === "mod") {
                            if (!BRDashboard.invitees[o.id]) BRDashboard.invitees[o.id] = o.data;
                            else jQuery.extend(BRDashboard.invitees[o.id], o.data);
                            if (typeof o.user_id != "undefined") {
                                BRDashboard.invitee_id_by_user[o.user_id] = o.id;
                                BRDashboard.box(o.user_id, "invitee_id", null, o.id)
                            }
                        }
                    }, "invitee", 1);
                    BRDashboard._subscribe(function (o) {
                        if (o.command === "del") {
                            delete BRDashboard.invitees[o.id];
                            if (typeof o.user_id != "undefined") delete BRDashboard.invitee_id_by_user[o.user_id]
                        }
                    }, "invitee", -1);
                    BRDashboard._subscribe(function (o) {
                        var idx = BRDashboard.selectedListeners.indexOf(o.id);
                        if (o.selected && idx == -1) BRDashboard.selectedListeners.push(o.id);
                        if (!o.selected && idx != -1) BRDashboard.selectedListeners.splice(idx, 1)
                    }, "select_listener", 1);
                    BRDashboard._subscribe(function (o) {
                        var user_id = undefined;
                        if (o.attr == "user_id") user_id = o.value;
                        var h = {};
                        h[o.attr] = o.value;
                        if (br_room_context.invitation_id === o.idx && o.attr === "role") {
                            BRDashboard.updateRoomContext("is_host", o.value === "Host")
                        }
                        BRDashboard.fire({
                            type: "invitee",
                            command: "mod",
                            id: o.idx,
                            user_id: user_id,
                            data: h
                        })
                    }, "invitations", 1);
                    BRDashboard._subscribe(function (o) {
                        if (o.id != br_room_context.conference_id) return;
                        if (o.attr === "access_config") br_room_context.conference_access_config = o.value
                    }, "conferences", 1);
                    BRDashboard._subscribe(function (o) {
                        if (o.attr === "dtmf") BRDashboard.box(o.idx, "dtmf", null, o.value)
                    }, "gue", 1);
                    BRDashboard._subscribe(function (o) {
                        if (o.updated && o.updated.conference_access_config) parseAndSetAccessConfig(br_room_context.conference_access_config)
                    }, "room_context", 1)
                },
                updateRoomContext: function (key, value) {
                    var h = {};
                    h[key] = true;
                    br_room_context[key] = value;
                    BRDashboard.fire({
                        type: "room_context",
                        updated: h,
                        "new": br_room_context
                    })
                },
                notify: function (key, fn) {
                    function interval() {
                        var empty = true;
                        for (var key in BRDashboard.notification_dict) if (BRDashboard.notification_dict.hasOwnProperty(key)) {
                            var i = BRDashboard.notification_dict[key].cdown;
                            if (i >= 0) {
                                BRDashboard.notification_dict[key].fn(i % 2 ? 2 : 1);
                                BRDashboard.notification_dict[key].cdown--;
                                empty = false
                            }
                        }
                        if (empty) {
                            clearInterval(BRDashboard.notification_thread_id);
                            BRDashboard.notification_thread_id = undefined
                        }
                    }
                    if (typeof BRDashboard.notification_dict[key] === "undefined") fn(0);
                    BRDashboard.notification_dict[key] = {
                        cdown: 6,
                        fn: fn
                    };
                    if (typeof BRDashboard.notification_thread_id === "undefined") {
                        interval();
                        BRDashboard.notification_thread_id = setInterval(interval, 1e3)
                    }
                },
                resetNotify: function (key) {
                    var dict = BRDashboard.notification_dict[key];
                    if (typeof dict !== "undefined") {
                        BRDashboard.notification_dict[key].fn(3);
                        delete BRDashboard.notification_dict[key]
                    }
                }
            };
            this.commands = BRCommands;
            this.notify = BRDashboard
        };
    typeof window.BR === "undefined" ? window.BR = {
        v1: {}
    } : typeof window.BR.v1 === "undefined" && (window.BR.v1 = {});
    window.BR.v1.logic = {
        create: function () {
            return new LogicContainer
        }
    }
}();
!
function (window) {
    var supported = null,
        version = null,
        getUserMedia = null,
        _stopUserMedia = null,
        attachMediaStream = null,
        sdpConstraints = {
            mandatory: {
                OfferToReceiveAudio: true,
                OfferToReceiveVideo: true
            }
        };

    function init() {
        if (navigator.mozGetUserMedia) {
            supported = "firefox";
            RTCPeerConnection = function () {
                return mozRTCPeerConnection()
            };
            RTCSessionDescription = mozRTCSessionDescription;
            RTCIceCandidate = mozRTCIceCandidate;
            getUserMedia = navigator.mozGetUserMedia.bind(navigator);
            _stopUserMedia = function (element, stream) {
                element.pause();
                element.mozSrcObject = null
            };
            attachMediaStream = function (element, stream) {
                element.mozSrcObject = stream;
                element.play()
            };
            MediaStream.prototype.getVideoTracks = function () {
                return []
            };
            MediaStream.prototype.getAudioTracks = function () {
                return []
            }
        } else if (navigator.webkitGetUserMedia) {
            supported = "chrome";
            version = parseInt(navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./)[2]);
            getUserMedia = navigator.webkitGetUserMedia.bind(navigator);
            _stopUserMedia = function (element, stream) {
                element.pause();
                element.src = "";
                if (typeof stream.stop !== "undefined") stream.stop()
            };
            RTCPeerConnection = webkitRTCPeerConnection;
            attachMediaStream = function (element, stream) {
                element.src = webkitURL.createObjectURL(stream)
            };
            if (!webkitMediaStream.prototype.getVideoTracks) {
                webkitMediaStream.prototype.getVideoTracks = function () {
                    return this.videoTracks
                };
                webkitMediaStream.prototype.getAudioTracks = function () {
                    return this.audioTracks
                }
            }
        }
    }
    function fireOnce(en, pc, opts) {
        var fn = opts[en];
        if (typeof fn === "undefined") return false;
        var fl = "_br_" + en;
        if (pc[fl]) return false;
        fn.call(opts);
        pc[fl] = true
    }
    function createPeerConnection(opts) {
        var pc_config = {
            iceServers: [{
                url: "stun:stun.l.google.com:19302"
            }]
        };
        var pc_constraints = {
            optional: [{
                DtlsSrtpKeyAgreement: true
            }]
        };
        var pc = null;
        try {
            pc = new RTCPeerConnection(pc_config, pc_constraints);
            pc.oniceconnectionstatechange = function (event) {
                if (pc.iceConnectionState === "connected") fireOnce("connected", pc, opts);
                if (pc.iceConnectionState === "disconnected") fireOnce("disconnected", pc, opts)
            };
            pc.onicecandidate = function (event) {
                if (!event.candidate) {
                    opts.signalOut && opts.signalOut(undefined);
                    if (supported === "chrome" && version < 27) fireOnce("connected", pc, opts);
                    return
                }
                opts.signalOut && opts.signalOut({
                    type: "candidate",
                    label: event.candidate.sdpMLineIndex,
                    id: event.candidate.sdpMid,
                    candidate: event.candidate.candidate
                })
            }
        } catch (e) {
            opts.onSupportFailure && opts.onSupportFailure(e.message);
            return
        }
        if (opts.element) {
            pc.onaddstream = function (event) {
                attachMediaStream(opts.element, event.stream);
                opts.setStream && opts.setStream(event.stream)
            }
        }
        pc.onremovestream = function (event) {};
        return pc
    }
    function setStatus(state) {}
    function mergeConstraints(cons1, cons2) {
        var merged = cons1;
        for (var name in cons2.mandatory) {
            merged.mandatory[name] = cons2.mandatory[name]
        }
        merged.optional.concat(cons2.optional);
        return merged
    }
    function setLocalAndSendMessage(how, pc, sessionDescription) {
        sessionDescription.sdp = preferOpus(sessionDescription.sdp);
        pc.setLocalDescription(sessionDescription);
        how.signalOut && how.signalOut(sessionDescription)
    }
    function openWebcam(opts) {
        try {
            getUserMedia({
                audio: true,
                video: {
                    mandatory: {},
                    optional: []
                }
            }, function (stream) {
                opts.element && attachMediaStream(opts.element, stream);
                opts.setStream && opts.setStream(stream)
            }, function (error) {
                opts.onError && opts.onError(error.code)
            })
        } catch (e) {
            opts.onSupportFailure && opts.onSupportFailure(e.message)
        }
    }
    function callPeer(stream, opts) {
        var pc = createPeerConnection(opts);
        if (stream) pc.addStream(stream);
        var constraints = {
            optional: [],
            mandatory: {
                MozDontOfferDataChannel: true
            }
        };
        if (supported !== "firefox") {
            for (prop in constraints.mandatory) {
                if (prop.indexOf("Moz") != -1) {
                    delete constraints.mandatory[prop]
                }
            }
        }
        constraints = mergeConstraints(constraints, sdpConstraints);
        pc.createOffer(function (sdp) {
            setLocalAndSendMessage(opts, pc, sdp)
        }, opts.onError, constraints);
        opts.setPC && opts.setPC(pc)
    }
    function answer(msg, stream, opts) {
        var pc = createPeerConnection(opts);
        if (stream) pc.addStream(stream);
        var rtcsd = new RTCSessionDescription(msg);
        pc.setRemoteDescription(rtcsd);
        pc.createAnswer(function (sdp) {
            setLocalAndSendMessage(opts, pc, sdp)
        }, opts.onError, sdpConstraints);
        opts.setPC && opts.setPC(pc)
    }
    function setRemoteDescription(pc, msg) {
        pc.setRemoteDescription(new RTCSessionDescription(msg))
    }
    function candidate(pc, msg) {
        var candy = new RTCIceCandidate({
            sdpMLineIndex: msg.label,
            candidate: msg.candidate
        });
        pc.addIceCandidate(candy)
    }
    function stop(element, stream) {
        _stopUserMedia(element, stream)
    }
    function stopConnection(pc) {
        try {
            pc.close()
        } catch (e) {
            console.log && console.log("Exception in RTCPeerConnection.close()", pc, e)
        }
        pc = null
    }
    function mediaChannelAction(stream, action) {
        var tracks, endis;
        switch (action) {
        case "mute":
            tracks = stream.getAudioTracks();
            endis = false;
            break;
        case "unmute":
            tracks = stream.getAudioTracks();
            endis = true;
            break;
        case "video_off":
            tracks = stream.getVideoTracks();
            endis = false;
            break;
        case "video_on":
            tracks = stream.getVideoTracks();
            endis = true;
            break;
        default:
            return false
        }
        for (var i = 0; i < tracks.length; i++) {
            tracks[i].enabled = endis
        }
        return true
    }
    function preferOpus(sdp) {
        var sdpLines = sdp.split("\r\n");
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search("m=audio") !== -1) {
                var mLineIndex = i;
                break
            }
        }
        if (mLineIndex === null) return sdp;
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search("opus/48000") !== -1) {
                var opusPayload = extractSdp(sdpLines[i], /:(\d+) opus\/48000/i);
                if (opusPayload) sdpLines[mLineIndex] = setDefaultCodec(sdpLines[mLineIndex], opusPayload);
                break
            }
        }
        sdpLines = removeCN(sdpLines, mLineIndex);
        sdp = sdpLines.join("\r\n");
        return sdp
    }
    function addStereoToSDP(msg) {
        if (!msg.sdp) return;
        var sdpLines = msg.sdp.split("\r\n");
        var fmtpLineIndex = null;
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search("opus/48000") !== -1) {
                var opusPayload = extractSdp(sdpLines[i], /:(\d+) opus\/48000/i);
                break
            }
        }
        for (var i = 0; i < sdpLines.length; i++) {
            if (sdpLines[i].search("a=fmtp") !== -1) {
                var payload = extractSdp(sdpLines[i], /a=fmtp:(\d+)/);
                if (payload === opusPayload) {
                    fmtpLineIndex = i;
                    break
                }
            }
        }
        if (fmtpLineIndex === null) return;
        sdpLines[fmtpLineIndex] = sdpLines[fmtpLineIndex].concat(" stereo=1");
        msg.sdp = sdpLines.join("\r\n")
    }
    function extractSdp(sdpLine, pattern) {
        var result = sdpLine.match(pattern);
        return result && result.length == 2 ? result[1] : null
    }
    function setDefaultCodec(mLine, payload) {
        var elements = mLine.split(" ");
        var newLine = new Array;
        var index = 0;
        for (var i = 0; i < elements.length; i++) {
            if (index === 3) newLine[index++] = payload;
            if (elements[i] !== payload) newLine[index++] = elements[i]
        }
        return newLine.join(" ")
    }
    function removeCN(sdpLines, mLineIndex) {
        var mLineElements = sdpLines[mLineIndex].split(" ");
        for (var i = sdpLines.length - 1; i >= 0; i--) {
            var payload = extractSdp(sdpLines[i], /a=rtpmap:(\d+) CN\/\d+/i);
            if (payload) {
                var cnPos = mLineElements.indexOf(payload);
                if (cnPos !== -1) {
                    mLineElements.splice(cnPos, 1)
                }
                sdpLines.splice(i, 1)
            }
        }
        sdpLines[mLineIndex] = mLineElements.join(" ");
        return sdpLines
    }
    init();
    window.wrapRTC = {
        supported: supported,
        openWebcam: openWebcam,
        stop: stop,
        stopConnection: stopConnection,
        callPeer: callPeer,
        answer: answer,
        setRemoteDescription: setRemoteDescription,
        candidate: candidate,
        mediaChannelAction: mediaChannelAction,
        addStereoToSDP: addStereoToSDP
    }
}(window);
!
function () {
    typeof window.BR === "undefined" ? window.BR = {
        v1: {}
    } : typeof window.BR.v1 === "undefined" && (window.BR.v1 = {});
    window.BR.v1.controllers = {
        chat: {
            _subscribeChat: function () {
                var month_names = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var n = this._api.notify,
                    _this = this;
                n.subscribe(function (o) {
                    var user_id = null,
                        user = "",
                        msg = o.data;
                    if (typeof msg === "undefined") return _this.onClear();
                    if (msg.match(/^{/)) {
                        try {
                            var r = JSON.parse(msg);
                            user_id = r.user_id;
                            user = r.user;
                            msg = r.text;
                            if (r.to) return
                        } catch (e) {
                            return
                        }
                    } else if (msg.match(/^(\d+)-([^:]*):(.*)$/)) {
                        user_id = RegExp.$1;
                        user = RegExp.$2;
                        msg = RegExp.$3
                    } else if (msg.match(/^([^:]*):(.*)$/)) {
                        user = RegExp.$1;
                        msg = RegExp.$2
                    }
                    var avatar = null;
                    if (user_id && n.user_map && n.user_map[user_id] && n.user_map[user_id].avatar_small) avatar = n.user_map[user_id].avatar_small;
                    var msg_top = '<div style="color: #bbb; border: 0; border-top: 1px solid #ddd;">';
                    msg_top += '<div style="float: left; font-weight: bold;">' + user + '</div><div style="float: right;">';
                    var time = "";
                    if (o.ts !== undefined) {
                        var d = new Date;

                        function dmy(d) {
                            return d.getFullYear() + "-" + d.getMonth() + "-" + d.getDate() + "--" + d.getHours()
                        }
                        function tme(d) {
                            return d.getTime()
                        }
                        function time_since_midnight(d) {
                            return ((d.getHours() * 60 + d.getMinutes()) * 60 + d.getSeconds()) * 1e3 + d.getMilliseconds()
                        }
                        var today = dmy(d);
                        var time_now = tme(d);
                        var tsm = time_since_midnight(d);
                        d.setTime(o.ts);
                        if (dmy(d) !== today) {
                            if (time_now - tme(d) < 864e5 + tsm) time += "Yesterday ";
                            else time += month_names[d.getMonth()] + " " + d.getDate() + " "
                        }
                        time += d.getHours() + ":" + (d.getMinutes() < 10 ? "0" : "") + d.getMinutes()
                    }
                    _this.onMessage({
                        text: msg,
                        time: time,
                        user: user,
                        avatar: avatar,
                        origin_id: undefined
                    })
                }, "chat");
                n.subscribe(function (o) {
                    switch (o.command) {
                    case "clearChat":
                        _this.onClear();
                        break
                    }
                }, "command")
            },
            _onInit: function () {
                this._subscribeChat()
            },
            onMessage: function (msg) {},
            onClear: function () {},
            sendElement: function (id) {
                var elem = document.getElementById(id);
                if (typeof elem === "undefined") return;
                this.sendMessage({
                    text: elem.value
                }, function () {
                    elem.value = ""
                })
            },
            sendMessage: function (msg, fn) {
                this._api.commands.sendChat(this._api.context.user_id, this._api.context.user_name, msg, fn)
            }
        },
        summary: {
            _onInit: function () {
                var n = this._api.notify,
                    _this = this;
                n.subscribe(function (o) {
                    if (o.updated.is_live && _this._api.context.is_live) {
                        _this.onLoad(_this._api.context)
                    }
                }, "room_context")
            },
            onLoad: function (cxt) {}
        },
        participants: {
            count: 0,
            excludeSelf: false,
            _onInit: function () {
                var n = this._api.notify,
                    _this = this,
                    ud = function (id) {
                        if (n.user_map[id]) _this.onUpdate(id, n.user_map[id])
                    };
                n.subscribe(function (o) {
                    if (_this.excludeSelf && o.id == _this._api.context.user_id) return;
                    switch (o.command) {
                    case "show":
                        _this.count++;
                        _this.onCountChange(_this.count);
                        ud(o.idx);
                        break;
                    case "hide":
                        _this.count--;
                        _this.onRemove(o.idx);
                        _this.onCountChange(_this.count);
                        break
                    }
                }, "box");
                n.subscribe(function (o) {
                    if (!_this.excludeSelf || o.id != _this._api.context.user_id) ud(o.idx)
                }, "users");
                this.onCountChange(0)
            },
            onCountChange: function (newCount) {},
            onUpdate: function (id, user) {},
            onRemove: function (id, user) {}
        },
        privateConference: {
            _haveWebRTC: typeof wrapRTC === "undefined" ? false : !! wrapRTC.supported,
            _key: null,
            _incoming: [],
            _pc: null,
            _peer: null,
            _newKey: function () {
                this._key = Math.random().toString(36).substring(2)
            },
            _setPC: function (pc) {
                this._pc = pc;
                for (var i = 0; i < this._incoming.length; i++) this._signalIn(this._incoming[i]);
                this._incoming = []
            },
            _sendAction: function (msg, peer_key) {
                this._api.commands.videoAction("p2p-" + this._key + (peer_key ? "-" + peer_key : ""), msg)
            },
            _uname: function (id) {
                var n = this._api.notify,
                    u = null;
                if (n && n.user_map && id && n.user_map[id]) u = n.user_map[id];
                if (u) return (u.name || "") + (u.name && u.last_name ? " " : "") + (u.last_name || "")
            },
            _possiblePeerUpdate: function (obj, peer_key) {
                if (!peer_key && this._peer && this._peer.connection_id == obj.connection_id) return this._stop(false);
                if (this._peer && this._peer.key != peer_key) return;
                if (obj.data) {
                    try {
                        var data = JSON.parse(obj.data);
                        if (typeof data.available !== "undefined") this.onUserStatusUpdate("presence", {
                            id: obj.user_id,
                            connection_id: obj.connection_id,
                            key: peer_key,
                            available: data.available
                        })
                    } catch (e) {}
                } else if (this._peer) {
                    this._stop(false)
                }
            },
            _preoffer: function (peer_key, msg, obj) {
                if (this._peer) return;
                this._peer = {
                    key: peer_key,
                    connection_id: obj.connection_id,
                    key_at_preoffer: this._key
                };
                this._sendAction(JSON.stringify({
                    available: false
                }));
                this.onCallStatusUpdate("ringing", {
                    awaiting_permission: false,
                    name: this._uname(obj.user_id)
                })
            },
            _answer: function (peer_key, msg, obj) {
                var _this = this,
                    call_key = _this._key;
                if (!this._peer) return;
                if (this._peer.key_at_preoffer != this._key) return this._stop(false);
                if (this.options.stereo) wrapRTC.addStereoToSDP(msg);
                this.onCallStatusUpdate("ringing", {
                    awaiting_permission: true,
                    name: this._uname(obj.user_id)
                });
                wrapRTC.openWebcam({
                    element: _this.localVideo,
                    onSupportFailure: function (msg) {
                        _this._error(call_key, msg)
                    },
                    onError: function (code) {
                        switch (code) {
                        case 1:
                            _this._error(call_key);
                            break;
                        default:
                        }
                    },
                    setStream: function (stream) {
                        if (_this._key !== call_key) return;
                        _this.onCallStatusUpdate("ringing", {
                            awaiting_permission: false,
                            name: _this._uname(obj.user_id)
                        });
                        _this._peer.stream = stream;
                        wrapRTC.answer(msg, stream, {
                            element: _this.remoteVideo,
                            onError: function (error) {
                                _this._error(call_key, error)
                            },
                            setPC: function (pc) {
                                _this._setPC(pc)
                            },
                            signalOut: function (msg) {
                                _this._sendAction(JSON.stringify(msg), peer_key)
                            },
                            connected: function () {
                                _this.onCallStatusUpdate("connected")
                            },
                            disconnected: function () {
                                _this._stop(false)
                            }
                        })
                    }
                })
            },
            _signalIn: function (msg) {
                var _this = this;
                switch (msg.type) {
                case "answer":
                    if (true) {
                        if (_this.options.stereo) wrapRTC.addStereoToSDP(msg);
                        wrapRTC.setRemoteDescription(_this._pc, msg)
                    }
                    break;
                case "candidate":
                    if (true) {
                        wrapRTC.candidate(_this._pc, msg)
                    }
                    break;
                case "metadata":
                    _this.onCallStatusUpdate("metadata", msg);
                    break
                }
            },
            _setupVideo: function () {
                var n = this._api.notify,
                    _this = this;
                this._newKey();
                n.subscribe(function (o) {
                    var sk = o.subkey.split(/-/, 3);
                    if (sk.length < 1) return;
                    var mechanism = sk[0];
                    if (mechanism != "p2p") return;
                    var from_key = sk[1];
                    var to_key = sk[2];
                    if (to_key) {
                        if (_this._key && to_key == _this._key && typeof o.data !== "undefined") {
                            try {
                                var obj = JSON.parse(o.data);
                                if (obj.type === "preoffer") _this._preoffer(from_key, obj, o);
                                else if (obj.type === "offer") _this._answer(from_key, obj, o);
                                else if (_this._pc) _this._signalIn(obj);
                                else _this._incoming.push(obj)
                            } catch (e) {}
                        }
                        return
                    }
                    if (o.connection_id != n.connection_id && from_key.length) {
                        _this._possiblePeerUpdate(o, from_key)
                    }
                }, "video");
                n.subscribe(function (o) {
                    if (o.command === "del" && o.connection_id != n.connection_id) {
                        _this._possiblePeerUpdate(o)
                    }
                }, "online");
                n.subscribe(function (o) {
                    if (o.updated.is_live && _this._api.context.is_live) {
                        _this._sendAction(JSON.stringify({
                            available: true
                        }))
                    }
                }, "room_context")
            },
            _error: function (original_key, msg) {
                if (this._key === original_key) {
                    if (msg) {
                        if (console && console.log) console.log("WebRTC error [" + msg + "]");
                        this.onCallStatusUpdate("error")
                    } else this.onCallStatusUpdate("permission_denied");
                    this._stop(true)
                }
            },
            _stop: function (delay) {
                this._sendAction(undefined);
                this.onCallStatusUpdate("done", {
                    delay: delay ? 1500 : 0
                });
                if (this._pc) {
                    wrapRTC.stopConnection(this._pc);
                    this._pc = null
                }
                this._incoming = [];
                this._peer = null;
                this._newKey();
                this._sendAction(JSON.stringify({
                    available: true
                }))
            },
            _onInit: function () {
                if (this._haveWebRTC) {
                    this._setupVideo()
                }
            },
            localVideo: null,
            remoteVideo: null,
            onUserStatusUpdate: function (state, params) {},
            onCallStatusUpdate: function (state, params) {},
            call: function (params) {
                var _this = this,
                    call_key = _this._key;
                if (_this._peer) return;
                _this._peer = {
                    key: params.key,
                    connection_id: params.connection_id
                };
                _this._sendAction(JSON.stringify({
                    available: false
                }));
                _this._sendAction(JSON.stringify({
                    type: "preoffer"
                }), _this._peer.key);
                _this.onCallStatusUpdate("calling", {
                    awaiting_permission: true,
                    name: _this._uname(params.id)
                });
                wrapRTC.openWebcam({
                    element: _this.localVideo,
                    onSupportFailure: function (msg) {
                        _this._error(call_key, msg)
                    },
                    onError: function (code) {
                        switch (code) {
                        case 1:
                            _this._error(call_key);
                            break;
                        default:
                        }
                    },
                    setStream: function (stream) {
                        if (_this._key !== call_key) return;
                        _this.onCallStatusUpdate("calling", {
                            awaiting_permission: false,
                            name: _this._uname(params.id)
                        });
                        _this._peer.stream = stream;
                        wrapRTC.callPeer(stream, {
                            element: _this.remoteVideo,
                            onError: function (error) {
                                _this._error(call_key, error)
                            },
                            setPC: function (pc) {
                                _this._setPC(pc)
                            },
                            connected: function () {
                                _this.onCallStatusUpdate("connected")
                            },
                            disconnected: function () {
                                _this._stop(false)
                            },
                            signalOut: function (msg) {
                                _this._sendAction(JSON.stringify(msg), params.key)
                            }
                        })
                    }
                })
            },
            control: function (key, value) {
                if (!this._peer || !this._peer.stream) return;
                switch (key) {
                case "hangup":
                    this._stop(false);
                    break;
                case "mute":
                case "unmute":
                case "video_off":
                case "video_on":
                    wrapRTC.mediaChannelAction(this._peer.stream, key);
                    this._sendAction(JSON.stringify({
                        type: "metadata",
                        key: key
                    }), this._peer.key);
                    break
                }
            }
        },
        presentation: {
            presentations: [],
            _set_presentation: function (value) {
                var arr = null;
                if (value) arr = value.match(/^([^:]+):([^:]+):([^:]+):(\d):(.+)$/);
                this.onPresentationChange(arr ? {
                    numPages: arr[1],
                    presentationIndex: arr[2],
                    presentationName: unescape(arr[3]),
                    multipage: arr[4] == 1,
                    url: arr[5]
                } : null)
            },
            _set_ptr: function (value) {
                var obj = null;
                if (value) {
                    if (!/^(\d+),(\d+)$/.exec(value)) return;
                    obj = {
                        x: parseInt(RegExp.$1, 10),
                        y: parseInt(RegExp.$2, 10)
                    }
                }
                this.onSetPointer(obj)
            },
            _onInit: function () {
                var n = this._api.notify,
                    _this = this;
                this.onPresentationChange(null);
                n.subscribe(function (h) {
                    if (h.attr === undefined && h.value === undefined) {
                        if (_this.presentations[h.idx] !== undefined) {
                            _this.onRemovePresentation(h.idx);
                            delete _this.presentations[h.idx]
                        }
                    } else {
                        if (typeof _this.presentations[h.idx] === "undefined") _this.presentations[h.idx] = {
                            media_file: {
                                id: h.idx
                            }
                        };
                        _this.presentations[h.idx].media_file[h.attr] = h.value;
                        var mf = _this.presentations[h.idx].media_file;
                        if (mf.name && mf.url && mf.slideshow_pages > 0 && !mf._added) {
                            mf._added = true;
                            _this.onAddPresentation(mf.id, mf.name)
                        }
                    }
                }, "media_files");
                n.subscribe(function (o) {
                    switch (o.variable) {
                    case "presenter":
                        if (o.value) {
                            var arr = o.value.match(/^\s*([^:]+):(.*)$/);
                            if (arr && arr.length == 3) {
                                _this.onPresenterChange(arr[2], arr[1] == _this._api.context.user_id)
                            }
                        }
                        break;
                    case "presentation":
                        _this._set_presentation(o.value);
                        break;
                    case "ptr":
                        _this._set_ptr(o.value);
                        return;
                    case "show":
                        _this.onChangePage(o.value);
                        break;
                    case undefined:
                        if (o.value == undefined) {
                            _this._set_presentation("");
                            _this.onPresenterChange("", false)
                        }
                        break
                    }
                    _this.onCheckPointer()
                }, "slide")
            },
            onChangePage: function (newPageNum) {},
            onPresentationChange: function (obj) {},
            onPresenterChange: function (name, me) {},
            onAddPresentation: function (idx, name) {},
            onRemovePresentation: function (idx) {},
            onSetPointer: function (obj) {},
            onCheckPointer: function () {},
            changePresentation: function (idx) {
                var mf = idx in this.presentations ? this.presentations[idx].media_file : null;
                this._api.commands.slideAction("presentation", mf ? mf.slideshow_pages + ":" + mf.id + ":" + escape(mf.name) + ":" + (mf.multipage ? 1 : 0) + ":" + mf.url : undefined)
            },
            changePage: function (newPage) {
                this._api.commands.slideAction("show", newPage)
            },
            close: function () {
                this._api.commands.slideAction(undefined, undefined)
            },
            makeMePresenter: function () {
                this._api.commands.slideAction("presenter", this._api.context.user_id + ":" + this._api.context.user_name)
            },
            setPointer: function (x, y) {
                this._api.commands.slideAction("ptr", x + "," + y)
            }
        }
    }
}(); /*! Socket.IO.min.js build:0.9.11, production. Copyright(c) 2011 LearnBoost <dev@learnboost.com> MIT Licensed */
var io = "undefined" == typeof module ? {} : module.exports;
(function () {
    (function (a, b) {
        var c = a;
        c.version = "0.9.11", c.protocol = 1, c.transports = [], c.j = [], c.sockets = {}, c.connect = function (a, d) {
            var e = c.util.parseUri(a),
                f, g;
            b && b.location && (e.protocol = e.protocol || b.location.protocol.slice(0, -1), e.host = e.host || (b.document ? b.document.domain : b.location.hostname), e.port = e.port || b.location.port), f = c.util.uniqueUri(e);
            var h = {
                host: e.host,
                secure: "https" == e.protocol,
                port: e.port || ("https" == e.protocol ? 443 : 80),
                query: e.query || ""
            };
            c.util.merge(h, d);
            if (h["force new connection"] || !c.sockets[f]) g = new c.Socket(h);
            return !h["force new connection"] && g && (c.sockets[f] = g), g = g || c.sockets[f], g.of(e.path.length > 1 ? e.path : "")
        }
    })("object" == typeof module ? module.exports : this.io = {}, this), function (a, b) {
        var c = a.util = {},
            d = /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/,
            e = ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"];
        c.parseUri = function (a) {
            var b = d.exec(a || ""),
                c = {},
                f = 14;
            while (f--) c[e[f]] = b[f] || "";
            return c
        }, c.uniqueUri = function (a) {
            var c = a.protocol,
                d = a.host,
                e = a.port;
            return "document" in b ? (d = d || document.domain, e = e || (c == "https" && document.location.protocol !== "https:" ? 443 : document.location.port)) : (d = d || "localhost", !e && c == "https" && (e = 443)), (c || "http") + "://" + d + ":" + (e || 80)
        }, c.query = function (a, b) {
            var d = c.chunkQuery(a || ""),
                e = [];
            c.merge(d, c.chunkQuery(b || ""));
            for (var f in d) d.hasOwnProperty(f) && e.push(f + "=" + d[f]);
            return e.length ? "?" + e.join("&") : ""
        }, c.chunkQuery = function (a) {
            var b = {},
                c = a.split("&"),
                d = 0,
                e = c.length,
                f;
            for (; d < e; ++d) f = c[d].split("="), f[0] && (b[f[0]] = f[1]);
            return b
        };
        var f = !1;
        c.load = function (a) {
            if ("document" in b && document.readyState === "complete" || f) return a();
            c.on(b, "load", a, !1)
        }, c.on = function (a, b, c, d) {
            a.attachEvent ? a.attachEvent("on" + b, c) : a.addEventListener && a.addEventListener(b, c, d)
        }, c.request = function (a) {
            if (a && "undefined" != typeof XDomainRequest && !c.ua.hasCORS) return new XDomainRequest;
            if ("undefined" != typeof XMLHttpRequest && (!a || c.ua.hasCORS)) return new XMLHttpRequest;
            if (!a) try {
                return new(window[["Active"].concat("Object").join("X")])("Microsoft.XMLHTTP")
            } catch (b) {}
            return null
        }, "undefined" != typeof window && c.load(function () {
            f = !0
        }), c.defer = function (a) {
            if (!c.ua.webkit || "undefined" != typeof importScripts) return a();
            c.load(function () {
                setTimeout(a, 100)
            })
        }, c.merge = function (b, d, e, f) {
            var g = f || [],
                h = typeof e == "undefined" ? 2 : e,
                i;
            for (i in d) d.hasOwnProperty(i) && c.indexOf(g, i) < 0 && (typeof b[i] != "object" || !h ? (b[i] = d[i], g.push(d[i])) : c.merge(b[i], d[i], h - 1, g));
            return b
        }, c.mixin = function (a, b) {
            c.merge(a.prototype, b.prototype)
        }, c.inherit = function (a, b) {
            function c() {}
            c.prototype = b.prototype, a.prototype = new c
        }, c.isArray = Array.isArray ||
        function (a) {
            return Object.prototype.toString.call(a) === "[object Array]"
        }, c.intersect = function (a, b) {
            var d = [],
                e = a.length > b.length ? a : b,
                f = a.length > b.length ? b : a;
            for (var g = 0, h = f.length; g < h; g++)~c.indexOf(e, f[g]) && d.push(f[g]);
            return d
        }, c.indexOf = function (a, b, c) {
            for (var d = a.length, c = c < 0 ? c + d < 0 ? 0 : c + d : c || 0; c < d && a[c] !== b; c++);
            return d <= c ? -1 : c
        }, c.toArray = function (a) {
            var b = [];
            for (var c = 0, d = a.length; c < d; c++) b.push(a[c]);
            return b
        }, c.ua = {}, c.ua.hasCORS = "undefined" != typeof XMLHttpRequest &&
        function () {
            try {
                var a = new XMLHttpRequest
            } catch (b) {
                return !1
            }
            return a.withCredentials != undefined
        }(), c.ua.webkit = "undefined" != typeof navigator && /webkit/i.test(navigator.userAgent), c.ua.iDevice = "undefined" != typeof navigator && /iPad|iPhone|iPod/i.test(navigator.userAgent)
    }("undefined" != typeof io ? io : module.exports, this), function (a, b) {
        function c() {}
        a.EventEmitter = c, c.prototype.on = function (a, c) {
            return this.$events || (this.$events = {}), this.$events[a] ? b.util.isArray(this.$events[a]) ? this.$events[a].push(c) : this.$events[a] = [this.$events[a], c] : this.$events[a] = c, this
        }, c.prototype.addListener = c.prototype.on, c.prototype.once = function (a, b) {
            function d() {
                c.removeListener(a, d), b.apply(this, arguments)
            }
            var c = this;
            return d.listener = b, this.on(a, d), this
        }, c.prototype.removeListener = function (a, c) {
            if (this.$events && this.$events[a]) {
                var d = this.$events[a];
                if (b.util.isArray(d)) {
                    var e = -1;
                    for (var f = 0, g = d.length; f < g; f++) if (d[f] === c || d[f].listener && d[f].listener === c) {
                        e = f;
                        break
                    }
                    if (e < 0) return this;
                    d.splice(e, 1), d.length || delete this.$events[a]
                } else(d === c || d.listener && d.listener === c) && delete this.$events[a]
            }
            return this
        }, c.prototype.removeAllListeners = function (a) {
            return a === undefined ? (this.$events = {}, this) : (this.$events && this.$events[a] && (this.$events[a] = null), this)
        }, c.prototype.listeners = function (a) {
            return this.$events || (this.$events = {}), this.$events[a] || (this.$events[a] = []), b.util.isArray(this.$events[a]) || (this.$events[a] = [this.$events[a]]), this.$events[a]
        }, c.prototype.emit = function (a) {
            if (!this.$events) return !1;
            var c = this.$events[a];
            if (!c) return !1;
            var d = Array.prototype.slice.call(arguments, 1);
            if ("function" == typeof c) c.apply(this, d);
            else {
                if (!b.util.isArray(c)) return !1;
                var e = c.slice();
                for (var f = 0, g = e.length; f < g; f++) e[f].apply(this, d)
            }
            return !0
        }
    }("undefined" != typeof io ? io : module.exports, "undefined" != typeof io ? io : module.parent.exports), function (exports, nativeJSON) {
        function f(a) {
            return a < 10 ? "0" + a : a
        }
        function date(a, b) {
            return isFinite(a.valueOf()) ? a.getUTCFullYear() + "-" + f(a.getUTCMonth() + 1) + "-" + f(a.getUTCDate()) + "T" + f(a.getUTCHours()) + ":" + f(a.getUTCMinutes()) + ":" + f(a.getUTCSeconds()) + "Z" : null
        }
        function quote(a) {
            return escapable.lastIndex = 0, escapable.test(a) ? '"' + a.replace(escapable, function (a) {
                var b = meta[a];
                return typeof b == "string" ? b : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
            }) + '"' : '"' + a + '"'
        }
        function str(a, b) {
            var c, d, e, f, g = gap,
                h, i = b[a];
            i instanceof Date && (i = date(a)), typeof rep == "function" && (i = rep.call(b, a, i));
            switch (typeof i) {
            case "string":
                return quote(i);
            case "number":
                return isFinite(i) ? String(i) : "null";
            case "boolean":
            case "null":
                return String(i);
            case "object":
                if (!i) return "null";
                gap += indent, h = [];
                if (Object.prototype.toString.apply(i) === "[object Array]") {
                    f = i.length;
                    for (c = 0; c < f; c += 1) h[c] = str(c, i) || "null";
                    return e = h.length === 0 ? "[]" : gap ? "[\n" + gap + h.join(",\n" + gap) + "\n" + g + "]" : "[" + h.join(",") + "]", gap = g, e
                }
                if (rep && typeof rep == "object") {
                    f = rep.length;
                    for (c = 0; c < f; c += 1) typeof rep[c] == "string" && (d = rep[c], e = str(d, i), e && h.push(quote(d) + (gap ? ": " : ":") + e))
                } else for (d in i) Object.prototype.hasOwnProperty.call(i, d) && (e = str(d, i), e && h.push(quote(d) + (gap ? ": " : ":") + e));
                return e = h.length === 0 ? "{}" : gap ? "{\n" + gap + h.join(",\n" + gap) + "\n" + g + "}" : "{" + h.join(",") + "}", gap = g, e
            }
        }
        "use strict";
        if (nativeJSON && nativeJSON.parse) return exports.JSON = {
            parse: nativeJSON.parse,
            stringify: nativeJSON.stringify
        };
        var JSON = exports.JSON = {},
            cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
            escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
            gap, indent, meta = {
                "\b": "\\b",
                "\t": "\\t",
                "\n": "\\n",
                "\f": "\\f",
                "\r": "\\r",
                '"': '\\"',
                "\\": "\\\\"
            },
            rep;
        JSON.stringify = function (a, b, c) {
            var d;
            gap = "", indent = "";
            if (typeof c == "number") for (d = 0; d < c; d += 1) indent += " ";
            else typeof c == "string" && (indent = c);
            rep = b;
            if (!b || typeof b == "function" || typeof b == "object" && typeof b.length == "number") return str("", {
                "": a
            });
            throw new Error("JSON.stringify")
        }, JSON.parse = function (text, reviver) {
            function walk(a, b) {
                var c, d, e = a[b];
                if (e && typeof e == "object") for (c in e) Object.prototype.hasOwnProperty.call(e, c) && (d = walk(e, c), d !== undefined ? e[c] = d : delete e[c]);
                return reviver.call(a, b, e)
            }
            var j;
            text = String(text), cx.lastIndex = 0, cx.test(text) && (text = text.replace(cx, function (a) {
                return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
            }));
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) return j = eval("(" + text + ")"), typeof reviver == "function" ? walk({
                "": j
            }, "") : j;
            throw new SyntaxError("JSON.parse")
        }
    }("undefined" != typeof io ? io : module.exports, typeof JSON != "undefined" ? JSON : undefined), function (a, b) {
        var c = a.parser = {},
            d = c.packets = ["disconnect", "connect", "heartbeat", "message", "json", "event", "ack", "error", "noop"],
            e = c.reasons = ["transport not supported", "client not handshaken", "unauthorized"],
            f = c.advice = ["reconnect"],
            g = b.JSON,
            h = b.util.indexOf;
        c.encodePacket = function (a) {
            var b = h(d, a.type),
                c = a.id || "",
                i = a.endpoint || "",
                j = a.ack,
                k = null;
            switch (a.type) {
            case "error":
                var l = a.reason ? h(e, a.reason) : "",
                    m = a.advice ? h(f, a.advice) : "";
                if (l !== "" || m !== "") k = l + (m !== "" ? "+" + m : "");
                break;
            case "message":
                a.data !== "" && (k = a.data);
                break;
            case "event":
                var n = {
                    name: a.name
                };
                a.args && a.args.length && (n.args = a.args), k = g.stringify(n);
                break;
            case "json":
                k = g.stringify(a.data);
                break;
            case "connect":
                a.qs && (k = a.qs);
                break;
            case "ack":
                k = a.ackId + (a.args && a.args.length ? "+" + g.stringify(a.args) : "")
            }
            var o = [b, c + (j == "data" ? "+" : ""), i];
            return k !== null && k !== undefined && o.push(k), o.join(":")
        }, c.encodePayload = function (a) {
            var b = "";
            if (a.length == 1) return a[0];
            for (var c = 0, d = a.length; c < d; c++) {
                var e = a[c];
                b += "\ufffd" + e.length + "\ufffd" + a[c]
            }
            return b
        };
        var i = /([^:]+):([0-9]+)?(\+)?:([^:]+)?:?([\s\S]*)?/;
        c.decodePacket = function (a) {
            var b = a.match(i);
            if (!b) return {};
            var c = b[2] || "",
                a = b[5] || "",
                h = {
                    type: d[b[1]],
                    endpoint: b[4] || ""
                };
            c && (h.id = c, b[3] ? h.ack = "data" : h.ack = !0);
            switch (h.type) {
            case "error":
                var b = a.split("+");
                h.reason = e[b[0]] || "", h.advice = f[b[1]] || "";
                break;
            case "message":
                h.data = a || "";
                break;
            case "event":
                try {
                    var j = g.parse(a);
                    h.name = j.name, h.args = j.args
                } catch (k) {}
                h.args = h.args || [];
                break;
            case "json":
                try {
                    h.data = g.parse(a)
                } catch (k) {}
                break;
            case "connect":
                h.qs = a || "";
                break;
            case "ack":
                var b = a.match(/^([0-9]+)(\+)?(.*)/);
                if (b) {
                    h.ackId = b[1], h.args = [];
                    if (b[3]) try {
                        h.args = b[3] ? g.parse(b[3]) : []
                    } catch (k) {}
                }
                break;
            case "disconnect":
            case "heartbeat":
            }
            return h
        }, c.decodePayload = function (a) {
            if (a.charAt(0) == "\ufffd") {
                var b = [];
                for (var d = 1, e = ""; d < a.length; d++) a.charAt(d) == "\ufffd" ? (b.push(c.decodePacket(a.substr(d + 1).substr(0, e))), d += Number(e) + 1, e = "") : e += a.charAt(d);
                return b
            }
            return [c.decodePacket(a)]
        }
    }("undefined" != typeof io ? io : module.exports, "undefined" != typeof io ? io : module.parent.exports), function (a, b) {
        function c(a, b) {
            this.socket = a, this.sessid = b
        }
        a.Transport = c, b.util.mixin(c, b.EventEmitter), c.prototype.heartbeats = function () {
            return !0
        }, c.prototype.onData = function (a) {
            this.clearCloseTimeout(), (this.socket.connected || this.socket.connecting || this.socket.reconnecting) && this.setCloseTimeout();
            if (a !== "") {
                var c = b.parser.decodePayload(a);
                if (c && c.length) for (var d = 0, e = c.length; d < e; d++) this.onPacket(c[d])
            }
            return this
        }, c.prototype.onPacket = function (a) {
            return this.socket.setHeartbeatTimeout(), a.type == "heartbeat" ? this.onHeartbeat() : (a.type == "connect" && a.endpoint == "" && this.onConnect(), a.type == "error" && a.advice == "reconnect" && (this.isOpen = !1), this.socket.onPacket(a), this)
        }, c.prototype.setCloseTimeout = function () {
            if (!this.closeTimeout) {
                var a = this;
                this.closeTimeout = setTimeout(function () {
                    a.onDisconnect()
                }, this.socket.closeTimeout)
            }
        }, c.prototype.onDisconnect = function () {
            return this.isOpen && this.close(), this.clearTimeouts(), this.socket.onDisconnect(), this
        }, c.prototype.onConnect = function () {
            return this.socket.onConnect(), this
        }, c.prototype.clearCloseTimeout = function () {
            this.closeTimeout && (clearTimeout(this.closeTimeout), this.closeTimeout = null)
        }, c.prototype.clearTimeouts = function () {
            this.clearCloseTimeout(), this.reopenTimeout && clearTimeout(this.reopenTimeout)
        }, c.prototype.packet = function (a) {
            this.send(b.parser.encodePacket(a))
        }, c.prototype.onHeartbeat = function (a) {
            this.packet({
                type: "heartbeat"
            })
        }, c.prototype.onOpen = function () {
            this.isOpen = !0, this.clearCloseTimeout(), this.socket.onOpen()
        }, c.prototype.onClose = function () {
            var a = this;
            this.isOpen = !1, this.socket.onClose(), this.onDisconnect()
        }, c.prototype.prepareUrl = function () {
            var a = this.socket.options;
            return this.scheme() + "://" + a.host + ":" + a.port + "/" + a.resource + "/" + b.protocol + "/" + this.name + "/" + this.sessid
        }, c.prototype.ready = function (a, b) {
            b.call(this)
        }
    }("undefined" != typeof io ? io : module.exports, "undefined" != typeof io ? io : module.parent.exports), function (a, b, c) {
        function d(a) {
            this.options = {
                port: 80,
                secure: !1,
                document: "document" in c ? document : !1,
                resource: "socket.io",
                transports: b.transports,
                "connect timeout": 1e4,
                "try multiple transports": !0,
                reconnect: !0,
                "reconnection delay": 500,
                "reconnection limit": Infinity,
                "reopen delay": 3e3,
                "max reconnection attempts": 10,
                "sync disconnect on unload": !1,
                "auto connect": !0,
                "flash policy port": 10843,
                manualFlush: !1
            }, b.util.merge(this.options, a), this.connected = !1, this.open = !1, this.connecting = !1, this.reconnecting = !1, this.namespaces = {}, this.buffer = [], this.doBuffer = !1;
            if (this.options["sync disconnect on unload"] && (!this.isXDomain() || b.util.ua.hasCORS)) {
                var d = this;
                b.util.on(c, "beforeunload", function () {
                    d.disconnectSync()
                }, !1)
            }
            this.options["auto connect"] && this.connect()
        }
        function e() {}
        a.Socket = d, b.util.mixin(d, b.EventEmitter), d.prototype.of = function (a) {
            return this.namespaces[a] || (this.namespaces[a] = new b.SocketNamespace(this, a), a !== "" && this.namespaces[a].packet({
                type: "connect"
            })), this.namespaces[a]
        }, d.prototype.publish = function () {
            this.emit.apply(this, arguments);
            var a;
            for (var b in this.namespaces) this.namespaces.hasOwnProperty(b) && (a = this.of(b), a.$emit.apply(a, arguments))
        }, d.prototype.handshake = function (a) {
            function f(b) {
                b instanceof Error ? (c.connecting = !1, c.onError(b.message)) : a.apply(null, b.split(":"))
            }
            var c = this,
                d = this.options,
                g = ["http" + (d.secure ? "s" : "") + ":/", d.host + ":" + d.port, d.resource, b.protocol, b.util.query(this.options.query, "t=" + +(new Date))].join("/");
            if (this.isXDomain() && !b.util.ua.hasCORS) {
                var h = document.getElementsByTagName("script")[0],
                    i = document.createElement("script");
                i.src = g + "&jsonp=" + b.j.length, h.parentNode.insertBefore(i, h), b.j.push(function (a) {
                    f(a), i.parentNode.removeChild(i)
                })
            } else {
                var j = b.util.request();
                j.open("GET", g, !0), this.isXDomain() && (j.withCredentials = !0), j.onreadystatechange = function () {
                    j.readyState == 4 && (j.onreadystatechange = e, j.status == 200 ? f(j.responseText) : j.status == 403 ? c.onError(j.responseText) : (c.connecting = !1, !c.reconnecting && c.onError(j.responseText)))
                }, j.send(null)
            }
        }, d.prototype.getTransport = function (a) {
            var c = a || this.transports,
                d;
            for (var e = 0, f; f = c[e]; e++) if (b.Transport[f] && b.Transport[f].check(this) && (!this.isXDomain() || b.Transport[f].xdomainCheck(this))) return new b.Transport[f](this, this.sessionid);
            return null
        }, d.prototype.connect = function (a) {
            if (this.connecting) return this;
            var c = this;
            return c.connecting = !0, this.handshake(function (d, e, f, g) {
                function h(a) {
                    c.transport && c.transport.clearTimeouts(), c.transport = c.getTransport(a);
                    if (!c.transport) return c.publish("connect_failed");
                    c.transport.ready(c, function () {
                        c.connecting = !0, c.publish("connecting", c.transport.name), c.transport.open(), c.options["connect timeout"] && (c.connectTimeoutTimer = setTimeout(function () {
                            if (!c.connected) {
                                c.connecting = !1;
                                if (c.options["try multiple transports"]) {
                                    var a = c.transports;
                                    while (a.length > 0 && a.splice(0, 1)[0] != c.transport.name);
                                    a.length ? h(a) : c.publish("connect_failed")
                                }
                            }
                        }, c.options["connect timeout"]))
                    })
                }
                c.sessionid = d, c.closeTimeout = f * 1e3, c.heartbeatTimeout = e * 1e3, c.transports || (c.transports = c.origTransports = g ? b.util.intersect(g.split(","), c.options.transports) : c.options.transports), c.setHeartbeatTimeout(), h(c.transports), c.once("connect", function () {
                    clearTimeout(c.connectTimeoutTimer), a && typeof a == "function" && a()
                })
            }), this
        }, d.prototype.setHeartbeatTimeout = function () {
            clearTimeout(this.heartbeatTimeoutTimer);
            if (this.transport && !this.transport.heartbeats()) return;
            var a = this;
            this.heartbeatTimeoutTimer = setTimeout(function () {
                a.transport.onClose()
            }, this.heartbeatTimeout)
        }, d.prototype.packet = function (a) {
            return this.connected && !this.doBuffer ? this.transport.packet(a) : this.buffer.push(a), this
        }, d.prototype.setBuffer = function (a) {
            this.doBuffer = a, !a && this.connected && this.buffer.length && (this.options.manualFlush || this.flushBuffer())
        }, d.prototype.flushBuffer = function () {
            this.transport.payload(this.buffer), this.buffer = []
        }, d.prototype.disconnect = function () {
            if (this.connected || this.connecting) this.open && this.of("").packet({
                type: "disconnect"
            }), this.onDisconnect("booted");
            return this
        }, d.prototype.disconnectSync = function () {
            var a = b.util.request(),
                c = ["http" + (this.options.secure ? "s" : "") + ":/", this.options.host + ":" + this.options.port, this.options.resource, b.protocol, "", this.sessionid].join("/") + "/?disconnect=1";
            a.open("GET", c, !1), a.send(null), this.onDisconnect("booted")
        }, d.prototype.isXDomain = function () {
            var a = c.location.port || ("https:" == c.location.protocol ? 443 : 80);
            return this.options.host !== c.location.hostname || this.options.port != a
        }, d.prototype.onConnect = function () {
            this.connected || (this.connected = !0, this.connecting = !1, this.doBuffer || this.setBuffer(!1), this.emit("connect"))
        }, d.prototype.onOpen = function () {
            this.open = !0
        }, d.prototype.onClose = function () {
            this.open = !1, clearTimeout(this.heartbeatTimeoutTimer)
        }, d.prototype.onPacket = function (a) {
            this.of(a.endpoint).onPacket(a)
        }, d.prototype.onError = function (a) {
            a && a.advice && a.advice === "reconnect" && (this.connected || this.connecting) && (this.disconnect(), this.options.reconnect && this.reconnect()), this.publish("error", a && a.reason ? a.reason : a)
        }, d.prototype.onDisconnect = function (a) {
            var b = this.connected,
                c = this.connecting;
            this.connected = !1, this.connecting = !1, this.open = !1;
            if (b || c) this.transport.close(), this.transport.clearTimeouts(), b && (this.publish("disconnect", a), "booted" != a && this.options.reconnect && !this.reconnecting && this.reconnect())
        }, d.prototype.reconnect = function () {
            function e() {
                if (a.connected) {
                    for (var b in a.namespaces) a.namespaces.hasOwnProperty(b) && "" !== b && a.namespaces[b].packet({
                        type: "connect"
                    });
                    a.publish("reconnect", a.transport.name, a.reconnectionAttempts)
                }
                clearTimeout(a.reconnectionTimer), a.removeListener("connect_failed", f), a.removeListener("connect", f), a.reconnecting = !1, delete a.reconnectionAttempts, delete a.reconnectionDelay, delete a.reconnectionTimer, delete a.redoTransports, a.options["try multiple transports"] = c
            }
            function f() {
                if (!a.reconnecting) return;
                if (a.connected) return e();
                if (a.connecting && a.reconnecting) return a.reconnectionTimer = setTimeout(f, 1e3);
                a.reconnectionAttempts++ >= b ? a.redoTransports ? (a.publish("reconnect_failed"), e()) : (a.on("connect_failed", f), a.options["try multiple transports"] = !0, a.transports = a.origTransports, a.transport = a.getTransport(), a.redoTransports = !0, a.connect()) : (a.reconnectionDelay < d && (a.reconnectionDelay *= 2), a.connect(), a.publish("reconnecting", a.reconnectionDelay, a.reconnectionAttempts), a.reconnectionTimer = setTimeout(f, a.reconnectionDelay))
            }
            this.reconnecting = !0, this.reconnectionAttempts = 0, this.reconnectionDelay = this.options["reconnection delay"];
            var a = this,
                b = this.options["max reconnection attempts"],
                c = this.options["try multiple transports"],
                d = this.options["reconnection limit"];
            this.options["try multiple transports"] = !1, this.reconnectionTimer = setTimeout(f, this.reconnectionDelay), this.on("connect", f)
        }
    }("undefined" != typeof io ? io : module.exports, "undefined" != typeof io ? io : module.parent.exports, this), function (a, b) {
        function c(a, b) {
            this.socket = a, this.name = b || "", this.flags = {}, this.json = new d(this, "json"), this.ackPackets = 0, this.acks = {}
        }
        function d(a, b) {
            this.namespace = a, this.name = b
        }
        a.SocketNamespace = c, b.util.mixin(c, b.EventEmitter), c.prototype.$emit = b.EventEmitter.prototype.emit, c.prototype.of = function () {
            return this.socket.of.apply(this.socket, arguments)
        }, c.prototype.packet = function (a) {
            return a.endpoint = this.name, this.socket.packet(a), this.flags = {}, this
        }, c.prototype.send = function (a, b) {
            var c = {
                type: this.flags.json ? "json" : "message",
                data: a
            };
            return "function" == typeof b && (c.id = ++this.ackPackets, c.ack = !0, this.acks[c.id] = b), this.packet(c)
        }, c.prototype.emit = function (a) {
            var b = Array.prototype.slice.call(arguments, 1),
                c = b[b.length - 1],
                d = {
                    type: "event",
                    name: a
                };
            return "function" == typeof c && (d.id = ++this.ackPackets, d.ack = "data", this.acks[d.id] = c, b = b.slice(0, b.length - 1)), d.args = b, this.packet(d)
        }, c.prototype.disconnect = function () {
            return this.name === "" ? this.socket.disconnect() : (this.packet({
                type: "disconnect"
            }), this.$emit("disconnect")), this
        }, c.prototype.onPacket = function (a) {
            function d() {
                c.packet({
                    type: "ack",
                    args: b.util.toArray(arguments),
                    ackId: a.id
                })
            }
            var c = this;
            switch (a.type) {
            case "connect":
                this.$emit("connect");
                break;
            case "disconnect":
                this.name === "" ? this.socket.onDisconnect(a.reason || "booted") : this.$emit("disconnect", a.reason);
                break;
            case "message":
            case "json":
                var e = ["message", a.data];
                a.ack == "data" ? e.push(d) : a.ack && this.packet({
                    type: "ack",
                    ackId: a.id
                }), this.$emit.apply(this, e);
                break;
            case "event":
                var e = [a.name].concat(a.args);
                a.ack == "data" && e.push(d), this.$emit.apply(this, e);
                break;
            case "ack":
                this.acks[a.ackId] && (this.acks[a.ackId].apply(this, a.args), delete this.acks[a.ackId]);
                break;
            case "error":
                a.advice ? this.socket.onError(a) : a.reason == "unauthorized" ? this.$emit("connect_failed", a.reason) : this.$emit("error", a.reason)
            }
        }, d.prototype.send = function () {
            this.namespace.flags[this.name] = !0, this.namespace.send.apply(this.namespace, arguments)
        }, d.prototype.emit = function () {
            this.namespace.flags[this.name] = !0, this.namespace.emit.apply(this.namespace, arguments)
        }
    }("undefined" != typeof io ? io : module.exports, "undefined" != typeof io ? io : module.parent.exports), function (a, b, c) {
        function d(a) {
            b.Transport.apply(this, arguments)
        }
        a.websocket = d, b.util.inherit(d, b.Transport), d.prototype.name = "websocket", d.prototype.open = function () {
            var a = b.util.query(this.socket.options.query),
                d = this,
                e;
            return e || (e = c.MozWebSocket || c.WebSocket), this.websocket = new e(this.prepareUrl() + a), this.websocket.onopen = function () {
                d.onOpen(), d.socket.setBuffer(!1)
            }, this.websocket.onmessage = function (a) {
                d.onData(a.data)
            }, this.websocket.onclose = function () {
                d.onClose(), d.socket.setBuffer(!0)
            }, this.websocket.onerror = function (a) {
                d.onError(a)
            }, this
        }, b.util.ua.iDevice ? d.prototype.send = function (a) {
            var b = this;
            return setTimeout(function () {
                b.websocket.send(a)
            }, 0), this
        } : d.prototype.send = function (a) {
            return this.websocket.send(a), this
        }, d.prototype.payload = function (a) {
            for (var b = 0, c = a.length; b < c; b++) this.packet(a[b]);
            return this
        }, d.prototype.close = function () {
            return this.websocket.close(), this
        }, d.prototype.onError = function (a) {
            this.socket.onError(a)
        }, d.prototype.scheme = function () {
            return this.socket.options.secure ? "wss" : "ws"
        }, d.check = function () {
            return "WebSocket" in c && !("__addTask" in WebSocket) || "MozWebSocket" in c
        }, d.xdomainCheck = function () {
            return !0
        }, b.transports.push("websocket")
    }("undefined" != typeof io ? io.Transport : module.exports, "undefined" != typeof io ? io : module.parent.exports, this), function (a, b) {
        function c() {
            b.Transport.websocket.apply(this, arguments)
        }
        a.flashsocket = c, b.util.inherit(c, b.Transport.websocket), c.prototype.name = "flashsocket", c.prototype.open = function () {
            var a = this,
                c = arguments;
            return WebSocket.__addTask(function () {
                b.Transport.websocket.prototype.open.apply(a, c)
            }), this
        }, c.prototype.send = function () {
            var a = this,
                c = arguments;
            return WebSocket.__addTask(function () {
                b.Transport.websocket.prototype.send.apply(a, c)
            }), this
        }, c.prototype.close = function () {
            return WebSocket.__tasks.length = 0, b.Transport.websocket.prototype.close.call(this), this
        }, c.prototype.ready = function (a, d) {
            function e() {
                var b = a.options,
                    e = b["flash policy port"],
                    g = ["http" + (b.secure ? "s" : "") + ":/", b.host + ":" + b.port, b.resource, "static/flashsocket", "WebSocketMain" + (a.isXDomain() ? "Insecure" : "") + ".swf"];
                c.loaded || (typeof WEB_SOCKET_SWF_LOCATION == "undefined" && (WEB_SOCKET_SWF_LOCATION = g.join("/")), e !== 843 && WebSocket.loadFlashPolicyFile("xmlsocket://" + b.host + ":" + e), WebSocket.__initialize(), c.loaded = !0), d.call(f)
            }
            var f = this;
            if (document.body) return e();
            b.util.load(e)
        }, c.check = function () {
            return typeof WebSocket != "undefined" && "__initialize" in WebSocket && !! swfobject ? swfobject.getFlashPlayerVersion().major >= 10 : !1
        }, c.xdomainCheck = function () {
            return !0
        }, typeof window != "undefined" && (WEB_SOCKET_DISABLE_AUTO_INITIALIZATION = !0), b.transports.push("flashsocket")
    }("undefined" != typeof io ? io.Transport : module.exports, "undefined" != typeof io ? io : module.parent.exports);
    if ("undefined" != typeof window) var swfobject = function () {
            function A() {
                if (t) return;
                try {
                    var a = i.getElementsByTagName("body")[0].appendChild(Q("span"));
                    a.parentNode.removeChild(a)
                } catch (b) {
                    return
                }
                t = !0;
                var c = l.length;
                for (var d = 0; d < c; d++) l[d]()
            }
            function B(a) {
                t ? a() : l[l.length] = a
            }
            function C(b) {
                if (typeof h.addEventListener != a) h.addEventListener("load", b, !1);
                else if (typeof i.addEventListener != a) i.addEventListener("load", b, !1);
                else if (typeof h.attachEvent != a) R(h, "onload", b);
                else if (typeof h.onload == "function") {
                    var c = h.onload;
                    h.onload = function () {
                        c(), b()
                    }
                } else h.onload = b
            }
            function D() {
                k ? E() : F()
            }
            function E() {
                var c = i.getElementsByTagName("body")[0],
                    d = Q(b);
                d.setAttribute("type", e);
                var f = c.appendChild(d);
                if (f) {
                    var g = 0;
                    (function () {
                        if (typeof f.GetVariable != a) {
                            var b = f.GetVariable("$version");
                            b && (b = b.split(" ")[1].split(","), y.pv = [parseInt(b[0], 10), parseInt(b[1], 10), parseInt(b[2], 10)])
                        } else if (g < 10) {
                            g++, setTimeout(arguments.callee, 10);
                            return
                        }
                        c.removeChild(d), f = null, F()
                    })()
                } else F()
            }
            function F() {
                var b = m.length;
                if (b > 0) for (var c = 0; c < b; c++) {
                    var d = m[c].id,
                        e = m[c].callbackFn,
                        f = {
                            success: !1,
                            id: d
                        };
                    if (y.pv[0] > 0) {
                        var g = P(d);
                        if (g) if (S(m[c].swfVersion) && !(y.wk && y.wk < 312)) U(d, !0), e && (f.success = !0, f.ref = G(d), e(f));
                        else if (m[c].expressInstall && H()) {
                            var h = {};
                            h.data = m[c].expressInstall, h.width = g.getAttribute("width") || "0", h.height = g.getAttribute("height") || "0", g.getAttribute("class") && (h.styleclass = g.getAttribute("class")), g.getAttribute("align") && (h.align = g.getAttribute("align"));
                            var i = {},
                                j = g.getElementsByTagName("param"),
                                k = j.length;
                            for (var l = 0; l < k; l++) j[l].getAttribute("name").toLowerCase() != "movie" && (i[j[l].getAttribute("name")] = j[l].getAttribute("value"));
                            I(h, i, d, e)
                        } else J(g), e && e(f)
                    } else {
                        U(d, !0);
                        if (e) {
                            var n = G(d);
                            n && typeof n.SetVariable != a && (f.success = !0, f.ref = n), e(f)
                        }
                    }
                }
            }
            function G(c) {
                var d = null,
                    e = P(c);
                if (e && e.nodeName == "OBJECT") if (typeof e.SetVariable != a) d = e;
                else {
                    var f = e.getElementsByTagName(b)[0];
                    f && (d = f)
                }
                return d
            }
            function H() {
                return !u && S("6.0.65") && (y.win || y.mac) && !(y.wk && y.wk < 312)
            }
            function I(b, c, d, e) {
                u = !0, r = e || null, s = {
                    success: !1,
                    id: d
                };
                var g = P(d);
                if (g) {
                    g.nodeName == "OBJECT" ? (p = K(g), q = null) : (p = g, q = d), b.id = f;
                    if (typeof b.width == a || !/%$/.test(b.width) && parseInt(b.width, 10) < 310) b.width = "310";
                    if (typeof b.height == a || !/%$/.test(b.height) && parseInt(b.height, 10) < 137) b.height = "137";
                    i.title = i.title.slice(0, 47) + " - Flash Player Installation";
                    var j = y.ie && y.win ? ["Active"].concat("").join("X") : "PlugIn",
                        k = "MMredirectURL=" + h.location.toString().replace(/&/g, "%26") + "&MMplayerType=" + j + "&MMdoctitle=" + i.title;
                    typeof c.flashvars != a ? c.flashvars += "&" + k : c.flashvars = k;
                    if (y.ie && y.win && g.readyState != 4) {
                        var l = Q("div");
                        d += "SWFObjectNew", l.setAttribute("id", d), g.parentNode.insertBefore(l, g), g.style.display = "none", function () {
                            g.readyState == 4 ? g.parentNode.removeChild(g) : setTimeout(arguments.callee, 10)
                        }()
                    }
                    L(b, c, d)
                }
            }
            function J(a) {
                if (y.ie && y.win && a.readyState != 4) {
                    var b = Q("div");
                    a.parentNode.insertBefore(b, a), b.parentNode.replaceChild(K(a), b), a.style.display = "none", function () {
                        a.readyState == 4 ? a.parentNode.removeChild(a) : setTimeout(arguments.callee, 10)
                    }()
                } else a.parentNode.replaceChild(K(a), a)
            }
            function K(a) {
                var c = Q("div");
                if (y.win && y.ie) c.innerHTML = a.innerHTML;
                else {
                    var d = a.getElementsByTagName(b)[0];
                    if (d) {
                        var e = d.childNodes;
                        if (e) {
                            var f = e.length;
                            for (var g = 0; g < f; g++)(e[g].nodeType != 1 || e[g].nodeName != "PARAM") && e[g].nodeType != 8 && c.appendChild(e[g].cloneNode(!0))
                        }
                    }
                }
                return c
            }
            function L(c, d, f) {
                var g, h = P(f);
                if (y.wk && y.wk < 312) return g;
                if (h) {
                    typeof c.id == a && (c.id = f);
                    if (y.ie && y.win) {
                        var i = "";
                        for (var j in c) c[j] != Object.prototype[j] && (j.toLowerCase() == "data" ? d.movie = c[j] : j.toLowerCase() == "styleclass" ? i += ' class="' + c[j] + '"' : j.toLowerCase() != "classid" && (i += " " + j + '="' + c[j] + '"'));
                        var k = "";
                        for (var l in d) d[l] != Object.prototype[l] && (k += '<param name="' + l + '" value="' + d[l] + '" />');
                        h.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + i + ">" + k + "</object>", n[n.length] = c.id, g = P(c.id)
                    } else {
                        var m = Q(b);
                        m.setAttribute("type", e);
                        for (var o in c) c[o] != Object.prototype[o] && (o.toLowerCase() == "styleclass" ? m.setAttribute("class", c[o]) : o.toLowerCase() != "classid" && m.setAttribute(o, c[o]));
                        for (var p in d) d[p] != Object.prototype[p] && p.toLowerCase() != "movie" && M(m, p, d[p]);
                        h.parentNode.replaceChild(m, h), g = m
                    }
                }
                return g
            }
            function M(a, b, c) {
                var d = Q("param");
                d.setAttribute("name", b), d.setAttribute("value", c), a.appendChild(d)
            }
            function N(a) {
                var b = P(a);
                b && b.nodeName == "OBJECT" && (y.ie && y.win ? (b.style.display = "none", function () {
                    b.readyState == 4 ? O(a) : setTimeout(arguments.callee, 10)
                }()) : b.parentNode.removeChild(b))
            }
            function O(a) {
                var b = P(a);
                if (b) {
                    for (var c in b) typeof b[c] == "function" && (b[c] = null);
                    b.parentNode.removeChild(b)
                }
            }
            function P(a) {
                var b = null;
                try {
                    b = i.getElementById(a)
                } catch (c) {}
                return b
            }
            function Q(a) {
                return i.createElement(a)
            }
            function R(a, b, c) {
                a.attachEvent(b, c), o[o.length] = [a, b, c]
            }
            function S(a) {
                var b = y.pv,
                    c = a.split(".");
                return c[0] = parseInt(c[0], 10), c[1] = parseInt(c[1], 10) || 0, c[2] = parseInt(c[2], 10) || 0, b[0] > c[0] || b[0] == c[0] && b[1] > c[1] || b[0] == c[0] && b[1] == c[1] && b[2] >= c[2] ? !0 : !1
            }
            function T(c, d, e, f) {
                if (y.ie && y.mac) return;
                var g = i.getElementsByTagName("head")[0];
                if (!g) return;
                var h = e && typeof e == "string" ? e : "screen";
                f && (v = null, w = null);
                if (!v || w != h) {
                    var j = Q("style");
                    j.setAttribute("type", "text/css"), j.setAttribute("media", h), v = g.appendChild(j), y.ie && y.win && typeof i.styleSheets != a && i.styleSheets.length > 0 && (v = i.styleSheets[i.styleSheets.length - 1]), w = h
                }
                y.ie && y.win ? v && typeof v.addRule == b && v.addRule(c, d) : v && typeof i.createTextNode != a && v.appendChild(i.createTextNode(c + " {" + d + "}"))
            }
            function U(a, b) {
                if (!x) return;
                var c = b ? "visible" : "hidden";
                t && P(a) ? P(a).style.visibility = c : T("#" + a, "visibility:" + c)
            }
            function V(b) {
                var c = /[\\\"<>\.;]/,
                    d = c.exec(b) != null;
                return d && typeof encodeURIComponent != a ? encodeURIComponent(b) : b
            }
            var a = "undefined",
                b = "object",
                c = "Shockwave Flash",
                d = "ShockwaveFlash.ShockwaveFlash",
                e = "application/x-shockwave-flash",
                f = "SWFObjectExprInst",
                g = "onreadystatechange",
                h = window,
                i = document,
                j = navigator,
                k = !1,
                l = [D],
                m = [],
                n = [],
                o = [],
                p, q, r, s, t = !1,
                u = !1,
                v, w, x = !0,
                y = function () {
                    var f = typeof i.getElementById != a && typeof i.getElementsByTagName != a && typeof i.createElement != a,
                        g = j.userAgent.toLowerCase(),
                        l = j.platform.toLowerCase(),
                        m = l ? /win/.test(l) : /win/.test(g),
                        n = l ? /mac/.test(l) : /mac/.test(g),
                        o = /webkit/.test(g) ? parseFloat(g.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : !1,
                        p = !1,
                        q = [0, 0, 0],
                        r = null;
                    if (typeof j.plugins != a && typeof j.plugins[c] == b) r = j.plugins[c].description, r && (typeof j.mimeTypes == a || !j.mimeTypes[e] || !! j.mimeTypes[e].enabledPlugin) && (k = !0, p = !1, r = r.replace(/^.*\s+(\S+\s+\S+$)/, "$1"), q[0] = parseInt(r.replace(/^(.*)\..*$/, "$1"), 10), q[1] = parseInt(r.replace(/^.*\.(.*)\s.*$/, "$1"), 10), q[2] = /[a-zA-Z]/.test(r) ? parseInt(r.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0);
                    else if (typeof h[["Active"].concat("Object").join("X")] != a) try {
                        var s = new(window[["Active"].concat("Object").join("X")])(d);
                        s && (r = s.GetVariable("$version"), r && (p = !0, r = r.split(" ")[1].split(","), q = [parseInt(r[0], 10), parseInt(r[1], 10), parseInt(r[2], 10)]))
                    } catch (t) {}
                    return {
                        w3: f,
                        pv: q,
                        wk: o,
                        ie: p,
                        win: m,
                        mac: n
                    }
                }(),
                z = function () {
                    if (!y.w3) return;
                    (typeof i.readyState != a && i.readyState == "complete" || typeof i.readyState == a && (i.getElementsByTagName("body")[0] || i.body)) && A(), t || (typeof i.addEventListener != a && i.addEventListener("DOMContentLoaded", A, !1), y.ie && y.win && (i.attachEvent(g, function () {
                        i.readyState == "complete" && (i.detachEvent(g, arguments.callee), A())
                    }), h == top &&
                    function () {
                        if (t) return;
                        try {
                            i.documentElement.doScroll("left")
                        } catch (a) {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        A()
                    }()), y.wk &&
                    function () {
                        if (t) return;
                        if (!/loaded|complete/.test(i.readyState)) {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        A()
                    }(), C(A))
                }(),
                W = function () {
                    y.ie && y.win && window.attachEvent("onunload", function () {
                        var a = o.length;
                        for (var b = 0; b < a; b++) o[b][0].detachEvent(o[b][1], o[b][2]);
                        var c = n.length;
                        for (var d = 0; d < c; d++) N(n[d]);
                        for (var e in y) y[e] = null;
                        y = null;
                        for (var f in swfobject) swfobject[f] = null;
                        swfobject = null
                    })
                }();
            return {
                registerObject: function (a, b, c, d) {
                    if (y.w3 && a && b) {
                        var e = {};
                        e.id = a, e.swfVersion = b, e.expressInstall = c, e.callbackFn = d, m[m.length] = e, U(a, !1)
                    } else d && d({
                        success: !1,
                        id: a
                    })
                },
                getObjectById: function (a) {
                    if (y.w3) return G(a)
                },
                embedSWF: function (c, d, e, f, g, h, i, j, k, l) {
                    var m = {
                        success: !1,
                        id: d
                    };
                    y.w3 && !(y.wk && y.wk < 312) && c && d && e && f && g ? (U(d, !1), B(function () {
                        e += "", f += "";
                        var n = {};
                        if (k && typeof k === b) for (var o in k) n[o] = k[o];
                        n.data = c, n.width = e, n.height = f;
                        var p = {};
                        if (j && typeof j === b) for (var q in j) p[q] = j[q];
                        if (i && typeof i === b) for (var r in i) typeof p.flashvars != a ? p.flashvars += "&" + r + "=" + i[r] : p.flashvars = r + "=" + i[r];
                        if (S(g)) {
                            var s = L(n, p, d);
                            n.id == d && U(d, !0), m.success = !0, m.ref = s
                        } else {
                            if (h && H()) {
                                n.data = h, I(n, p, d, l);
                                return
                            }
                            U(d, !0)
                        }
                        l && l(m)
                    })) : l && l(m)
                },
                switchOffAutoHideShow: function () {
                    x = !1
                },
                ua: y,
                getFlashPlayerVersion: function () {
                    return {
                        major: y.pv[0],
                        minor: y.pv[1],
                        release: y.pv[2]
                    }
                },
                hasFlashPlayerVersion: S,
                createSWF: function (a, b, c) {
                    return y.w3 ? L(a, b, c) : undefined
                },
                showExpressInstall: function (a, b, c, d) {
                    y.w3 && H() && I(a, b, c, d)
                },
                removeSWF: function (a) {
                    y.w3 && N(a)
                },
                createCSS: function (a, b, c, d) {
                    y.w3 && T(a, b, c, d)
                },
                addDomLoadEvent: B,
                addLoadEvent: C,
                getQueryParamValue: function (a) {
                    var b = i.location.search || i.location.hash;
                    if (b) {
                        /\?/.test(b) && (b = b.split("?")[1]);
                        if (a == null) return V(b);
                        var c = b.split("&");
                        for (var d = 0; d < c.length; d++) if (c[d].substring(0, c[d].indexOf("=")) == a) return V(c[d].substring(c[d].indexOf("=") + 1))
                    }
                    return ""
                },
                expressInstallCallback: function () {
                    if (u) {
                        var a = P(f);
                        a && p && (a.parentNode.replaceChild(p, a), q && (U(q, !0), y.ie && y.win && (p.style.display = "block")), r && r(s)), u = !1
                    }
                }
            }
        }();
    (function () {
        if ("undefined" == typeof window || window.WebSocket) return;
        var a = window.console;
        if (!a || !a.log || !a.error) a = {
            log: function () {},
            error: function () {}
        };
        if (!swfobject.hasFlashPlayerVersion("10.0.0")) {
            a.error("Flash Player >= 10.0.0 is required.");
            return
        }
        location.protocol == "file:" && a.error("WARNING: web-socket-js doesn't work in file:///... URL unless you set Flash Security Settings properly. Open the page via Web server i.e. http://..."), WebSocket = function (a, b, c, d, e) {
            var f = this;
            f.__id = WebSocket.__nextId++, WebSocket.__instances[f.__id] = f, f.readyState = WebSocket.CONNECTING, f.bufferedAmount = 0, f.__events = {}, b ? typeof b == "string" && (b = [b]) : b = [], setTimeout(function () {
                WebSocket.__addTask(function () {
                    WebSocket.__flash.create(f.__id, a, b, c || null, d || 0, e || null)
                })
            }, 0)
        }, WebSocket.prototype.send = function (a) {
            if (this.readyState == WebSocket.CONNECTING) throw "INVALID_STATE_ERR: Web Socket connection has not been established";
            var b = WebSocket.__flash.send(this.__id, encodeURIComponent(a));
            return b < 0 ? !0 : (this.bufferedAmount += b, !1)
        }, WebSocket.prototype.close = function () {
            if (this.readyState == WebSocket.CLOSED || this.readyState == WebSocket.CLOSING) return;
            this.readyState = WebSocket.CLOSING, WebSocket.__flash.close(this.__id)
        }, WebSocket.prototype.addEventListener = function (a, b, c) {
            a in this.__events || (this.__events[a] = []), this.__events[a].push(b)
        }, WebSocket.prototype.removeEventListener = function (a, b, c) {
            if (!(a in this.__events)) return;
            var d = this.__events[a];
            for (var e = d.length - 1; e >= 0; --e) if (d[e] === b) {
                d.splice(e, 1);
                break
            }
        }, WebSocket.prototype.dispatchEvent = function (a) {
            var b = this.__events[a.type] || [];
            for (var c = 0; c < b.length; ++c) b[c](a);
            var d = this["on" + a.type];
            d && d(a)
        }, WebSocket.prototype.__handleEvent = function (a) {
            "readyState" in a && (this.readyState = a.readyState), "protocol" in a && (this.protocol = a.protocol);
            var b;
            if (a.type == "open" || a.type == "error") b = this.__createSimpleEvent(a.type);
            else if (a.type == "close") b = this.__createSimpleEvent("close");
            else {
                if (a.type != "message") throw "unknown event type: " + a.type;
                var c = decodeURIComponent(a.message);
                b = this.__createMessageEvent("message", c)
            }
            this.dispatchEvent(b)
        }, WebSocket.prototype.__createSimpleEvent = function (a) {
            if (document.createEvent && window.Event) {
                var b = document.createEvent("Event");
                return b.initEvent(a, !1, !1), b
            }
            return {
                type: a,
                bubbles: !1,
                cancelable: !1
            }
        }, WebSocket.prototype.__createMessageEvent = function (a, b) {
            if (document.createEvent && window.MessageEvent && !window.opera) {
                var c = document.createEvent("MessageEvent");
                return c.initMessageEvent("message", !1, !1, b, null, null, window, null), c
            }
            return {
                type: a,
                data: b,
                bubbles: !1,
                cancelable: !1
            }
        }, WebSocket.CONNECTING = 0, WebSocket.OPEN = 1, WebSocket.CLOSING = 2, WebSocket.CLOSED = 3, WebSocket.__flash = null, WebSocket.__instances = {}, WebSocket.__tasks = [], WebSocket.__nextId = 0, WebSocket.loadFlashPolicyFile = function (a) {
            WebSocket.__addTask(function () {
                WebSocket.__flash.loadManualPolicyFile(a)
            })
        }, WebSocket.__initialize = function () {
            if (WebSocket.__flash) return;
            WebSocket.__swfLocation && (window.WEB_SOCKET_SWF_LOCATION = WebSocket.__swfLocation);
            if (!window.WEB_SOCKET_SWF_LOCATION) {
                a.error("[WebSocket] set WEB_SOCKET_SWF_LOCATION to location of WebSocketMain.swf");
                return
            }
            var b = document.createElement("div");
            b.id = "webSocketContainer", b.style.position = "absolute", WebSocket.__isFlashLite() ? (b.style.left = "0px", b.style.top = "0px") : (b.style.left = "-100px", b.style.top = "-100px");
            var c = document.createElement("div");
            c.id = "webSocketFlash", b.appendChild(c), document.body.appendChild(b), swfobject.embedSWF(WEB_SOCKET_SWF_LOCATION, "webSocketFlash", "1", "1", "10.0.0", null, null, {
                hasPriority: !0,
                swliveconnect: !0,
                allowScriptAccess: "always"
            }, null, function (b) {
                b.success || a.error("[WebSocket] swfobject.embedSWF failed")
            })
        }, WebSocket.__onFlashInitialized = function () {
            setTimeout(function () {
                WebSocket.__flash = document.getElementById("webSocketFlash"), WebSocket.__flash.setCallerUrl(location.href), WebSocket.__flash.setDebug( !! window.WEB_SOCKET_DEBUG);
                for (var a = 0; a < WebSocket.__tasks.length; ++a) WebSocket.__tasks[a]();
                WebSocket.__tasks = []
            }, 0)
        }, WebSocket.__onFlashEvent = function () {
            return setTimeout(function () {
                try {
                    var b = WebSocket.__flash.receiveEvents();
                    for (var c = 0; c < b.length; ++c) WebSocket.__instances[b[c].webSocketId].__handleEvent(b[c])
                } catch (d) {
                    a.error(d)
                }
            }, 0), !0
        }, WebSocket.__log = function (b) {
            a.log(decodeURIComponent(b))
        }, WebSocket.__error = function (b) {
            a.error(decodeURIComponent(b))
        }, WebSocket.__addTask = function (a) {
            WebSocket.__flash ? a() : WebSocket.__tasks.push(a)
        }, WebSocket.__isFlashLite = function () {
            if (!window.navigator || !window.navigator.mimeTypes) return !1;
            var a = window.navigator.mimeTypes["application/x-shockwave-flash"];
            return !a || !a.enabledPlugin || !a.enabledPlugin.filename ? !1 : a.enabledPlugin.filename.match(/flashlite/i) ? !0 : !1
        }, window.WEB_SOCKET_DISABLE_AUTO_INITIALIZATION || (window.addEventListener ? window.addEventListener("load", function () {
            WebSocket.__initialize()
        }, !1) : window.attachEvent("onload", function () {
            WebSocket.__initialize()
        }))
    })(), function (a, b, c) {
        function d(a) {
            if (!a) return;
            b.Transport.apply(this, arguments), this.sendBuffer = []
        }
        function e() {}
        a.XHR = d, b.util.inherit(d, b.Transport), d.prototype.open = function () {
            return this.socket.setBuffer(!1), this.onOpen(), this.get(), this.setCloseTimeout(), this
        }, d.prototype.payload = function (a) {
            var c = [];
            for (var d = 0, e = a.length; d < e; d++) c.push(b.parser.encodePacket(a[d]));
            this.send(b.parser.encodePayload(c))
        }, d.prototype.send = function (a) {
            return this.post(a), this
        }, d.prototype.post = function (a) {
            function d() {
                this.readyState == 4 && (this.onreadystatechange = e, b.posting = !1, this.status == 200 ? b.socket.setBuffer(!1) : b.onClose())
            }
            function f() {
                this.onload = e, b.socket.setBuffer(!1)
            }
            var b = this;
            this.socket.setBuffer(!0), this.sendXHR = this.request("POST"), c.XDomainRequest && this.sendXHR instanceof XDomainRequest ? this.sendXHR.onload = this.sendXHR.onerror = f : this.sendXHR.onreadystatechange = d, this.sendXHR.send(a)
        }, d.prototype.close = function () {
            return this.onClose(), this
        }, d.prototype.request = function (a) {
            var c = b.util.request(this.socket.isXDomain()),
                d = b.util.query(this.socket.options.query, "t=" + +(new Date));
            c.open(a || "GET", this.prepareUrl() + d, !0);
            if (a == "POST") try {
                c.setRequestHeader ? c.setRequestHeader("Content-type", "text/plain;charset=UTF-8") : c.contentType = "text/plain"
            } catch (e) {}
            return c
        }, d.prototype.scheme = function () {
            return this.socket.options.secure ? "https" : "http"
        }, d.check = function (a, d) {
            try {
                var e = b.util.request(d),
                    f = c.XDomainRequest && e instanceof XDomainRequest,
                    g = a && a.options && a.options.secure ? "https:" : "http:",
                    h = c.location && g != c.location.protocol;
                if (e && (!f || !h)) return !0
            } catch (i) {}
            return !1
        }, d.xdomainCheck = function (a) {
            return d.check(a, !0)
        }
    }("undefined" != typeof io ? io.Transport : module.exports, "undefined" != typeof io ? io : module.parent.exports, this), function (a, b) {
        function c(a) {
            b.Transport.XHR.apply(this, arguments)
        }
        a.htmlfile = c, b.util.inherit(c, b.Transport.XHR), c.prototype.name = "htmlfile", c.prototype.get = function () {
            this.doc = new(window[["Active"].concat("Object").join("X")])("htmlfile"), this.doc.open(), this.doc.write("<html></html>"), this.doc.close(), this.doc.parentWindow.s = this;
            var a = this.doc.createElement("div");
            a.className = "socketio", this.doc.body.appendChild(a), this.iframe = this.doc.createElement("iframe"), a.appendChild(this.iframe);
            var c = this,
                d = b.util.query(this.socket.options.query, "t=" + +(new Date));
            this.iframe.src = this.prepareUrl() + d, b.util.on(window, "unload", function () {
                c.destroy()
            })
        }, c.prototype._ = function (a, b) {
            this.onData(a);
            try {
                var c = b.getElementsByTagName("script")[0];
                c.parentNode.removeChild(c)
            } catch (d) {}
        }, c.prototype.destroy = function () {
            if (this.iframe) {
                try {
                    this.iframe.src = "about:blank"
                } catch (a) {}
                this.doc = null, this.iframe.parentNode.removeChild(this.iframe), this.iframe = null, CollectGarbage()
            }
        }, c.prototype.close = function () {
            return this.destroy(), b.Transport.XHR.prototype.close.call(this)
        }, c.check = function (a) {
            if (typeof window != "undefined" && ["Active"].concat("Object").join("X") in window) try {
                var c = new(window[["Active"].concat("Object").join("X")])("htmlfile");
                return c && b.Transport.XHR.check(a)
            } catch (d) {}
            return !1
        }, c.xdomainCheck = function () {
            return !1
        }, b.transports.push("htmlfile")
    }("undefined" != typeof io ? io.Transport : module.exports, "undefined" != typeof io ? io : module.parent.exports), function (a, b, c) {
        function d() {
            b.Transport.XHR.apply(this, arguments)
        }
        function e() {}
        a["xhr-polling"] = d, b.util.inherit(d, b.Transport.XHR), b.util.merge(d, b.Transport.XHR), d.prototype.name = "xhr-polling", d.prototype.heartbeats = function () {
            return !1
        }, d.prototype.open = function () {
            var a = this;
            return b.Transport.XHR.prototype.open.call(a), !1
        }, d.prototype.get = function () {
            function b() {
                this.readyState == 4 && (this.onreadystatechange = e, this.status == 200 ? (a.onData(this.responseText), a.get()) : a.onClose())
            }
            function d() {
                this.onload = e, this.onerror = e, a.retryCounter = 1, a.onData(this.responseText), a.get()
            }
            function f() {
                a.retryCounter++, !a.retryCounter || a.retryCounter > 3 ? a.onClose() : a.get()
            }
            if (!this.isOpen) return;
            var a = this;
            this.xhr = this.request(), c.XDomainRequest && this.xhr instanceof XDomainRequest ? (this.xhr.onload = d, this.xhr.onerror = f) : this.xhr.onreadystatechange = b, this.xhr.send(null)
        }, d.prototype.onClose = function () {
            b.Transport.XHR.prototype.onClose.call(this);
            if (this.xhr) {
                this.xhr.onreadystatechange = this.xhr.onload = this.xhr.onerror = e;
                try {
                    this.xhr.abort()
                } catch (a) {}
                this.xhr = null
            }
        }, d.prototype.ready = function (a, c) {
            var d = this;
            b.util.defer(function () {
                c.call(d)
            })
        }, b.transports.push("xhr-polling")
    }("undefined" != typeof io ? io.Transport : module.exports, "undefined" != typeof io ? io : module.parent.exports, this), function (a, b, c) {
        function e(a) {
            b.Transport["xhr-polling"].apply(this, arguments), this.index = b.j.length;
            var c = this;
            b.j.push(function (a) {
                c._(a)
            })
        }
        var d = c.document && "MozAppearance" in c.document.documentElement.style;
        a["jsonp-polling"] = e, b.util.inherit(e, b.Transport["xhr-polling"]), e.prototype.name = "jsonp-polling", e.prototype.post = function (a) {
            function i() {
                j(), c.socket.setBuffer(!1)
            }
            function j() {
                c.iframe && c.form.removeChild(c.iframe);
                try {
                    h = document.createElement('<iframe name="' + c.iframeId + '">')
                } catch (a) {
                    h = document.createElement("iframe"), h.name = c.iframeId
                }
                h.id = c.iframeId, c.form.appendChild(h), c.iframe = h
            }
            var c = this,
                d = b.util.query(this.socket.options.query, "t=" + +(new Date) + "&i=" + this.index);
            if (!this.form) {
                var e = document.createElement("form"),
                    f = document.createElement("textarea"),
                    g = this.iframeId = "socketio_iframe_" + this.index,
                    h;
                e.className = "socketio", e.style.position = "absolute", e.style.top = "0px", e.style.left = "0px", e.style.display = "none", e.target = g, e.method = "POST", e.setAttribute("accept-charset", "utf-8"), f.name = "d", e.appendChild(f), document.body.appendChild(e), this.form = e, this.area = f
            }
            this.form.action = this.prepareUrl() + d, j(), this.area.value = b.JSON.stringify(a);
            try {
                this.form.submit()
            } catch (k) {}
            this.iframe.attachEvent ? h.onreadystatechange = function () {
                c.iframe.readyState == "complete" && i()
            } : this.iframe.onload = i, this.socket.setBuffer(!0)
        }, e.prototype.get = function () {
            var a = this,
                c = document.createElement("script"),
                e = b.util.query(this.socket.options.query, "t=" + +(new Date) + "&i=" + this.index);
            this.script && (this.script.parentNode.removeChild(this.script), this.script = null), c.async = !0, c.src = this.prepareUrl() + e, c.onerror = function () {
                a.onClose()
            };
            var f = document.getElementsByTagName("script")[0];
            f.parentNode.insertBefore(c, f), this.script = c, d && setTimeout(function () {
                var a = document.createElement("iframe");
                document.body.appendChild(a), document.body.removeChild(a)
            }, 100)
        }, e.prototype._ = function (a) {
            return this.onData(a), this.isOpen && this.get(), this
        }, e.prototype.ready = function (a, c) {
            var e = this;
            if (!d) return c.call(this);
            b.util.load(function () {
                c.call(e)
            })
        }, e.check = function () {
            return "document" in c
        }, e.xdomainCheck = function () {
            return !0
        }, b.transports.push("jsonp-polling")
    }("undefined" != typeof io ? io.Transport : module.exports, "undefined" != typeof io ? io : module.parent.exports, this), typeof define == "function" && define.amd && define([], function () {
        return io
    })
})()