# customMetadata

OJS3 plugin for creating custom metadata fields to submission metadata.

NOTICE: this is just a concept with **a lot** of loose ends not a ready to use plugin. Requires a database table to work, see below

TODO: automatic creation of the required database table with schema.xml
TODO: Use $customField->getType() to switch between templates input/textarea 
TODO: support multilingual input. Would require custom_metadata_settings table and some changes
TODO: UI in the backend
TODO: Field labels and description only showing a translation string
TODO: input validation


DB table needed:
```
CREATE TABLE `custom_metadata` (
  `custom_metadata_id` bigint(20) NOT NULL,
  `context_id` bigint(20) NOT NULL,
  `type` text NOT NULL,
  `localized` tinyint(1) NOT NULL,
  `name` text NOT NULL,
  `label` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `custom_metadata`
  ADD PRIMARY KEY (`custom_metadata_id`);

ALTER TABLE `custom_metadata`
  MODIFY `custom_metadata_id` bigint(20) NOT NULL AUTO_INCREMENT;
```
