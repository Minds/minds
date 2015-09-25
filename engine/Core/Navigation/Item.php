<?php

namespace Minds\Core\Navigation;

class Item{

	private $name;
	private $path = "/";
	private $params = array();
	private $subItems = array();
	private $title;
	private $text;
	private $icon = "home";
	private $class = "";
	private $priority = 500;
	private $extras = array();

	/**
	 * Set name
	 * @param string $name
	 * @return $this
	 */
	public function setName($name){
		$this->name = $name;
		return $this;
	}

	/**
	 * Return the name
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * Set path
	 * @param string $path
	 * @return $this
	 */
	public function setPath($path){
		$this->path = $path;
		return $this;
	}

	/**
	 * Return the path
	 * @return string
	 */
	public function getPath(){
		return $this->path;
	}

	/**
	 * Set params
	 * @param string $params
	 * @return $this
	 */
	public function setParams(array $params = array()){
		$this->params = $params;
		return $this;
	}

	/**
	 * Return params
	 * @return array
	 */
	public function getParams(){
		return $this->params;
	}

	/**
	 * Add a subitem
	 * @param Navigation\Item $item
	 * @return $this
	 */
	public function addSubItem($item){
		$this->subItems[] = $item;
		return $this;
	}

	/**
	 * Return sub items
	 * @return array
	 */
	public function getSubItems(){
		return $this->subItems;
	}

	/**
	 * Set title
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title){
		$this->title = $title;
		return $this;
	}

	/**
	 * Return the title
	 * @return string
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 	* Set icon
	 	* @param string $icon
	 	* @return $this
	 	*/
	public function setIcon($icon){
		$this->icon = $icon;
		return $this;
	}

	/**
	 * Return the icon
	 * @return string
	 */
	public function getIcon(){
		return $this->icon;
	}

	/**
	 * Set class
	 * @param string $class
	 * @return $this
	 */
	public function setClass($class){
		$this->class = $class;
		return $this;
	}

	/**
	 * Return the class
	 * @return string
	 */
	public function getClass(){
		return $this->class;
	}

	public function setPriority($priority = 500){
		$this->priority = $priority;
		return $this;
	}

	public function getPriority(){
		return $this->priority;
	}

	/**
	 * Set extras
	 * @param array $extras
	 * @return $this
	 */
	public function setExtras($extras = array()){
		$this->extras = $extras;
		return $this;
	}

	/**
	 * Get extras
	 * @return array
	 */
	public function getExtras(){
 		return $this->extras;
 	}

	/**
	 * Export the item to an array
	 * @return array
	 */
	public function export(){
		$subitems = array();
		foreach($this->subItems as $subitem){
			$subitems[] = $subitem->export();
		}
		return array(
			"name" => $this->name,
			"path" => $this->path,
			"params" => $this->params,
			"submenus" => $subitems,
			"title" => $this->title,
			"text" => $this->name,
			"icon" => $this->icon,
			"class" => $this->class,
			"extras" => $this->extras
		);
	}

}
