<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Default Settings Store
	|--------------------------------------------------------------------------
	|
	| This option controls the default settings store that gets used while
	| using this settings library.
	|
	| Supported: "json", "database"
	|
	*/
	'store' => 'database',

	/*
	|--------------------------------------------------------------------------
	| JSON Store
	|--------------------------------------------------------------------------
	|
	| If the store is set to "json", settings are stored in the defined
	| file path in JSON format. Use full path to file.
	|
	*/
	'path' => storage_path().'/settings.json',

	/*
	|--------------------------------------------------------------------------
	| Database Store
	|--------------------------------------------------------------------------
	|
	| The settings are stored in the defined file path in JSON format.
	| Use full path to JSON file.
	|
	*/
	// If set to null, the default connection will be used.
	'connection' => null,
	// Name of the table used.
	'table' => 'app_settings',
	// If you want to use custom column names in database store you could 
	// set them in this configuration
	'keyColumn' => 'key',
	'valueColumn' => 'value',



	////firebase config
	'firebase_api_key'=>'AIzaSyC1GWjZ1Irhj7_OB4Ob--_a_rcP0xnk1Js',
	'firebase_auth_domain'=> 'shs-chat-c425e.firebaseapp.com',
	'firebase_database_url'=> 'https://shs-chat-c425e-default-rtdb.firebaseio.com/',
	'firebase_project_id'=> 'shs-chat-c425e',
	'firebase_storage_bucket'=> 'shs-chat-c425e.appspot.com',
	'firebase_messaging_sender_id'=> '963124896977',
	'firebase_app_id'=> '1:963124896977:web:016e3a562edc51652211f0',
	'firebase_measurement_id'=> 'G-2MVVRHDF8M',


];
