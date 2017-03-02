<?php

/**
 * @defgroup plugins_generic_customMetadata CustomMetadata Plugin
 */
 
/**
 * @file plugins/generic/customMetadata/index.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_customMetadata
 * @brief Wrapper for customMetadata plugin.
 *
 */
require_once('CustomMetadataPlugin.inc.php');

return new CustomMetadataPlugin();

?>
