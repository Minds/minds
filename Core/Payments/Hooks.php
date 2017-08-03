<?php
/**
 * Subscription Hooks
 */
namespace Minds\Core\Payments;

use Minds\Core;

class Hooks
{
    private $hooks = [];

    public function loadDefaults()
    {
        $this->hooks[] = new Core\Wallet\PointsSubscription;
        $this->hooks[] = new Core\Plus\Webhook;
        $this->hooks[] = new Core\Wire\Webhook;
        return $this;
    }

    public function register($hook)
    {
        if (class_implements($hook, 'Minds\Core\Payments\HookInterface')) {
            $this->hooks[] = $hook;
        }
        return $this;
    }

    public function __call($function, $vars = [])
    {
        foreach ($this->hooks as $hook) {
            if (method_exists($hook, $function)) {
                call_user_func_array([$hook, $function], $vars);
            }
        }
        return $this;
    }
}
