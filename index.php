<?php

if (!empty($_REQUEST['debug'])) {
	// to turn on error reporting:
	ini_set('display_errors',1);
	error_reporting(E_ALL);
}
else {
	error_reporting(0);
}


# =====================================
# Globals Definitions and Configuration
# -------------------------------------
if (true) {

	# Load required functions:
	# ------------------------

	## ---- API Functions
	require_once ('functions_API.php');
	## ---- Flat file DB Functions
	require_once ('functions_files.php');
	## ---- HTML Helpers Functions
	require_once ('functions_helpers.php');
	## ---- HTML Elements Functions
	require_once ('functions_page_elements.php');
	## ---- HTML Content Functions
	require_once ('functions_page_content.php');


	// Load external files:
	// -------------------------------
	require_once ('configuration.php');
	load_file('content', 'content.txt');
	load_file('preferences', 'preferences.txt');

	$public_nav_items = explode(',', $GLOBALS['preferences']['public_top_nav']);
	$GLOBALS['preferences']['public_top_nav'] = $public_nav_items;

	$loggedin_nav_items = explode(',', $GLOBALS['preferences']['loggedin_top_nav']);
	$GLOBALS['preferences']['loggedin_top_nav'] = $loggedin_nav_items;

	$loggedin_customer_nav_items = explode(',', $GLOBALS['preferences']['loggedin_customer_top_nav']);
	$GLOBALS['preferences']['loggedin_customer_top_nav'] = $loggedin_customer_nav_items;

	$public_footer_items = explode(',', $GLOBALS['preferences']['public_footer_links']);
	$GLOBALS['preferences']['public_footer_links'] = $public_footer_items;

	$loggedin_footer_items = explode(',', $GLOBALS['preferences']['loggedin_footer_links']);
	$GLOBALS['preferences']['loggedin_footer_links'] = $loggedin_footer_items;

	$loggedin_customer_footer_items = explode(',', $GLOBALS['preferences']['loggedin_customer_footer_links']);
	$GLOBALS['preferences']['loggedin_customer_footer_links'] = $loggedin_customer_footer_items;

	//$campaigns_to_show = explode('|,|', $GLOBALS['preferences']['campaigns_to_show']);
	//$GLOBALS['preferences']['campaigns_to_show'] = $campaigns_to_show;

	$GLOBALS['preferences']['redeem_types'] = explode(',', $GLOBALS['preferences']['redeem_type']);

	// Custom Additional Customer Fields:
	if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
		$customer_fields_order = explode('|', $GLOBALS['preferences']['customer_fields_order']);
		$GLOBALS['preferences']['customer_fields_order'] = $customer_fields_order;
	} else {
		$customer_fields_order = '';
		$GLOBALS['preferences']['customer_fields_order'] = '';
	}
	if (!empty($GLOBALS['preferences']['fields_to_show'])) {
		$customer_fields_to_show = explode('|', $GLOBALS['preferences']['fields_to_show']);
		$GLOBALS['preferences']['fields_to_show'] = $customer_fields_to_show;
	} else {
		$customer_fields_to_show = array();
		$GLOBALS['preferences']['fields_to_show'] = array();
	}

	// Custom Additional Transaction Fields:
	if (!empty($GLOBALS['preferences']['transaction_fields_order'])) {
		$transaction_fields_order = explode('|', $GLOBALS['preferences']['transaction_fields_order']);
		$GLOBALS['preferences']['transaction_fields_order'] = $transaction_fields_order;
	} else {
		$transaction_fields_order = '';
		$GLOBALS['preferences']['transaction_fields_order'] = '';
	}
	if (!empty($GLOBALS['preferences']['transaction_fields_to_show'])) {
		$transaction_fields_to_show = explode('|', $GLOBALS['preferences']['transaction_fields_to_show']);
		$GLOBALS['preferences']['transaction_fields_to_show'] = $transaction_fields_to_show;
	} else {
		$transaction_fields_to_show = array();
		$GLOBALS['preferences']['transaction_fields_to_show'] = array();
	}

	// Application definitions.
	// -----------------------
	date_default_timezone_set('America/New_York');
	$GLOBALS['base_path']	= $_SERVER['REQUEST_URI'];
	$GLOBALS['vars'] 			= array();

	$GLOBALS['menus']['public_top_nav']							= array('login','new_account_how');
	$GLOBALS['menus']['loggedin_top_nav']						= array('home','rules','activate_account','new_account','reports','logout');
	$GLOBALS['menus']['loggedin_customer_top_nav']			= array('add','redeem','balance','myinfo');
	$GLOBALS['menus']['public_footer_links']					= array('login','new_account_how');
	$GLOBALS['menus']['loggedin_footer_links']				= array('home','rules','activate_account','new_account','logout');
	$GLOBALS['menus']['loggedin_customer_footer_links']	= array('add','redeem','balance','myinfo');

	$GLOBALS['default_customer_fields'] = array(	'card_number',
																'customer_password',
																'first_name',
																'last_name',
																'phone',
																'email',
																'custom_date',
																'address1',
																'address2',
																'city',
																'state',
																'zip',
																'country');

}
# ===================


# =========================
# Put $_REQUESTS into $GLOBALS['vars']
# -------------------------
	foreach ($_REQUEST as $key => $value) {
		if (is_array($value)) {
			foreach ($value as $array_value) {
				$GLOBALS['vars'][$key][] = html_entity_decode($array_value);
			}
		} else {
			if (	strpos($key, '__ALC') === FALSE && strpos($key, '__utm') === FALSE) {
				$GLOBALS['vars'][$key] = html_entity_decode($value);
			}
		}
	}
# =========================

# =============
# Display Logic
# -------------
// HTML Content:
// ------------
if (!empty($GLOBALS['vars']['action']) && $GLOBALS['vars']['action'] != 'phrase_change') {

	// Get the valid users list:
	// get_user_credentials();

	// Always verify the credentials first:
	if (!empty($GLOBALS['vars']['user_name']) && !empty($GLOBALS['vars']['user_password'])) {
		$verified = verify_user_credentials ();
	} else {
		$verified = false;
	}

	if ($verified) {
		// --- LOGGED IN USER ACTIONS

		if ($GLOBALS['vars']['action'] == 'home') {
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'home';

			// Remove previously looked up customer:
			unset ($GLOBALS['vars']['customer_code']);
			unset ($GLOBALS['vars']['customer_card']);

		} else
		//
		if ($GLOBALS['vars']['action'] == 'activate_account') {
			get_custom_customer_fields ();
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'activate_account';
		} else
		if ($GLOBALS['vars']['action'] == 'record_activate_account') {
			get_custom_customer_fields ();
			activate_account_info();
			if ($GLOBALS['activate_account_result'] !== FALSE) {

				get_customer_info();

				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'add';
			} else {
				// Something is wrong:
				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'activate_account';
			}
		} else
		if ($GLOBALS['vars']['action'] == 'new_account') {
			// Clear any previous customer info:
			if (!empty($GLOBALS['vars']['customer_code'])) {
				unset($GLOBALS['vars']['customer_code']);
			}
			if (isset($GLOBALS['vars']['customer_card'])) {
				unset($GLOBALS['vars']['customer_card']);
			}
			if (isset($GLOBALS['vars']['card_number'])) {
				unset($GLOBALS['vars']['card_number']);
			}

			get_custom_customer_fields ();
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'new_account';
		} else 
		if($GLOBALS['vars']['action'] == 'reports') {
			get_custom_customer_fields ();
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'reports';
		}
		else
		if ($GLOBALS['vars']['action'] == 'record_new_account') {
			get_custom_customer_fields ();
			new_customer_info();
			if ($GLOBALS['new_customer_result'] !== FALSE) {

				get_customer_info();

				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'add';
				$GLOBALS['vars']['action'] = 'selected_customer';

			} else {
				// Something is wrong:
				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'new_account';
			}
		} else
		//
		if ($GLOBALS['vars']['action'] == 'myinfo') {
			// Get the customer's information.
			get_customer_info();
			get_custom_customer_fields ();

			if ((string)$GLOBALS['customer_info']->attributes()->status != 'success') {
				$GLOBALS['errors']['customer_not_found'] = $GLOBALS['content']['customer_not_found'];
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'home';
			} else {
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'myinfo';
			}

		} else
		if ($GLOBALS['vars']['action'] == 'record_myinfo') {
			get_custom_customer_fields ();
			update_customer_info();

			if (!isset($GLOBALS['customer_update']) || (string)$GLOBALS['customer_update']->attributes()->status != 'success') {
				$GLOBALS['errors']['error_update_customer_short'] = $GLOBALS['content']['error_update_customer_short'];

				get_customer_info();
				get_custom_customer_fields ();

				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'myinfo';
			}
			else {  // success

				get_customer_info();
				get_custom_customer_fields ();

				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'myinfo_changed';
			}

		} else
		//
		if ($GLOBALS['vars']['action'] == 'balance') {
			// Get the customer's information.
			get_customer_info();

			if ((string)$GLOBALS['customer_info']->attributes()->status != 'success') {
				$GLOBALS['errors']['customer_not_found'] = $GLOBALS['content']['customer_not_found'];
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'home';
			} else {
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'balance';
			}

		} else
		if ($GLOBALS['vars']['action'] == 'add') {

			find_customer();

			//If the match results in no customers
			if ((string)$GLOBALS['customer_search_results']->attributes()->status == 'no_match') {

				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'home';
				$GLOBALS['errors'][] = (string)$GLOBALS['customer_search_results']->message;
			}

			elseif ((string)$GLOBALS['customer_search_results']->attributes()->status == 'success') {

				// If the match results in multiple customers
				if (sizeof($GLOBALS['customer_search_results']->customer) > 1) {
					$GLOBALS['vars']['public_nav'] 		= 'N';
					$GLOBALS['vars']['what_to_show']	= 'customer_list';

				}
				// if the match results in a single customer:
				else {
					$GLOBALS['vars']['customer_code'] = (string)$GLOBALS['customer_search_results']->customer->code;

					get_customer_info();
					get_custom_transaction_fields ();

					if (!empty($GLOBALS['customer_info']->customer->card_number)) {
						$GLOBALS['vars']['customer_card'] =  (string)$GLOBALS['customer_info']->customer->card_number;
					}

					$GLOBALS['vars']['public_nav'] 		= 'N';
					$GLOBALS['vars']['what_to_show']	= 'add';
				}
			}

			else {  // there is an error with the submission:
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'home';
				$GLOBALS['errors'][] = (string)$GLOBALS['customer_search_results']->error;
			}
		} else
		if ($GLOBALS['vars']['action'] == 'selected_customer') {

 			get_customer_info();
			get_custom_transaction_fields ();

			if (!empty($GLOBALS['customer_info']->customer->card_code)) {
				$GLOBALS['vars']['customer_code'] =  (string)$GLOBALS['customer_info']->customer->card_code;
			}
			if (!empty($GLOBALS['customer_info']->customer->card_number)) {
				$GLOBALS['vars']['customer_card'] =  (string)$GLOBALS['customer_info']->customer->card_number;
			}

 			$GLOBALS['vars']['public_nav'] 		= 'N';
 			$GLOBALS['vars']['what_to_show']	= 'add';

		} else
		if ($GLOBALS['vars']['action'] == 'add_transaction') {

			get_customer_info();
			get_custom_transaction_fields ();

			// Validate Inputs:
			$validated = true;

			// Campaign-based Validations:
			if ($GLOBALS['vars']['which_campaign_type'] == 'points'
				 && (empty($GLOBALS['vars']['promo_id']) && empty($GLOBALS['vars']['amount']))) {
				$validated = false;
				$GLOBALS['error']['points_amount'] = 'transaction_error_type_points';
			}
			elseif ($GLOBALS['vars']['which_campaign_type'] == 'giftcard' && empty($GLOBALS['vars']['amount'])) {
				$validated = false;
				$GLOBALS['error']['giftcard_amount'] = 'transaction_error_type_monetary';
			}
			elseif ($GLOBALS['vars']['which_campaign_type'] == 'buyx' && empty($GLOBALS['vars']['service_product'])) {
				$validated = false;
				$GLOBALS['error']['service_product'] = 'transaction_error_type_item';
			}

			// validate the custom fields:
			if (!empty($GLOBALS['transaction_fields'])) {
				foreach ($GLOBALS['transaction_fields'] as $transaction_field_name => $transaction_field_data) {
					if (isset($GLOBALS['vars'][$transaction_field_name])) {
						if ($transaction_field_data['type'] == 'Text') {
							// nothing to validate - can be any value.
						}
						if ($transaction_field_data['type'] == 'Date') {
							// a Date could be "de-set" or removed. So ignore those cases:
							if (!empty($GLOBALS['vars'][$transaction_field_name]) && $GLOBALS['vars'][$transaction_field_name] != '0000-00-00') {
								$date_elements = explode('-', $GLOBALS['vars'][$transaction_field_name]);
								$validated = checkdate ( $date_elements[1] , $date_elements[2] , $date_elements[0] );
								if (!$validated) {
									$GLOBALS['error'][$transaction_field_name]['custom_date'] = 'transaction_error_type_date';
								}
							}
						}
						elseif ($transaction_field_data['type'] == 'Pick') {
							if (!in_array($GLOBALS['vars'][$transaction_field_name], $transaction_field_data['choices'])) {
								$validated = false;
								$GLOBALS['error'][$transaction_field_name]['custom_pick'] = 'transaction_error_type_pick';
							}
						}
						elseif ($transaction_field_data['type'] == 'List') {
							// List elements could be "de-set" or removed. So ignore those cases:
							if (!empty($GLOBALS['vars'][$transaction_field_name]) && sizeof($GLOBALS['vars'][$transaction_field_name]) > 0) {
								foreach ($GLOBALS['vars'][$transaction_field_name] as $selected_choice) {
									if (!in_array($selected_choice, $transaction_field_data['choices'])) {
										$validated = false;
										$GLOBALS['error'][$transaction_field_name]['custom_list'] = 'transaction_error_type_list';
									}
								}
							}
						}
						elseif ($transaction_field_data['type'] == 'Number') {
							// a number could be "de-set" or removed. So ignore those cases:
							if (!empty($GLOBALS['vars'][$transaction_field_name])) {
								if (!is_numeric($GLOBALS['vars'][$transaction_field_name])) {
									$validated = false;
									$GLOBALS['error'][$transaction_field_name]['custom_number'] = 'transaction_error_type_number';
								}
							}
						}
						elseif ($transaction_field_data['type'] == 'Monetary') {
							// an amount could be "de-set" or removed. So ignore those cases:
							if (!empty($GLOBALS['vars'][$transaction_field_name])) {
								if (!is_numeric($GLOBALS['vars'][$transaction_field_name])) {
									$validated = false;
									$GLOBALS['error'][$transaction_field_name]['custom_monetary'] = 'transaction_error_type_monetary';
								}
							}
						}
					}
				}
			}

			if ($validated === true) {
				$customer_transaction_added = record_transaction($GLOBALS['vars']['which_campaign_type']);
			}
			// Missing information!
			else {
				$customer_transaction_added = false;
			}

			if ($customer_transaction_added !== false) {
				//unset($GLOBALS['customer_info']);
				$GLOBALS['success']['add_transaction'] = editor('success_add_transaction', 50, 3);
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'balance';
			}
			else {
				$GLOBALS['errors']['add_transaction'] = editor('error_add_transaction', 50, 3);
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'add';
			}

		} else
		if ($GLOBALS['vars']['action'] == 'redeem') {
			// Get the customer's information.
			get_customer_info();
			get_custom_customer_fields ();

			if ((string)$GLOBALS['customer_info']->attributes()->status != 'success') {
				$GLOBALS['errors']['customer_not_found'] = $GLOBALS['content']['customer_not_found'];
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'home';
			} else {
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'redeem';
			}

		} else
		if ($GLOBALS['vars']['action'] == 'confirm_redeem') {
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'confirm_redeem';
			// Get the customer's information.
			get_customer_info();
			get_custom_customer_fields ();

		} else
		if ($GLOBALS['vars']['action'] == 'redeem_reward') {

			$customer_updated = update_customer_info();

			if(!$customer_updated) {
				$GLOBALS['errors']['update_customer'] = editor('error_update_customer', 50, 3);
			}

			if($customer_updated) {
				get_customer_info();
				redeem_reward();
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'redeem_result';
			}
			else {
				$GLOBALS['vars']['public_nav'] 		= 'N';
				$GLOBALS['vars']['what_to_show']	= 'confirm_redeem';
				get_customer_info();
			}
		} else
		if ($GLOBALS['vars']['action'] == 'redeem_amount') {

			get_customer_info();

			redeem_amount();

			if ($GLOBALS['redeem_amount_result']->attributes()->status == 'success') {


				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'balance';
			}
			else {

				$GLOBALS['errors']['api_result'] = (string)$GLOBALS['redeem_amount_result']->error;

				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'redeem';
			}

		} else
		if ($GLOBALS['vars']['action'] == 'redeem_direct') {

			get_customer_info();

			redeem_reward();

			if ($GLOBALS['redeem_rewards_result']->attributes()->status == 'success') {

				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'balance';
			}
			else {

				$GLOBALS['errors']['api_result'] = (string)$GLOBALS['redeem_rewards_result']->error;

				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'redeem';
			}

		} else
		//
		if ($GLOBALS['vars']['action'] == 'rules') {
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'rules';

		} else
		//
		if ($GLOBALS['vars']['action'] == 'admin_login') {
			$GLOBALS['vars']['public_nav'] 		= 'N';
			$GLOBALS['vars']['what_to_show']	= 'admin_login';
		} else
		if ($GLOBALS['vars']['action'] == 'admin_logout') {
			unset($GLOBALS['vars']['admin_name']);
			unset($GLOBALS['vars']['admin_password']);
			$GLOBALS['vars']['public_nav'] 	= 'N';
			$GLOBALS['vars']['what_to_show']	= 'home';
		} else

		if ($GLOBALS['vars']['action'] == 'admin_logged_in') {
			if (verify_admin()) {
				get_custom_customer_fields ();
				get_custom_transaction_fields ();
				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'admin';
			} else {
				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'admin_login';
				$GLOBALS['errors']['login_incorrect'] = $GLOBALS['content']['login_incorrect'];
			}
		} else

		if ($GLOBALS['vars']['action'] == 'record_site_preferences') {
			if (verify_admin()) {
				update_site_preferences();
				get_custom_customer_fields ();
				get_custom_transaction_fields ();

				$GLOBALS['vars']['public_nav'] 	= 'N';
				//$GLOBALS['vars']['what_to_show']	= $GLOBALS['preferences']['loggedin_top_nav'][0];
				$GLOBALS['vars']['what_to_show']	= 'admin';

			} else {
				$GLOBALS['vars']['public_nav'] 	= 'N';
				$GLOBALS['vars']['what_to_show']	= 'admin_login';
				$GLOBALS['errors']['login_incorrect'] = $GLOBALS['content']['login_incorrect'];
			}
		}

		//
	}
	else {
		// --- NON-LOGGED-IN USER ACTIONS

		if ($GLOBALS['vars']['action'] == 'new_account_how') {
			$GLOBALS['vars']['public_nav'] 		= 'Y';
			$GLOBALS['vars']['what_to_show']	= 'new_account_how';
		} else
		//
		if ($GLOBALS['vars']['action'] == 'admin_login') {
			$GLOBALS['vars']['public_nav'] 		= 'Y';
			$GLOBALS['vars']['what_to_show']	= 'admin_login';
		} else

		if ($GLOBALS['vars']['action'] == 'admin_logout') {
			unset($GLOBALS['vars']['admin_name']);
			unset($GLOBALS['vars']['admin_password']);
		} else

		if ($GLOBALS['vars']['action'] == 'admin_logged_in') {
			if (verify_admin()) {
				get_custom_customer_fields ();
				get_custom_transaction_fields ();
				$GLOBALS['vars']['public_nav'] 		= 'Y';
				$GLOBALS['vars']['what_to_show']	= 'admin';
			} else {
				$GLOBALS['vars']['public_nav'] 	= 'Y';
				$GLOBALS['vars']['what_to_show']	= 'admin_login';
				$GLOBALS['errors']['login_incorrect'] = $GLOBALS['content']['login_incorrect'];
			}
		} else
		if ($GLOBALS['vars']['action'] == 'record_site_preferences') {
			if (verify_admin()) {
				update_site_preferences();
				get_custom_customer_fields ();
				get_custom_transaction_fields ();

				$GLOBALS['vars']['public_nav'] 		= 'Y';
				//$GLOBALS['vars']['what_to_show']		= $GLOBALS['preferences']['public_top_nav'][0];
				$GLOBALS['vars']['what_to_show']	= 'admin';


			} else {
				$GLOBALS['vars']['public_nav'] 	= 'Y';
				$GLOBALS['vars']['what_to_show']	= 'admin_login';
				$GLOBALS['errors']['login_incorrect'] = $GLOBALS['content']['login_incorrect'];
			}
		} else
		//
		if ($GLOBALS['vars']['action'] != 'logout' && $GLOBALS['vars']['action'] != 'login') {
			$GLOBALS['errors']['login_incorrect'] = $GLOBALS['content']['login_incorrect'];
		}

		//
		if (!isset($GLOBALS['vars']['what_to_show'])) {
			$GLOBALS['vars']['what_to_show'] = 'login';
		}

	}
	//
	output_HTML_page();
	//
}
//
// AJAX Requests:
// ------------
elseif (!empty($GLOBALS['vars']['action']) && $GLOBALS['vars']['action'] == 'phrase_change') {

	// Definitions:
	$ajax_status = 'error';

	// Always verify the admin credentials first:
	if (verify_admin()) {

		if (!empty($GLOBALS['vars']['phrase']) && !empty($GLOBALS['vars']['phrase_key'])) {
			$GLOBALS['content'][$GLOBALS['vars']['phrase_key']] = $GLOBALS['vars']['phrase'];
			$write_success = write_file($GLOBALS['content'], 'content.txt');
			$ajax_status = ($write_success) ? 'success' : 'Error recording definition';

		} else {
			// Error. Ignore.
			$ajax_status .= ': Empty definition';
		}

	} else {
		$ajax_status .= ': User not valid';
	}

	// Return an XML file with the success result(s)
	header("Content-type: text/xml");
	//echo the XML declaration
	echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);

	echo '<response status="'.$ajax_status.'"></response>';

}
//
// First time visitor
// ------------------
else {
	$GLOBALS['vars']['what_to_show'] = 'login';
	output_HTML_page();
}
# =============



?>
