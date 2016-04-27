<?php

namespace Minds\Core\Events;

use Minds\Exceptions;

class Dispatcher
{
    /**
     * Register of event listeners and their handlers.
     */
    private static $events = array();

    /**
     * Initialise Core event listeners
     */
    public static function init()
    {
        Listeners\Channel::init();
        Listeners\Comments::init();
    }

    /**
     * Register a handler for an event.
     * @param type $event The event
     * @param type $namespace Namespace for this event (e.g. object type)
     * @param \callable $handler a callable handler
     * @param type $priority Priority - lower numbers executed first.
     */
    public static function register($event, $namespace, $handler, $priority = 500)
    {
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
    public static function unregister($namespace, $event, $handler)
    {
        if (isset(self::$events[$namespace]) && isset(self::$events[$namespace][$event])) {
            foreach (self::$events[$namespace][$event] as $key => $event_callback) {
                if ($event_callback == $handler) {
                    unset(self::$events[$namespace][$event][$key]);
                }
            }
        }
    }

    /**
     * Trigger the event.
     * @param string $event
     * @param string $namespace
     * @param mixed $params Parameters to pass to the callback
     * @param mixed $default_return Default return value, if not set by the handler.
     */
    public static function trigger($event, $namespace, $params, $default_return = null)
    {
        $calls = array();

        if (isset(self::$events[$namespace][$event])) {
            $calls[] = self::$events[$namespace][$event];
        }
        if (isset(self::$events[$namespace]['all'])) {
            $calls[] = self::$events[$namespace]['all'];
        }
        //always trigger the all listener for namespace
        foreach (array('all', 'elgg/hook/all', 'elgg/event/all') as $ns) {
            if (isset(self::$events[$ns][$event])) {
                $calls[] = self::$events[$ns][$event];
            }
        }

        $calls = array_unique($calls, SORT_REGULAR);

      // New event format, expects event object
        $eventobj = new Event(array(
          'namespace' => $namespace,
          'event' => $event,
          'parameters' => $params
        ));
        $eventobj->setResponse($default_return);

        try {
            // Dispatch event
        foreach ($calls as $callbacks) {
            if (!is_array($callbacks)) {
                continue;
            }

            foreach ($callbacks as $callback) {
                if (!is_callable($callback)) {
                    continue;
                }

                $ns = $namespace;
                $ev = $event;

            // There's a potential namespace collision on old style elgg events/hooks, so we namespace them off, however some hooks/events check this parameter.
            // Therefore we need to normalise the namespace before dispatch
            if (strpos($ns, 'elgg/event/') === 0) {
                // old style event
              $ns = str_replace('elgg/event/', '', $ns);

                $args = array($ev, $ns, $params);
                if (call_user_func_array($callback, $args) === false) {
                    throw new Exceptions\StopEventException("Event propagation for old style $ns/$ev stopped by $callback");
                }
            } elseif (strpos($ns, 'elgg/hook/') === 0) {
                // Old style hook
              $ns = str_replace('elgg/hook/', '', $ns);

                $args = array($ev, $ns, $eventobj->response(), $params);
                $temp_return_value = call_user_func_array($callback, $args);
                if (!is_null($temp_return_value)) {
                    $eventobj->setResponse($temp_return_value);
                }
            } else {
                $args = array($eventobj);
                call_user_func_array($callback, $args);
            }
            }
        }
        } catch (\Minds\Core\exceptions\StopEventException $ex) {
            // Stop execution when we get this exception, all other exceptions bubble up.
        return false;
        }

        return $eventobj->response();
    }
}
