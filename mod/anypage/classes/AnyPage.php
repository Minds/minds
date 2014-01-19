<?php
/**
 * AnyPage page
 *
 * Access handled by setRequiresLogin() instead of access_id to allow proper redirects instead of
 * 404 errors.
 *
 */
class AnyPage extends ElggObject {
	/**
	 * Set subclass.
	 * @return bool
	 */
	public function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'anypage';

		return true;
	}

	/**
	 * Override access id, owner, and container.
	 * Save to private settings for faster lookup
	 *
	 * @return int
	 */
	public function save() {
		$site = elgg_get_site_entity();

		$this->access_id = ACCESS_PUBLIC;
		$this->container_guid = $site->getGUID();
		$this->owner_guid = $site->getGUID();

		return parent::save();
	}

	/**
	 * Sets the path for this page. The path is normalized.
	 *
	 * @param string $path
	 * @return bool
	 */
	public function setPagePath($path) {
		return $this->page_path = $this->normalizePath($path);
	}

	/**
	 * Returns the path for this page.
	 * @return string
	 */
	public function getPagePath() {
		return $this->page_path;
	}

	/**
	 * Sets if this page should use a view instead of the built in object display.
	 * 
	 * @param bool $use_view
	 * @return bool
	 */
	public function setUseView($use_view = false) {
		return $this->use_view = (bool)$use_view;
	}

	/**
	 * Returns if this page uses a view.
	 *
	 * @return bool
	 */
	public function usesView() {
		return (bool) $this->use_view;
	}

	/**
	 * Returns the view for this page.
	 *
	 * Currently only magic views are supported as anypage/<view_name>
	 * @return mixed False if not set to use a view, else the view name as a string
	 */
	public function getView() {
		if (!$this->use_view) {
			return false;
		}

		return 'anypage' . $this->getPagePath();
	}

	/**
	 * Gets the public facing URL for the page.
	 * 
	 * @return string
	 */
	public function getURL() {
		return elgg_normalize_url($this->getPagePath());
	}

	/**
	 * Set if this page is visible though walled gardens
	 *
	 * @param bool $visible
	 * @return bool
	 */
	public function setVisibleThroughWalledGarden($visible = true) {
		return $this->visible_through_walled_garden = (bool)$visible;
	}

	/**
	 * Is this page visible through walled gardens?
	 *
	 * @return bool
	 */
	public function isVisibleThroughWalledGarden() {
		return $this->visible_through_walled_garden;
	}

	/**
	 * Set if this page requires a login.
	 *
	 * @param type $requires
	 * @return type
	 */
	public function setRequiresLogin($requires = false) {
		return $this->requires_login = (bool)$requires;
	}

	/**
	 * Does this page require a login?
	 * 
	 * @return bool
	 */
	public function requiresLogin() {
		return $this->requires_login;
	}

	/**
	 * Does $path conflict with a registered page handler?
	 *
	 * Path is normalized.
	 *
	 * @param string $path
	 * @return boolean
	 */
	static function hasPageHandlerConflict($path) {
		$page_handlers = elgg_get_config('pagehandler');
		// remove first slashes to get the real handler
		$path = ltrim(AnyPage::normalizePath($path), '/');
		$pages = explode('/', $path);
		$handler = array_shift($pages);
		if (isset($page_handlers[$handler])) {
			return true;
		}

		return false;
	}

	/**
	 * Does $path conflict with a registered AnyPage path?
	 * If $page is passed, its location is ignored.
	 *
	 * Path is normalized.
	 *
	 * @param string $path
	 * @param AnyPage $page
	 * @return bool
	 */
	static function hasAnyPageConflict($path, $page = null) {
		$path = AnyPage::normalizePath($path);

		if ($page && $page->getPagePath() == $path) {
			return false;
		}

		$paths = AnyPage::getRegisteredPagePaths();
		return in_array($path, $paths);
	}

	/**
	 * Normalize a path. Removes trailing /s. Adds leading /s.
	 *
	 * @param string $path
	 * @return string
	 */
	static function normalizePath($path) {
		return '/' . ltrim(sanitise_filepath($path, false), '/');
	}

	/**
	 * Returns all registered AnyPage paths from the plugin settings
	 *
	 * @return array guid => path_name
	 */
	static function getRegisteredPagePaths() {
		
		$entities = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'anypage',
			'limit' => 0
		));

		$paths = array();
		foreach ($entities as $entity) {
			$paths[$entity->getGUID()] = $entity->getPagePath();
		}

		return $paths;
	}

	/**
	 * Returns an AnyPage entity from its path
	 *
	 * @param string $path
	 * @return mixed AnyPage entity or false
	 */
	public static function getAnyPageEntityFromPath($path) {
		$path = AnyPage::normalizePath($path);

		$entities = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'anypage',
			'limit' => 0
		));

		foreach($entities as $entity){
			if($entity->page_path == $path){
				return $entity;
			}
		}

		return false;
	}

	/**
	 * Returns paths for pages marked as public through walled garden
	 *
	 * @param string $path
	 * @return mixed AnyPage entity or false
	 */
	public static function getPathsVisibleThroughWalledGarden() {
		$entities = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'anypage',
			'metadata_name' => 'visible_through_walled_garden',
			'metadata_value' => '1'
		));

		$paths = array();
		foreach ($entities as $page) {
			$paths[] = $page->getPagePath();
		}

		return $paths;
	}
	
	/**
	 * Check if the first segment of a path would fail Elgg's default rewrite rules,
	 * which only support a-z0-9_-
	 *
	 * @param string $path
	 * @return bool
	 */
	public static function hasUnsupportedPageHandlerCharacter($path) {
		// get "page handler" chunk
		$path = ltrim(AnyPage::normalizePath($path), '/');
		$pages = explode('/', $path);
		$handler = array_shift($pages);

		$regexp = '/[^A-Za-z0-9\_\-]/';
		return preg_match($regexp, $handler);
	}
}
