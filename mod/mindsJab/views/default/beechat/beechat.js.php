/**
 * Beechat
 * 
 * @package beechat
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Beechannels <contact@beechannels.com>
 * @copyright Beechannels 2007-2010
 * @link http://beechannels.com/
 */ 

/** Globals
 *
 */
g_beechat_user = null;
g_beechat_roster_items = null;

/** Class: BeeChat
 *  An object container for all BeeChat mod functions
 *
 */
BeeChat = {
    BOSH_SERVICE: '/http-bind/',
    DOMAIN: '<?php echo $vars['config']->chatsettings['domain'] ?>',
    RESOURCE: 'beebac',
    INACTIVITY_PERIOD_LENGTH: 60,

    NS: {
	CHAT_STATES: 'http://jabber.org/protocol/chatstates'
    },

    Message: {
	Types: {
	    NORMAL: 'normal',
	    CHAT: 'chat',
	    GROUPCHAT: 'groupchat',
	    HEADLINE: 'headline',
	    ERROR: 'error'
	},

	ChatStates: {
	    COMPOSING: 'composing',
	    PAUSED: 'paused',
	    ACTIVE: 'active',
	    INACTIVE: 'inactive',
	    GONE: 'gone'
	}
    },

    IQ: {
	Types: {
	    GET: 'get',
	    RESULT: 'result',
	    SET: 'set',
	    ERROR: 'error'
	}
    },

    Presence: {
	Types: {
	    UNAVAILABLE: 'unavailable',
	    SUBSCRIBE: 'subscribe',
	    SUBSCRIBED: 'subscribed',
	    UNSUBSCRIBE: 'unsubscribe',
	    UNSUBSCRIBED: 'unsubscribed',
	    PROBE: 'probe',
	    ERROR: 'error'
	},

	ChildElements: {
	    SHOW: 'show',
	    STATUS: 'status',
	    PRIORITY: 'priority'
	},

	ShowElements: {
	    CHAT: 'chat',
	    DND: 'dnd',
	    AWAY: 'away',
	    XA: 'xa'
	}
    },

    Roster: {
	DEFAULT_GROUP: 'Contacts'
    },


    Events: {
	Identifiers: {
	    UPDATE_CONNECTION_STATE: 0,
	    UPDATE_ROSTER: 1,
	    RECV_PRESENCE: 2,
	    RECV_CHAT_MESSAGE: 3
	},
	Messages: {
	    ConnectionStates: {
		CONNECTING: "<?php echo elgg_echo('beechat:connection:state:connecting'); ?>",
		AUTHENTICATING: "<?php echo elgg_echo('beechat:connection:state:authenticating'); ?>",
		FAILED: "<?php echo elgg_echo('beechat:connection:state:failed'); ?>",
		DISCONNECTING: "<?php echo elgg_echo('beechat:connection:state:disconnecting'); ?>",
		OFFLINE: "<?php echo elgg_echo('beechat:connection:state:offline'); ?>",
		ONLINE: "<?php echo elgg_echo('beechat:connection:state:online'); ?>"
	    }
	}
    }
};


/** Class: BeeChat.Core
 *  An object container for all BeeChat Core functions
 *
 */
BeeChat.Core = {
    ReferenceTables: {
	AvailabilityRates: {
	    AVAILABLE: 0,
	    ONLINE: 0,
	    CHAT: 0,
	    DND: 1,
	    AWAY: 2,
	    XA: 3,
	    UNAVAILABLE: 10,
	    OFFLINE: 10
	}
    }
};


/** Class: BeeChat.Core.User
 *  Create a BeeChat.Core.User object
 *
 *  Parameters:
 *    (String) jid - The user's jabber id
 *
 *  Returns:
 *    A new BeeChat.Core.User.
 */
BeeChat.Core.User = function(jid)
{
    if (!(this instanceof arguments.callee))
	return new BeeChat.Core.User(jid);

    /** Private members
     *
     */
    var _connection = null;
    var _attached = false;
    var _initialized = false;
    var _jid = null;
    var _roster = null;
    var _msgTemp = [];
    var _funcs = [];

    /** Constructor
     *
     */
    this.init = function(jid)
    {
	_jid = jid;
	_roster = new BeeChat.Core.Roster();
    }

    /** Accessors
     *
     */
    this.getConnection = function()
    {
	return _connection;
    }

    this.getJid = function()
    {
	return _jid;
    }

    this.getRoster = function()
    {
	return _roster;
    }

    this.isAttached = function()
    {
	return _attached;
    }

    this.isInitialized = function()
    {
	return _initialized;
    }

    /** Mutators
     *
     */
    this.setInitialized = function(isInitialized)
    {
	_initialized = isInitialized;
    }

    /** Function: addObserver
     *  Add an observer for a specified type of event
     *
     *  Parameters:
     *    (BeeChat.Events.Identifiers) eventType - The type of event to observer
     *    (Object) pFunc - A function to call when the event will be triggered
     */
    this.addObserver = function(eventType, pFunc)
    {
	if (jQuery.inArray(pFunc, _funcs) == -1) {
	    if (!_funcs[eventType])
		_funcs[eventType] = [];
	    _funcs[eventType].push(pFunc);
	}
    }

    /** Function: removeObserver
     *  Remove an observer
     *
     *  Parameters:
     *    (Object) pFunc - The registered function
     */
    this.removeObserver = function(pFunc)
    {
	var index = null;

	for (var key in _funcs) {
	    if (typeof _funcs[key] != 'object')
		continue;
	    if ((index = jQuery.inArray(pFunc, _funcs[key])) != -1)
		_funcs.splice(index, 1);
	}
    }

    /** Function: connect
     *  Connect the user to the BOSH service
     *
     *  Parameters:
     *    (String) password - The user's password
     *
     */
    this.connect = function(password)
    {
	if (_connection == null)
	    _connection = new Strophe.Connection(BeeChat.BOSH_SERVICE);
	_connection.connect(_jid, password, _onConnect);
    }

    /** Function: attach
     *  Attach user's connection to an existing XMPP session
     *
     *  Parameters:
     *    (String) sid - The SID of the existing XMPP session
     *    (String) rid - The RID of the existing XMPP session
     */
    this.attach = function(sid, rid)
    {
	if (_connection == null) {
	    _connection = new Strophe.Connection(BeeChat.BOSH_SERVICE);
	}
	_connection.attach(_jid, sid, rid, _onConnect);
	_attached = true;
	_onConnect(Strophe.Status.CONNECTED);
    }

    /** Function: disconnect
     *  Disconnect the user from the BOSH service
     *
     */
    this.disconnect = function()
    {
	if (_connection != null) {
	    _connection.disconnect();
	    _connection = null;
	}
    }

    /** Function: requestSessionPause
     *  Request a session pause to the server connection manager
     *
     */
    this.requestSessionPause = function()
    {
	var req = $build('body', {
		rid: _connection.rid,
		sid: _connection.sid,
		pause: BeeChat.INACTIVITY_PERIOD_LENGTH,
		xmlns: Strophe.NS.HTTPBIND
	    });

	_attached = false;
	_connection.send(req.tree());
    }

    /** Function: requestRoster
     *  Request a new roster to the server
     *
     */
    this.requestRoster = function()
    {
	var req = $iq({from: _jid, type: BeeChat.IQ.Types.GET})
	.c('query', {xmlns: Strophe.NS.ROSTER});

	_connection.send(req.tree());
    }

    /** Function: sendInitialPresence
     *  Send initial presence to the server in order to signal availability for communications
     *
     */
    this.sendInitialPresence = function()
    {
	_connection.send($pres().tree());
	_initialized = true;
    }

    /** Function: sendChatMessage
     *  Send a chat message to the server
     *
     *  Parameters:
     *    (String) addressee - The addressee of the chat message
     *    (String) msg - The chat message
     *
     */
    this.sendChatMessage = function(addressee, msg)
    {
	var req = $msg({
		type: BeeChat.Message.Types.CHAT,
		to: addressee,
		from: _connection.jid
	    }).c('body').t(msg).up().c(BeeChat.Message.ChatStates.ACTIVE, {xmlns: BeeChat.NS.CHAT_STATES});

	_connection.send(req.tree());
    }

    /** Function: sendChatStateMessage
     *  Send a chat state message to the server
     *
     *  Parameters:
     *    (String) addressee - The addressee of the chat state message
     *    (BeeChat.Message.ChatsState) state - The chat state that will be send
     *
     */
    this.sendChatStateMessage = function(addressee, state)
    {
	var req = $msg({
		type: BeeChat.Message.Types.CHAT,
		to: addressee,
		from: _connection.jid
	    }).c(state, {xmlns: BeeChat.NS.CHAT_STATES});

	_connection.send(req.tree());
    }

    /** Function: sendPresenceAvailabiliy
     *  Send a detailed presence stanza to the server
     *
     *  Parameters:
     *    (BeeChat.Presence.ShowElements) availability - The availability status
     *    (String) details - Detailed status information
     *
     */
    this.sendPresenceAvailability = function(availability, details)
    {
	var req = $pres()
	.c(BeeChat.Presence.ChildElements.SHOW).t(availability).up()
	.c(BeeChat.Presence.ChildElements.STATUS).t(details).up()
	.c(BeeChat.Presence.ChildElements.PRIORITY).t('1');

	_connection.send(req.tree());
    }

    /** PrivateFunction: _fire
     *  Triggers registered funcs of registered observers for a specified type of event
     *
     */
    function _fire(eventType, data, scope)
    {
	if (_funcs[eventType] != undefined) {
	    for (var i = 0; i < _funcs[eventType].length; i++)
		_funcs[eventType][i].call((scope || window), data);
	}
    }

    /** PrivateFunction: _onConnect
     *  Connection state manager
     *
     *  Parameters:
     *    (Strophe.Status) status - A Strophe connection status constant
     *
     */
    function _onConnect(status)
    {
	var msg = null;

	if (status == Strophe.Status.CONNECTING) 
{
	    msg = BeeChat.Events.Messages.ConnectionStates.CONNECTING;
	}
	else if (status == Strophe.Status.AUTHENTICATING) {
	    msg = BeeChat.Events.Messages.ConnectionStates.AUTHENTICATING;
	}
	else if (status == Strophe.Status.AUTHFAIL)
	    msg = BeeChat.Events.Messages.ConnectionStates.FAILED;
 	else if (status == Strophe.Status.CONNFAIL)
	    msg = BeeChat.Events.Messages.ConnectionStates.FAILED;
 	else if (status == Strophe.Status.DISCONNECTING)
	    msg = BeeChat.Events.Messages.ConnectionStates.DISCONNECTING;
 	else if (status == Strophe.Status.DISCONNECTED)
	    msg = BeeChat.Events.Messages.ConnectionStates.OFFLINE;
 	else if (status == Strophe.Status.CONNECTED) {
	    msg = BeeChat.Events.Messages.ConnectionStates.ONLINE;
	    _connection.addHandler(_onIQResult, null, 'iq', BeeChat.IQ.Types.RESULT, null, null);
	    _connection.addHandler(_onPresence, null, 'presence', null, null, null);
	    _connection.addHandler(_onMessageChat, null, 'message', BeeChat.Message.Types.CHAT, null, null);

	}

	_fire(BeeChat.Events.Identifiers.UPDATE_CONNECTION_STATE, msg);
    }

    /** PrivateFunction: _onIQResult
     *  Manage received IQ stanza of 'result' type
     *
     *  Parameters:
     *    (XMLElement) iq - The iq stanza received
     *
     */
    function _onIQResult(iq)
    {
	_roster.updateFromIQResult(iq);
	_fire(BeeChat.Events.Identifiers.UPDATE_ROSTER, _roster.getItems());

	return true;
    }

    /** PrivateFunction: _onPresence
     *  Manage received presence stanza
     *
     *  Parameters:
     *    (XMLElement) presence - The presence stanza received
     *
     */
    function _onPresence(presence)
    {
	if (Strophe.getBareJidFromJid($(presence).attr('from')).toLowerCase() != Strophe.getBareJidFromJid(_jid).toLowerCase()) {
	    _roster.updateFromPresence(presence);
	}
	_fire(BeeChat.Events.Identifiers.RECV_PRESENCE, _roster.getOnlineItems());
	return true;
    }

    /** PrivateFunction: _onMessageChat
     *  Manage received message stanza of 'chat' type
     *
     *  Parameters:
     *    (XMLElement) message - The message stanza received
     *
     */
    function _onMessageChat(message)
    {
	var data = {
	    contactBareJid: Strophe.getBareJidFromJid($(message).attr('from')),
	    msg: message
	};
	_msgTemp.push(data);

	if (_initialized == true) {
	    for (var key in _msgTemp) {
		if (typeof _msgTemp[key] != 'object')
		    continue;
		_fire(BeeChat.Events.Identifiers.RECV_CHAT_MESSAGE, _msgTemp[key]);
		_msgTemp.shift();
	    }
	}

	return true;
    }

    this.init(jid);
};


/** Constructor: BeeChat.Core.Roster
 *  Create a BeeChat.Core.Roster object
 *
 *  Parameters:
 *    (Object) items - The roster's items in object notation
 *
 *  Returns:
 *    A new BeeChat.Core.Roster.
 */
BeeChat.Core.Roster = function()
{
    if (!(this instanceof arguments.callee))
	return new BeeChat.Core.Roster();

    /** Private members
     *
     */
    _items = null;


    /** Constructor
     *
     */
    this.init = function()
    {
	_items = (arguments.length > 0) ? arguments[0] : {};
    }

    /** Accessors
     *
     */
    this.getItems = function()
    {
	return _items;
    }

    /** Mutators
     *
     */
    this.setItems = function(items)
    {
	for (var key in items) {
	    _items[key] = new BeeChat.Core.RosterItem(items[key]);
	}
    }

    this.setIcons = function(icons)
    {
	for (var key in icons) {
	    _items[key + '@' + BeeChat.DOMAIN].icon_small = icons[key].small;
	    _items[key + '@' + BeeChat.DOMAIN].icon_tiny = icons[key].tiny;
	}
    }

    this.setStatuses = function(statuses)
    {
	for (var key in statuses)  {
	    _items[key + '@' + BeeChat.DOMAIN].status = statuses[key];
	}
    }

    /** Function: updateFromIQResult
     *  Update the roster items from an IQ result stanza
     *
     *  Parameters:
     *    (XMLElement) iq - The IQ result stanza
     */
    this.updateFromIQResult = function(iq)
    {
	$(iq).find('item').each(function() {
		var attr = {
		    bareJid: Strophe.getBareJidFromJid($(this).attr('jid')).toLowerCase(),
		    name: $(this).attr('name'),
		    subscription: $(this).attr('subscription'),
		    groups: [],
		    presences: {}
		};

		$(this).find('group').each(function() {
			attr['groups'].push($(this).text());
		    });

		if (attr['groups'].length == 0)
		    attr['groups'].push(BeeChat.Roster.DEFAULT_GROUP);

		if (!_items[attr.bareJid])
		    _items[attr.bareJid] = new BeeChat.Core.RosterItem(attr);
		else {
		    _items[attr.bareJid].bareJid = attr.bareJid;
		    _items[attr.bareJid].name = attr.name;
		    _items[attr.bareJid].subscription = attr.subscription;
		    _items[attr.bareJid].groups = attr.groups;
		}
	    });
    }

    /** Function: updateFromPresence
     *  Update the roster items from a presence stanza
     *
     *  Parameters:
     *    (XMLElement) presence - The presence stanza
     *
     *  Returns:
     *    (String) The bare jid of the roster item who updated his presence
     */
    this.updateFromPresence = function(presence)
    {
	var jid = $(presence).attr('from').toLowerCase();
	var attr = {
	    bareJid: Strophe.getBareJidFromJid(jid),
	    name: null,
	    subscription: null,
	    groups: null,
	    presences: {}
	};

	attr.presences[jid] = {};
	attr.presences[jid].type = (!$(presence).attr('type')) ? 'available' : $(presence).attr('type');

	if (attr.presences[jid].type == 'available') {
	    $(presence).children().each(function() {
		    if (this.tagName == BeeChat.Presence.ChildElements.SHOW)
			attr.presences[jid].show = $(this).text();
		    if (this.tagName == BeeChat.Presence.ChildElements.STATUS)
			attr.presences[jid].status = $(this).text();
		});

	    if (!attr.presences[jid].show)
		attr.presences[jid].show = 'chat';
	} else {
	    attr.presences[jid].show = 'offline';
	}

	if (!_items[attr.bareJid])
	    _items[attr.bareJid] = new BeeChat.Core.RosterItem(attr);
	else
	    _items[attr.bareJid].presences[jid] = attr.presences[jid];
    }

    /** Function: getOnlineItems
     *
     *
     */
    this.getOnlineItems = function()
    {
	var sortedOnlineBareJid = [];
	var sortedOnlineItems = {};

	for (var key in _items) {
	    if (typeof _items[key] != 'object')
		continue;

	    var pres = _items[key].getStrongestPresence();

	    if (pres != null && pres.type == 'available') {
		sortedOnlineBareJid.push(key);
	    }
	}

	if (sortedOnlineBareJid.length > 1) {
	    sortedOnlineBareJid.sort();
	    sortedOnlineBareJid.sort(statusSort);
	}

	for (var key in sortedOnlineBareJid) {
	    sortedOnlineItems[sortedOnlineBareJid[key]] = _items[sortedOnlineBareJid[key]];
	}

	return (sortedOnlineItems);
    }

    /** Function: getSizeOnlineItems
     *  Return the number of available items
     *
     *  Returns:
     *    (int) The number of available items
     */
    this.getSizeOnlineItems = function()
    {
	var n = 0;

	for (var key in _items) {
	    if (typeof _items[key] != 'object')
		continue;

	    var pres = _items[key].getStrongestPresence();

	    if (pres != null && pres.type == 'available')
		++n;
	}
	return (n);
    }

    /** Function: getItemsUsernamesAsList
     *
     */
    this.getItemsUsernamesAsList = function()
    {
	var data = '';

	for (var key in _items) {
	    if (typeof _items[key] != 'object')
		continue;
	    data = data + Strophe.getNodeFromJid(key) + ',';
	}

	return (data);
    }

    /** PrivateFunction: statusSort
     *
     */
    function statusSort(x, y)
    {
	var xPres = _items[x].getStrongestPresence();
	var yPres = _items[y].getStrongestPresence();

	if (xPres != null && yPres != null)
	    return (BeeChat.Core.Roster.Utils.comparePresences(xPres, yPres));
	return (0);
    }

    this.init();
};

BeeChat.Core.Roster.Utils = {

    /** Function: comparePresences
     *  Compare the two presences x and y
     *
     *  Parameters:
     *    (Object) xPres - The x presence in object notation
     *    (Object) yPres - The y presence in object notation
     *
     *  Returns:
     *    0 if presence are equal, 1 if x > y, -1 if y > x
     *
     *  Note:
     *    Presences are tagged in the following order:
     *      ONLINE < DND < AWAY < XA < OFFLINE
     *
     */
    comparePresences: function(xPres, yPres)
    {
	var xRate = 0;
	var yRate = 0;

	if (xPres.type == 'unavailable')
	    xRate += BeeChat.Core.ReferenceTables.AvailabilityRates[xPres.type.toUpperCase()];
	if (yPres.type == 'unavailable')
	    yRate += BeeChat.Core.ReferenceTables.AvailabilityRates[yPres.type.toUpperCase()];

	if (xPres.show != null)
	    xRate += BeeChat.Core.ReferenceTables.AvailabilityRates[xPres.show.toUpperCase()];
	if (yPres.show != null)
	    yRate =+ BeeChat.Core.ReferenceTables.AvailabilityRates[yPres.show.toUpperCase()];

	if (xRate > yRate)
	    return (1);
	else if (xRate == yRate)
	    return (0);
	return (-1);
    }
};


/** Constructor: BeeChat.Core.RosterItem
 *  Create a BeeChat.Core.RosterItem object
 *
 *  Parameters:
 *    (Object) attr - The RosterItem's attributes in object notation
 *
 *  Returns:
 *    A new BeeChat.Core.RosterItem.
 */
BeeChat.Core.RosterItem = function()
{
    this.bareJid = (arguments.length > 0) ? arguments[0].bareJid : null;
    this.name = (arguments.length > 0) ? arguments[0].name : null;
    this.subscription = (arguments.length > 0) ? arguments[0].subscription : null;
    this.groups = (arguments.length > 0) ? arguments[0].groups : null;
    this.presences = (arguments.length > 0) ? arguments[0].presences : null;
    this.icon_small = (arguments.length > 0) ? arguments[0].icon_small : null;
    this.icon_tiny = (arguments.length > 0) ? arguments[0].icon_tiny : null;
    this.status = (arguments.length > 0) ? arguments[0].status : null;
};
BeeChat.Core.RosterItem.prototype = {
    /** Function: getStrongestPresence
     *  Return the strongest presence of the RosterItem
     *
     */
    getStrongestPresence: function()
    {
	var res = null;

	for (var key in this.presences) {
	    if (typeof this.presences[key] != 'object')
		continue;
	    if (res == null)
		res = this.presences[key];
	    else
		if (BeeChat.Core.Roster.Utils.comparePresences(this.presences[key], res) == -1)
		    res = this.presences[key];
	}
	return (res);
    }
};


/** Class: BeeChat.UI
 *  An object container for all BeeChat UI functions
 *
 */
BeeChat.UI = {
    HAS_FOCUS: true,

    Resources: {
	Paths: {
	    ICONS: '<?php echo $vars['config']->url; ?>mod/beechat/graphics/icons/',
	    MEMBER_PROFILE: '<?php echo $vars['url']; ?>pg/profile/'
	},

	/*
	Cookies: {
	    DOMAIN: 'beechannels.com',
	    FILENAME_CONN: 'beechat_conn'
	},
	*/

	Emoticons: {
    	    FILENAME_SMILE: 'emoticon_smile.png',
	    FILENAME_UNHAPPY: 'emoticon_unhappy.png',
	    FILENAME_GRIN: 'emoticon_grin.png',
	    FILENAME_EVILGRIN: 'emoticon_evilgrin.png',
	    FILENAME_SURPRISED: 'emoticon_surprised.png',
	    FILENAME_TONGUE: 'emoticon_tongue.png',
	    FILENAME_WINK: 'emoticon_wink.png'
	},

	Strings: {
	    Availability: {
		AVAILABLE: "<?php echo elgg_echo('beechat:availability:available'); ?>",
		CHAT: "<?php echo elgg_echo('beechat:availability:available'); ?>",
		ONLINE: "<?php echo elgg_echo('beechat:availability:available'); ?>",
		DND: "<?php echo elgg_echo('beechat:availability:dnd'); ?>",
		AWAY: "<?php echo elgg_echo('beechat:availability:away'); ?>",
		XA:"<?php echo elgg_echo('beechat:availability:xa'); ?>",
		OFFLINE: "<?php echo elgg_echo('beechat:availability:offline'); ?>"
	    },

	    Contacts: {
		BUTTON: "<?php echo elgg_echo('beechat:contacts:button'); ?>"
	    },

	    ChatMessages: {
		SELF: "<?php echo $_SESSION['user']->name; ?>",
		COMPOSING: "<?php echo elgg_echo('beechat:chat:composing'); ?>"
	    },

	    Box: {
		MINIMIZE: "<?php echo elgg_echo('beechat:box:minimize'); ?>",
		CLOSE: "<?php echo elgg_echo('beechat:box:close'); ?>",
		SHOWHIDE: "<?php echo elgg_echo('beechat:box:showhide'); ?>"
	    }
	},

	StyleClasses: {
	    Availability: {
		Left: {
		    ONLINE: 'beechat_left_availability_chat',
		    DND: 'beechat_left_availability_dnd',
		    AWAY: 'beechat_left_availability_away',
		    XA: 'beechat_left_availability_xa',
		    OFFLINE: 'beechat_left_availability_offline'
		},

		Right: {
		    ONLINE: 'beechat_right_availability_chat',
		    DND: 'beechat_right_availability_dnd',
		    AWAY: 'beechat_right_availability_away',
		    XA: 'beechat_right_availability_xa',
		    OFFLINE: 'beechat_right_availability_offline'
		},

		Control: {
		    UP: 'beechat_availability_switcher_control_up',
		    DOWN: 'beechat_availability_switcher_control_down'
		}
	    },

	    ChatBox: {
		MAIN: 'beechat_chatbox',
		TOP: 'beechat_chatbox_top',
		SUBTOP: 'beechat_chatbox_subtop',
		TOP_ICON: 'beechat_chatbox_top_icon',
		TOP_CONTROLS: 'beechat_chatbox_top_controls',
		CONTENT: 'beechat_chatbox_content',
		INPUT: 'beechat_chatbox_input',
		BOTTOM: 'beechat_chatbox_bottom',
		CONTROL: 'beechat_chatbox_control',
		STATE: 'beechat_chatbox_state',
		MESSAGE: 'beechat_chatbox_message',
		MESSAGE_SENDER: 'beechat_chatbox_message_sender',
		MESSAGE_DATE: 'beechat_chatbox_message_date'
	    },

	    ScrollBox: {
		SELECTED: 'beechat_scrollbox_selected'
	    },

	    BOX_CONTROL: 'beechat_box_control',
	    LABEL: 'beechat_label',
	    UNREAD_COUNT: 'beechat_unread_count'
	},

	Elements: {
	    ID_DIV_BAR: 'beechat',
	    ID_DIV_BAR_CENTER: 'beechat_center',
	    ID_DIV_BAR_RIGHT: 'beechat_right',

	    ID_TOOLTIP_TRIGGER: 'beechat_tooltip_trigger',

	    ID_SPAN_CONTACTS_BUTTON: 'beechat_contacts_button',
	    ID_SPAN_CLOSE_BOX: 'beechat_box_control_close',

	    ID_DIV_CONTACTS: 'beechat_contacts',
	    ID_DIV_CONTACTS_CONTROLS: 'beechat_contacts_controls',
	    ID_SPAN_CONTACTS_CONTROL_MINIMIZE: 'beechat_contacts_control_minimize',
	    ID_DIV_CONTACTS_CONTENT: 'beechat_contacts_content',
	    ID_UL_CONTACTS_LIST: 'beechat_contacts_list',

	    ID_DIV_AVAILABILITY_SWITCHER: 'beechat_availability_switcher',
	    ID_SPAN_AVAILABILITY_SWITCHER_CONTROL: 'beechat_availability_switcher_control',
	    ID_SPAN_CURRENT_AVAILABILITY: 'beechat_current_availability',
	    ID_UL_AVAILABILITY_SWITCHER_LIST: 'beechat_availability_switcher_list',

	    ID_DIV_CHATBOXES: 'beechat_chatboxes',

	    ID_DIV_SCROLLBOXES: 'beechat_scrollboxes'
	}
    },


    /** Function: initialize
     *  Initialize the BeeChat UI
     *
     */
    initialize: function(ts, token)
    {
	this.ts = ts;
	this.token = token;
	$('#' + BeeChat.UI.Resources.Elements.ID_TOOLTIP_TRIGGER).tooltip({
		offset: [-3, 8],
		effect: 'fade'
	    });

	$('#accountlinks').find('li').filter('[class=last]').bind('click', function() {
		if (g_beechat_user != null)
		    g_beechat_user.disconnect();
	    });

	BeeChat.UI.AvailabilitySwitcher.initialize(BeeChat.Presence.ShowElements.CHAT);
	BeeChat.UI.ContactsList.initialize();
	BeeChat.UI.ScrollBoxes.initialize();
	BeeChat.UI.loadConnection();
    },

    /** Function: getUserDetails
     *  Retrieve user details
     *
     *  Returns:
     *    User details in object notation.
     *
     */
    addActionTokens: function(url_string)
    {
	return url_string + "?__elgg_ts="+this.ts + "&__elgg_token=" + this.token;
    },

    getUserDetails: function(cb_func)
    {
	var json = null;
	var self = this;

	$.ajax({
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/get_details"; ?>'),
		async: true,
		dataType: 'json',
		success: function(data) {
			cb_func(data);
		}
	    });

	return (json);
    },

    /** Function: connect
     *  Create the user and connect him to the BOSH service
     *
     *  Parameters:
     *    (Object) conn - Running connection informations in object notation
     */
    connect: function()
    {
	var conn = (arguments.length > 0) ? arguments[0] : null;
	var userDetails = {
	    jid: (conn != null) ? conn.jid : null,
	    password: null
	}
	var self = this;
	if (conn == null || (conn != null && conn.attached)) {
	    BeeChat.UI.getUserDetails(function(retrievedUserDetails) {
	    	userDetails.jid = retrievedUserDetails.username + '@' + BeeChat.DOMAIN + '/' + BeeChat.RESOURCE;
	    	userDetails.password = retrievedUserDetails.password;
		self.connect_end(conn, userDetails)
	    });
	}
	else
	        this.connect_end(conn, userDetails)
    },

    connect_end: function(conn, userDetails)
    {
	g_beechat_user = new BeeChat.Core.User(userDetails.jid);
	g_beechat_user.addObserver(BeeChat.Events.Identifiers.UPDATE_CONNECTION_STATE, BeeChat.UI.updateConnectionStatus);
	g_beechat_user.addObserver(BeeChat.Events.Identifiers.UPDATE_ROSTER, BeeChat.UI.onRosterUpdate);
	g_beechat_user.addObserver(BeeChat.Events.Identifiers.RECV_PRESENCE, BeeChat.UI.ContactsList.update);
	g_beechat_user.addObserver(BeeChat.Events.Identifiers.RECV_CHAT_MESSAGE, BeeChat.UI.onChatMessage);

	if (conn == null || (conn != null && conn.attached))
	    g_beechat_user.connect(userDetails.password);
	else
	    g_beechat_user.attach(conn.sid, conn.rid);
    },
 
    /** Function: disconnect
     *  Terminate the user's XMPP session
     *
     */
    disconnect: function()
    {
	g_beechat_user.disconnect();
    },

    /** Function: updateConnectionStatus
     *
     */
    updateConnectionStatus: function(connStatusMsg)
    {
	BeeChat.UI.ContactsList.updateButtonText(connStatusMsg);
	if (connStatusMsg == BeeChat.Events.Messages.ConnectionStates.ONLINE) {
	    if (!g_beechat_user.isAttached()) {
		g_beechat_user.requestRoster();
		//BeeChat.UI.ContactsList.toggleDisplay();
		$('#' + BeeChat.UI.Resources.Elements.ID_UL_CONTACTS_LIST).show();
		$('.' + BeeChat.UI.Resources.StyleClasses.ChatBox.INPUT + '>textarea').removeAttr('disabled');
	    }
	    if (g_beechat_user.isAttached()) {
		BeeChat.UI.loadState();
	    }

	    $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).attr('class', 'online');
	    BeeChat.UI.saveConnection();
	}
	else if (connStatusMsg == BeeChat.Events.Messages.ConnectionStates.OFFLINE) {
	    var contactsBoxElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CONTACTS);

	    if (!contactsBoxElm.is(':hidden'))
		BeeChat.UI.ContactsList.toggleDisplay();

	    $('#' + BeeChat.UI.Resources.Elements.ID_UL_CONTACTS_LIST).empty();
	    BeeChat.UI.AvailabilitySwitcher.initialize(BeeChat.Presence.ShowElements.CHAT);
	    BeeChat.UI.ContactsList.updateButtonText(BeeChat.UI.Resources.Strings.Contacts.BUTTON);
	    $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).attr('class', 'offline');
	    $('.' + BeeChat.UI.Resources.StyleClasses.ChatBox.INPUT + '>textarea').attr('disabled', 'true');
	    $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CHATBOXES).children().hide();
	    $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES).find('ul').children()
	    .attr('class', BeeChat.UI.Resources.StyleClasses.LABEL + ' ' + BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Left[BeeChat.Presence.Types.UNAVAILABLE.toUpperCase()]);
	    g_beechat_user = null;
	    BeeChat.UI.saveConnection();
	}
    },

    /** Function: saveConnection
     *  Save connection informations (non sensible data) in $_SESSION.
     *
     */
    saveConnection: function()
    {
	var conn = null;

	if (g_beechat_user != null) {
	    var userConn = g_beechat_user.getConnection();

	    conn = {
		'jid': userConn.jid,
		'sid': userConn.sid,
		'rid': userConn.rid,
		'attached': g_beechat_user.isAttached()
	    };
	}
	var self = this;
	
	$.ajax({
		type: 'POST',
		async: false,
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/save_state"; ?>'),
		data: { beechat_conn: JSON.stringify(conn) },
		async:false
	    });

	/*
	$.cookie(BeeChat.UI.Resources.Cookies.FILENAME_CONN, null);
	$.cookie(BeeChat.UI.Resources.Cookies.FILENAME_CONN, JSON.stringify(conn), {path: '/', domain: BeeChat.UI.Resources.Cookies.DOMAIN});
	*/
    },

    /** Function: loadConnection
     *  Check if a connection already exists. In the case that a connection exists,
     *  this function triggers the connection process.
     *
     */
    loadConnection: function()
    {
	var self = this;
	$.ajax({
		type: 'GET',
		async: false,
		cache: false,
		dataType: 'json',
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/get_connection"; ?>'),
		success: function(conn) {
		    if (conn != null) {
			if (conn.attached)
			    BeeChat.UI.connect();
			else
			    BeeChat.UI.connect(conn);
			}
		},
		error: function() {
		    BeeChat.UI.connect();
		}
	    });

	/*
	var conn = JSON.parse($.cookie(BeeChat.UI.Resources.Cookies.FILENAME_CONN));

	if (conn != null) {
	    if (conn.attached)
		BeeChat.UI.connect();
	    else
		BeeChat.UI.connect(conn);
	} else
	    BeeChat.UI.connect();
	*/
    },

    /** Function: saveState
     *  Save app state in $_SESSION
     *
     */
    saveState: function()
    {
	var self = this;
	var currentAvailabilityClass = $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CURRENT_AVAILABILITY).attr('class');
	var currentAvailability = currentAvailabilityClass.substr(currentAvailabilityClass.lastIndexOf('_') + 1);

	var data = {
	    availability: currentAvailability,
	    contacts: g_beechat_roster_items,
	    chats: {},
	    contacts_list: {
		minimized: $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CONTACTS).is(':hidden')
	    }
	};

	$('#' + BeeChat.UI.Resources.Elements.ID_DIV_CHATBOXES).children().each(function() {
		var contactBareJid = $(this).attr('bareJid');
		//var contactBareJid = $(this).data('bareJid');

		data.chats[contactBareJid] = {
		    'html_content': $(this).children().filter('[bareJid=' + contactBareJid + ']').html(),
		    'minimized': $(this).is(':hidden'),
		    'unread': BeeChat.UI.UnreadCountBox.getElm(contactBareJid).text()
		};
	    });

	$.ajax({
		type: 'POST',
		async: false,
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/save_state"; ?>'),
		data: { beechat_state: JSON.stringify(data) },
		async:false 
	    });
    },

    /** Function: loadState
     *  Load app state from $_SESSION
     *
     */
    loadState: function()
    {
	var self = this;
	$.ajax({
		type: 'GET',
		async: true,
		cache: false,
		dataType: 'json',
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/get_state"; ?>'),
		success: function(json) {
		    BeeChat.UI.AvailabilitySwitcher.initialize(json.availability);

		    if (!json.contacts_list.minimized) {
				$('#' + BeeChat.UI.Resources.Elements.ID_DIV_CONTACTS).show();
				BeeChat.UI.ContactsList.showedStyle();
		    }

		    g_beechat_user.getRoster().setItems(json.contacts);
		    g_beechat_roster_items = g_beechat_user.getRoster().getItems();
		    BeeChat.UI.ContactsList.update(g_beechat_user.getRoster().getOnlineItems())
		    g_beechat_user.setInitialized(true);

		    var scrollBoxesElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES);
		    var scrollBoxElmToShow = null;

		    // Load save chats
		    for (var key in json.chats) {
			BeeChat.UI.ScrollBoxes.add(key);

			var chatBoxElm = BeeChat.UI.ChatBoxes.getChatBoxElm(key);

			if (!json.chats[key].minimized) {
			    scrollBoxElmToShow = BeeChat.UI.ScrollBoxes.getScrollBoxElm(key);
			}

			var chatBoxContentElm = chatBoxElm.children().filter('[bareJid=' + key + ']');

			chatBoxContentElm.append(json.chats[key].html_content);
			chatBoxContentElm.attr({scrollTop: chatBoxContentElm.attr('scrollHeight')});

			BeeChat.UI.UnreadCountBox.update(key, json.chats[key].unread);
		    }
		    if (scrollBoxElmToShow != null)
			scrollBoxesElm.trigger('goto', scrollBoxesElm.find('ul').children().index(scrollBoxElmToShow));
		    else
			scrollBoxesElm.trigger('goto', 0);

		    g_beechat_user.sendPresenceAvailability(json.availability, '');
		    BeeChat.UI.ScrollBoxes.isInitialized = true;

			  for (var key in json.chats) {
					if (json.chats[key].minimized) {
						BeeChat.UI.ChatBoxes.getChatBoxElm(key).hide();
						BeeChat.UI.ScrollBoxes.unselect(key);
					}
				}

		},
		error: function() {
		    BeeChat.UI.ContactsList.initialize();
		}
	    });
    },

    /** Function: loadRosterItemsIcons
     *
     */
    loadRosterItemsIcons: function()
    {
	var data = g_beechat_user.getRoster().getItemsUsernamesAsList();
	var self = this;

	$.ajax({
		type: 'POST',
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/get_icons"; ?>'),
		async: true,
		cache: false,
		data: {'beechat_roster_items_usernames': data},
		dataType: 'json',
		success: function(json) {
		    g_beechat_user.getRoster().setIcons(json);
		    g_beechat_roster_items = g_beechat_user.getRoster().getItems();
		}
	    });
    },

    /** Function: loadRosterItemsStatuses
     *
     */
    loadRosterItemsStatuses: function()
    {
	var data = g_beechat_user.getRoster().getItemsUsernamesAsList();

	var self = this;
	$.ajax({
		type: 'POST',
		url: self.addActionTokens('<?php echo $vars['url'] . "action/beechat/get_statuses"; ?>'),
		async: true,
		cache: false,
		data: {'beechat_roster_items_usernames': data},
		dataType: 'json',
		success: function(json) {
		    g_beechat_user.getRoster().setStatuses(json);
		    g_beechat_roster_items = g_beechat_user.getRoster().getItems();
		}
	    });
    },

    /** Function: onRosterUpdate
     *  Notified by core on a roster update
     *
     */
    onRosterUpdate: function(rosterItems)
    {
	g_beechat_roster_items = rosterItems;

	if (!g_beechat_user.isInitialized()) {

	    BeeChat.UI.loadRosterItemsIcons();
	    BeeChat.UI.loadRosterItemsStatuses();
	    g_beechat_user.sendInitialPresence();
	}
    },

    /** Function: onChatMessage
     *
     */
    onChatMessage: function(data)
    {
	if ($(data.msg).find('body').length == 0) {
	    BeeChat.UI.ChatBoxes.updateChatState(data.contactBareJid, data.msg);
	}
	else {
	    BeeChat.UI.ChatBoxes.update(data.contactBareJid, BeeChat.UI.Utils.getContactName(data.contactBareJid), Strophe.getText($(data.msg).find('body')[0]));
	}
    }
};


/** Class: BeeChat.UI.Resources.ReferenceTables
 *  An object container for all reference tables
 *
 */
BeeChat.UI.Resources.ReferenceTables = {
    Styles: {
	Availability: {
	    Left: {
		AVAILABLE: BeeChat.UI.Resources.StyleClasses.Availability.Left.ONLINE,
		CHAT: BeeChat.UI.Resources.StyleClasses.Availability.Left.ONLINE,
		DND: BeeChat.UI.Resources.StyleClasses.Availability.Left.DND,
		AWAY: BeeChat.UI.Resources.StyleClasses.Availability.Left.AWAY,
		XA: BeeChat.UI.Resources.StyleClasses.Availability.Left.XA,
		UNAVAILABLE: BeeChat.UI.Resources.StyleClasses.Availability.Left.OFFLINE,
		OFFLINE: BeeChat.UI.Resources.StyleClasses.Availability.Left.OFFLINE
	    },

	    Right: {
		AVAILABLE: BeeChat.UI.Resources.StyleClasses.Availability.Right.ONLINE,
		CHAT: BeeChat.UI.Resources.StyleClasses.Availability.Right.ONLINE,
		DND: BeeChat.UI.Resources.StyleClasses.Availability.Right.DND,
		AWAY: BeeChat.UI.Resources.StyleClasses.Availability.Right.AWAY,
		XA: BeeChat.UI.Resources.StyleClasses.Availability.Right.XA,
		UNAVAILABLE: BeeChat.UI.Resources.StyleClasses.Availability.Right.OFFLINE,
		OFFLINE: BeeChat.UI.Resources.StyleClasses.Availability.Right.OFFLINE
	    }
	}
    }
};


/** Class: BeeChat.UI.ContactsList
 *  An object container for all ContactsList functions
 *
 */
BeeChat.UI.ContactsList = {
    /** Function: initialize
     *  Initialize the contacts list by binding elements
     *
     */
    initialize: function()
    {
		$('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_CONTROL_MINIMIZE).unbind('click').bind('click', BeeChat.UI.ContactsList.toggleDisplay);
	$('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).unbind('click').bind('click', function() {
		if (g_beechat_user == null)
		    BeeChat.UI.connect();
		else
		    BeeChat.UI.ContactsList.toggleDisplay();
	    });
    },

    /** Function: update
     *  Update the contacts list content
     *
     *  Parameters:
     *    (Object)(BeeChat.Core.RosterItem) onlineRosterItems - A hash of RosterItems in object notation
     *
     */
    update: function(onlineRosterItems)
    {
	var contactsListElm = $('#' + BeeChat.UI.Resources.Elements.ID_UL_CONTACTS_LIST);

	contactsListElm.children().each(function() {
		var contactBareJid = $(this).attr('bareJid');

		if (g_beechat_roster_items != null) {
		    if ($.inArray(contactBareJid, onlineRosterItems) == -1) {
			BeeChat.UI.ScrollBoxes.updateAvailability(contactBareJid);
			$(this).remove();
		    }
		}
	    });

	for (var key in onlineRosterItems) {
	    if (typeof onlineRosterItems[key] != 'object')
		continue;

	    var contactElm = contactsListElm.find('li').filter('[bareJid=' + key + ']');

	    if (contactElm.length == 0) {
		contactElm = $('<li></li>')
		    .attr('bareJid', key)
		    .append($('<img />')
			    .attr('src', g_beechat_roster_items[key].icon_tiny))
		    .append(BeeChat.UI.Utils.getTruncatedContactName(key, 25))
		    .appendTo(contactsListElm)
		    .bind('click', function() {
					if (!BeeChat.UI.ChatBoxes.getChatBoxElm($(this).attr('bareJid')).is(':visible')) {
						BeeChat.UI.ContactsList.toggleDisplay();
					}

			    BeeChat.UI.ScrollBoxes.add($(this).attr('bareJid'), true);
			});
	    }

	    BeeChat.UI.ContactsList.updateContactAvailability(contactElm, key);
	}

	BeeChat.UI.ContactsList.updateButtonText(BeeChat.UI.Resources.Strings.Contacts.BUTTON + ' (<strong>' + g_beechat_user.getRoster().getSizeOnlineItems() + '</strong>)');
    },

    /** Function: updateContactAvailability
     *
     */
    updateContactAvailability: function(contactElm, contactBareJid)
    {
	// Update from contactsList
	contactElm.attr('class', BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Right[g_beechat_roster_items[contactBareJid].getStrongestPresence().show.toUpperCase()]);

	// Update from scrollBoxes
	BeeChat.UI.ScrollBoxes.updateAvailability(contactBareJid);
    },

    /** Function: updateButtonText
     *
     *
     */
    updateButtonText: function(msg)
    {
	$('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).html(msg);
    },

    /** Function: toggleDisplay
     *  Toggle the contacts box display (hide | show)
     *
     */
    toggleDisplay: function()
    {
	var contactsBoxElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CONTACTS);

	contactsBoxElm.toggle();
	if (contactsBoxElm.is(':hidden')) {
	    BeeChat.UI.ContactsList.hiddenStyle();
	} else {
	    BeeChat.UI.ContactsList.showedStyle();
	}
	$('#' + BeeChat.UI.Resources.Elements.ID_UL_AVAILABILITY_SWITCHER_LIST).hide();
    },

    /** Function: hiddenStyle
     *
     */
    hiddenStyle: function()
    {
	$('#' + BeeChat.UI.Resources.Elements.ID_DIV_BAR_RIGHT).css({'border-left': '1px solid #BBBBBB', 'border-right': '1px solid #BBBBBB', 'background-color': '#DDDDDD'});
    },

    /** Function: showedStyle
     *
     */
    showedStyle: function()
    {
	$('#' + BeeChat.UI.Resources.Elements.ID_DIV_BAR_RIGHT).css({'border-left': '1px solid #666666', 'border-right': '1px solid #666666', 'background-color': 'white'});
    }
};


/** Class: BeeChat.UI.AvailabilitySwitcher
 *  An object container for all AvailabilitySwitcher functions
 *
 */
BeeChat.UI.AvailabilitySwitcher = {
    /** Function: initialize
     *  Initialize the availability switcher by setting the current user's availability
     *  and binding actions
     *
     */
    initialize: function(availability)
    {
		$('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CURRENT_AVAILABILITY).unbind('click').bind('click', BeeChat.UI.AvailabilitySwitcher.toggleListDisplay);
		
		$('#' + BeeChat.UI.Resources.Elements.ID_SPAN_AVAILABILITY_SWITCHER_CONTROL).unbind('click').bind('click', BeeChat.UI.AvailabilitySwitcher.toggleListDisplay);
		
		$('#' + BeeChat.UI.Resources.Elements.ID_UL_AVAILABILITY_SWITCHER_LIST).find('li').each(function() {
		$(this).unbind('click').bind('click', function() {
			var availabilityClass = $(this).attr('class');
			var availability = availabilityClass.substr(availabilityClass.lastIndexOf('_') + 1);

			if (availability == 'offline')
			    BeeChat.UI.disconnect();
			else {
			    g_beechat_user.sendPresenceAvailability(availability, '');
			    BeeChat.UI.AvailabilitySwitcher.update(availability);
			    $('#' + BeeChat.UI.Resources.Elements.ID_UL_AVAILABILITY_SWITCHER_LIST).hide('slow');
			    $('#' + BeeChat.UI.Resources.Elements.ID_UL_CONTACTS_LIST).show('slow');
			}
		    });
	    });
	BeeChat.UI.AvailabilitySwitcher.update(availability);
    },

    /** Function: update
     *  Update the current user's availability
     *
     *  Parameters:
     *    (BeeChat.Presence.ShowElements) availability - The current user's availability
     */
    update: function(availability)
    {
	var upperCasedAvailability = availability.toUpperCase();

	$('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CURRENT_AVAILABILITY)
	.attr('class', BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Left[upperCasedAvailability])
	.text(BeeChat.UI.Resources.Strings.Availability[upperCasedAvailability]);

	if (availability == 'chat')
	    $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).attr('class', 'online');
	else if (availability == 'xa' || availability == 'away')
	    $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).attr('class', 'away');
	else if (availability == 'dnd')
	    $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_CONTACTS_BUTTON).attr('class', 'dnd');
    },

    /** Function: switchControlClass
     *
     */
    switchControlClass: function()
    {
	var switcherControlElm = $('#' + BeeChat.UI.Resources.Elements.ID_SPAN_AVAILABILITY_SWITCHER_CONTROL);

	if (switcherControlElm.attr('class') == BeeChat.UI.Resources.StyleClasses.Availability.Control.UP)
	    switcherControlElm.attr('class', BeeChat.UI.Resources.StyleClasses.Availability.Control.DOWN);
	else
	    switcherControlElm.attr('class', BeeChat.UI.Resources.StyleClasses.Availability.Control.UP);
    },

    /** Function: toggleListDisplay
     *
     */
    toggleListDisplay: function()
    {
		BeeChat.UI.AvailabilitySwitcher.switchControlClass();
		$('#' + BeeChat.UI.Resources.Elements.ID_UL_CONTACTS_LIST).toggle('slow');
		$('#' + BeeChat.UI.Resources.Elements.ID_UL_AVAILABILITY_SWITCHER_LIST).toggle('slow');
    }
};


/** Class: BeeChat.UI.ScrollBoxes
 *  An object container for all ScrollBoxes related functions
 *
 */
BeeChat.UI.ScrollBoxes = {
    isInitialized: false,

    /** Function: initialize
     *
     */
    initialize: function() {
	var $prev = $('#beechat_center_prev'),
	$next = $('#beechat_center_next');

	$('#' + BeeChat.UI.Resources.Elements.ID_DIV_BAR_CENTER).serialScroll({
		    target: '#beechat_scrollboxes',
		    items: 'li',
		    prev: '#beechat_center_prev',
		    next: '#beechat_center_next',
		    axys: 'x',
		    start: 2,
		    step: -1,
		    interval: 0,
		    duration: 0,
		    cycle: false,
		    force: true,
		    jump: true,
		    lock: true,
		    lazy: true,
		    constant: true,

		    onBefore: function(e, elem, $pane, $items, pos) {
		      $next.add($prev).hide();
		      $prev.add($next).hide();
		      if (pos != 0) {
			  $next.show();
		      }
		      if (pos != $items.length - 1)
			  $prev.show();
		    },

		    onAfter: function(elem) {
		    	BeeChat.UI.ChatBoxes.takeStand($(elem).attr('bareJid'));
			BeeChat.UI.ScrollBoxes.isInitialized = true;
		    }
	    });
    },

    /** Function: add
     *  Add a scrollbox to the scrollboxes bar
     *
     */
    add: function(contactBareJid)
    {
	var scrollBoxesElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES);
	var scrollBoxElm = scrollBoxesElm.find('ul').children().filter('[bareJid=' + contactBareJid + ']');

	if (scrollBoxElm.length == 0) {
	    var availClass = null;
	    var pres = g_beechat_roster_items[contactBareJid] != null ? g_beechat_roster_items[contactBareJid].getStrongestPresence() : null;

	    if (pres != null)
		availClass = BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Left[pres.show.toUpperCase()];
	    else
		availClass = BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Left[BeeChat.Presence.Types.UNAVAILABLE.toUpperCase()];

	    scrollBoxElm = $('<li></li>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.LABEL + ' ' + availClass)
		.attr('bareJid', contactBareJid)
		.attr('title', BeeChat.UI.Resources.Strings.Box.SHOWHIDE)
		.text(BeeChat.UI.Utils.getTruncatedContactName(contactBareJid, 11))
		.append($('<span></span>')
			.attr('class', BeeChat.UI.Resources.StyleClasses.BOX_CONTROL)
			.attr('id', BeeChat.UI.Resources.Elements.ID_SPAN_CLOSE_BOX)
			.text('X')
			.attr('title', BeeChat.UI.Resources.Strings.Box.CLOSE)
			.bind('click', function() {
				var scrollBoxesElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES);

				BeeChat.UI.ChatBoxes.remove($(this).parent().attr('bareJid'));
				scrollBoxesElm.trigger('goto', scrollBoxesElm.find('ul').children().index(BeeChat.UI.ScrollBoxes.getSelectedScrollBoxElm()));
			    }));

	    scrollBoxesElm.find('ul').append(scrollBoxElm);
	    BeeChat.UI.ChatBoxes.add(contactBareJid);
	    if (arguments.length == 2 && arguments[1])
		scrollBoxesElm.trigger('goto', scrollBoxesElm.find('ul').children().index(scrollBoxElm));
	    BeeChat.UI.loadRosterItemsIcons();
	    BeeChat.UI.loadRosterItemsStatuses();
	} else {
	    scrollBoxesElm.trigger('goto', scrollBoxesElm.find('ul').children().index(scrollBoxElm));
	}
    },

    /** Function: remove
     *
     */
    remove: function(contactBareJid)
    {
	BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid).remove();
    },

    /** Function: unselect
     *
     */
    unselect: function(contactBareJid)
    {
	var scrollBoxElm = BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid);
	scrollBoxElm.attr('class', (scrollBoxElm.attr('class')).replace(/beechat_scrollbox_selected/, ''));
    },

    /** Function: select
     *
     */
    select: function(contactBareJid)
    {
	var scrollBoxElm = BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid);
	var scrollBoxElmClasses = scrollBoxElm.attr('class');

	if (scrollBoxElmClasses.search(/beechat_scrollbox_selected/) == -1)
	    scrollBoxElm.attr('class', scrollBoxElmClasses + ' ' + BeeChat.UI.Resources.StyleClasses.ScrollBox.SELECTED);
    },

    /** Function: updateAvailability
     *
     */
    updateAvailability: function(contactBareJid)
    {
	var pres = g_beechat_roster_items[contactBareJid].getStrongestPresence();
	var scrollBoxElm = BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid);
	var scrollBoxElmClasses = scrollBoxElm.attr('class');
	var updatedAvailability = null;

	if (pres != null)
	    updatedAvailability = BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Left[pres.show.toUpperCase()];
	else
	    updatedAvailability = BeeChat.UI.Resources.ReferenceTables.Styles.Availability.Left[BeeChat.Presence.Types.UNAVAILABLE.toUpperCase()];

	if (scrollBoxElmClasses == undefined || scrollBoxElmClasses.search(/(beechat_left_availability_)/g) == -1) {
	    scrollBoxElm.attr('class', BeeChat.UI.Resources.StyleClasses.LABEL + ' ' + updatedAvailability);
	} else {
	    updatedAvailability = updatedAvailability.replace(/(beechat_left_availability)/g, '');

	    scrollBoxElmClasses = scrollBoxElmClasses.replace(/(_chat)/g, updatedAvailability);
	    scrollBoxElmClasses = scrollBoxElmClasses.replace(/(_dnd)/g, updatedAvailability);
	    scrollBoxElmClasses = scrollBoxElmClasses.replace(/(_away)/g, updatedAvailability);
	    scrollBoxElmClasses = scrollBoxElmClasses.replace(/(_xa)/g, updatedAvailability);
	    scrollBoxElmClasses = scrollBoxElmClasses.replace(/(_offline)/g, updatedAvailability);

	    scrollBoxElm.attr('class', scrollBoxElmClasses);
	}
    },

    /** Function: getSelectedScrollBoxElm
     *
     */
    getSelectedScrollBoxElm: function(contactBareJid)
    {
	var elm = undefined;

	$('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES).find('ul').children().each(function() {
		if ($(this).attr('class').search(/beechat_scrollbox_selected/) != -1)
		    elm = $(this);
	    });

	return (elm);
    },

    /** Function: getScrollBoxElm
     *
     */
    getScrollBoxElm: function(contactBareJid)
    {
	return $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES).find('ul').children().filter('[bareJid=' + contactBareJid + ']');
    }
};


/** Class: BeeChat.UI.ChatBoxes
 *  An object container for all ChatBoxes related functions
 *
 */
BeeChat.UI.ChatBoxes = {
    dateLastComposing: {},
    lastTimedPauses: {},

    /** Function: add
     *
     */
    add: function(contactBareJid)
    {
	var chatBoxes = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CHATBOXES);

	if ($(chatBoxes).children().filter('[bareJid=' + contactBareJid + ']').length == 0) {
	    var chatBox = $('<div></div>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.MAIN)
		.attr('bareJid', contactBareJid)
		.hide();

	    var chatBoxTop = $('<div></div>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.TOP)
		.append($('<a></a>')
			.attr('href', BeeChat.UI.Resources.Paths.MEMBER_PROFILE + Strophe.getNodeFromJid(contactBareJid))
			.append($('<img />')
				.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.TOP_ICON)
				.attr('src', g_beechat_roster_items[contactBareJid].icon_small)))
		.append($('<span></span>')
			.attr('class', BeeChat.UI.Resources.StyleClasses.LABEL)
			.html('<a href="' + BeeChat.UI.Resources.Paths.MEMBER_PROFILE + Strophe.getNodeFromJid(contactBareJid) + '">' + BeeChat.UI.Utils.getTruncatedContactName(contactBareJid) + '</a>'))
		.append($('<div></div>')
			.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.TOP_CONTROLS)
			.append($('<span></span>')
				.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.CONTROL)
				.attr('id', BeeChat.UI.Resources.Elements.ID_SPAN_CLOSE_BOX)
				.text('X')
				.attr('title', BeeChat.UI.Resources.Strings.Box.CLOSE)
				.bind('click', function() {
					BeeChat.UI.ChatBoxes.remove($(this).parent().parent().parent().attr('bareJid'));
				    }))
			.append($('<span></span>')
				.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.CONTROL)
				.attr('id', BeeChat.UI.Resources.Elements.ID_SPAN_CLOSE_BOX)
				.text('_')
				.attr('title', BeeChat.UI.Resources.Strings.Box.MINIMIZE)
				.css({'font-size': '1.6em', 'position': 'relative', 'line-height': '4px'})
				.bind('click', function() {
					BeeChat.UI.ScrollBoxes.unselect($(this).parent().parent().parent().attr('bareJid'));
					$(this).parent().parent().parent().fadeOut('slow');
				    })));

	    var chatBoxSubTop = $('<div></div>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.SUBTOP)
		.append(BeeChat.UI.Utils.getTruncatedContactStatus(g_beechat_roster_items[contactBareJid].status != undefined ? g_beechat_roster_items[contactBareJid].status : ''));

	    var chatBoxContent = $('<div></div>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.CONTENT)
		.attr('bareJid', contactBareJid);

	    var chatBoxInput = $('<div></div>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.INPUT)
		.append($('<textarea></textarea>')
			.attr('bareJid', contactBareJid)
			.bind('keypress', BeeChat.UI.ChatBoxes.onTypingMessage)
			.bind('keyup', function(e) {
				if ((e.keyCode ? e.keyCode : e.which) == 13)
				    $(this).attr('value', '');
			    }));

	    var chatBoxBottom = $('<div></div>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.BOTTOM)
		.append($('<span></span>')
			.append($('<span></span>')));

	    chatBox.append(chatBoxTop).append(chatBoxSubTop).append(chatBoxContent).append(chatBoxInput).append(chatBoxBottom).appendTo(chatBoxes);
	}
    },

    /** Function: takeStand
     *
     */
    takeStand: function(contactBareJid)
    {
	var chatBoxesElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CHATBOXES).children();
	var chatBoxElm = chatBoxesElm.filter('[bareJid=' + contactBareJid + ']');
	var chatBoxContentElm = chatBoxElm.children().filter('[bareJid=' + contactBareJid + ']');
	var scrollBoxesElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES);
	var scrollBoxElm = BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid);

	if (!chatBoxElm.is(':hidden')) {
	    BeeChat.UI.ScrollBoxes.unselect(contactBareJid);
	    chatBoxElm.hide();
	} else {
	    // Hide all other chatboxes
	    $.each(chatBoxesElm.filter('[bareJid!=' + contactBareJid + ']'), function() {
		    BeeChat.UI.ScrollBoxes.unselect($(this).attr('bareJid'));
		    $(this).hide();
		});
	    // Add selected scrollbox style
	    BeeChat.UI.ScrollBoxes.select(contactBareJid);
	    // Remove UnreadCountBox
	    BeeChat.UI.UnreadCountBox.remove(contactBareJid);
	    // Position the chatbox
	    var pos = scrollBoxElm.position().left - (chatBoxElm.width() - scrollBoxElm.width()) + 24;
	    chatBoxElm.show().css({'left': pos});
	    // Scroll down the content of the chatbox
	    chatBoxContentElm.attr({scrollTop: chatBoxContentElm.attr('scrollHeight')});
	    // Focus textarea
	    chatBoxElm.children().filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.INPUT + ']').find('textarea').focus();
	}
    },

    /** Function: onTypingMessage
     *
     */
    onTypingMessage: function(e)
    {
	var keyCode = (e.keyCode) ? e.keyCode : e.which;
	var contactBareJid = $(this).attr('bareJid');

	if (keyCode == 13 && $(this).val() != '') {
	    g_beechat_user.sendChatMessage(contactBareJid, jQuery.trim($(this).val()));
	    BeeChat.UI.ChatBoxes.update(contactBareJid, BeeChat.UI.Utils.truncateString(BeeChat.UI.Resources.Strings.ChatMessages.SELF, 24), $(this).val());
	    clearTimeout(BeeChat.UI.ChatBoxes.lastTimedPauses[contactBareJid]);
	    BeeChat.UI.ChatBoxes.lastTimedPauses[contactBareJid] = null;
	} else {
	    var nowTime = new Date().getTime();

	    if (BeeChat.UI.ChatBoxes.dateLastComposing[contactBareJid] == null || BeeChat.UI.ChatBoxes.dateLastComposing[contactBareJid] + 2000 < nowTime) {
		BeeChat.UI.ChatBoxes.dateLastComposing[contactBareJid] = nowTime;
		g_beechat_user.sendChatStateMessage(contactBareJid, BeeChat.Message.ChatStates.COMPOSING);
	    }

	    clearTimeout(BeeChat.UI.ChatBoxes.lastTimedPauses[contactBareJid]);
	    BeeChat.UI.ChatBoxes.lastTimedPauses[contactBareJid] = setTimeout('g_beechat_user.sendChatStateMessage(\'' + contactBareJid + '\', BeeChat.Message.ChatStates.PAUSED)', 2000);

	    var chatBoxTextAreaElm = BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid).children().filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.INPUT + ']').find('textarea');
	    chatBoxTextAreaElm.attr({scrollTop: chatBoxTextAreaElm.attr('scrollHeight')});
	}
    },

    /** Function: update
     *
     */
    update: function(contactBareJid, fromName, msg)
    {
	var chatBoxElm = BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid);

	if (chatBoxElm.length == 0) {
	    BeeChat.UI.ScrollBoxes.add(contactBareJid);
	    chatBoxElm = BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid);
	}

	var chatBoxContentElm = chatBoxElm.children().filter('[bareJid=' + contactBareJid + ']');

	chatBoxContentElm.find('p').filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.STATE + ']').remove();

	var chatBoxLastMessageElm = $(chatBoxContentElm).find('div').filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.MESSAGE + ']').filter(':last');

	if (chatBoxLastMessageElm && chatBoxLastMessageElm.find('span').filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.MESSAGE_SENDER + ']').text() == fromName) {
	    chatBoxLastMessageElm.append('<p>' + BeeChat.UI.Utils.getPrintableChatMessage(msg) + '</p>');
	} else {
	    chatBoxContentElm.append($('<div></div>')
				     .attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.MESSAGE)
				     .append($('<span></span>')
					     .attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.MESSAGE_SENDER)
					     .text(fromName))
				     .append($('<span></span>')
					     .attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.MESSAGE_DATE)
					     .text(BeeChat.UI.Utils.getNowFormattedTime()))
				     .append('<p>' + BeeChat.UI.Utils.getPrintableChatMessage(msg) + '</p>'));
	}

	chatBoxContentElm.attr({scrollTop: chatBoxContentElm.attr('scrollHeight')});

	var scrollBoxesElm = $('#' + BeeChat.UI.Resources.Elements.ID_DIV_SCROLLBOXES);
	var scrollBoxElm = BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid);

	if (BeeChat.UI.ScrollBoxes.isInitialized == false) {
	    scrollBoxesElm.trigger('goto', scrollBoxesElm.find('ul').children().index(scrollBoxElm));
	}

	if (chatBoxElm.is(':hidden')) {
	    BeeChat.UI.UnreadCountBox.update(contactBareJid);
	    if (BeeChat.UI.HAS_FOCUS)
	    	DHTMLSound();
	}

	if (!BeeChat.UI.HAS_FOCUS)
		DHTMLSound();
    },

    /** Function: updateChatState
     *
     */
    updateChatState: function(contactBareJid, msg)
    {
	var chatBoxContentElm = BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid).children().filter('[bareJid=' + contactBareJid + ']');

	$(msg).children().each(function() {
		if (this.tagName == BeeChat.Message.ChatStates.COMPOSING) {
		    if (chatBoxContentElm.find('p').filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.STATE + ']').length == 0) {
			$('<p></p>')
			    .attr('class', BeeChat.UI.Resources.StyleClasses.ChatBox.STATE)
			    .html(BeeChat.UI.Utils.getContactName(contactBareJid) + BeeChat.UI.Resources.Strings.ChatMessages.COMPOSING + "</br />")
			    .appendTo(chatBoxContentElm);
		    }
		} else if (this.tagName == BeeChat.Message.ChatStates.PAUSED) {
		    chatBoxContentElm.find('p').filter('[class=' + BeeChat.UI.Resources.StyleClasses.ChatBox.STATE + ']').remove();
		}
	    });
	chatBoxContentElm.attr({scrollTop: chatBoxContentElm.attr('scrollHeight')});
    },

    /** Function: remove
     *
     */
    remove: function(contactBareJid)
    {
	BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid).remove();
	BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid).remove();
    },

    /** Function: show
     *
     */
    show: function(contactBareJid)
    {
	BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid).show();
    },

    /** Function: hide
     *
     */
    hide: function(contactBareJid)
    {
	BeeChat.UI.ChatBoxes.getChatBoxElm(contactBareJid).hide();
    },

    /** Function: getChatBoxElm
     *
     */
    getChatBoxElm: function(contactBareJid)
    {
	return $('#' + BeeChat.UI.Resources.Elements.ID_DIV_CHATBOXES).children().filter('[bareJid=' + contactBareJid + ']');
    }
};

BeeChat.UI.UnreadCountBox = {
    /** Function: add
     *
     */
    add: function(contactBareJid)
    {
	BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid)
	.append($('<span></span>')
		.attr('class', BeeChat.UI.Resources.StyleClasses.UNREAD_COUNT));
    },

    /** Function: remove
     *
     */
    remove: function(contactBareJid)
    {
	BeeChat.UI.UnreadCountBox.getElm(contactBareJid).remove();
    },

    /** Function: update
     *
     */
    update: function(contactBareJid)
    {
	if (arguments.length > 1 && !arguments[1])
	    return;

	var unreadCountBoxElm = BeeChat.UI.UnreadCountBox.getElm(contactBareJid);
	if (unreadCountBoxElm.length == 0) {
	    BeeChat.UI.UnreadCountBox.add(contactBareJid);
	    unreadCountBoxElm = BeeChat.UI.UnreadCountBox.getElm(contactBareJid);
	}
	if (arguments.length == 1) {
	    var unreadCount = unreadCountBoxElm.text();
	    unreadCountBoxElm.text(++unreadCount);
	} else
	    unreadCountBoxElm.text(arguments[1]);
    },

    /** Function: getElm
     *
     */
    getElm: function(contactBareJid)
    {
	return BeeChat.UI.ScrollBoxes.getScrollBoxElm(contactBareJid).find('span').filter('[class=' + BeeChat.UI.Resources.StyleClasses.UNREAD_COUNT +' ]');
    }
};

/** Class: BeeChat.UI.Utils
 *  An object container for all UI utilities functions
 *
 */
BeeChat.UI.Utils = {
    /** Function: getTruncatedContactName
     *
     */
    getTruncatedContactName: function(bareJid)
    {
	return (BeeChat.UI.Utils.truncateString(BeeChat.UI.Utils.getContactName(bareJid), (arguments.length == 2) ? arguments[1] : 21));
    },

    /** Function: getTruncatedContactStatus
     *
     */
    getTruncatedContactStatus: function(contactStatus)
    {
	return (BeeChat.UI.Utils.truncateString(contactStatus, (arguments.length == 2 ? arguments[1] : 50)));
    },

    /** Function: getContactName
     *
     */
    getContactName: function(bareJid)
    {
	var contactName = bareJid;

	if (g_beechat_roster_items != null && g_beechat_roster_items[bareJid])
	    contactName = g_beechat_roster_items[bareJid].name;
	// no contact name so we show bareJid
	if (!contactName || contactName == '')
		contactName = bareJid;

	return (contactName);
    },

    /** Function: getPrintableChatMessage
     *
     */
    getPrintableChatMessage: function(msg)
    {
    	var val = new String;
		val = $('<div>' + msg + '</div>');
		msg = val.text();
		
		msg = jQuery.trim(msg);
		msg = BeeChat.UI.Utils.replaceLinks(msg);
		msg = BeeChat.UI.Utils.replaceSmileys(msg);

		return msg;
    },

    /** Function: getNowFormattedTime
     *
     */
    getNowFormattedTime: function()
    {
	var date = new Date();

	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getSeconds();

	if (hours < 10)
	    hours = '0' + hours;
	if (minutes < 10)
	    minutes = '0' + minutes;
	if (seconds < 10)
	    seconds = '0' + seconds;
	return (hours + ':' + minutes + ':' + seconds);
    },


    /** Function: replaceSmileys
     *  Replace smileys founded in a string to beautiful icons :)
     *
     *  Parameters:
     *    (String) str - The string containing smileys
     *
     */
    replaceSmileys: function(str)
    {
	str = str.replace(/(;\))/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_WINK + '" />');
	str = str.replace(/(:\))/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_SMILE + '" />');
	str = str.replace(/(:\()/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_UNHAPPY + '" />');
	str = str.replace(/(:D)/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_GRIN + '" />');
	str = str.replace(/(:o)/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_SURPRISED + '" />');
	str = str.replace(/(xD)/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_EVILGRIN + '" />');
	str = str.replace(/(:p)/gi, '<img src="' + BeeChat.UI.Resources.Paths.ICONS + BeeChat.UI.Resources.Emoticons.FILENAME_TONGUE + '" />');

	return (str);
    },

    /** Function: replaceLinks
     *  Transform links founded in a string to clickable links
     *
     *  Parameters:
     *    (String) str - The string where will be replaced links
     */
    replaceLinks: function(str)
    {
	var xpr =
	/((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/gi;

	return (str.replace(xpr, '<a href="$1" target="_blank">$1</a>'));
    },

    /** Function: truncateString
     *  Truncate a string at a specified length
     *
     *  Parameters:
     *    (String) str - The string to truncate
     *    (int) len - The maximum length of str
     */
    truncateString: function(str, len)
    {
	if (str != null && str.length > len)
	    return ((str.substr(0, len) + '...'));
	return (str);
    }
};


/** Executed when the DOM is ready
 *
 */
function init_beechat(ts, token) {
	if (typeof document.body.style.maxHeight === "undefined") { // IE6
	    return;
	}

	BeeChat.UI.initialize(ts, token);
}

/** Play pock sound
 *
 */
function DHTMLSound() {
  document.getElementById("beechatpock").innerHTML=
    "<embed src='<?php echo $vars['url'] ?>mod/beechat/sounds/newmessage.wav' hidden=true autostart=true loop=false>";
}

/** Window resizing
 *
 */
$(window).resize(function() {
	if (typeof document.body.style.maxHeight === "undefined") { // IE6
	    return;
	}

	$('#' + BeeChat.UI.Resources.Elements.ID_DIV_CHATBOXES).children().each(function() {
		var scrollBoxElm = BeeChat.UI.ScrollBoxes.getScrollBoxElm($(this).attr('bareJid'));
		var pos = scrollBoxElm.position().left - ($(this).width() - scrollBoxElm.width()) + 24;

		$(this).css({'left': pos});
	    });
});


/** Executed when the page is unloaded
 *
 */
$(window).unload(function() {
	if (typeof document.body.style.maxHeight === "undefined") { // IE6
	    return;
	}

	if (!$('#beechat').length)
		return;

	if (g_beechat_user != null) {
	    g_beechat_user.requestSessionPause();
	    BeeChat.UI.saveState();
	}
	BeeChat.UI.saveConnection();
    });


/** Check whether the BeeChat tab is active or not
 *
 */
$(window).bind('blur', function() {
	BeeChat.UI.HAS_FOCUS = false;
    });

$(window).bind('focus', function() {
	BeeChat.UI.HAS_FOCUS = true;
    });