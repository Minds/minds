<?php

class hjSegment extends ElggObject {

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes['subtype'] = "hjsegment";
        $this->attributes['access_id'] = ACCESS_PUBLIC;
    }

    public function getTitle() {
        return elgg_echo("$this->title");
    }

    public function getSections() {
        $sections = $this->get('sections');
        if (!is_array($sections)) {
            $sections = array($sections);
        }
        return $sections;
    }

    public function getSectionTitle($section = null) {
        if ($section) {
            return elgg_echo("hj:{$this->get('handler')}:{$section}");
        } else {
            return 'Default Section';
        }
    }

    public function getSectionName($section) {
        return elgg_echo("$section");
    }

    public function getSectionContent($section, $params) {
        $widget = elgg_extract('widget', $params, false);
        if ($widget && elgg_instanceof($widget, 'object', 'widget')) {
            $metadata = array(
                array('name' => 'widget', 'value' => $widget->guid),
            );
        } else {
            $metadata = array(
                array('name' => 'segment', 'value' => $this->guid)
            );
        }
		
        $content = hj_framework_get_entities_from_metadata_by_priority('object', $section, null, null, $metadata, $params['limit'], $params['offset'], $params['count']);
		
        return $content;
    }

    public function addWidget($section, $guid = null, $context = 'framework') {
        $widget = new ElggWidget($guid);
        $widget->title = $this->getSectionTitle($section);
        $widget->name = $this->getSectionName($section);
        $widget->owner_guid = $this->owner_guid;
        $widget->container_guid = $this->guid;
        $widget->handler = $this->get('handler');
        $widget->context = $context;
        $widget->section = $section;
        $widget->access_id = $this->access_id;
        if ($widget->save()) {
            $widget->move(1, 1);
        }

        return $widget;
    }

    public function getWidgets($context = 'framework') {
        $owner = $this->getOwnerEntity();
        $options = array(
            'type' => 'object',
            'subtype' => 'widget',
            'owner_guid' => $owner->guid,
            //'container_guid' => $portfolio->guid,
            'private_setting_name' => 'context',
            'private_setting_value' => $context,
            'limit' => 0
        );
        $widgets = elgg_get_entities_from_private_settings($options);
        foreach ($widgets as $widget) {
            if ($widget->container_guid == $this->guid) {
                $segment_widgets[] = $widget;
            }
        }
        if (!$segment_widgets) {
            return array();
        }
        return $segment_widgets;
    }

    public function sortWidgets($widgets) {
        $sorted_widgets = array();
        foreach ($widgets as $widget) {
            if ($widget->container_guid == $this->guid) {
                if (!isset($sorted_widgets[(int) $widget->column])) {
                    $sorted_widgets[(int) $widget->column] = array();
                }
                $sorted_widgets[(int) $widget->column][$widget->order] = $widget;
            }
        }

        foreach ($sorted_widgets as $col => $widgets) {
            ksort($sorted_widgets[$col]);
        }
        return $sorted_widgets;
    }

}