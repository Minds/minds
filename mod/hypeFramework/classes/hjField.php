<?php

class hjField extends ElggObject {

    /**
     *
     * @var string Type of the Field Input
     */
    private $input_type = 'text';

    /**
     *
     * @var string Field Input Name
     */
    private $name = NULL;

    /**
     *
     * @var string Translatable label
     */
    private $label = NULL;

    /**
     *
     * @var mixed Default value of the field input
     */
    private $value = NULL;

    /**
     *
     * @var string Additional class to add to the field input 
     */
    private $class = NULL;

    /**
     *
     * @var string Tooltip to add to the field input
     */
    private $tooltip = NULL;

    /**
     *
     * @var bool Is this field required?
     */
    private $mandatory = false;

    /**
     *
     * @var bool Disable editing?
     */
    private $disabled = false;

    /**
     *
     * @var array Array of input options
     */
    private $options = NULL;

    /**
     *
     * @var array Array of input options => values 
     */
    private $options_values = NULL;

    /**
     *
     * @var int Position of the field in the form
     */
    private $priority = 0;

    /**
     *
     * @var bool Protected field input
     */
    private $guarded = false;

    /**
     *
     * @var string Position of the output field in the image block
     */
    private $image_block_section = 'description';

    protected function initializeAttributes() {
        parent::initializeAttributes();
        $this->attributes['subtype'] = 'hjfield';
        $this->attributes['owner_guid'] = elgg_get_site_entity()->guid;
        $this->attributes['access_id'] = ACCESS_PUBLIC;
    }

    public function __construct($guid = null) {
        parent::__construct($guid);
        if (!$guid)
            $this->setDefaults();
        //$this->save();
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
        if (parent::save()) {
            //$this->setLabel();
            return $this->guid;
        }
        return false;
    }

    public function getLabel() {
        $name = $this->get('name');
        $form = $this->getContainerForm();
        $form_type = $this->subject_entity_type;
        $form_subtype = $form->subject_entity_subtype;
        $label = "hj:label:$form_subtype:$name";
        //add_translation('en', array($label => $this->title));
        return elgg_echo($label);
    }

    public function getContainerForm() {
        return get_entity($this->container_guid);
    }

    public function getMetaMap() {
        return array(
            'input_type' => array(),
            'name' => array(),
            'value' => array('eval' => true),
			'entity' => array(),
            'class' => array(),
            'mandatory' => array(),
            'disabled' => array(),
            'options' => array('eval' => true),
            'options_values' => array('eval' => true)
        );
    }

    public function getParams($subject = null) {
        $meta = $this->getMetaMap();
        $form = $this->getContainerForm();
        $eval = $form->eval;

        foreach ($meta as $metaname => $check) {
            if ($this->get($metaname)) {
                $params[$metaname] = $this->get($metaname);
                if ($check['eval'] && $eval) {
                    $to_eval = strval($this->get($metaname));
                    if (is_string($to_eval)) {
                        if (substr($to_eval, -1) == ';') {
                            eval("\$params_eval = $to_eval");
                        } else {
                            eval("\$params_eval = $to_eval;");
                        }
                    }
                    $params[$metaname] = $params_eval;
                }
                if ($check['add_to_class']) {
                    $params['class'] = "{$params['class']} {$this->get($metaname)}";
                }
            }
        }
        if (!empty($subject) && elgg_instanceof($subject)) {
            $metaname = $this->get('name');
            $params['value'] = $subject->$metaname;
			$params['entity'] = $subject;
        }
        return $params;
    }

    public function isGuarded() {
        return $this->get('guarded');
    }

}