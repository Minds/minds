<?php
namespace Minds\Entities\Boost;

class Factory
{

    /**
     * @param string $type
     * @return BoostEntityInterface
     * @throws \Exception
     */
    public function build($type)
    {
        $handler = $type;

        switch ($type) {
            case 'newsfeed':
            case 'content':
            case 'suggested':
                $handler = 'network';
                break;

            case 'channel':
                $handler = 'peer';
                break;
        }

        $handler = 'Minds\\Entities\\Boost\\' . ucfirst($handler);

        if (class_exists($handler)) {
            $instance = new $handler();

            if ($instance instanceof BoostEntityInterface) {
                return $instance;
            }
        }

        throw new \Exception('Entity handler not found');
    }
}
