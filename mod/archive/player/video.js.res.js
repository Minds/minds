/**
* Video.js Resolution Selector
*
* This plugin for Video.js adds a resolution selector option
* to the toolbar. Usage:
*
* <video>
* <source data-res="480" src="..." />
* <source data-res="240" src="..." />
* </video>
*/

(function( _V_ ) {

/***********************************************************************************
* Define some helper functions
***********************************************************************************/
var methods = {

/**
* Utility function for merging 2 objects recursively. It treats
* arrays like plain objects and it relies on a for...in loop which will
* break if the Object prototype is messed with.
*
* @param (object) destination The object to modify and return
* @param (object) source The object to use to overwrite the first
* object
*
* @returns (object) The modified first object is returned
*/
extend : function( destination, source ) {

for ( var prop in source ) {

// Sanity check
if ( ! source.hasOwnProperty( prop ) ) { continue; }

// Enable recursive (deep) object extension
if ( typeof source[prop] == 'object' && null !== source[prop] ) {

destination[prop] = methods.extend( destination[prop] || {}, source[prop] );

} else {

destination[prop] = source[prop];
}
}

return destination;
},

/**
* In a future version, this can be made more intelligent,
* but for now, we'll just add a "p" at the end if we are passed
* numbers.
*
* @param (string) res The resolution to make a label for
*
* @returns (string) The label text string
*/
res_label : function( res ) {

return ( /^\d+$/.test( res ) ) ? res + 'p' : res;
}
};

/***********************************************************************************
* Setup our resolution menu items
***********************************************************************************/
_V_.ResolutionMenuItem = _V_.MenuItem.extend({

/** @constructor */
init : function( player, options ){

// Modify options for parent MenuItem class's init.
options.label = methods.res_label( options.res );
options.selected = ( options.res.toString() === player.getCurrentRes().toString() );

// Call the parent constructor
_V_.MenuItem.call( this, player, options );

// Store the resolution as a call property
this.resolution = options.res;

// Register our click handler
this.on( 'click', this.onClick );

// Toggle the selected class whenever the resolution changes
player.on( 'changeRes', _V_.bind( this, function() {

if ( this.resolution == player.getCurrentRes() ) {

this.selected( true );

} else {

this.selected( false );
}
}));
}
});

// Handle clicks on the menu items
_V_.ResolutionMenuItem.prototype.onClick = function() {

var player = this.player(),
video_el = player.el().firstChild,
current_time = player.currentTime(),
is_paused = player.paused(),
button_nodes = player.controlBar.resolutionSelector.el().firstChild.children,
button_node_count = button_nodes.length;

// Do nothing if we aren't changing resolutions
if ( player.getCurrentRes() == this.resolution ) { return; }

// Make sure the loadedmetadata event will fire
if ( 'none' == video_el.preload ) { video_el.preload = 'metadata'; }

// Change the source and make sure we don't start the video over
player.src( player.availableRes[this.resolution] ).one( 'loadedmetadata', function() {

player.currentTime( current_time );

if ( ! is_paused ) { player.play(); }
});

// Save the newly selected resolution in our player options property
player.currentRes = this.resolution;

// Update the button text
while ( button_node_count > 0 ) {

button_node_count--;

if ( 'vjs-current-res' == button_nodes[button_node_count].className ) {

button_nodes[button_node_count].innerHTML = methods.res_label( this.resolution );
break;
}
}

// Update the classes to reflect the currently selected resolution
player.trigger( 'changeRes' );
};

/***********************************************************************************
* Setup our resolution menu title item
***********************************************************************************/
_V_.ResolutionTitleMenuItem = _V_.MenuItem.extend({

init : function( player, options ) {

// Call the parent constructor
_V_.MenuItem.call( this, player, options );

// No click handler for the menu title
this.off( 'click' );
}
});

/***********************************************************************************
* Define our resolution selector button
***********************************************************************************/
_V_.ResolutionSelector = _V_.MenuButton.extend({

/** @constructor */
init : function( player, options ) {

// Add our list of available resolutions to the player object
player.availableRes = options.available_res;

// Call the parent constructor
_V_.MenuButton.call( this, player, options );
}
});

// Create a menu item for each available resolution
_V_.ResolutionSelector.prototype.createItems = function() {	

var player = this.player(),
items = [],
current_res;

// Add the menu title item
items.push( new _V_.ResolutionTitleMenuItem( player, {

el : _V_.Component.prototype.createEl( 'li', {

className	: 'vjs-menu-title vjs-res-menu-title',
innerHTML	: 'Quality'
})
}));

// Add an item for each available resolution
for ( current_res in player.availableRes ) {

// Don't add an item for the length attribute
if ( 'length' == current_res ) { continue; }

items.push( new _V_.ResolutionMenuItem( player, {
res : current_res
}));
}

// Sort the available resolutions in descending order
items.sort(function( a, b ) {

if ( typeof a.resolution == 'undefined' ) {

return -1;

} else {

return parseInt( b.resolution ) - parseInt( a.resolution );
}
});

return items;
};

/***********************************************************************************
* Register the plugin with videojs, main plugin function
***********************************************************************************/
_V_.plugin( 'resolutionSelector', function( options ) {

// Only enable the plugin on HTML5 videos
if ( ! this.el().firstChild.canPlayType ) { return; }

var player = this,
sources	= player.options().sources,
i = sources.length,
j,
found_type,

// Override default options with those provided
settings = methods.extend({

default_res	: '',	// (string) The resolution that should be selected by default ( '480' or '480,1080,240' )
force_types	: false	// (array) List of media types. If passed, we need to have source for each type in each resolution or that resolution will not be an option

}, options || {} ),

available_res = { length : 0 },
current_res,
resolutionSelector,

// Split default resolutions if set and valid, otherwise default to an empty array
default_resolutions = ( settings.default_res && typeof settings.default_res == 'string' ) ? settings.default_res.split( ',' ) : [];

// Get all of the available resoloutions
while ( i > 0 ) {

i--;

// Skip sources that don't have data-res attributes
if ( ! sources[i]['data-res'] ) { continue; }

current_res = sources[i]['data-res'];

if ( typeof available_res[current_res] !== 'object' ) {

available_res[current_res] = [];
available_res.length++;
}

available_res[current_res].push( sources[i] );
}

// Check for forced types
if ( settings.force_types ) {

// Loop through all available resoultions
for ( current_res in available_res ) {

// Don't count the length property as a resolution
if ( 'length' == current_res ) { continue; }

i = settings.force_types.length;

// For each resolution loop through the required types
while ( i > 0 ) {

i--;

j = available_res[current_res].length;
found_types = 0;

// For each required type loop through the available sources to check if its there
while ( j > 0 ) {

j--;

if ( settings.force_types[i] === available_res[current_res][j].type ) {

found_types++;
}
} // End loop through current resolution sources

if ( found_types < settings.force_types.length ) {

delete available_res[current_res];
available_res.length--;
break;
}
} // End loop through required types
} // End loop through resolutions
}

// Make sure we have at least 2 available resolutions before we add the button
if ( available_res.length < 2 ) { return; }

// Loop through the choosen default resolutions if there were any
for ( i = 0; i < default_resolutions.length; i++ ) {

// Set the video to start out with the first available default res
if ( available_res[default_resolutions[i]] ) {

player.src( available_res[default_resolutions[i]] );
player.currentRes = default_resolutions[i];
break;
}
}

// Helper function to get the current resolution
player.getCurrentRes = function() {

if ( typeof player.currentRes !== 'undefined' ) {

return player.currentRes;

} else {

try {

return res = player.options().sources[0]['data-res'];

} catch(e) {

return '';
}
}
};

// Get the started resolution
current_res = player.getCurrentRes();

if ( current_res ) { current_res = methods.res_label( current_res ); }

// Add the resolution selector button
resolutionSelector = new _V_.ResolutionSelector( player, {

el : _V_.Component.prototype.createEl( null, {

className	: 'vjs-res-button vjs-menu-button vjs-control',
innerHTML	: '<div class="vjs-control-content"><span class="vjs-current-res">' + ( current_res || 'Quality' ) + '</span></div>',
role	: 'button',
'aria-live'	: 'polite', // let the screen reader user know that the text of the button may change
tabIndex	: 0

}),
available_res	: available_res
});

// Add the button to the control bar object and the DOM
player.controlBar.resolutionSelector = player.controlBar.addChild( resolutionSelector );
});

})( videojs );
