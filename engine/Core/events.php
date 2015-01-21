<?php

namespace Minds\Core;

use Minds\Core\Data\Event;

class events {

    /**
     * Register of event listeners and their handlers.
     */
    private static $events = [];

    /**
     * Register a handler for an event.
     * @param type $namespace Namespace for this event (e.g. object type)
     * @param type $event The event
     * @param \callable $handler a callable handler
     * @param type $priority Priority - lower numbers executed first.
     */
    public static function register($namespace, $event, $handler, $priority = 500) {

	if (empty($namespace) || empty($event) || !is_callable($handler)) {
	    return false;
	}

	if (!isset(self::$events)) {
	    self::$events = array();
	}
	if (!isset(self::$events[$namespace])) {
	    self::$events[$namespace] = array();
	}
	if (!isset(self::$events[$namespace][$event])) {
	    self::$events[$namespace][$event] = array();
	}


	$priority = max((int) $priority, 0);

	while (isset(self::$events[$namespace][$event][$priority])) {
	    $priority++;
	}
	self::$events[$namespace][$event][$priority] = $handler;
	ksort(self::$events[$namespace][$event]);
	return true;
    }

    /**
     * Unregister a handler.
     * TODO: Handle unregister of closures.
     * @param type $namespace
     * @param type $event
     * @param \callable $handler
     */
    public static function unregister($namespace, $event, $handler) {
	if (isset(self::$events[$event]) && isset(self::$events[$event][$namespace])) {
	    foreach (self::$events[$event][$namespace] as $key => $event_callback) {
		if ($event_callback == $callback) {
		    unset(self::$events[$event][$namespace][$key]);
		}
	    }
	}
    }

    /**
     * Trigger the event.
     * @param string $namespace
     * @param string $event
     * @param mixed $params Parameters to pass to the callback
     * @param mixed $default_return Default return value, if not set by the handler.
     */
    public static function trigger($namespace, $event, $params, $default_return = null) {

	$calls = array();

	if (isset(self::$events[$namespace][$event])) {
	    if ($event != 'all' && $namespace != 'all') {
		$calls[] = self::$events[$namespace][$event];
	    }
	}
	if (isset(self::$events[$namespace]['all'])) {
	    if ($namespace != 'all') {
		$calls[] = self::$events[$namespace]['all'];
	    }
	}
	if (isset(self::$events['all'][$event])) {
	    if ($event != 'all') {
		$calls[] = self::$events['all'][$event];
	    }
	}
	if (isset(self::$events['all']['all'])) {
	    $calls[] = self::$events['all']['all'];
	}

	// New event format, expects event object
	$event = new Event([
	    'namespace' => $namespace,
	    'event' => $event,
	    'parameters' => $params
	]);
	$event->setResponse($default_return);
	$args = [$event];

	try {

	    // Dispatch event
	    foreach ($calls as $callback_list) {
		if (is_array($callback_list)) {
		    foreach ($callback_list as $callback) {
			if (is_callable($callback)) {

			    // There's a potential namespace collision on old style elgg events/hooks, so we namespace them off, however some hooks/events check this parameter. 
			    // Therefore we need to normalise the namespace before dispatch
			    if (strpos('elgg/event', $namespace) === 0) {
				// old style event
				$namespace = str_replace('elgg/event/', '', $namespace);

				if (call_user_func_array($callback, array($event, $namespace, $params)) === false) {
				    throw new exceptions\StopEventException("Event propagation for old style $namespace/$event stopped by $callback");
				}
			    } elseif (strpos('elgg/hook', $namespace) === 0) {

				// Old style hook
				$namespace = str_replace('elgg/hook/', '', $namespace);

				$temp_return_value = call_user_func_array($callback, array($event, $namespace, $default_return, $params));
				if (!is_null($temp_return_value)) {
				    $event->setResponse($temp_return_value);
				}
			    } else {
				call_user_func_array($callback, array($event));
			    }
			}
		    }
		}
	    }
	} catch (Minds\Core\exceptions\StopEventException $ex) {
	    // Stop execution when we get this exception, all other exceptions bubble up.
	}
	
	return $event->response();
    }

}
