<?php

class hjAnnotation extends ElggObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'hjannotation';
		$this->attributes['owner_guid'] = elgg_get_logged_in_user_entity()->guid;
		$this->attributes['access_id'] = ACCESS_DEFAULT;
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	public function save($create_elgg_annotation = true) {
		if (parent::save()) {
			if (!$this->river_id && $create_elgg_annotation) {
				$container = $this->getContainerEntity();
				if (!elgg_instanceof($container, 'object', 'hjannotation')) {
					$container = $this->findOriginalContainer();
				}
				if ($container->getType() != 'river') {
					if ($id = create_annotation($container->guid, $this->annotation_name, $this->annotation_value, '', $this->owner_guid, $this->access_id)) {
						$this->annotation_id = $id;
					}
				}
			}
			$notify_settings = elgg_trigger_plugin_hook('hj:notification:setting', 'annotation', null, '');
			if (in_array($this->annotation_name, $notify_settings)) {
				$this->sendNotification();
			}
			return $this->guid;
		}
		return false;
	}

	public function findOriginalContainer() {
		$check = true;
		$container = $this;
		while ($check) {
			if (!elgg_instanceof($container, 'object', 'hjannotation')) {
				$check = false;
			} else {
				if (!$container->river_id) {
					$container = $container->getContainerEntity();
				} else {
					$container = elgg_get_river(array('id' => $container->river_id));
					$container = $container[0];
				}
			}
		}
		return $container;
	}

	public function findOriginalOwner() {
		$container = $this->findOriginalContainer();
		if ($container->getType() == 'river') {
			$owner = $container->getSubjectEntity();
		} else {
			$owner = $container->getOwnerEntity();
		}
		return $owner;
	}

	public function sendNotification() {
		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			return true;
		}
		if (!$this->river_id) {
			$container = $this->getContainerEntity();
			$owner = $this->getOwnerEntity();
		} else {
			$container = elgg_get_river(array('id' => $this->river_id));
			$container = $container[0];
			$owner = $container->getSubjectEntity();
		}
		$original_container = $this->findOriginalContainer();
		$original_container_owner = $this->findOriginalOwner();

		$annotation_name = $this->annotation_name;
		$annotation_value = $this->annotation_value;

		$type = $original_container->getType();
		$subtype = $original_container->getSubtype();

		if ($type == 'river') {
			$subject = $original_container->getObjectEntity();
			$subject_subtype = $subject->getSubtype();
			$subject_name = elgg_view('output/url', array('href' => $subject->getURL(), 'text' => $subject->title));
			$entity_title = elgg_echo('hj:comments:notify:activity', array(
				elgg_echo("hj:comments:notify:activity_type:$original_container->action_type", array(
					elgg_echo("item:object:$subject_subtype"),
					$subject_name
				))
					));
			$entity_url = elgg_get_config('url') . "activity/owner/$original_container_owner->username";
			$entity_url = elgg_view('output/url', array('href' => $entity_url, 'text' => $entity_url));
			$river_id = $original_container->id;
		} else {
			if (!$entity_title = $original_container->title) {
				$entity_title = $original_container->name;
			}
			$entity_url = $original_container->getURL();
			$entity_subtype = elgg_echo("item:object:{$original_container->getSubtype()}");
			$entity_title = elgg_view('output/url', array('href' => $entity_url, 'text' => $entity_title));
			$entity_title = elgg_echo("hj:comments:notify:post", array($entity_subtype, $entity_title));
			$river_id = null;
		}
		$username = elgg_view('output/url', array('href' => $user->getURL(), 'text' => $user->name));

		$container_loop = $this;
		$check = true;
		$to_annotation_owners = array();

		while ($check) {
			if ($container_loop->getType() == 'river') {
				break;
			}
			if ($river_id = $container_loop->river_id) {
				$container_loop = elgg_get_river(array('id' => $river_id));
				$container_loop = $container_loop[0];
				$river_id = $container_loop->id;
			} else if (elgg_instanceof($container_loop)) {
				$container_loop = $container_loop->getContainerEntity();
				$river_id = null;
			}

			$options = array(
				'type' => 'object',
				'subtype' => 'hjannotation',
				'container_guid' => $container_loop->guid,
				'metadata_name_value_pairs' => array(
					array('name' => 'annotation_name', 'value' => $annotation_name),
					array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
					array('name' => 'river_id', 'value' => $river_id)
				),
				'count' => false,
				'limit' => 0,
			);

			$annotations = elgg_get_entities_from_metadata($options);
			if ($annotations) {
				foreach ($annotations as $annotation) {
					if ($annotation->owner_guid != $original_container_owner->guid
							&& $annotation->owner_guid != $user->guid) {
						$to_annotation_owners[] = $annotation->owner_guid;
					}
				}
			}
			$to_annotation_owners = array_unique($to_annotation_owners);
			if (!elgg_instanceof($container_loop, 'object', 'hjannotation')) {
				$check = false;
			}
		}
		// Notify owners of comments in the thread
		if ($to_annotation_owners) {
			$to = $to_annotation_owners;
			$from = $user->guid;
			$subject = elgg_echo("$annotation_name:email:level2:subject");
			$body = elgg_echo("$annotation_name:email:level2:body", array(
				$username,
				$entity_title,
				$annotation_value,
				$entity_url
					));
			notify_user($to, $from, $subject, $body);
		}


		// Notify the owner of the original content
		if ($original_container_owner->guid != $user->guid) {
			$to = $original_container_owner->guid;
			$from = $user->guid;
			$subject = elgg_echo("$annotation_name:email:level1:subject");
			$body = elgg_echo("$annotation_name:email:level1:body", array(
				$username,
				$entity_title,
				$annotation_value,
				$entity_url
					));
			notify_user($to, $from, $subject, $body);
		}

		return true;
	}

}