<?php

/**
 * Creates hjForm class to manage forms
 */
class hjForm extends ElggObject {

    private $name = NULL;

    /**
     *
     * @var string Action to perform on form submission
     */
    private $action = 'action/framework/entities/save';

    /**
     *
     * @var string 'POST' or 'GET'
     */
    private $method = 'POST';

    /**
     *
     * @var string Encryption type for this form
     */
    private $enctype = 'multipart/form-data';

    /**
     *
     * @var bool Use PHP eval() to assess fields
     */
    private $eval = true;

    /**
     *
     * @var string Form title translatable label
     */
    private $label = NULL;

    /**
     *
     * @var string ElggEntity type that this form creates/edits
     */
    private $subject_entity_type = 'object';

    /**
     *
     * @var string ElggEntity subtype that this form creates/edits
     */
    private $subject_entity_subtype = 'hjformsubmission';

    private $handler = '';

    /**
     * Properties of the submitted form
     * @var bool
     */
    private $notify_admins = true;
    private $update_river = false;
    private $comments_on = false;
    private $ajaxify = true;

    /**
     * Construct hjFrom
     */
    function __construct($guid = null) {
        parent::__construct($guid);
        if (!$guid)
            $this->setDefaults();
    }

    protected function initializeAttributes() {
        parent::initializeAttributes();
        $this->attributes['type'] = 'object';
        $this->attributes['subtype'] = 'hjform';
        $this->attributes['title'] = 'default';
        $this->attributes['owner_guid'] = elgg_get_site_entity()->guid;
        $this->attributes['access_id'] = ACCESS_PUBLIC;
    }

    public function setDefaults() {
        $properties = get_object_vars($this);
        foreach ($properties as $key => $default) {
            if ($key != 'attributes') {
                $this->set($key, $default);
            }
        }
    }

    public function save() {
        $result = parent::save();
        elgg_set_plugin_setting("hj:form:$this->subject_entity_type:$this->subject_entity_subtype ", $this->guid, 'hypeFramework');
        return $result;
    }

    public function getTitle($subject_entity = null) {
        if (!$subject_entity) {
            $title_label = "hj:label:form:new:$this->title";
        } else {
            $title_label = "hj:label:form:edit:$this->title";
        }
        return elgg_echo($title_label);
    }

    public function getFieldTypes() {
        $types = hj_formbuilder_get_input_types_array();
        return $types;
    }

    /**
     * Get fields associated with this form
     *
     * @param string $sort_by
     * @param string $as
     * @param string $direction
     * @param bool $count
     * @param int $limit


     * @return array
     */
    public function getFields($sort_by = 'priority', $as = 'SIGNED', $direction = 'ASC', $count = false, $limit = 9999) {
        $db_prefix = elgg_get_config('dbprefix');
        $fields = elgg_get_entities(array(
            'type' => 'object',
            'subtype' => 'hjfield',
            'container_guid' => $this->guid,
            'count' => $count,
            'limit' => $limit,
            'joins' => array("JOIN {$db_prefix}metadata as mt on e.guid = mt.entity_guid
                          JOIN {$db_prefix}metastrings as msn on mt.name_id = msn.id
                          JOIN {$db_prefix}metastrings as msv on mt.value_id = msv.id"
            ),
            'wheres' => array("((msn.string = '$sort_by'))"),
            'order_by' => "CAST(msv.string AS $as) $direction"
                ));
        //foreach ($fields as $field) system_message($field->input_type);
        return $fields;
    }

    public function addField($properties) {
        if ($properties) {
            $field = new hjField();
            $field->owner_guid = $this->owner_guid;
            $field->container_guid = $this->guid;
            $field->access_id = $this->access_id;
            if ($count = $this->getFields('priority', 'SIGNED', 'ASC', true, 9999)) {
                $field->priority = $count + 1;
            } else {
                $field->priority = 0;
            }
            foreach ($properties as $key => $property) {
                $field->$key = $property;
            }
            return $field->save
                    ();
        } else {
            return false;
        }
    }

}