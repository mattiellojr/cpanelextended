<?php
/**
 * cPanel Extended for Blesta
 *
 * @package blesta
 * @subpackage blesta.components.modules.cpanelextended
 * @author Phillips Data, Inc.
 * @copyright Copyright (c) 2015, Phillips Data, Inc.
 * @license http://www.blesta.com/license/ The Blesta License Agreement
 * @link http://www.blesta.com/ Blesta
 */
class cpanelextended extends Module {
	/**
	 * @var string The version of this module
	 */
	private static $version = "4.0.0";
	/**
	 * @var string The authors of this module
	 */
	private static $authors = array(array("name" => "CyanDark, Inc.", "url" => "http://cyandark.com"));
	/**
	 * @var array An array of request variables
	 */
	private $vars = array();
	/**
	 * Initializes the module
	 */
	public function __construct() {
		// Load components required by this module
		Loader::loadComponents($this, array(
			"Input",
			"Json"
		));
		Loader::loadHelpers($this, array(
			"DataStructure"
		));
		// Load the language required by this module
		Language::loadLang("cpanelextended", null, dirname(__FILE__) . DS . "language" . DS);
	}
	/**
	 * Returns the name of this module
	 *
	 * @return string The common name of this module
	 */
	public function getName() {
		return Language::_("Cpe.name", true);
	}
	/**
	 * Returns the version of this gateway
	 *
	 * @return string The current version of this gateway
	 */
	public function getVersion() {
		return self::$version;
	}
	/**
	 * Returns the name and url of the authors of this module
	 *
	 * @return array The name and url of the authors of this module
	 */
	public function getAuthors() {
		return self::$authors;
	}
	/**
	 * Returns all tabs to display to an admin when managing a service whose
	 * package uses this module
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @return array An array of tabs in the format of method => title. Example: array('methodName' => "Title", 'methodName2' => "Title2")
	 */
	public function getAdminTabs($package) {
		return array();
	}
	/**
	 * Returns all tabs to display to a client when managing a service whose
	 * package uses this module
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @return array An array of tabs in the format of method => title. Example: array('methodName' => "Title", 'methodName2' => "Title2")
	 */
	public function getClientTabs($package = null) {
		return array(
			'dashboard' => array(
				'name' => Language::_("Cpe.dashboard", true),
				'icon' => "fa fa-dashboard"
			),
			'details' => array(
				'name' => Language::_("Cpe.details", true),
				'icon' => "fa fa-info-circle"
			),
			'stats' => array(
				'name' => Language::_("Cpe.stats", true),
				'icon' => "fa fa-pie-chart"
			),
			'changepass' => array(
				'name' => Language::_("Cpe.changepass", true),
				'icon' => "fa fa-key"
			),
			"ftpaccounts" => array(
				'name' => Language::_("Cpe.ftp", true),
				'icon' => "fa fa-users"
			),
			"webdisk" => array(
				'name' => Language::_("Cpe.webdisk", true),
				'icon' => "fa fa-hdd-o"
			),
			"databases" => array(
				'name' => Language::_("Cpe.databases", true),
				'icon' => "fa fa-database"
			),
			"remotedatabase" => array(
				'name' => Language::_("Cpe.remotedatabase", true),
				'icon' => "fa fa-database"
			),
			"emails" => array(
				'name' => Language::_("Cpe.emails", true),
				'icon' => "fa fa-envelope"
			),
			"emailforwarder" => array(
				'name' => Language::_("Cpe.emailforwarder", true),
				'icon' => "fa fa-share"
			),
			"subdomains" => array(
				'name' => Language::_("Cpe.subdomains", true),
				'icon' => "fa fa-globe fa-flip-horizontal"
			),
			"parkeddomains" => array(
				'name' => Language::_("Cpe.parkeddomains", true),
				'icon' => "fa fa-globe"
			),
			"addondomains" => array(
				'name' => Language::_("Cpe.addondomains", true),
				'icon' => "fa fa-globe"
			),
			"zoneeditor" => array(
				'name' => Language::_("Cpe.dns_zone", true),
				'icon' => "fa fa-sitemap"
			),
			"cron" => array(
				'name' => Language::_("Cpe.cron", true),
				'icon' => "fa fa-clock-o"
			),
			"ssh" => array(
				'name' => Language::_("Cpe.ssh", true),
				'icon' => "fa fa-terminal"
			),
			"ssl" => array(
				'name' => Language::_("Cpe.ssl", true),
				'icon' => "fa fa-lock"
			),
			"softaculous" => array(
				'name' => Language::_("Cpe.softaculous", true),
				'icon' => "fa fa-bolt"
			),
			"loginto" => array(
				'name' => Language::_("Cpe.loginto", true),
				'icon' => "fa fa-sign-in"
			)
		);
	}
	public function getLogo() {
		return "views/default/images/logo.png";
	}
	/**
	 * Returns the value used to identify a particular service
	 *
	 * @param stdClass $service A stdClass object representing the service
	 * @return string A value used to identify this service amongst other similar services
	 */
	public function getServiceName($service) {
		foreach($service->fields as $field) {
			if($field->key == "cpanel_domain")
				return $field->value;
		}
		return null;
	}
	/**
	 * Returns the value used to identify a particular package service which has
	 * not yet been made into a service. This may be used to uniquely identify
	 * an uncreated services of the same package (i.e. in an order form checkout)
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param array $vars An array of user supplied info to satisfy the request
	 * @return string The value used to identify this package service
	 * @see Module::getServiceName()
	 */
	public function getPackageServiceName($package, array $vars = null) {
		if(isset($vars['cpanel_domain']))
			return $vars['cpanel_domain'];
		return null;
	}
	/**
	 * Returns a noun used to refer to a module row
	 *
	 * @return string The noun used to refer to a module row
	 */
	public function moduleRowName() {
		return Language::_("Cpe.module_row", true);
	}
	/**
	 * Returns a noun used to refer to a module row in plural form
	 *
	 * @return string The noun used to refer to a module row in plural form
	 */
	public function moduleRowNamePlural() {
		return Language::_("Cpe.module_row_plural", true);
	}
	/**
	 * Returns a noun used to refer to a module group
	 *
	 * @return string The noun used to refer to a module group
	 */
	public function moduleGroupName() {
		return Language::_("Cpe.module_group", true);
	}
	/**
	 * Returns the key used to identify the primary field from the set of module row meta fields.
	 *
	 * @return string The key used to identify the primary field from the set of module row meta fields
	 */
	public function moduleRowMetaKey() {
		return "server_name";
	}
	/**
	 * Returns an array of available service deligation order methods. The module
	 * will determine how each method is defined. For example, the method "first"
	 * may be implemented such that it returns the module row with the least number
	 * of services assigned to it.
	 *
	 * @return array An array of order methods in key/value paris where the key is the type to be stored for the group and value is the name for that option
	 * @see Module::selectModuleRow()
	 */
	public function getGroupOrderOptions() {
		return array(
			'first' => Language::_("Cpe.order_options.first", true)
		);
	}
	/**
	 * Determines which module row should be attempted when a service is provisioned
	 * for the given group based upon the order method set for that group.
	 *
	 * @return int The module row ID to attempt to add the service with
	 * @see Module::getGroupOrderOptions()
	 */
	public function selectModuleRow($module_group_id) {
		if(!isset($this->ModuleManager))
			Loader::loadModels($this, array(
				"ModuleManager"
			));
		$group = $this->ModuleManager->getGroup($module_group_id);
		if($group) {
			switch($group->add_order) {
				default:
				case "first":
					foreach($group->rows as $row) {
						if($row->meta->account_limit > (isset($row->meta->account_count) ? $row->meta->account_count : 0))
							return $row->id;
					}
					break;
			}
		}
		return 0;
	}
	/**
	 * Debug whatever the **** you want...
	 *
	 * return string
	 */
	public function debug() {
		if(func_num_args() === 0)
			return;
		// Get all passed variables
		$variables = func_get_args();
		$output    = array();
		foreach($variables as $var) {
			$output[] = print_r($var, true);
		}
		return '<pre class="debug">' . implode("\n", $output) . '</pre>';
	}
	/**
	 * Initializes the cPanel API, checks an connection and return an cPanelAPI Instance
	 * or False in case of fail
	 *
	 * @param string $host The host to the cPanel server
	 * @param string $user The user to connect as
	 * @param string $pass The password for the user
	 *
	 * @return CpanelApi The CPanelApi instance
	 */
	public function getApi($host, $user, $pass, $port = 2087, $usessl = true, $key = '', $currentUsername = '') {
		Loader::load(dirname(__FILE__) . DS . "api" . DS . "cpanel_api.php");
		$api = new CpanelApi($host, $user, $pass, $port, $usessl, $key, $currentUsername);
		return $api;
	}
	/**
	 * Initialize the cPanel API by data provided from module row meta
	 *
	 * @return cPanelAPI The CPanelApi instance
	 */
	public function getApiByMeta($meta, $fields = null) {
		return $this->getApi($meta->host_name, $meta->user_name, $meta->password, ($meta->use_ssl ? 2087 : 2086), $meta->use_ssl, $meta->key, isset($fields->cpanel_username) ? $fields->cpanel_username : '');
	}
	/**
	 * Adds the service to the remote server. Sets Input errors on failure,
	 * preventing the service from being added.
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param array $vars An array of user supplied info to satisfy the request
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being added (if the current service is an addon service service and parent service has already been provisioned)
	 * @param string $status The status of the service being added. These include:
	 * 	- active
	 * 	- canceled
	 * 	- pending
	 * 	- suspended
	 * @return array A numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function addService($package, array $vars = null, $parent_package = null, $parent_service = null, $status = "pending") {
		$row = $this->getModuleRow();
		$api = $this->getApiByMeta($row->meta);
		// If no username given, generate a username
		if(!$this->getFromVars($vars, 'cpanel_username') and $this->getFromVars($vars, 'cpanel_domain')) {
			$vars['cpanel_username'] = $this->generateUsername($vars['cpanel_domain']);
		}
		$params = $this->getInputFieldsToCreate((array) $vars, $package);
		$this->validateService($package, $vars);
		if($this->Input->errors())
			return;
		// Only provision the service if 'use_module' is true
		if($vars['use_module'] == "true") {
			$masked_params             = $params;
			$masked_params['password'] = "***";
			$this->log($row->meta->host_name . "|createacct", serialize($masked_params), "input", true);
			unset($masked_params);
			$result = $api->createacct($params);
			$this->parseResponse($result->getCleanResponse());
			if($this->Input->errors())
				return;
			// If reseller and we have an ACL set, update the reseller's ACL
			if($package->meta->type == "reseller" && $package->meta->acl != "")
				$api->setacls(array(
					'reseller' => $params['username'],
					'acllist' => $package->meta->acl
				));
		}
		// Return service fields
		return array(
			array(
				'key' => "cpanel_domain",
				'value' => $params['domain'],
				'encrypted' => 0
			),
			array(
				'key' => "cpanel_username",
				'value' => $params['username'],
				'encrypted' => 0
			),
			array(
				'key' => "cpanel_password",
				'value' => $params['password'],
				'encrypted' => 1
			),
			array(
				'key' => "cpanel_confirm_password",
				'value' => $params['password'],
				'encrypted' => 1
			)
		);
	}
	/**
	 * Edits the service on the remote server. Sets Input errors on failure,
	 * preventing the service from being edited.
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $vars An array of user supplied info to satisfy the request
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being edited (if the current service is an addon service)
	 * @return array A numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function editService($package, $service, array $vars = array(), $parent_package = null, $parent_service = null) {
		$row    = $this->getModuleRow();
		$fields = $this->serviceFieldsToObject($service->fields);
		$api    = $this->getApiByMeta($row->meta, $fields->cpanel_username);
		$params = $fields;
		$this->validateService($package, $vars, true);
		if($this->Input->errors())
			return;
		// Remove password if not being updated
		if(isset($vars['cpanel_password']) and $vars['cpanel_password'] == "")
			unset($vars['cpanel_password']);
		// Only update the service if 'use_module' is true
		if($vars['use_module'] == "true") {
			// Check for fields that changed
			$delta = array();
			foreach($vars as $key => $value) {
				if(!array_key_exists($key, $fields) || $vars[$key] != $fields->$key)
					$delta[$key] = $value;
			}
			// Update domain (if changed)
			if(isset($delta['cpanel_domain'])) {
				$this->log($row->meta->host_name . "|modifyacct", serialize($params), "input", true);
				$result = $this->parseResponse($api->modifyacct(array(
					"user" => $fields->cpanel_username,
					"domain" => $delta['cpanel_domain']
				)));
				if(!$this->Input->errors())
					$fields->cpanel_domain = $delta['cpanel_domain'];
			}
			// Update password (if changed)
			if(!$this->Input->errors() and isset($delta['cpanel_password'])) {
				$this->log($row->meta->host_name . "|passwd", "***", "input", true);
				$result = $this->parseResponse($api->passwd(array(
					"user" => $fields->cpanel_username,
					"pass" => $delta['cpanel_password']
				)));
				if(!$this->Input->errors())
					$fields->cpanel_password = $delta['cpanel_password'];
			}
			// Update username (if changed), do last so we can always rely on $fields['cpanel_username'] to contain the username
			if(!$this->Input->errors() and isset($delta['cpanel_username'])) {
				$this->log($row->meta->host_name . "|modifyacct", serialize($params), "input", true);
				$result = $this->parseResponse($api->modifyacct(array(
					"user" => $fields->cpanel_username,
					"newuser" => $delta['cpanel_username']
				)));
				if(!$this->Input->errors())
					$fields->cpanel_username = $delta['cpanel_username'];
			}
		}
		return array(
			array(
				'key' => "cpanel_domain",
				'value' => $fields->cpanel_domain,
				'encrypted' => 0
			),
			array(
				'key' => "cpanel_username",
				'value' => $fields->cpanel_username,
				'encrypted' => 0
			),
			array(
				'key' => "cpanel_password",
				'value' => $fields->cpanel_password,
				'encrypted' => 1
			),
			array(
				'key' => "cpanel_confirm_password",
				'value' => $fields->cpanel_confirm_password,
				'encrypted' => 1
			)
		);
	}
	/**
	 * Cancels the service on the remote server. Sets Input errors on failure,
	 * preventing the service from being canceled.
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being canceled (if the current service is an addon service)
	 * @return mixed null to maintain the existing meta fields or a numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function cancelService($package, $service, $parent_package = null, $parent_service = null) {
		if(($row = $this->getModuleRow())) {
			$api    = $this->getApiByMeta($row->meta);
			$fields = $this->serviceFieldsToObject($service->fields);
			$api->removeacct(array(
				'username' => $fields->cpanel_username
			));
			$this->log($row->meta->host_name . "|removeacct", serialize($fields->cpanel_username), "input", true);
		}
		return null;
	}
	/**
	 * Suspends the service on the remote server. Sets Input errors on failure,
	 * preventing the service from being suspended.
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being suspended (if the current service is an addon service)
	 * @return mixed null to maintain the existing meta fields or a numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function suspendService($package, $service, $parent_package = null, $parent_service = null) {
		$row = $this->getModuleRow();
		if($row) {
			$api = $this->getApiByMeta($row->meta);
			if($api->checkConnection()) {
				$fields = $this->serviceFieldsToObject($service->fields);
				$api->suspendacct(array(
					"user" => $fields->cpanel_username
				));
				$this->parseResponse($api->getCleanResponse());
			}
		}
		return null;
	}
	/**
	 * Unsuspends the service on the remote server. Sets Input errors on failure,
	 * preventing the service from being unsuspended.
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being unsuspended (if the current service is an addon service)
	 * @return mixed null to maintain the existing meta fields or a numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function unsuspendService($package, $service, $parent_package = null, $parent_service = null) {
		$row = $this->getModuleRow();
		if($row) {
			$api = $this->getApiByMeta($row->meta);
			if($api->checkConnection()) {
				$fields = $this->serviceFieldsToObject($service->fields);
				$api->unsuspendacct(array(
					"user" => $fields->cpanel_username
				));
				$this->parseResponse($api->getCleanResponse());
			}
		}
		return null;
	}
	/**
	 * Allows the module to perform an action when the service is ready to renew.
	 * Sets Input errors on failure, preventing the service from renewing.
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being renewed (if the current service is an addon service)
	 * @return mixed null to maintain the existing meta fields or a numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function renewService($package, $service, $parent_package = null, $parent_service = null) {
		return null;
	}
	/**
	 * Updates the package for the service on the remote server. Sets Input
	 * errors on failure, preventing the service's package from being changed.
	 *
	 * @param stdClass $package_from A stdClass object representing the current package
	 * @param stdClass $package_to A stdClass object representing the new package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param stdClass $parent_package A stdClass object representing the parent service's selected package (if the current service is an addon service)
	 * @param stdClass $parent_service A stdClass object representing the parent service of the service being changed (if the current service is an addon service)
	 * @return mixed null to maintain the existing meta fields or a numerically indexed array of meta fields to be stored for this service containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function changeServicePackage($package_from, $package_to, $service, $parent_package = null, $parent_service = null) {
		$fields = $this->serviceFieldsToObject($service->fields);
		if(($row = $this->getModuleRow())) {
			$api = $this->getApiByMeta($row->meta, $fields);
			// Only request a package change if it has changed
			if($package_from->meta->package != $package_to->meta->package) {
				$this->log($row->meta->host_name . "|changepackage", serialize(array(
					$fields->cpanel_username,
					$package_to->meta->package
				)), "input", true);
				$this->parseResponse($api->changepackage(array(
					'user' => $fields->cpanel_username,
					'pkg' => $package_to->meta->package
				)));
			}
			$params = $this->getInputFieldsToUpdate($fields, $package_to);
			//echo $this->debug($api->modifyacct($params)); die();
			$this->parseResponse($api->modifyacct($params));
		}
		return null;
	}
	/**
	 * Attempts to validate service info. This is the top-level error checking method. Sets Input errors on failure.
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param array $vars An array of user supplied info to satisfy the request
	 * @param boolean $edit True if this is an edit, false otherwise
	 * @return boolean True if the service validates, false otherwise. Sets Input errors when false.
	 */
	public function validateService($package, array $vars = null, $edit = false) {
		$rules = array(
			'cpanel_domain' => array(
				'format' => array(
					'rule' => array(
						array(
							$this,
							"validateHostName"
						)
					),
					'message' => Language::_("Cpe.!error.cpanel_domain.format", true)
				)
			),
			'cpanel_username' => array(
				'format' => array(
					'if_set' => true,
					'rule' => array(
						"matches",
						"/^[a-z]([a-z0-9])*$/i"
					),
					'message' => Language::_("Cpe.!error.cpanel_username.format", true)
				),
				'length' => array(
					'if_set' => true,
					'rule' => array(
						"betweenLength",
						1,
						8
					),
					'message' => Language::_("Cpe.!error.cpanel_username.length", true)
				)
			),
			'cpanel_password' => array(
				'valid' => array(
					'rule' => array(
						"isPassword",
						8
					),
					'message' => Language::_("Cpe.!error.cpanel_password.valid", true),
					'last' => true
				),
				'matches' => array(
					'rule' => array(
						"compares",
						"==",
						isset($vars['cpanel_confirm_password']) ? $vars['cpanel_confirm_password'] : null
					),
					'message' => Language::_("Cpe.!error.cpanel_password.matches", true)
				)
			)
		);
		if(!isset($vars['cpanel_domain']) || strlen($vars['cpanel_domain']) < 4)
			unset($rules['cpanel_domain']['test']);
		if($edit) {
			// If this is an edit and no password given then don't evaluate password
			// since it won't be updated
			if(!array_key_exists('cpanel_password', $vars) || $vars['cpanel_password'] == "")
				unset($rules['cpanel_password']);
			// Validate domain if given
			$rules['cpanel_domain']['format']['if_set'] = true;

		}
		$this->Input->setRules($rules);
		return $this->Input->validates($vars);
	}
	/**
	 * Get From Vars
	 *
	 * @param array $vars
	 * @param string $key
	 * @param mixed $default
	 * @return string or boolean
	 */
	public function getFromVars($vars, $key = '', $default = false) {
		if(empty($key) or !is_array($vars))
			return false;
		if(array_key_exists($key, $vars) and !empty($vars[$key])) {
			return $vars[$key];
		} else {
			return $default;
		}
	}
	/**
	 * Generates a username from the given host name
	 *
	 * @param string $host_name The host name to use to generate the username
	 * @return string The username generated from the given hostname
	 */
	private function generateUsername($host_name) {
		// Remove everything except letters and numbers from the domain
		// ensure no number appears in the beginning
		$username  = ltrim(preg_replace('/[^a-z0-9]/i', '', $host_name), '0123456789');
		$length    = strlen($username);
		$pool      = "abcdefghijklmnopqrstuvwxyz0123456789";
		$pool_size = strlen($pool);
		if($length < 5) {
			for($i = $length; $i < 8; $i++) {
				$username .= substr($pool, mt_rand(0, $pool_size - 1), 1);
			}
			$length = strlen($username);
		}
		if(strpos($username,'test') !== false){
			$username = 'u'.$username;
		} else {
			$username = 'u'.explode(".", $host_name)[1].$username;
		}
		return substr($username, 0, min($length, 8));
	}
	/**
	 * Returns an array of service field to set for the service using the given input
	 *
	 * @param array $vars An array of key/value input pairs
	 * @param stdClass $package A stdClass object representing the package for the service
	 * @return array An array of key/value pairs representing service fields
	 */
	private function getInputFieldsToCreate(array $vars, $package) {
		$fields = array(
			'domain' => isset($vars['cpanel_domain']) ? $vars['cpanel_domain'] : null,
			'username' => isset($vars['cpanel_username']) ? $vars['cpanel_username'] : null,
			'password' => isset($vars['cpanel_password']) ? $vars['cpanel_password'] : null,
			'plan' => $package->meta->package,
			'reseller' => ($package->meta->type == "reseller" ? 1 : 0),
			'quota' => !empty($package->meta->webquota) ? $package->meta->webquota : null,
			'bwlimit' => !empty($package->meta->bandwidth) ? $package->meta->bandwidth : null,
			'ip' => !empty($package->meta->dedicatedip) ? 'y' : null,
			'cgi' => !empty($package->meta->cgi) ? 1 : null,
			'frontpage' => !empty($package->meta->frontpagext) ? 1 : null,
			'hasshell' => !empty($package->meta->shellaccess) ? 1 : null,
			'cpmod' => !empty($package->meta->cptheme) ? $package->meta->cptheme : null,
			'maxftp' => !empty($package->meta->maxftp) ? $package->meta->maxftp : null,
			'maxsql' => !empty($package->meta->maxsql) ? $package->meta->maxsql : null,
			'maxsub' => !empty($package->meta->maxsubdomains) ? $package->meta->maxsubdomains : null,
			'maxpark' => !empty($package->meta->maxparkeddomains) ? $package->meta->maxparkeddomains : null,
			'maxaddon' => !empty($package->meta->maxaddondomains) ? $package->meta->maxaddondomains : null,
			'maxpop' => !empty($package->meta->maxpop) ? $package->meta->maxpop : null
		);
		return $fields;
	}
	/**
	 * Returns an array of service field to set for the service using the given input
	 *
	 * @param array $vars An array of key/value input pairs
	 * @param stdClass $package A stdClass object representing the package for the service
	 * @return array An array of key/value pairs representing service fields
	 */
	private function getInputFieldsToUpdate($fields, $package) {
		$fields = array(
			'user' => $fields->cpanel_username,
			'QUOTA' => !empty($package->meta->webquota) ? $package->meta->webquota : null,
			'BWLIMIT' => !empty($package->meta->bandwidth) ? $package->meta->bandwidth : null,
			'HASSCGI' => !empty($package->meta->cgi) ? 1 : null,
			'HASSHELL' => !empty($package->meta->shellaccess) ? 1 : null,
			'RS' => !empty($package->meta->cptheme) ? $package->meta->cptheme : null,
			'MAXFTP' => !empty($package->meta->maxftp) ? $package->meta->maxftp : null,
			'MAXSQL' => !empty($package->meta->maxsql) ? $package->meta->maxsql : null,
			'MAXSUB' => !empty($package->meta->maxsubdomains) ? $package->meta->maxsubdomains : null,
			'MAXPARK' => !empty($package->meta->maxparkeddomains) ? $package->meta->maxparkeddomains : null,
			'MAXADDON' => !empty($package->meta->maxaddondomains) ? $package->meta->maxaddondomains : null,
			'MAXPOP' => !empty($package->meta->maxpop) ? $package->meta->maxpop : null
		);
		return $fields;
	}
	/**
	 * Validates input data when attempting to add a package, returns the meta
	 * data to save when adding a package. Performs any action required to add
	 * the package on the remote server. Sets Input errors on failure,
	 * preventing the package from being added.
	 *
	 * @param array An array of key/value pairs used to add the package
	 * @return array A numerically indexed array of meta fields to be stored for this package containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function addPackage(array $vars = null) {
		// Set rules to validate input data
		$this->Input->setRules($this->getPackageRules($vars));
		// Build meta data to return
		$meta = array();
		if($this->Input->validates($vars)) {
			// If not reseller, then no need to store ACL
			if($vars['meta']['type'] != "reseller")
				unset($vars['meta']['acl']);
			// Return all package meta fields
			foreach($vars['meta'] as $key => $value) {
				$meta[] = array(
					'key' => $key,
					'value' => $value,
					'encrypted' => 0
				);
			}
		}
		return $meta;
	}
	/**
	 * Validates input data when attempting to edit a package, returns the meta
	 * data to save when editing a package. Performs any action required to edit
	 * the package on the remote server. Sets Input errors on failure,
	 * preventing the package from being edited.
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param array An array of key/value pairs used to edit the package
	 * @return array A numerically indexed array of meta fields to be stored for this package containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 * @see Module::getModule()
	 * @see Module::getModuleRow()
	 */
	public function editPackage($package, array $vars = null) {
		// Set rules to validate input data
		$this->Input->setRules($this->getPackageRules($vars));
		// Build meta data to return
		$meta = array();
		if($this->Input->validates($vars)) {
			// Return all package meta fields
			foreach($vars['meta'] as $key => $value) {
				$meta[] = array(
					'key' => $key,
					'value' => $value,
					'encrypted' => 0
				);
			}
		}
		return $meta;
	}
	/**
	 * Returns the rendered view of the manage module page
	 *
	 * @param mixed $module A stdClass object representing the module and its rows
	 * @param array $vars An array of post data submitted to or on the manager module page (used to repopulate fields after an error)
	 * @return string HTML content containing information to display when viewing the manager module page
	 */
	public function manageModule($module, array &$vars) {
		// Load the view into this object, so helpers can be automatically added to the view
		$this->view           = new View("manage", "default");
		$this->view->base_uri = $this->base_uri;
		$this->view->setDefaultView("components" . DS . "modules" . DS . "cpanelextended" . DS);
		// Load the helpers required for this view
		Loader::loadHelpers($this, array(
			"Form",
			"Html",
			"Widget"
		));
		$this->view->set("module", $module);
		return $this->view->fetch();
	}
	/**
	 * Returns the rendered view of the add module row page
	 *
	 * @param array $vars An array of post data submitted to or on the add module row page (used to repopulate fields after an error)
	 * @return string HTML content containing information to display when viewing the add module row page
	 */
	public function manageAddRow(array &$vars) {
		// Load the view into this object, so helpers can be automatically added to the view
		$this->view           = new View("add_row", "default");
		$this->view->base_uri = $this->base_uri;
		$this->view->setDefaultView("components" . DS . "modules" . DS . "cpanelextended" . DS);
		if(!empty($vars)) {
			if(empty($vars['use_ssl']))
				$vars['use_ssl'] = "false";
		}
		// Load the helpers required for this view
		Loader::loadHelpers($this, array(
			"Form",
			"Html",
			"Widget"
		));
		$this->view->set("vars", (object) $vars);
		return $this->view->fetch();
	}
	/**
	 * Returns the rendered view of the edit module row page
	 *
	 * @param stdClass $module_row The stdClass representation of the existing module row
	 * @param array $vars An array of post data submitted to or on the edit module row page (used to repopulate fields after an error)
	 * @return string HTML content containing information to display when viewing the edit module row page
	 */
	public function manageEditRow($module_row, array &$vars) {
		// Load the view into this object, so helpers can be automatically added to the view
		$this->view           = new View("edit_row", "default");
		$this->view->base_uri = $this->base_uri;
		$this->view->setDefaultView("components" . DS . "modules" . DS . "cpanelextended" . DS);
		// Load the helpers required for this view
		Loader::loadHelpers($this, array(
			"Form",
			"Html",
			"Widget"
		));
		if(empty($vars))
			$vars = $module_row->meta;
		else {
			if(empty($vars['use_ssl']))
				$vars['use_ssl'] = "false";
		}
		$this->view->set("vars", (object) $vars);
		return $this->view->fetch();
	}
	/**
	 * Adds the module row on the remote server. Sets Input errors on failure,
	 * preventing the row from being added.
	 *
	 * @param array $vars An array of module info to add
	 * @return array A numerically indexed array of meta fields for the module row containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 */
	public function addModuleRow(array &$vars) {
		$this->Input->setRules($this->getModuleRowRules($vars));
		if($this->Input->validates($vars))
			return $this->formatRowMeta($vars);
	}
	/**
	 * Edits the module row on the remote server. Sets Input errors on failure,
	 * preventing the row from being updated.
	 *
	 * @param stdClass $module_row The stdClass representation of the existing module row
	 * @param array $vars An array of module info to update
	 * @return array A numerically indexed array of meta fields for the module row containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 */
	public function editModuleRow($module_row, array &$vars) {
		$this->Input->setRules($this->getModuleRowRules($vars));
		if($this->Input->validates($vars))
			return $this->formatRowMeta($vars);
	}
	/**
	 * Returns all fields used when adding/editing a package, including any
	 * javascript to execute when the page is rendered with these fields.
	 *
	 * @param $vars stdClass A stdClass object representing a set of post fields
	 * @return ModuleFields A ModuleFields object, containg the fields to render as well as any additional HTML markup to include
	 */
	public function getPackageFields($vars = null) {
		Loader::loadHelpers($this, array(
			"Html"
		));
		$fields = new ModuleFields();
		$fields->setHtml("
			<script type=\"text/javascript\">
				$(document).ready(function() {
					// Set whether to show or hide the ACL option
					$('#cpanel_acl').closest('li').hide();
					if ($('input[name=\"meta[type]\"]:checked').val() == 'reseller')
						$('#cpanel_acl').closest('li').show();
					$('input[name=\"meta[type]\"]').change(function() {
						if ($(this).val() == 'reseller')
							$('#cpanel_acl').closest('li').show();
						else
							$('#cpanel_acl').closest('li').hide();
					});
				});
			</script>
		");

		$row = $this->getCorrectModuleRow($vars);
		$api = $this->getApiByMeta($row->meta);
		if(!isset($this->ArrayHelper))
			$this->ArrayHelper = $this->DataStructure->create("Array");
		$packages = array(
			"" => Language::_('Cpe.label.defaultpackage', true)
		);
		$acls     = array(
			"" => Language::_('Cpe.label.default', true)
		);

		if($row) {
			$pkglist  = $api->listpkgs()->getResponse();
			$aclslist = $api->listacls()->getResponse();

			//Generate a list with all ACLS
			$keys = (array) $this->Json->decode($api->listacls())->acls;
			$acls = array(
				"" => Language::_('Cpe.label.default', true)
			);
			foreach($keys as $key => $value) {
				$acls[$key] = $key;
			}
			//$fields->setHtml($this->debug($acls));

			$packages = array(
				"" => Language::_('Cpe.label.defaultpackage', true)
			) + $this->ArrayHelper->numericToKey($pkglist->package, "name", "name");
		}
		$fields->setHtml(Language::_('Cpe.misc.packageaddhint', true));

		// Set the cPanel package as a selectable option
		$package = $fields->label(Language::_('Cpe.label.package', true), "cpanel_package");
		$package->attach($fields->fieldSelect("meta[package]", $packages, $this->Html->ifSet($vars->meta['package']), array(
			'id' => "cpanel_package"
		)));
		$fields->setField($package);

		$quota = $fields->label(Language::_('Cpe.label.webquota', true), "cpanel_quota");
		$quota->attach($fields->fieldText("meta[webquota]", $this->Html->ifSet($vars->meta['webquota']), array(
			"style" => "width: 75px;"
		)));
		$quota->attach($fields->tooltip(Language::_('Cpe.tooltip.quota', true)));
		$fields->setField($quota);
		$bw = $fields->label(Language::_('Cpe.label.bandwidth', true), "cpanel_bw");
		$bw->attach($fields->fieldText("meta[bandwidth]", $this->Html->ifSet($vars->meta['bandwidth']), array(
			"style" => "width: 75px;"
		)));
		$bw->attach($fields->tooltip(Language::_('Cpe.bandwidth.tooltip')));
		$fields->setField($bw);
		$shell = $fields->label(Language::_('Cpe.label.shellacc', true), "cpanel_shell");
		$shell->attach($fields->fieldCheckbox("meta[shellaccess]", 1, $this->Html->ifSet($vars->meta['shellaccess'], false)));
		$fields->setField($shell);
		$cgi = $fields->label(Language::_('Cpe.label.cgiacc', true), "cpanel_cgi");
		$cgi->attach($fields->fieldCheckbox("meta[cgi]", 1, $this->Html->ifSet($vars->meta['cgi'], false)));
		$fields->setField($cgi);
		$frontpageext = $fields->label(Language::_('Cpe.label.frontpageext', true), "cpanel_fpe");
		$frontpageext->attach($fields->fieldCheckbox("meta[frontpageext]", 1, $this->Html->ifSet($vars->meta['frontpageext'], false)));
		$fields->setField($frontpageext);
		$cptheme = $fields->label(Language::_('Cpe.label.cptheme', true), "cpanel_theme");
		$cptheme->attach($fields->fieldText("meta[cptheme]", $this->Html->ifSet($vars->meta['cptheme']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($cptheme);
		$maxftp = $fields->label(Language::_('Cpe.label.maxftp', true), "cpanel_maxftp");
		$maxftp->attach($fields->fieldText("meta[maxftp]", $this->Html->ifSet($vars->meta['maxftp']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($maxftp);
		$maxsql = $fields->label(Language::_('Cpe.label.maxsql', true), "cpanel_maxsql");
		$maxsql->attach($fields->fieldText("meta[maxsql]", $this->Html->ifSet($vars->meta['maxsql']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($maxsql);
		$maxpop = $fields->label(Language::_('Cpe.label.maxpop', true), "cpanel_maxpop");
		$maxpop->attach($fields->fieldText("meta[maxpop]", $this->Html->ifSet($vars->meta['maxpop']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($maxpop);
		$dedicatedip = $fields->label(Language::_('Cpe.label.dedip', true), "cpanel_dedicatedip");
		$dedicatedip->attach($fields->fieldCheckbox("meta[dedicatedip]", 1, $this->Html->ifSet($vars->meta['dedicatedip'], false)));
		$fields->setField($dedicatedip);

		$maxdb = $fields->label("Max SQL Databases", "cpanel_maxdatabases");
		$maxdb->attach($fields->fieldText("meta[maxdatabases]", $this->Html->ifSet($vars->meta['maxdatabases']), array("style" => "width: 75px;")));
		$fields->setField($maxdb);

		$maxsubdomains = $fields->label(Language::_('Cpe.label.maxsub', true), "cpanel_maxsubdomains");
		$maxsubdomains->attach($fields->fieldText("meta[maxsubdomains]", $this->Html->ifSet($vars->meta['maxsubdomains']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($maxsubdomains);
		$maxparkeddomains = $fields->label(Language::_('Cpe.label.maxpark', true), "cpanel_maxparkeddomains");
		$maxparkeddomains->attach($fields->fieldText("meta[maxparkeddomains]", $this->Html->ifSet($vars->meta['maxparkeddomains']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($maxparkeddomains);
		$maxaddondomains = $fields->label(Language::_('Cpe.label.maxaddon', true), "cpanel_maxaddondomains");
		$maxaddondomains->attach($fields->fieldText("meta[maxaddondomains]", $this->Html->ifSet($vars->meta['maxaddondomains']), array(
			"style" => "width: 75px;"
		)));
		$fields->setField($maxaddondomains);
		$type = $fields->label(Language::_('Cpe.label.type', true), "cpanel_type");
		$type->attach($fields->fieldSelect("meta[type]", array(
			"standard" => "standard",
			"reseller" => "reseller"
		), $this->Html->ifSet($vars->meta['type'])));
		$fields->setField($type);
		// Set the cPanel package as a selectable option
		$acl = $fields->label(Language::_('Cpe.label.acl', true), "cpanel_acl");
		$acl->attach($fields->fieldSelect("meta[acl]", $acls, $this->Html->ifSet($vars->meta['acl']), array(
			'id' => "cpanel_acl"
		)));
		$fields->setField($acl);
		return $fields;
	}
	public function getCorrectModuleRow($vars) {
		$module_row = null;
		if(isset($vars->module_group) && $vars->module_group == "") {
			if(isset($vars->module_row) && $vars->module_row > 0) {
				$module_row = $this->getModuleRow($vars->module_row);
			} else {
				$rows = $this->getModuleRows();
				if(isset($rows[0]))
					$module_row = $rows[0];
				unset($rows);
			}
		} else {
			// Fetch the 1st server from the list of servers in the selected group
			$rows = $this->getModuleRows($vars->module_group);
			if(isset($rows[0]))
				$module_row = $rows[0];
			unset($rows);
		}
		return $module_row;
	}
	/**
	 * Rules performed by validator while creating or updating a package
	 *
	 */
	public function getPackageRules() {
		$rules = array(
			'module_row' => array(
				'license_valid' => array(
					'rule' => array(
						array(
							$this,
							"validateLicenseKey"
						)
					),
					'message' => null
				)
			)
		);
		return $rules;
	}
	/**
	 * Returns an array of key values for fields stored for a module, package,
	 * and service under this module, used to substitute those keys with their
	 * actual module, package, or service meta values in related emails.
	 *
	 * @return array A multi-dimensional array of key/value pairs where each key is one of 'module', 'package', or 'service' and each value is a numerically indexed array of key values that match meta fields under that category.
	 * @see Modules::addModuleRow()
	 * @see Modules::editModuleRow()
	 * @see Modules::addPackage()
	 * @see Modules::editPackage()
	 * @see Modules::addService()
	 * @see Modules::editService()
	 */
	public function getEmailTags() {
		return array(
			'module' => array('host_name', 'name_servers'),
			'package' => array('type', 'package', 'acl'),
			'service' => array('cpanel_username', 'cpanel_password', 'cpanel_domain')
		);
	}
	/**
	 * Returns all fields to display to an admin attempting to add a service with the module
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param $vars stdClass A stdClass object representing a set of post fields
	 * @return ModuleFields A ModuleFields object, containg the fields to render as well as any additional HTML markup to include
	 */
	public function getAdminAddFields($package, $vars = null) {
		Loader::loadHelpers($this, array(
			"Html"
		));
		$fields = new ModuleFields();
		// Create domain label
		$domain = $fields->label(Language::_('Cpe.label.domain', true), "cpanel_domain");
		// Create domain field and attach to domain label
		$domain->attach($fields->fieldText("cpanel_domain", $this->Html->ifSet($vars->cpanel_domain), array(
			'id' => "cpanel_domain"
		)));
		// Set the label as a field
		$fields->setField($domain);
		// Create password label
		$password = $fields->label(Language::_('Cpe.label.password', true), "cpanel_password");
		// Create password field and attach to password label
		$password->attach($fields->fieldPassword("cpanel_password", array(
			'id' => "cpanel_password"
		)));
		// Set the label as a field
		$fields->setField($password);
		// Confirm password label
		$confirm_password = $fields->label(Language::_('Cpe.label.passwordconfirm', true), "cpanel_confirm_password");
		// Create confirm password field and attach to password label
		$confirm_password->attach($fields->fieldPassword("cpanel_confirm_password", array(
			'id' => "cpanel_confirm_password"
		)));
		// Set the label as a field
		$fields->setField($confirm_password);
		return $fields;
	}
	/**
	 * Returns all fields to display to a client attempting to add a service with the module
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param $vars stdClass A stdClass object representing a set of post fields
	 * @return ModuleFields A ModuleFields object, containg the fields to render as well as any additional HTML markup to include
	 */
	public function getClientAddFields($package, $vars = null) {
		// Same as admin
		return $this->getAdminAddFields($package, $vars);
	}
	/**
	 * Returns all fields to display to an admin attempting to edit a service with the module
	 *
	 * @param stdClass $package A stdClass object representing the selected package
	 * @param $vars stdClass A stdClass object representing a set of post fields
	 * @return ModuleFields A ModuleFields object, containg the fields to render as well as any additional HTML markup to include
	 */
	public function getAdminEditFields($package, $vars = null) {
		// Same as adding
		return $this->getAdminAddFields($package, $vars);
	}
	/**
	 * Fetches the HTML content to display when viewing the service info in the
	 * admin interface.
	 *
	 * @param stdClass $service A stdClass object representing the service
	 * @param stdClass $package A stdClass object representing the service's package
	 * @return string HTML content containing information to display when viewing the service info
	 */
	public function getAdminServiceInfo($service, $package) {
		$row                  = $this->getModuleRow();
		// Load the view into this object, so helpers can be automatically added to the view
		$this->view           = new View("admin_service_info", "default");
		$this->view->base_uri = $this->base_uri;
		$this->view->setDefaultView("components" . DS . "modules" . DS . "cpanelextended" . DS);
		// Load the helpers required for this view
		Loader::loadHelpers($this, array(
			"Form",
			"Html"
		));
		$this->view->set("module_row", $row);
		$this->view->set("package", $package);
		$this->view->set("service", $service);
		$this->view->set("service_fields", $this->serviceFieldsToObject($service->fields));
		return $this->view->fetch();
	}
	/**
	 * Fetches the HTML content to display when viewing the service info in the
	 * client interface.
	 *
	 * @param stdClass $service A stdClass object representing the service
	 * @param stdClass $package A stdClass object representing the service's package
	 * @return string HTML content containing information to display when viewing the service info
	 */
	public function getClientServiceInfo($service, $package) {
		$row                  = $this->getModuleRow();
		// Load the view into this object, so helpers can be automatically added to the view
		$this->view           = new View("client_service_info", "default");
		$this->view->base_uri = $this->base_uri;
		$this->view->setDefaultView("components" . DS . "modules" . DS . "cpanelextended" . DS);
		$this->uri  					= $this->base_uri . "services/manage/" . $service->id . "/";
		// Load the helpers required for this view
		Loader::loadHelpers($this, array(
			"Form",
			"Html"
		));
		$this->view->set("module_row", $row);
		$this->view->set("package", $package);
		$this->view->set("service", $service);
		$this->view->set("service_uri", $this->uri);
		$this->view->set("service_fields", $this->serviceFieldsToObject($service->fields));
		return $this->view->fetch();
	}
	/**
	 * Formats module row input fields into a proper format required by Module::addModuleRow() and Module::editModuleRow().
	 *
	 * @param array An array of input key/value pairs
	 * @return array A numerically indexed array of meta fields for the module row containing:
	 * 	- key The key for this meta field
	 * 	- value The value for this key
	 * 	- encrypted Whether or not this field should be encrypted (default 0, not encrypted)
	 */
	private function formatRowMeta(array &$vars) {
		$meta        = array();
		$meta_fields = array(
			"server_name",
			"host_name",
			"user_name",
			"password",
			"key",
			"use_ssl",
			"account_limit",
			"name_servers",
			"port_number"
		);
		$encrypted   = array(
			"username",
			"password",
			"key"
		);
		foreach($vars as $key => $value) {
			if(in_array($key, $meta_fields)) {
				$meta[] = array(
					'key' => $key,
					'value' => $value,
					'encrypted' => (in_array($key, $encrypted)) ? 1 : 0
				);
			}
		}
		return $meta;
	}
	/**
	 * Returns all rules to validate when adding/edit a module row
	 *
	 * @return array An array of rules to validate when adding/editing a module row
	 */
	private function getModuleRowRules(array $vars) {
		$rules = array(
			'server_name' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => Language::_("Cpe.!error.servername.empty", true)
				),
				'license_valid' => array(
					'rule' => array(
						array(
							$this,
							"validateLicenseKey"
						)
					),
					'message' => null
				)
			),
			'host_name' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => Language::_("Cpe.!error.hostname.empty", true)
				)
			),
			'user_name' => array(
				'empty' => array(
					'rule' => "isEmpty",
					'negate' => true,
					'message' => Language::_("Cpe.!error.username.empty", true)
				)
			),
			'password' => array(
				'valid_connection' => array(
					'rule' => array(
						array(
							$this,
							"validateApiConnection"
						),
						$vars['host_name'],
						$vars['user_name'],
						$vars['port_number'],
						&$vars['use_ssl'],
						$vars['key']
					),
					'message' => "The connection data you provided is invalid"
				)
			)
		);
		return $rules;
	}
	/**
	 * Parses the response from the API into a stdClass object
	 *
	 * @param string $response The response from the API
	 * @return stdClass A stdClass object representing the response, void if the response was an error
	 */
	private function parseResponse($response) {
		$row     = $this->getModuleRow();
		$result  = $this->Json->decode($response);
		$success = true;
		// Set internal error
		if(!$result) {
			//$this->Input->setErrors(array('api' => array('internal' => "Internal error....")));
			$success = false;
		}
		// Only some API requests return status, so only use it if its available
		if(isset($result->status) && $result->status == 0) {
			$this->Input->setErrors(array(
				'api' => array(
					'result' => $result->statusmsg
				)
			));
			$success = false;
		} elseif(isset($result->result) && is_array($result->result) && isset($result->result[0]->status) && $result->result[0]->status == 0) {
			$this->Input->setErrors(array(
				'api' => array(
					'result' => $result->result[0]->statusmsg
				)
			));
			$success = false;
		} elseif(isset($result->cpanelresult) && !empty($result->cpanelresult->error)) {
			$this->Input->setErrors(array(
				'api' => array(
					'result' => (isset($result->cpanelresult->data->reason) ? $result->cpanelresult->data->reason : $result->cpanelresult->error)
				)
			));
			$success = false;
		} elseif(isset($result->cpanelresult->data[0]->status) && $result->cpanelresult->data[0]->status == 0) {
			$this->Input->setErrors(array(
				'api' => array(
					'result' => (isset($result->cpanelresult->data[0]->statusmsg) ? $result->cpanelresult->data[0]->statusmsg : $result->cpanelresult->error)
				)
			));
			$success = false;
		} elseif(isset($result->cpanelresult) && !empty($result->cpanelresult->error)) {
			$this->Input->setErrors(array(
				'api' => array(
					'error' => (isset($result->cpanelresult->data->reason) ? $result->cpanelresult->data->reason : $result->cpanelresult->error)
				)
			));
			$success = false;
		}
		// Log the response
		$this->log($row->meta->host_name, $response, "output", $success);
		// Return if any errors encountered
		if(!$success)
			return;
		return $result;
	}
	public function parseResponseToJson($response) {
		$result  = $this->Json->decode($response);
		$success = true;
		$data    = array();
		// Set internal error
		if(!$result) {
			$data[]  = array(
				'message' => 'Internal error....'
			);
			$success = false;
		}
		// Only some API requests return status, so only use it if its available
		if(isset($result->status) && $result->status == 0) {
			$data[]  = array(
				'message' => $result->statusmsg
			);
			$success = false;
		} elseif(isset($result->result) && is_array($result->result) && isset($result->result[0]->status) && $result->result[0]->status == 0) {
			$data[]  = array(
				'message' => $result->result[0]->statusmsg
			);
			$success = false;
		} elseif(isset($result->cpanelresult->data[0]->status) && $result->cpanelresult->data[0]->status == 0) {
			$data[]  = array(
				'message' => (isset($result->cpanelresult->data[0]->statusmsg) ? $result->cpanelresult->data[0]->statusmsg : $result->cpanelresult->error)
			);
			$success = false;
		} elseif(isset($result->cpanelresult) && !empty($result->cpanelresult->error)) {
			$data[]  = array(
				'message' => (isset($result->cpanelresult->data->reason) ? $result->cpanelresult->data->reason : $result->cpanelresult->error)
			);
			$success = false;
		} elseif(isset($result->error) && !empty($result->error)) {
			$data[]  = array(
				'message' => $result->error
			);
			$success = false;
		}
		return compact("success", "data");
	}
	public function prepareView($page, $fields = array(), $directory = '') {
		if(empty($directory))
			$directory = "components" . DS . "modules" . DS . "cpanelextended" . DS;
		$this->view           = new View($page, "default");
		$this->view->base_uri = $this->base_uri;
		$this->view->setDefaultView($directory);
		$this->view->view_dir = Router::makeURI(str_replace("index.php/", "", WEBDIR) . $this->view->view_path . "views" . DS . "default" . DS);
		;
		/*$this->view->debug = function($what)
		{
		return $this->debug($what);
		};*/
		foreach($fields as $key => $field) {
			$this->view->{$key} = $field;
		}
		$this->view->uri          = $this->base_uri . "services/manage/" . $this->vars->serviceid . "/" . $this->vars->pagename . '/';
		$this->view->jsScripts    = '<script type="text/javascript" src="' . $this->view->view_dir . 'javascript/jquery.validate.min.js"></script><script type="text/javascript" src="' . $this->view->view_dir . 'javascript/cpanelextended.functions.js"></script>';
		$this->view->commonHeader = "<script type=\"text/javascript\">
                                            var BASEURL = '" . $this->view->uri . "';
                                            var LOADERURL = '/blesta/components/modules/cpanelextended/views/default/images/loader.gif';
                                            var LOADERTEXT = '" . Language::_('Cpe.misc.loadertext', true) . "';
                                         </script>
                                         <link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->view->view_dir . "css/cpanelextended.css\">";
		// Load the helpers required for this view
		Loader::loadHelpers($this, array(
			"Form",
			"Html",
			"Widget",
			"Javascript"
		));
		return $this->view;
	}
	/**
	 * Client Dashboard tab
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $get Any GET parameters
	 * @param array $post Any POST parameters
	 * @param array $files Any FILES parameters
	 * @return string The string representing the contents of this tab
	 */
	public function dashboard($package, $service, array $vars = null, array $post = null, array $files = null) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$this->vars = $this->getPageVars($vars);
		$this->prepareView("dashboard");
		$stats             = $this->getStats($package, $service);
		$this->view->stats = $stats;
		$this->view->set("fields", $fields);
		$this->view->set("stats", $stats);
		$this->view->set("server", $row->meta->host_name);
		$this->view->set("nameservers", $row->meta->name_servers);
		return $this->view->fetch();
	}
	/**
	 * Client Details tab
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $get Any GET parameters
	 * @param array $post Any POST parameters
	 * @param array $files Any FILES parameters
	 * @return string The string representing the contents of this tab
	 */
	public function details($package, $service, array $vars = null, array $post = null, array $files = null) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$this->vars = $this->getPageVars($vars);
		$this->prepareView("details");
		$stats             = $this->getStats($package, $service);
		$this->view->stats = $stats;
		$this->view->set("fields", $fields);
		$this->view->set("stats", $stats);
		$this->view->set("server", $row->meta->host_name);
		$this->view->set("nameservers", $row->meta->name_servers);
		return $this->view->fetch();
	}
	/**
	 * Client Statistics tab (bandwidth/disk usage)
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $get Any GET parameters
	 * @param array $post Any POST parameters
	 * @param array $files Any FILES parameters
	 * @return string The string representing the contents of this tab
	 */
	public function stats($package, $service, array $vars = null, array $post = null, array $files = null) {
		$this->vars = $this->getPageVars($vars);
		$this->prepareView("stats");
		$stats             = $this->getStats($package, $service);
		$this->view->stats = $stats;
		//	$this->view->set("stats", $stats);
		//	$this->view->set("user_type", $package->meta->type);
		return $this->view->fetch();
	}
	/**
	 * Fetches all account stats
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @return stdClass A stdClass object representing all of the stats for the account
	 */
	private function getStats($package, $service) {
		$row            = $this->getModuleRow();
		$fields         = $this->serviceFieldsToObject($service->fields);
		$service_fields = $this->serviceFieldsToObject($service->fields);
		$api            = $this->getApiByMeta($row->meta, $fields);
		$stats          = new stdClass();
		// Fetch account info
		$this->log($row->meta->host_name . "|accountsummary", serialize($fields->cpanel_username), "input", true);
		$stats->account_info    = $this->parseResponse($api->accountsummary());
		$stats->disk_usage      = array(
			'used' => null,
			'limit' => null
		);
		$stats->bandwidth_usage = array(
			'used' => null,
			'limit' => null
		);
		// Get bandwidth/disk for reseller user
		if($package->meta->type == "reseller") {
			$this->log($row->meta->host_name . "|resellerstats", serialize($service_fields->cpanel_username), "input", true);
			$reseller_info = $this->parseResponse($api->resellerstats($service_fields->cpanel_username));
			if(isset($reseller_info->result)) {
				$stats->disk_usage['used']       = $reseller_info->result->diskused;
				$stats->disk_usage['limit']      = $reseller_info->result->diskquota;
				$stats->disk_usage['alloc']      = $reseller_info->result->totaldiskalloc;
				$stats->bandwidth_usage['used']  = $reseller_info->result->totalbwused;
				$stats->bandwidth_usage['limit'] = $reseller_info->result->bandwidthlimit;
				$stats->bandwidth_usage['alloc'] = $reseller_info->result->totalbwalloc;
			}
		}
		// Get bandwidth/disk for standard user
		else {
			$params = array(
				'month' => date('n'),
				'year' => date('Y'),
				'search' => $service_fields->cpanel_username,
				'searchtype' => "user"
			);
			$this->log($row->meta->host_name . "|showbw", serialize($params), "input", true);
			$bw = $this->parseResponse($api->showbw($params));
			if(isset($bw->bandwidth[0]->acct[0])) {
				$stats->bandwidth_usage['used']  = $bw->bandwidth[0]->acct[0]->totalbytes / (1024 * 1024);
				$stats->bandwidth_usage['limit'] = $bw->bandwidth[0]->acct[0]->limit / (1024 * 1024);
			}
			if(isset($stats->account_info->acct[0])) {
				$stats->disk_usage['used']  = preg_replace("/[^0-9]/", "", $stats->account_info->acct[0]->diskused);
				$stats->disk_usage['limit'] = preg_replace("/[^0-9]/", "", $stats->account_info->acct[0]->disklimit);
			}
		}
		return $stats;
	}
	/**
	 * Client Actions (reset password)
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $get Any GET parameters
	 * @param array $post Any POST parameters
	 * @param array $files Any FILES parameters
	 * @return string The string representing the contents of this tab
	 */
	public function changepass($package, $service, array $get = null, array $post = null, array $files = null) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		//Change Password
		if(!isset($this->Record))
			Loader::loadComponents($this, array(
				"Record"
			));
		//Comparing Passwords
		if(!empty($post)) {
			$stats = new stdClass();
			if($post['pass'] == $post['pass_confirm']) {
				$pass_defined = $post['pass'];
				$params       = array(
					'user' => $fields->cpanel_username,
					'pass' => $pass_defined
				);
				$this->log($row->meta->host_name . "|passwd", serialize($params), "input", true);
				$response = $this->parseResponse($api->passwd($params));
				$this->Record->query("UPDATE `service_fields` SET `value` = '" . $this->ModuleManager->systemEncrypt($pass_defined) . "' WHERE `key` = 'cpanel_password' AND `service_id` =" . $service->id);
				$this->Record->query("UPDATE `service_fields` SET `value` = '" . $this->ModuleManager->systemEncrypt($pass_defined) . "' WHERE `key` = 'cpanel_confirm_password' AND `service_id` =" . $service->id);
			}
		}
		$this->prepareView("changepass");
		$this->view->set("response", $response);
		$this->view->set("row", $row);
		$this->view->set("fields", $fields);
		$this->view->server    = $row;
		$this->view->fields    = $fields;
		$this->view->cpanelurl = $api->buildUrl();
		return $this->view->fetch();
	}
	/**
	 * DNS Zone Editor (reset password)
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $get Any GET parameters
	 * @param array $post Any POST parameters
	 * @param array $files Any FILES parameters
	 * @return string The string representing the contents of this tab
	 */
	public function zoneeditor($package, $service, array $get = null, array $post = null, array $files = null) {
		global $out;
		//Add DNS
		if(isset($post['ttl']) && isset($post['name']) && isset($post['record']) && isset($post['type']) && !($post['type'] == 'MX')) {
			$row            = $this->getModuleRow();
			$fields         = $this->serviceFieldsToObject($service->fields);
			$service_fields = $this->serviceFieldsToObject($service->fields);
			$api            = $this->getApiByMeta($row->meta, $fields);
			$stats          = new stdClass();
			$params         = array(
				'domain' => $service_fields->cpanel_domain,
				'name' => $post['name'],
				'type' => $post['type'],
				'address' => $post['record'],
				'cname' => $post['record'],
				'txtdata' => $post['record'],
				'ttl' => $post['ttl'],
				'class' => "IN"
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("ZoneEdit", "add_zone_record", $params));
		}

		//Add MX
		if (isset($post['ttl']) && isset($post['record']) && isset($post['type']) && $post['type'] == 'MX'){
			$row            = $this->getModuleRow();
			$fields         = $this->serviceFieldsToObject($service->fields);
			$service_fields = $this->serviceFieldsToObject($service->fields);
			$api            = $this->getApiByMeta($row->meta, $fields);
			$stats          = new stdClass();
			$params         = array(
				'domain' => $service_fields->cpanel_domain,
				'exchange' => $post['record'],
				'priority' => $post['ttl']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("Email", "addmx", $params));
		}

		//Delete Zone
		if(isset($_GET['delete'])) {
			$row            = $this->getModuleRow();
			$fields         = $this->serviceFieldsToObject($service->fields);
			$service_fields = $this->serviceFieldsToObject($service->fields);
			$api            = $this->getApiByMeta($row->meta, $fields);
			$stats          = new stdClass();
			$params         = array(
				'domain' => $service_fields->cpanel_domain,
				'line' => $_GET['delete']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("ZoneEdit", "remove_zone_record", $params));
		}

		//Delete MX
		if(isset($_GET['deletemx']) && isset($_GET['pref'])) {
			$row            = $this->getModuleRow();
			$fields         = $this->serviceFieldsToObject($service->fields);
			$service_fields = $this->serviceFieldsToObject($service->fields);
			$api            = $this->getApiByMeta($row->meta, $fields);
			$stats          = new stdClass();
			$params         = array(
				'domain' => $service_fields->cpanel_domain,
				'exchange' => $_GET['deletemx'],
				'preference' => $_GET['pref']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("Email", "delmx", $params));
		}

		$row            = $this->getModuleRow();
		$fields         = $this->serviceFieldsToObject($service->fields);
		$service_fields = $this->serviceFieldsToObject($service->fields);
		$api            = $this->getApiByMeta($row->meta, $fields);

		//Get DNS Zones
		$stats  = new stdClass();
		$params = array(
			'domain' => $service_fields->cpanel_domain
		);
		$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
		$response = json_decode($api->sendApi2Request("ZoneEdit", "fetchzone_records", $params), true);

		$this->prepareView("dns_zone");
		$this->view->set("response", $response);
		$this->view->set("row", $row);
		$this->view->set("fields", $fields);
		$this->view->server    = $row;
		$this->view->fields    = $fields;
		$this->view->cpanelurl = $api->buildUrl();
		return $this->view->fetch();
	}
	/**
	 * Email Forwarders (reset password)
	 *
	 * @param stdClass $package A stdClass object representing the current package
	 * @param stdClass $service A stdClass object representing the current service
	 * @param array $get Any GET parameters
	 * @param array $post Any POST parameters
	 * @param array $files Any FILES parameters
	 * @return string The string representing the contents of this tab
	 */
	public function emailforwarder($package, $service, array $get = null, array $post = null, array $files = null) {
		global $out;
		//Add Forwarder
		if(isset($post['email']) && isset($post['fwdemail'])) {
			$row            = $this->getModuleRow();
			$fields         = $this->serviceFieldsToObject($service->fields);
			$service_fields = $this->serviceFieldsToObject($service->fields);
			$api            = $this->getApiByMeta($row->meta, $fields);
			$stats          = new stdClass();
			$params         = array(
				'domain' => $service_fields->cpanel_domain,
				'email' => $post['email'] . '@' . $service_fields->cpanel_domain,
				'fwdopt' => "fwd",
				'fwdemail' => $post['fwdemail']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("Email", "addforward", $params));
		}
		//Remove Forwarder
		if(isset($post['email_f']) && isset($post['emaildest'])) {
			$row            = $this->getModuleRow();
			$fields         = $this->serviceFieldsToObject($service->fields);
			$service_fields = $this->serviceFieldsToObject($service->fields);
			$api            = $this->getApiByMeta($row->meta, $fields);
			$stats          = new stdClass();
			$params         = array(
				'emaildest' => $post['emaildest'],
				'email' => $post['email_f'] . '@' . $service_fields->cpanel_domain
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("Email", "delforward", $params));
		}
		$row            = $this->getModuleRow();
		$fields         = $this->serviceFieldsToObject($service->fields);
		$service_fields = $this->serviceFieldsToObject($service->fields);
		$api            = $this->getApiByMeta($row->meta, $fields);
		//Get Forwarders
		$stats  = new stdClass();
		$params = array(
			'domain' => $service_fields->cpanel_domain
		);
		$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
		//Parse Response
		$response = $this->parseResponse($api->sendApi2Request("Email", "listforwards", $params));
		$this->prepareView("emailforwarder");
		$this->view->set("response", $response);
		$this->view->set("row", $row);
		$this->view->set("fields", $fields);
		$this->view->set("service_fields", $service_fields);
		$this->view->server    = $row;
		$this->view->fields    = $fields;
		$this->view->cpanelurl = $api->buildUrl();
		return $this->view->fetch();
	}
	/**
	 * Manage user FTP accounts
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 *
	 * @return string
	 */
	public function ftpAccounts($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$this->vars = $this->getPageVars($vars);
		$api        = $this->getApiByMeta($row->meta, $fields);
		switch($this->vars->action) {
			case "listftp":
				if($api->sendApi2Request("Ftp", "listftpwithdisk")->isSuccess()) {
					$this->printJson($api->getResponse());
				}
				break;
			case "create":
				$this->Input->setRules($this->getUserRules('Ftp'));
				if(!empty($post) and $this->Input->validates($post)) {
					if($api->sendApi2Request("Ftp", "addftp", array(
						"user" => $post["ftpusername"],
						"pass" => $post["ftppassword"],
						"homedir" => $post["directory"],
						"quota" => $post["ftpquota"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				}
				break;
			case "delete":
				if(!empty($post)) {
					if($api->sendApi1Request("Ftp", "delftp", array(
						'user' => $post["username"],
						'destroy' => $post["destroy"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->view           = $this->prepareView("ftpdelete", compact("fields"));
					$this->view->username = $this->getFromVars($_GET, "username");
					//$this->view->uri = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . "/";
					return $this->showCorrectView($this->view);
				}
				break;
			case "changepassword":
				if(!empty($post)) {
					if($api->sendApi1Request("Ftp", "passwdftp", array(
						'user' => $post["username"],
						'passwd' => $post["password"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->view           = $this->prepareView("ftpchangepassword", compact("fields"));
					$this->view->username = $this->getFromVars($_GET, 'username', $fields->cpanel_username);
					return $this->showCorrectView($this->view);
				}
				break;
			case "changequota":
				if(!empty($post)) {
					$post = array_map('urldecode', $post);
					if($api->sendApi1Request("Ftp", "ftpquota", array(
						'user' => $post["username"],
						'quota' => $post["newquota"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->view               = $this->prepareView("ftpchangequota", compact("fields"));
					$this->view->currentQuota = $this->getFromVars($_GET, 'currentQuota', 'unlimited');
					$this->view->currentUser  = $this->getFromVars($_GET, 'username', $fields->cpanel_username);
					//$this->view->uri = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . "/" . $vars->action .'?currentQuota='.$this->getFromVars($_GET, 'currentQuota', 'unlimited').'&username='.$this->getFromVars($_GET, 'username', urlencode($fields->cpanel_username.'@'.$fields->cpanel_domain));
					return $this->showCorrectView($this->view);
				}
				break;
			default:
				break;
		}
		$api->sendApi2Request("Ftp", "listftpwithdisk");
		//$this->parseResponse($api->getCleanResponse());
		if($api->isSuccess()) {
			$this->prepareView("ftpaccounts", compact("fields"));
			//$this->Javascript->setFile("cpanelextended.functions.js", "head", "components" . DS . "modules" . DS . "cpanelextended" . DS . "views" . DS . "default" . DS . "javascript" . DS);
			$this->view->accounts = $api->getResponse();
			//$this->view->uri = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . '/';
			return $this->view->fetch();
		} else {
			return $api->getResultMessage();
		}
	}
	/**
	 * Manage Databases
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 *
	 * @return string
	 */
	public function databases($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$api2       = clone $api;
		$vars       = $this->getPageVars($vars);
		$this->vars = $vars;
		$this->uri  = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . '/';
		switch($vars->action) {
			case "adddb":
				if($api->sendApi1Request("Mysql", "adddb", array(
					"dbname" => $post["dbname"]
				))) {
					return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "adduser":
				if($api->sendApi1Request("Mysql", "adduser", array(
					"username" => $post["dbusername"],
					"password" => $post["dbpassword"]
				))) {
					return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "addusertodb":
				if(isset($post["privileges"]) and in_array("all", $post["privileges"]))
					$privileges = "all";
				else
					$privileges = implode(" ", $post["privileges"]);
				if($api->sendApi1Request("Mysql", "adduserdb", array(
					"dbname" => $post["database"],
					"dbuser" => $post["dbuser"],
					"perms_list" => $privileges
				))) {
					return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "deleteuserfromdb":
				$db       = $this->getFromVars($_GET, "database");
				$username = $this->getFromVars($_GET, "dbusername");
				if($api->sendApi1Request("Mysql", "deluserdb", array(
					"dbuser" => $db,
					"dbname" => $username
				))) {
					return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "changeprivileges":
				if(!empty($post)) {
					if($api->sendApi1Request($privileges, $action)) {
						return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$db       = $this->getFromVars($_GET, "database");
					$username = $this->getFromVars($_GET, "username");
					$this->prepareView("databaseschangeprivileges", compact("fields"));
					$this->view->database = $db;
					$this->view->username = $username;
					if($api->sendApi2Request("MysqlFE", "userdbprivs", array(
						"db" => $db,
						"username" => $username
					))->isSuccess()) {
						$this->view->privileges = $api->getResponse();
						return $this->showCorrectView($this->view);
					}
				}
				break;
			case "deleteuser":
				if(!empty($post)) {
					if($api->sendApi1Request("Mysql", "deluser", array(
						"dbuser" => $post["dbuser"]
					))) {
						return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("databasesdeleteuser", compact("fields"));
					$this->view->username = $this->getFromVars($_GET, "dbuser");
					return $this->showCorrectView($this->view);
				}
				break;
			case "deletedb":
				if(!empty($post)) {
					if($api->sendApi1Request("Mysql", "deldb", array(
						"dbname" => $post["dbname"]
					))) {
						return $this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("databasesdeletedb", compact("fields"));
					$this->view->dbname = $this->getFromVars($_GET, "dbname");
					return $this->showCorrectView($this->view);
				}
				break;
			default:
				break;
		}
		if($api->sendApi2Request("MysqlFE", "listdbs")->isSuccess() and $api2->sendApi2Request("MysqlFE", "listusers")->isSuccess()) {
			$this->prepareView("databases", compact("fields"));
			$this->view->databases   = $api->getResponse();
			$this->view->users       = $api2->getResponse();
			$this->view->username    = $fields->cpanel_username;
			//$this->view->uri = $this->uri;
			$this->view->dboptions   = $this->DataStructure->create("Array")->numericToKey($this->view->databases->cpanelresult->data, "db", "db");
			$this->view->useroptions = $this->DataStructure->create("Array")->numericToKey($this->view->users->cpanelresult->data, "user", "user");
			return $this->view->fetch();
		} else {
			return $api->getResultMessage();
		}
	}
	/**
	 * Manage Remote MySQL
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 *
	 * @return string
	 */
	public function remotedatabase($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$vars       = $this->getPageVars($vars);
		$this->vars = $vars;
		$this->uri  = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . '/';

		//Add Host
		if(!empty($post['addhost']) && count(explode('.', $post['addhost'])) > 2){
			$params         = array(
				'host' => $post['addhost']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("MysqlFE", "authorizehost", $params));
		}

		//Remove Host
		if(!empty($_GET['delhost'])){
			$params         = array(
				'host' => $_GET['delhost']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("MysqlFE", "deauthorizehost", $params));
		}
		if($api->sendApi2Request("MysqlFE", "listhosts")->isSuccess()) {
			$this->prepareView("remotedatabase", compact("fields"));
			$this->view->hosts   = $api->getResponse();
			return $this->view->fetch();
		} else {
			return $api->getResultMessage();
		}
	}
	/**
	 * Manage user emails
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 *
	 * @return string
	 */
	public function emails($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$api2       = clone $api;
		$vars       = $this->getPageVars($vars);
		$this->vars = $vars;
		switch($vars->action) {
			case "create":
				if(!empty($post)) {
					if($api->sendApi2Request("Email", "addpop", array(
						'domain' => $post["emaildomain"],
						'email' => $post["emailusername"],
						'password' => $post["emailpassword"],
						'quota' => $post["emailquota"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				}
				break;
			case "changequota":
				if(!empty($post)) {
					if($api->sendApi2Request("Email", "editquota", array(
						'domain' => $post["emaildomain"],
						'email' => $post["emailusername"],
						'quota' => $post["newquota"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					@list($username, $domain) = explode("@", $this->getFromVars($_GET, "username", $fields->cpanel_username . '@' . $fields->cpanel_domain));
					$this->prepareView("emailschangequota");
					$this->view->username = $username;
					$this->view->domain   = $domain;
					$this->view->quota    = $this->getFromVars($_GET, "currentQuota", 0);
					return $this->showCorrectView($this->view);
				}
				break;
			case "changepassword":
				if(!empty($post)) {
					if($api->sendApi2Request("Email", "passwdpop", array(
						'domain' => $post['emaildomain'],
						'email' => $post['emailusername'],
						'password' => $post['password']
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					@list($username, $domain) = explode("@", $this->getFromVars($_GET, "username", $fields->cpanel_username . '@' . $fields->cpanel_domain));
					$this->prepareView("emailschangepassword");
					$this->view->username = $username;
					$this->view->domain   = $domain;
					return $this->showCorrectView($this->view);
				}
				break;
			case "delete":
				if(!empty($post)) {
					if($api->sendApi2Request("Email", "delpop", array(
						'domain' => $post['emaildomain'],
						'email' => $post['emailusername']
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					@list($username, $domain) = explode("@", $this->getFromVars($_GET, "username", $fields->cpanel_username . '@' . $fields->cpanel_domain));
					$this->prepareView("emailsdelete");
					$this->view->username = $username;
					$this->view->domain   = $domain;
					return $this->showCorrectView($this->view);
				}
				break;
			default:
				break;
		}
		if($api->sendApi2Request("Email", "listpopswithdisk")->isSuccess()) {
			$this->prepareView("emails");
			$domains              = $api2->sendApi2Request("Email", "listmaildomains", array(
				"user" => $fields->cpanel_username
			))->getResponse();
			$this->view->accounts = $api->getResponse();
			$this->view->username = $fields->cpanel_username;
			$this->view->domains  = $this->toArray($domains->cpanelresult->data, 'domain');
			$this->uri            = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . '/';
			$this->view->uri      = $this->uri;
			return $this->view->fetch();
		} else {
			return "The data couldn't be fetched. Please try again;";
		}
	}
	/**
	 * Subdomains
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function subdomains($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		switch($this->vars->action) {
			case "create":
				if($api->sendApi2Request("SubDomain", "addsubdomain", array(
					'domain' => $post["subdomainname"],
					'dir' => $post["subdomainroot"],
					'rootdomain' => $post["maindomain"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "redirect":
				if(!empty($post)) {
					if($api->sendApi1Request("SubDomain", "setsuburl", array(
						'sub' => $post["subdomain"],
						'url' => $post["url"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("subdomainredirect");
					$this->view->subdomain = $this->getFromVars($_GET, "domain");
					return $this->showCorrectView($this->view);
				}
				break;
			case "disableredir":
				if($api->sendApi1Request("SubDomain", "disablesubrd", array(
					'sub' => $post["subdomain"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "delete":
				if(!empty($post)) {
					if($api->sendApi2Request("SubDomain", "delsubdomain", array(
						'domain' => $post["subdomainname"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("subdomaindelete");
					$this->view->subdomain = $this->getFromVars($_GET, "domain");
					return $this->showCorrectView($this->view);
				}
				break;
		}
		if($api->sendApi2Request("SubDomain", "listsubdomains")->isSuccess()) {
			$this->prepareView("subdomains");
			$this->view->subdomains = $api->getResponse();
			$domains                = $api->sendApi2Request("DomainLookup", "getbasedomains", array(
				"user" => $fields->cpanel_username
			))->getResponse();
			$this->view->username   = $fields->cpanel_username;
			$this->view->domains    = $this->toArray($domains->cpanelresult->data, 'domain');
			return $this->view->fetch();
		} else {
			return $api->getResultMessage();
		}
	}
	/**
	 * Addon Domains
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function addonDomains($package, $service, array $vars = array(), array $post = array()) {
		$row    = $this->getModuleRow();
		$fields = $this->serviceFieldsToObject($service->fields);
		$api    = $this->getApiByMeta($row->meta, $fields);
		;
		$this->vars = $this->getPageVars($vars);
		switch($this->vars->action) {
			case "create":
				if($api->sendApi1Request("Ftp", "addFtp", array(
					'user' => $post["domainusername"],
					'pass' => $post["domainpassword"],
					'homedir2' => $post["directory"]
				))) {
					$result = $this->parseResponseToJson($api->getCleanResponse());
					if($result['success'] == 0) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					} else {
						if($api->sendApi2Request("AddonDomain", "addaddondomain", array(
							'dir' => $post["directory"],
							'newdomain' => $post["newdomain"],
							'subdomain' => $post["domainusername"],
							'pass' => $post["domainpassword"]
						))) {
							// There can ba a problem with API response here at some cPanel versions
							$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
						}
					}
				}
				break;
			case "redirect":
				if(!empty($post)) {
					if($api->sendApi2Request("AddonDomain", "setsuburl", array(
						'subdomain' => $post['subdomain'],
						'url' => $post['url']
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("addondomainsredirect");
					$this->view->domain    = $this->getFromVars($_GET, 'domain');
					$this->view->subdomain = $this->getFromVars($_GET, 'subdomain');
					return $this->showCorrectView($this->view);
				}
				break;
			case "delete":
				if(!empty($post)) {
					if($api->sendApi2Request("AddonDomain", "deladdondomain", array(
						'domain' => $post['domain'],
						'subdomain' => $post['subdomain']
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("addondomainsdelete");
					$this->view->domain    = $this->getFromVars($_GET, 'domain');
					$this->view->subdomain = $this->getFromVars($_GET, 'subdomain');
					return $this->showCorrectView($this->view);
				}
				break;
			default:
				break;
		}
		if($api->sendApi2Request("AddonDomain", "listaddondomains")->isSuccess()) {
			$this->prepareView("addondomains");
			$this->view->username = $fields->cpanel_username;
			$this->view->domains  = $api->getResponse();
			return $this->view->fetch();
		} else {
			return $api->getResultMessage();
		}
	}
	/**
	 * Parked Domains
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function parkedDomains($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		switch($this->vars->action) {
			case "create":
				if($api->sendApi2Request("Park", "park", array(
					'domain' => $post["domainname"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "redirect":
				if(!empty($post)) {
					if($api->sendApi1Request("Park", "setredirecturl", array(
						'domain' => $post["domain"],
						'url' => $post["url"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("parkeddomainredirect");
					$this->view->domain = $this->getFromVars($_GET, "domain");
					return $this->showCorrectView($this->view);
				}
				break;
			case "disableredir":
				if($api->sendApi1Request("Park", "disableredirect", array(
					'domain' => $post["domain"],
					'url' => $post["url"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "delete":
				if(!empty($post)) {
					if($api->sendApi2Request("Park", "unpark", array(
						"domain" => $post["domain"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("parkeddomaindelete");
					$this->view->domain = $this->getFromVars($_GET, "domain");
					return $this->showCorrectView($this->view);
				}
				break;
			default:
				break;
		}
		if($api->sendApi2Request("Park", "listparkeddomains")->isSuccess()) {
			$this->prepareView("parkeddomains");
			$this->view->domains  = $api->getResponse();
			$this->view->username = $fields->cpanel_username;
			return $this->view->fetch();
		} else {
			return "The data couldn't be fetched. Please try again";
		}
	}
	/**
	 * Builds and retrieves a list of common cron settings
	 *
	 * @return array A list of nested common cron settings
	 */
	private function getCommonCronSettings() {
		if(!isset($this->Date))
			Loader::loadHelpers($this, array(
				"Date"
			));
		$all    = array(
			'--' => Language::_("Cpe.cron.commonsettings", true),
			'* * * * *' => Language::_("Cpe.cron.everyminute", true),
			'*/5 * * * *' => Language::_("Cpe.cron.everyfive", true),
			'0,30 * * * *' => Language::_("Cpe.cron.twicehour", true),
			'0 * * * *' => Language::_("Cpe.cron.oncehour", true),
			'0 0,12 * * *' => Language::_("Cpe.cron.twiceday", true),
			'0 0 * * *' => Language::_("Cpe.cron.onceday", true),
			'0 0 * * 0' => Language::_("Cpe.cron.onceweek", true),
			'0 0 1,15 * *' => Language::_("Cpe.cron.firstand15th", true),
			'0 0 1 * *' => Language::_("Cpe.cron.oncemonth", true),
			'0 0 1 1 *' => Language::_("Cpe.cron.onceyear", true)
		);
		$minute = array(
			'--' => Language::_("Cpe.cron.commonsettings", true),
			'*' => Language::_("Cpe.cron.minute.everyminute", true),
			'*/2' => Language::_("Cpe.cron.minute.otherminute", true),
			'*/5' => Language::_("Cpe.cron.minute.everyfive", true),
			'*/10' => Language::_("Cpe.cron.minute.everyten", true),
			'*/15' => Language::_("Cpe.cron.minute.every15", true),
			'0,30' => Language::_("Cpe.cron.minute.every30", true),
			'' => Language::_("Cpe.cron.minute.minutes", true)
		);
		for($i = 0; $i < 60; $i++) {
			$label = "";
			if($i % 15 == 0) {
				if($i == 0)
					$label = Language::_("Cpe.cron.minute.top", true);
				elseif($i == 15)
					$label = Language::_("Cpe.cron.minute.quarterpast", true);
				elseif($i == 30)
					$label = Language::_("Cpe.cron.minute.halfpast", true);
				elseif($i == 45)
					$label = Language::_("Cpe.cron.minute.quartertil", true);
			}
			$minute[$i] = Language::_("Cpe.cron.minute.label", true, str_pad($i, 2, "0", STR_PAD_LEFT), $label, $i);
		}
		$hour = array(
			'--' => Language::_("Cpe.cron.commonsettings", true),
			'*' => Language::_("Cpe.cron.hour.everyhour", true),
			'*/2' => Language::_("Cpe.cron.hour.otherhour", true),
			'*/3' => Language::_("Cpe.cron.hour.everythree", true),
			'*/4' => Language::_("Cpe.cron.hour.everyfour", true),
			'*/6' => Language::_("Cpe.cron.hour.everysix", true),
			'0,12' => Language::_("Cpe.cron.hour.everytwelve", true),
			'' => Language::_("Cpe.cron.hour.hours", true)
		);
		for($i = 0; $i < 24; $i++) {
			$hour[$i] = Language::_("Cpe.cron.hour.label", true, $this->Date->cast(str_pad($i, 2, "0", STR_PAD_LEFT) . ":00:00", "g:i A"), ($i == 0 ? "midnight" : ($i == 12 ? "noon" : "")), $i);
		}
		$day = array(
			'--' => Language::_("Cpe.cron.commonsettings", true),
			'*' => Language::_("Cpe.cron.day.everyday", true),
			'*/2' => Language::_("Cpe.cron.day.everyother", true),
			'1,15' => Language::_("Cpe.cron.day.twicemonth", true),
			'' => Language::_("Cpe.cron.day.days", true)
		);
		for($i = 1; $i <= 31; $i++) {
			$ordinal = "th";
			if($i % 10 == 1 && substr($i, -2, 2) != "11")
				$ordinal = "st";
			elseif($i % 10 == 2 && substr($i, -2, 2) != "12")
				$ordinal = "nd";
			elseif($i % 10 == 3 && substr($i, -2, 2) != "13")
				$ordinal = "rd";
			$day[$i] = Language::_("Cpe.cron.day.label", true, $i, $ordinal);
		}
		$month  = array(
			'--' => Language::_("Cpe.cron.commonsettings", true),
			'*' => Language::_("Cpe.cron.month.everymonth", true),
			'*/2' => Language::_("Cpe.cron.month.everyother", true),
			'*/4' => Language::_("Cpe.cron.month.everythree", true),
			'1,7' => Language::_("Cpe.cron.month.everysix", true),
			'' => Language::_("Cpe.cron.month.months", true)
		);
		$months = $this->Date->getMonths(1, 12, "n", "F");
		for($i = 1; $i <= 12; $i++) {
			$month[$i] = Language::_("Cpe.cron.month.month", true, (isset($months[$i]) ? $months[$i] : ""), $i);
		}
		$week_day = array(
			'--' => Language::_("Cpe.cron.commonsettings", true),
			'*' => Language::_("Cpe.cron.weekday.everyday", true),
			'1-5' => Language::_("Cpe.cron.weekday.monfri", true),
			'0,6' => Language::_("Cpe.cron.weekday.weekend", true),
			'1,3,5' => Language::_("Cpe.cron.weekday.monwedfri", true),
			'2,4' => Language::_("Cpe.cron.weekday.tuethu", true),
			'' => Language::_("Cpe.cron.weekday.weekday", true)
		);
		$days     = array(
			0 => Language::_("Cpe.sunday", true),
			1 => Language::_("Cpe.monday", true),
			2 => Language::_("Cpe.tuesday", true),
			3 => Language::_("Cpe.wednesday", true),
			4 => Language::_("Cpe.thursday", true),
			5 => Language::_("Cpe.friday", true),
			6 => Language::_("Cpe.saturday", true)
		);
		for($i = 0; $i < 7; $i++)
			$week_day[$i] = Language::_("Cpe.cron.weekday.label", true, $days[$i], $i);
		return array(
			'all' => $all,
			'minute' => $minute,
			'hour' => $hour,
			'day' => $day,
			'month' => $month,
			'weekday' => $week_day
		);
	}
	/**
	 * Cron
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function cron($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		switch($this->vars->action) {
			case "create":
				if(!empty($post)) {
					if($api->sendApi2Request("Cron", "add_line", array(
						'minute' => $post["jobminute"],
						'hour' => $post["jobhour"],
						'day' => $post["jobday"],
						'month' => $post["jobmonth"],
						'weekday' => $post["jobweekday"],
						'command' => $post["command"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				}
				break;
			case "delete":
				if(!empty($post)) {
					if($api->sendApi2Request("Cron", "remove_line", array(
						'line' => $post["line"]
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("crondelete");
					$this->view->line = $this->getFromVars($_GET, "line");
					return $this->showCorrectView($this->view);
				}
				break;
			case "edit":
				if(!empty($post)) {
					if($api->sendApi2Request("Cron", "edit_line", array(
						'line' => $post['line'],
						'command' => html_entity_decode($post['command']),
						'day' => $post['jobday'],
						'hour' => $post['jobhour'],
						'minute' => $post['jobminute'],
						'month' => $post['jobmonth'],
						'weekday' => $post['jobweekday']
					))) {
						$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
					}
				} else {
					$this->prepareView("cronedit");
					$this->view->set("common_settings", $this->getCommonCronSettings());
					$this->view->line = $this->getFromVars($_GET, "line");
					$this->view->job  = @unserialize(base64_decode($this->getFromVars($_GET, "data")));
					if(!is_object($this->view->job)) {
						$this->view->job          = new stdClass();
						$this->view->job->minute  = '';
						$this->view->job->hour    = '';
						$this->view->job->day     = '';
						$this->view->job->month   = '';
						$this->view->job->weekday = '';
						$this->view->job->command = '';
					}
					return $this->showCorrectView($this->view);
				}
				break;
			default:
				break;
		}
		if($api->sendApi2Request("Cron", "listcron")->isSuccess()) {
			$this->prepareView("cron");
			$this->view->set("common_settings", $this->getCommonCronSettings());
			$this->view->jobs     = $api->getResponse();
			$this->view->username = $fields->cpanel_username;
			//$this->view->uri = $this->uri;
			return $this->view->fetch();
		} else {
			return $api->getResultMessage();
		}
	}
	/**
	 * Manage SSH Access
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 *
	 * @return string
	 */
	public function ssh($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$vars       = $this->getPageVars($vars);
		$this->vars = $vars;
		$this->uri  = $this->base_uri . "services/manage/" . $vars->serviceid . "/" . $vars->pagename . '/';

		//Authorize Key
		if(!empty($post['keyfiles'])){
			$params         = array(
				'key' => $post['keyfiles'],
				'action' => 'authorize'
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("SSH", "authkey", $params));
		}

		//Import Key
		if(!empty($post['keyname']) && !empty($post['keypassphrase']) && !empty($post['keyfile'])){
			$params         = array(
				'key' => $post['keyfile'],
				'name' => $post['keyname'],
				'pass' => $post['keypassphrase']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response = $this->parseResponse($api->sendApi2Request("SSH", "importkey", $params));
		}

		$this->prepareView("ssh", compact("fields"));
		$this->view->hosts   = $api->getResponse();
		return $this->view->fetch();
	}
	/**
	 * SSL
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function ssl($package, $service, array $vars = array(), array $post = array()) {
		Loader::loadComponents($this, array(
			"Record"
		));
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		switch($this->vars->action) {
			case "generatecsr":
				$data = array(
					'city' => $post['city'],
					'company' => $post['company'],
					'companydivision' => $post['companydivision'],
					'country' => $post['country'],
					'email' => $post['email'],
					'host' => $post['domain'],
					'state' => $post['state'],
					'pass' => $post['pass']
				);
				if($api->sendApi2Request("SSL", "gencsr", $data)) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "generatekey":
				if($api->sendApi2Request("SSL", "genkey", array(
					'host' => empty($post["selectdomain"]) ? $post["domain"] : $post["selectdomain"],
					'keysize' => $post["keysize"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "generatecrt":
				$data = array(
					'host' => $post['domain'],
					'city' => $post['city'],
					'company' => $post['company'],
					'companydivision' => $post['companydivision'],
					'country' => $post['country'],
					'email' => $post['email'],
					'state' => $post['state']
				);
				if($api->sendApi2Request("SSL", "gencrt", $data)) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "uploadkey":
				if($api->sendApi2Request("SSL", "uploadkey", array(
					'host' => empty($post["selectdomain"]) ? $post["domain"] : $post["selectdomain"],
					'key' => $post["key"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "uploadcrt":
				if($api->sendApi2Request("SSL", "uploadcrt", array(
					$post["key"]
				))) {
					$this->printJson($this->parseResponseToJson($api->getCleanResponse()));
				}
				break;
			case "viewkey":
				$domain = $this->getFromVars($_GET, "domain", $fields->cpanel_domain);
				if($api->sendApi2Request("SSLInfo", "fetchinfo", array(
					'domain' => $domain
				))->isSuccess()) {
					$this->prepareView("sslviewkey");
					$this->view->data = $api->getResponse()->cpanelresult->data[0];
					return $this->showCorrectView($this->view);
				} else {
					$this->prepareView("sslerror");
					$this->view->response = $api->getResponse();
					return $this->showCorrectView($this->view);
				}
				break;
			case "viewcrt":
				$domain = $this->getFromVars($_GET, "domain", $fields->cpanel_domain);
				if($api->sendApi2Request("SSLInfo", "fetchinfo", array(
					'domain' => $domain
				))->isSuccess()) {
					$this->prepareView("sslviewcrt");
					$this->view->data = $api->getResponse()->cpanelresult->data[0];
					return $this->showCorrectView($this->view);
				} else {
					return 'Unknown error occured';
				}
				break;
			default:
				break;
		}
		$api2     = clone $api;
		$api3     = clone $api;
		$api4     = clone $api;
		$request1 = $api->sendApi2Request("SSL", "listkeys");
		//$this->parseResponse($request1->getCleanResponse());
		$request2 = $api3->sendApi2Request("SSL", "listcrts");
		//$this->parseResponse($request2->getCleanResponse());
		$request3 = $api4->sendApi2Request("SSL", "listcsrs");
		//$this->parseResponse($request3->getCleanResponse());
		if($request1->isSuccess() and $request2->isSuccess() and $request3->isSuccess() and !$this->Input->errors()) {
			$domains['']                     = 'Select a domain';
			$domains[$fields->cpanel_domain] = $fields->cpanel_domain;
			$domains                         = array_merge($domains, $this->DataStructure->create("Array")->numericToKey($api2->sendApi2Request("AddonDomain", "listaddondomains")->getResponse()->cpanelresult->data, "domain", "domain"));
			$domains                         = array_merge($domains, $this->DataStructure->create("Array")->numericToKey($api2->sendApi2Request("SubDomain", "listsubdomains")->getResponse()->cpanelresult->data, 'domain', 'domain'));
			$domains                         = array_merge($domains, $this->DataStructure->create("Array")->numericToKey($api2->sendApi2Request("Park", "listparkeddomains")->getResponse()->cpanelresult->data, 'domain', 'domain'));
			$keysdomains['']                 = 'Select a domain';
			$keysdomains                     = array_merge($keysdomains, $this->DataStructure->create("Array")->numericToKey($request1->getResponse()->cpanelresult->data, 'host', 'host'));
			$this->prepareView("ssl");
			$this->view->set("cpanel_domain", $fields->cpanel_domain);
			$this->view->keys        = $request1->getResponse();
			$this->view->crts        = $request2->getResponse();
			$this->view->csrs        = $request3->getResponse();
			$this->view->domains     = $domains;
			$this->view->keysdomains = $keysdomains;
			$this->view->username    = $fields->cpanel_username;
			$this->view->client      = $this->Record->select(array(
				"contacts.*"
			))->from("clients")->leftJoin("contacts", "contacts.client_id", "=", "clients.id", false)->where("clients.id", "=", $service->client_id)->fetch();
			return $this->view->fetch();
		} else {
			return "Request 1 status: <code>{$request1->getResultMessage()}</code><br>Request 2 status: <code>{$request2->getResultMessage()}</code><br>Request 3 status: <code>{$request3->getResultMessage()}</code><br>";
		}
	}
	/**
	 * Install Apps in your Site with Softaculous
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function softaculous($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		//Import the Softaculous API
		Loader::load(dirname(__FILE__) . DS . "api" . DS . "installapi.php");
		//include('api/installapi.php');
		if(isset($_POST['soft']) && isset($_POST['softdomain']) && isset($_POST['softdb']) && isset($_POST['dbusername']) && isset($_POST['dbuserpass']) && isset($_POST['admin_username']) && isset($_POST['admin_pass']) && isset($_POST['admin_email']) && isset($_POST['language']) && isset($_POST['site_name']) && isset($_POST['site_desc'])) {
			@set_time_limit(100);
			$new                         = new Soft_Install();
			$new->login                  = 'https://' . urlencode($fields->cpanel_username) . ':' . urlencode($fields->cpanel_password) . '@' . $row->meta->host_name . ':2083/frontend/x3/softaculous/index.live.php';
			$new->data['softdomain']     = $_POST['softdomain'];
			$new->data['softdirectory']  = $_POST['softdirectory'];
			$new->data['softdb']         = $_POST['softdb'];
			$new->data['dbusername']     = $_POST['dbusername'];
			$new->data['dbuserpass']     = $_POST['dbuserpass'];
			$new->data['admin_username'] = $_POST['admin_username'];
			$new->data['admin_pass']     = $_POST['admin_pass'];
			$new->data['admin_email']    = $_POST['admin_email'];
			$new->data['language']       = $_POST['language'];
			$new->data['site_name']      = $_POST['site_name'];
			$new->data['site_desc']      = $_POST['site_desc'];
			$res                         = $new->install($_POST['soft']);
			$response                    = str_replace('<html><head><META HTTP-EQUIV="refresh" CONTENT="2;URL=', "", $res);
			$response                    = str_replace('">', "", $response);
			$response                    = str_replace('</head><body></body></html>', "", $response);
			/*
			//Send Request over curl and get Token
			$random_cookie = rand(000000, 999999);

			$ch = curl_init();
			$url = 'https://'.$row->meta->host_name.':2083/login/';
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/' . $random_cookie . '.txt');
			curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/' . $random_cookie . '.txt');
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'user' => $fields->cpanel_username,
			'pass' => $fields->cpanel_password,
			'goto_uri' => $response,
			));
			$result_redir = curl_exec($ch);
			$result_redir = str_replace('<html><head><META HTTP-EQUIV="refresh" CONTENT="2;URL=',"",$result_redir);
			$result_redir = str_replace('">',"",$result_redir);
			$result_redir = str_replace('</head><body></body></html>',"",$result_redir);
			$result_redir = 'https://'.$row->meta->host_name.':2083'.$result_redir;
			//curl_close($ch);

			//Send Request to Softaculous

			$ch = curl_init();
			$url = $result_redir;
			curl_setopt($ch, CURLOPT_URL, $url );
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/' . $random_cookie . '.txt');
			curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/' . $random_cookie . '.txt');
			$result_exe = curl_exec($ch);
			print_r($result_exe);
			curl_close($ch);
			*/
		}
		$this->prepareView("softaculous");
		$this->view->set("res", $res);
		$this->view->set("response", $response);
		$this->view->set("resp", $resp);
		$this->view->set("row", $row);
		$this->view->set("fields", $fields);
		$this->view->server    = $row;
		$this->view->fields    = $fields;
		$this->view->cpanelurl = $api->buildUrl();
		return $this->view->fetch();
	}
	/**
	 * WebDisk
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function webdisk($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		//Delete webdisk
		if(isset($_GET['delete'])) {
			$params = array(
				'login' => base64_decode($_GET['delete'])
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response_del = print_r($api->sendApi2Request("WebDisk", "delwebdisk", $params), true);
		}
		//Add webdisk
		if(!empty($_POST['domain']) && !empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['perms'])) {
			$params = array(
				'domain' => $_POST['domain'],
				'user' => $_POST['user'],
				'password' => $_POST['password'],
				'homedir' => 'public_html/' . $_POST['homedir'],
				'perms' => $_POST['perms']
			);
			$this->log($row->meta->host_name . "|sendApi2Request", serialize($params), "input", true);
			$response_add = print_r($api->sendApi2Request("WebDisk", "addwebdisk", $params), true);
		}
		//Get the WebDisk's
		$this->log($row->meta->host_name . "|sendApi2Request", "input", true);
		$response = $api->sendApi2Request("WebDisk", "listwebdisks");
		$response = print_r($response, true);
		$response = substr($response, (strpos($response, "[response:protected] =>") + 23), strlen($response));
		$response = substr($response, 0, strpos($response, "[statusmsg:protected]"));
		$response = json_decode($response, true);
		$this->prepareView("webdisk");
		$this->view->set("res", $res);
		$this->view->set("response", $response);
		$this->view->set("response_del", $response_del);
		$this->view->set("response_add", $response_add);
		$this->view->set("resp", $resp);
		$this->view->set("row", $row);
		$this->view->set("fields", $fields);
		$this->view->server    = $row;
		$this->view->fields    = $fields;
		$this->view->cpanelurl = $api->buildUrl();
		return $this->view->fetch();
	}
	/**
	 * Allow you to login to one of the services: cPanel, phpMyAdmin, webMail
	 *
	 * @param type $package
	 * @param type $service
	 * @param array $vars
	 * @param array $post
	 */
	public function loginto($package, $service, array $vars = array(), array $post = array()) {
		$row        = $this->getModuleRow();
		$fields     = $this->serviceFieldsToObject($service->fields);
		$api        = $this->getApiByMeta($row->meta, $fields);
		$this->vars = $this->getPageVars($vars);
		$this->prepareView("loginto");
		$this->view->server    = $row;
		$this->view->fields    = $fields;
		$this->view->cpanelurl = $api->buildUrl();
		$this->view->set("package", $package);
		return $this->view->fetch();
	}
	/**
	 * To Array
	 * @param type $key
	 */
	public function toArray($result, $key) {
		$return = array();
		foreach($result as $current) {
			$return[] = $current->{$key};
		}
		return $return;
	}
	/**
	 * Converts any data to the JSON string
	 *
	 * @param mixed $data
	 */
	public function printJson($data = '') {
		header('Content-type: application/json');
		echo $this->Json->encode($data);
		exit;
	}
	public function showCorrectView(View $view) {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === "xmlhttprequest") {
			echo $view->fetch();
			exit;
		} else {
			return $view->fetch();
		}
	}
	public function getUserRules($what = '') {
		switch($what) {
			case 'Ftp':
				return array(
					'ftpusername' => array(
						'empty' => array(
							'rule' => "isEmpty",
							'negate' => true,
							'message' => 'Username can not be empty'
						)
					),
					'ftppassword' => array(
						'empty' => array(
							'rule' => "isEmpty",
							'negate' => true,
							'message' => 'Password can not be empty'
						)
					),
					'ftppasswordconfirm' => array(
						'empty' => array(
							'rule' => "isEmpty",
							'negate' => true,
							'message' => 'Password confirmation can not be empty'
						)
					)
				);
				break;
			default:
				break;
		}
		;
	}
	/**
	 * Convert an vars array to prettier and more
	 * understandable format
	 *
	 * @param array $vars
	 */
	public function getPageVars($vars) {
		$object            = new stdClass();
		$object->serviceid = $vars[0];
		$object->pagename  = $vars[1];
		$object->action    = isset($vars[2]) ? $vars[2] : '';
		$object->param1    = isset($vars[3]) ? $vars[3] : '';
		$object->param2    = isset($vars[4]) ? $vars[4] : '';
		$object->param3    = isset($vars[5]) ? $vars[5] : '';
		return $object;
	}
	/**
	 * Checks whether API connection is valid
	 *
	 * @param type $password
	 * @param type $host_name
	 * @param type $user_name
	 * @param type $port
	 * @param type $usessl
	 *
	 * @return boolean
	 */
	public function validateApiConnection($password, $host_name, $user_name, $port, $usessl, $key = '') {
		$api = $this->getApi($host_name, $user_name, $password, $port, $usessl, $key);
		if($api->checkConnection()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Checks whether license key is valid one
	 * !! Removed since applied license rights to Blesta.
	 *
	 * @return boolean True or False
	 */
	public function validateLicenseKey() {
		return true;
	}
	/**
	 * Verifies whether or not the givne str is a URL
	 *
	 * @param string $str A string
	 * @return boolean True if $str is a URL, false otherwise
	 */
	private function isUrl($str) {
		return preg_match("#^\S+://\S+\.\S+.+$#", $str);
	}
	/**
	 * Validates that the given hostname is valid
	 *
	 * @param string $host_name The host name to validate
	 * @return boolean True if the hostname is valid, false otherwise
	 */
	public function validateHostName($host_name) {
		if(strlen($host_name) > 255)
			return false;
		return $this->Input->matches($host_name, "/^([a-z0-9]|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9])(\.([a-z0-9]|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9]))+$/");
	}
}
?>
