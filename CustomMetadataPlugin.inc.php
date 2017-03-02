<?php

/**
 * @file plugins/generic/customMetadata/CustomMetadataPlugin.inc.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CustomMetadataPlugin
 * @ingroup plugins_generic_customMetadata
 *
 * @brief CustomMetadata plugin class
 */

# TODO: Use $customField->getType() to switch between templates input/textarea 
# TODO: support multilingual input. Would require custom_metadata_settings table and some changes
# TODO: UI in the backend
# TODO: Field labels and description only showing a translation string
# TODO: input validation
 
import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.form.FormBuilderVocabulary');


class CustomMetadataPlugin extends GenericPlugin {

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True if plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
			
			$this->import('CustomMetadataDAO');
			$customMetadataDao = new CustomMetadataDAO();
			DAORegistry::registerDAO('CustomMetadataDAO', $customMetadataDao);			

			// Insert new fields into author metadata submission form (submission step 3) and metadata form
			HookRegistry::register('Templates::Submission::SubmissionMetadataForm::AdditionalMetadata', array($this, 'metadataFieldEdit'));

			// Hook for initData in two forms -- init the new fields
			HookRegistry::register('submissionsubmitstep3form::initdata', array($this, 'metadataInitData'));
			HookRegistry::register('issueentrysubmissionreviewform::initdata', array($this, 'metadataInitData'));

			// Hook for readUserVars in two forms -- consider the new field entries
			HookRegistry::register('submissionsubmitstep3form::readuservars', array($this, 'metadataReadUserVars'));
			HookRegistry::register('issueentrysubmissionreviewform::readuservars', array($this, 'metadataReadUserVars'));

			// Hook for execute in two forms -- consider the new fields in the article settings
			HookRegistry::register('submissionsubmitstep3form::execute', array($this, 'metadataExecute'));
			HookRegistry::register('issueentrysubmissionreviewform::execute', array($this, 'metadataExecute'));

			// Hook for save in two forms -- add validation for the new fields
			HookRegistry::register('submissionsubmitstep3form::Constructor', array($this, 'addCheck'));
			HookRegistry::register('issueentrysubmissionreviewform::Constructor', array($this, 'addCheck'));

			// Consider the new fields for ArticleDAO for storage
			HookRegistry::register('articledao::getAdditionalFieldNames', array($this, 'articleSubmitGetFieldNames'));

			}
			return true;
		}
		return false;
	}

	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.customMetadata.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.customMetadata.description');
	}


	/*
	 * Metadata
	 */

	/**
	 * Insert custom metadata fields into author submission step 3 and metadata edit form
	 */
	function metadataFieldEdit($hookName, $params) {
		$smarty =& $params[1];
		$output =& $params[2];
		
		$fbv = $smarty->getFBV();
		$form = $fbv->getForm();
		$submission = $form->getSubmission();
		
		$contextId = $this->getCurrentContextId();
		$customMetadataDao = DAORegistry::getDAO('CustomMetadataDAO');
		$customFields = $customMetadataDao->getByContextId($contextId);			 
		while ($customField = $customFields->next()){
			
			$customValueField = $this->getcustomValueField($customField->getId());
			
			$smarty->assign(array(
				'type' => $customField->getType(),
				'localized' => $customField->getLocalized(),				
				'customValue' => $submission->getData($customValueField),
				'customValueId' => $customField->getId(),
				'fieldLabel' => $customField->getLabel(),
				'fieldDescription' => $customField->getDescription(),
			));
			
			$output .= $smarty->fetch($this->getTemplatePath() . 'textinput.tpl');
			
		}				

		return false;
	}

	/**
	 * Add custome metadata elements to the article
	 */
	function articleSubmitGetFieldNames($hookName, $params) {
		$fields =& $params[1];
		$contextId = $this->getCurrentContextId();
	
		$customMetadataDao = DAORegistry::getDAO('CustomMetadataDAO');
		$customFields = $customMetadataDao->getByContextId($contextId);		 
		while ($customField = $customFields->next()){
			$customValueField = "customValue".$customField->getId();
			$fields[] = $customValueField;
		}
		
		return false;
	}
	
	
	/**
	 * Concern custom metadata fields in the form
	 */
	function metadataReadUserVars($hookName, $params) {
		$userVars =& $params[1];
		$contextId = $this->getCurrentContextId();
		
		$customMetadataDao = DAORegistry::getDAO('CustomMetadataDAO');
		$customFields = $customMetadataDao->getByContextId($contextId); 
		while ($customField = $customFields->next()){
			$customValueField = "customValue".$customField->getId();
			$userVars[] = $customValueField;
		}
		
		return false;
	}

	/**
	 * Set article custom metadata fields
	 */
	function metadataExecute($hookName, $params) {
		$form =& $params[0];
		$contextId = $this->getCurrentContextId();
		
		if (get_class($form) == 'SubmissionSubmitStep3Form') {
			$article =& $params[1];
		} elseif (get_class($form) == 'IssueEntrySubmissionReviewForm') {
			$article = $form->getSubmission();
		}
		
		$customMetadataDao = DAORegistry::getDAO('CustomMetadataDAO');
		$customFields = $customMetadataDao->getByContextId($contextId);			 
		while ($customField = $customFields->next()){
			$customValueField = "customValue".$customField->getId();
			$customValue = $form->getData($customValueField);
			$article->setData($customValueField, $customValue);
		}
		
		return false;
	}


	/**
	 * Init article custom metadata fields
	 */
	function metadataInitData($hookName, $params) {
		$form =& $params[0];
		$contextId = $this->getCurrentContextId();
		
		if (get_class($form) == 'SubmissionSubmitStep3Form') {
			$article = $form->submission;
		} elseif (get_class($form) == 'IssueEntrySubmissionReviewForm') {
			$article = $form->getSubmission();
		}
		
		$customMetadataDao = DAORegistry::getDAO('CustomMetadataDAO');
		$customFields = $customMetadataDao->getByContextId($contextId);			 
		while ($customField = $customFields->next()){
			$customValueField = "customValue".$customField->getId();
			$customValue = $article->getData($customValueField);
			$form->setData($customValueField, $customValue);
		}
		
		return false;
	}
	
	/**
	 * Add check/validation
	 */
	function addCheck($hookName, $params) {
		$form =& $params[0];
		
		# Requires some changes to the plugin database
		
		return false;
	}	

	
	function getTemplatePath() {
		return parent::getTemplatePath();
	}
	
	function getCurrentContextId() {
		$contextId = null;
		$request = $this->getRequest();
		$context = $request->getContext();
		if ($context) $contextId = $context->getId();		
		return $contextId;
		
	}
	
	function getcustomValueField($customValueId) {
			return "customValue".$customValueId;
	}		
	
	

}
?>
