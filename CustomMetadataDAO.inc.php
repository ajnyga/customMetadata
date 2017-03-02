<?php

/**
 * @file plugins/generic/customMetadata/CustomMetadataDAO.inc.php
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

import('lib.pkp.classes.db.DAO');
import('plugins.generic.customMetadata.CustomMetadata');

class CustomMetadataDAO extends DAO {
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function getByContextId($contextId) {
		$result = $this->retrieveRange(
			'SELECT * FROM custom_metadata WHERE context_id = ?',
			(int) $contextId
		);
		
		return new DAOResultFactory($result, $this, '_fromRow');
	}	


	function _fromRow($row) {
		$customMetadata = $this->newDataObject();
		$customMetadata->setId($row['custom_metadata_id']);
		$customMetadata->setContextId($row['context_id']);
		$customMetadata->setType($row['type']);
		$customMetadata->setLocalized($row['localized']);		
		$customMetadata->setName($row['name']);
		$customMetadata->setLabel($row['label']);		
		$customMetadata->setDescription($row['description']);
		return $customMetadata;
	}
	
	function newDataObject() {
		return new CustomMetadata();
	}	
	
	
}

?>
