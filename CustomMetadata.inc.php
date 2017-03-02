<?php

/**
 * @file plugins/generic/customMetadata/CustomMetadata.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CustomMetadataDAO
 * @ingroup plugins_generic_customMetadata
 *
 * @brief DAO operations for CustomMetadata.
 */


class CustomMetadata extends DataObject {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	//
	// Get/set methods
	//

	function getContextId(){
		return $this->getData('contextId');
	}
	function setContextId($contextId) {
		return $this->setData('contextId', $contextId);
	}	
	
	function getType() {
		return $this->getData('type');
	}
	function setType($parent) {
		return $this->setData('type', $type);
	}	

	function getLocalized() {
		return $this->getData('localized');
	}
	function setLocalized($localized) {
		return $this->setData('localized', $localized);
	}	

	function getName() {
		return $this->getData('name');
	}
	function setName($name) {
		return $this->setData('name', $name);
	}

	function getLabel() {
		return $this->getData('label');
	}
	function setLabel($label) {
		return $this->setData('label', $label);
	}

	function getDescription() {
		return $this->getData('description');
	}
	function setDescription($description) {
		return $this->setData('description', $description);
	}	

	
	
}

?>
