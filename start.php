<?php

elgg_register_event_handler('init', 'system', 'hidereportedcontent_init');

function hidereportedcontent_init() {
	elgg_register_plugin_hook_handler('reportedcontent:add', 'system', 'hidereportedcontent');
}

/**
 * Hide reported entities from other users than entity owner
 */
function hidereportedcontent($hook, $type, $return, $params) {
	$report = $params['report'];
	$address = $report->address;

	$site_url = elgg_get_site_url();
	// Remove site url so we can access the page handler parameters
	$path = str_replace($site_url, '', $address);
	$parts = explode("/", $path);

	// This should be the guid if reporting a single entity
	$guid = $parts[2];

	$entity = get_entity($guid);
	if (elgg_instanceof($entity)) {
		// TODO Is this enough? The owner is able to change this back!
		$entity->access_id = ACCESS_PRIVATE;
		$entity->save();
	}

	return $return;
}
