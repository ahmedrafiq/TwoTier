<?php
	// API URL
	// ---------------------

	$GLOBALS['api_url'] 		= 'https://app.clienttoolbox.com/api.php';
	//$GLOBALS['api_user'] 		= 'ss_admin_user';
	//$GLOBALS['api_key']			= '21d23aaba9799a9e553db12e72329287c56eb995';
	//$GLOBALS['account_id']		= 'ss_admin';
	$GLOBALS['api_user'] 		= 'madison_admin';
	$GLOBALS['api_key']			= 'cf818a8cc45b61efd4d98371b4f48c1cb5d1ed8e';
	$GLOBALS['account_id']		= 'madison1';
	
	//$GLOBALS['master_campaign_id']				= '6750971200597889';
	//$GLOBALS['custom_campaigns_excluded_ids']	= '9340565611917357,8683550258637349';	//Comma Seperated ids*/
	$GLOBALS['master_campaign_id']				= '4038263580062721';
	$GLOBALS['custom_campaigns_excluded_ids']	= '2007247909466926,3612532495567747,3757547725667399';	//Comma Seperated ids
	$GLOBALS['consider_returned_purchases']		= true;

	// Admin User(s) definition
	// ---------------------
	$GLOBALS['admin_users']	= array('madison_dev' => '123456' );
	//$GLOBALS['admin_users']	= array('ss_admin_user' => 'ss_admin_user');
	 


	// Customization and Internationalization
	// -------------------------------------
	$GLOBALS['site_window_name']	= 'Store Rewards';
	$GLOBALS['navigation_height']	= 85;
	$GLOBALS['logo_location']		= 'images/clientsphere.png';
	$GLOBALS['logo_width']			= 200;
	$GLOBALS['logo_height']			= 56;
	$GLOBALS['logo_offset_top']	= 14;
	$GLOBALS['logo_offset_left']	= 10;
	$GLOBALS['european_dates'] 	= FALSE;
	$GLOBALS['european_numbers'] 	= TRUE;


	// Non-Web-Editable Content / Words:
	// ---------------------------------
	$GLOBALS['hard_coded_content'] = array(
		'submit' 				=> 'Submit',
		'save_changes'			=> 'Save Changes',
		'submit_information'	=> 'Submit Info',
		'submit_order'			=> 'Submit Order',
		'submit_quick'			=> 'Quick Add',
		'submit_find'			=> 'Find',
		'record'					=> 'Record',
		'deduct'					=> 'Deduct',
		'OK'						=> 'OK',
		'email_subject'		=> 'Client Redemption Request',
		'None'					=>	'None',
		'Edit'					=>	'Edit'
	);


?>
