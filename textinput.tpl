{**
 * plugins/generic/customMetadata/textinput.tpl
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Edit text input element 
 *
 *}
{assign var="customValueField" value="customValue`$customValueId`"}

{fbvFormArea id=$customValueField}
	{fbvFormSection label=$fieldLabel for="source" description=$fieldDescription}
		{fbvElement type="text" name=$customValueField id=$customValueField value=$customValue maxlength="255" readonly=$readOnly}
	{/fbvFormSection}
{/fbvFormArea}
