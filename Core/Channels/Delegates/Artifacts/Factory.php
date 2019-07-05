<?php
/**
 * Factory.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates\Artifacts;

class Factory
{
    /**
     * @param $delegateClass
     * @param mixed ...$args
     * @return ArtifactsDelegateInterface
     * @throws \Exception
     */
    public function build($delegateClass, ...$args)
    {
        $instance = new $delegateClass(...$args);

        if (!($instance instanceof ArtifactsDelegateInterface)) {
            throw new \Exception('Invalid class interface');
        }

        return $instance;
    }
}
