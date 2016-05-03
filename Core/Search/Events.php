<?php
/**
 * Search events listeners
 */
namespace Minds\Core\Search;

use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;
use Minds\Core\Search\Documents;

class Events
{
  public function init()
  {
    // Indexer
    Dispatcher::register('search:index', 'all', function(Event $event) {
      try {
        $params = $event->getParameters();

        if (!isset($params['entity'])) {
          return;
        }

        (new Documents())->index($params['entity']);
      } catch (\Exception $e) {
        error_log('Unable to index for search: ' . $e->getMessage());
      }
    });

    // Hooking for core entities
    Dispatcher::register('create', 'all', [ $this, 'hook' ]);
    Dispatcher::register('update', 'all', [ $this, 'hook' ]);
  }

  public function hook($hook, $type, $entity, array $params = [])
  {
    if (
      $entity &&
      $entity->access_id == 2 &&
      (
        in_array($entity->subtype, [ 'blog', 'image', 'album', 'video' ]) ||
        $entity->type == 'user' ||
        $entity->type == 'activity'
      )
    ) {
      try {
        Dispatcher::trigger('search:index', 'all', [
          'entity' => $entity
        ]);
      } catch (\Exception $e) { }
    }
  }
}
