<?php

# =================
# API Functions:
# -----------------
function submit_cURL ($data) {

	$ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $GLOBALS['api_url']);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	// turn off verification of SSL for testing:
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	// Send query to server:
	$result = curl_exec($ch);
	curl_close ($ch);

	return $result;
}
function verify_credentials () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'validate_customer_password';
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['card_number'] 		= (isset($GLOBALS['vars']['user_name'])) ? $GLOBALS['vars']['user_name'] : '' ;;
	$data['customer_password'] = (isset($GLOBALS['vars']['user_password'])) ? $GLOBALS['vars']['user_password'] : '' ;

	$result = submit_cURL ($data);

	if (!empty($result)) {
		$result_received = true;
		$GLOBALS['xml_result'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
	} else {
		$result_received = false;
	}
	if ($result_received && $GLOBALS['xml_result']['status'] == 'success') {
		return TRUE;
	} else {
		return FALSE;
	}
}
function verify_admin() {

	$result = FALSE;

	if (isset($GLOBALS['vars']['admin_name'])
		&& isset($GLOBALS['vars']['admin_password'])
		&& isset($GLOBALS['admin_users'])
		&& isset($GLOBALS['admin_users'][$GLOBALS['vars']['admin_name']])) {

		if ($GLOBALS['admin_users'][$GLOBALS['vars']['admin_name']] == $GLOBALS['vars']['admin_password']) {
			$result = TRUE;
		}
	}

	return $result;
}
function verify_user_credentials () {

	$data = array();
	$data['user_id'] 				= $GLOBALS['vars']['user_name'];
	$data['user_password'] 		= $GLOBALS['vars']['user_password'];
	$data['type'] 					= 'user_login';
	$result = submit_cURL ($data);

	if (empty($result)) return false;

	$response = new SimpleXMLElement(str_replace('&','&amp;',$result));

	if ((string)$response->attributes()->status != 'success') {
		return false;
	}
	else {
		$GLOBALS['api_user'] = (string)$response->account->user_id;
		$GLOBALS['api_key']	= (string)$response->account->user_api_key;

		$GLOBALS['user'] = get_object_vars($response->account);
		$GLOBALS['user']['verified'] = true;

		$GLOBALS['agency'] = get_object_vars($response->account);

		return true;
	}
}
function update_site_preferences() {

	$preferences = array();

	$preferences['public_top_nav'] 						= implode (',', $GLOBALS['vars']['public_top_nav_item']);
	$preferences['public_footer_links']					= implode (',', $GLOBALS['vars']['public_footer_nav_item']);
	$preferences['loggedin_top_nav'] 					= implode (',', $GLOBALS['vars']['loggedin_top_nav_item']);
	$preferences['loggedin_footer_links'] 				= implode (',', $GLOBALS['vars']['loggedin_footer_nav_item']);
	$preferences['loggedin_customer_top_nav'] 		= implode (',', $GLOBALS['vars']['loggedin_customer_top_nav_item']);
	$preferences['loggedin_customer_footer_links'] 	= implode (',', $GLOBALS['vars']['loggedin_customer_footer_nav_item']);
	$preferences['redeem_type'] 							= implode (',', $GLOBALS['vars']['redemption_types']);
	$preferences['notification_email'] 					= (!empty($GLOBALS['vars']['notification_email'])) ? $GLOBALS['vars']['notification_email'] : '';
	$preferences['edit_text'] 								= (!empty($GLOBALS['vars']['edit_text'])) ? 'true' : 'false';
	//$preferences['campaigns_to_show'] 					= implode ('|,|', $GLOBALS['vars']['campaigns_to_show']);

	if (($GLOBALS['vars']['final_submit'] == 'yes' && $GLOBALS['vars']['fields_tab'] == 'customers')
	 || ($GLOBALS['vars']['final_submit'] == 'no' && $GLOBALS['vars']['fields_tab'] == 'transactions')) {
			$preferences['customer_fields_order'] 			= (!empty($GLOBALS['vars']['customer_fields_order'])) 				? $GLOBALS['vars']['customer_fields_order'] : '';
			$preferences['fields_to_show'] 					= (!empty($GLOBALS['vars']['fields_to_show'])) 							? implode ('|', $GLOBALS['vars']['fields_to_show']) : ''; // It's an array from being checkboxes
			$preferences['transaction_fields_order'] 		= (!empty($GLOBALS['preferences']['transaction_fields_order'])) 	? implode ('|', $GLOBALS['preferences']['transaction_fields_order']) : '';
			$preferences['transaction_fields_to_show'] 	= (!empty($GLOBALS['preferences']['transaction_fields_to_show'])) ? implode ('|', $GLOBALS['preferences']['transaction_fields_to_show']) : '';
	}
	elseif (($GLOBALS['vars']['final_submit'] == 'yes' && $GLOBALS['vars']['fields_tab'] == 'transactions')
		  || ($GLOBALS['vars']['final_submit'] == 'no' && $GLOBALS['vars']['fields_tab'] == 'customers')){
			$preferences['customer_fields_order'] 			= (!empty($GLOBALS['preferences']['customer_fields_order'])) 		? implode ('|', $GLOBALS['preferences']['customer_fields_order']) : '';
			$preferences['fields_to_show'] 					= (!empty($GLOBALS['preferences']['fields_to_show'])) 				? implode ('|', $GLOBALS['preferences']['fields_to_show']) : '';
			$preferences['transaction_fields_order'] 		= (!empty($GLOBALS['vars']['transaction_fields_order'])) 			? $GLOBALS['vars']['transaction_fields_order'] : '';
			$preferences['transaction_fields_to_show'] 	= (!empty($GLOBALS['vars']['transaction_fields_to_show'])) 			? implode ('|', $GLOBALS['vars']['transaction_fields_to_show']) : ''; // It's an array from being checkboxes
	}
	else { // Something wrong - record previous settings, ignore any new ones.
			$preferences['customer_fields_order'] 			= (!empty($GLOBALS['preferences']['customer_fields_order'])) 		? implode ('|', $GLOBALS['preferences']['customer_fields_order']) : '';
			$preferences['fields_to_show'] 					= (!empty($GLOBALS['preferences']['fields_to_show'])) 				? implode ('|', $GLOBALS['preferences']['fields_to_show']) : '';
			$preferences['transaction_fields_order'] 		= (!empty($GLOBALS['preferences']['transaction_fields_order'])) 	? implode ('|', $GLOBALS['preferences']['transaction_fields_order']) : '';
			$preferences['transaction_fields_to_show'] 	= (!empty($GLOBALS['preferences']['transaction_fields_to_show'])) ? implode ('|', $GLOBALS['preferences']['transaction_fields_to_show']) : '';
	}

	$write_success = write_file($preferences, 'preferences.txt');

	if ($write_success) {
		$GLOBALS['preferences']['public_top_nav'] 						= $GLOBALS['vars']['public_top_nav_item'];
		$GLOBALS['preferences']['public_footer_links'] 					= $GLOBALS['vars']['public_footer_nav_item'];
		$GLOBALS['preferences']['loggedin_top_nav'] 						= $GLOBALS['vars']['loggedin_top_nav_item'];
		$GLOBALS['preferences']['loggedin_footer_links']				= $GLOBALS['vars']['loggedin_footer_nav_item'];
		$GLOBALS['preferences']['loggedin_customer_top_nav'] 			= $GLOBALS['vars']['loggedin_customer_top_nav_item'];
		$GLOBALS['preferences']['loggedin_customer_footer_links']	= $GLOBALS['vars']['loggedin_customer_footer_nav_item'];
		$GLOBALS['preferences']['redeem_types'] 							= $GLOBALS['vars']['redemption_types'];
		$GLOBALS['preferences']['notification_email'] 					= $GLOBALS['vars']['notification_email'];
		$GLOBALS['preferences']['edit_text'] 								= (!empty($GLOBALS['vars']['edit_text'])) ? 'true' : 'false';
		//$GLOBALS['preferences']['campaigns_to_show'] 					= $GLOBALS['vars']['campaigns_to_show'];
		if (($GLOBALS['vars']['final_submit'] == 'yes' && $GLOBALS['vars']['fields_tab'] == 'customers')
		 || ($GLOBALS['vars']['final_submit'] == 'no' && $GLOBALS['vars']['fields_tab'] == 'transactions')) {
			$GLOBALS['preferences']['customer_fields_order'] 			= explode('|', $GLOBALS['vars']['customer_fields_order']);
			$GLOBALS['preferences']['fields_to_show'] 					= (!empty($GLOBALS['vars']['fields_to_show'])) 							? $GLOBALS['vars']['fields_to_show'] : '' ; // in case all checkboxes are unchecked.
			$GLOBALS['preferences']['transaction_fields_order'] 		= (!empty($GLOBALS['preferences']['transaction_fields_order'])) 	? $GLOBALS['preferences']['transaction_fields_order'] : '';
			$GLOBALS['preferences']['transaction_fields_to_show'] 	= (!empty($GLOBALS['preferences']['transaction_fields_to_show'])) ? $GLOBALS['preferences']['transaction_fields_to_show'] : '';
		}
		elseif (($GLOBALS['vars']['final_submit'] == 'yes' && $GLOBALS['vars']['fields_tab'] == 'transactions')
			  || ($GLOBALS['vars']['final_submit'] == 'no' && $GLOBALS['vars']['fields_tab'] == 'customers')){
			$GLOBALS['preferences']['customer_fields_order'] 			= (!empty($GLOBALS['preferences']['customer_fields_order'])) 		? $GLOBALS['preferences']['customer_fields_order'] : '';
			$GLOBALS['preferences']['fields_to_show'] 					= (!empty($GLOBALS['preferences']['fields_to_show'])) 				? $GLOBALS['preferences']['fields_to_show'] : '';
			$GLOBALS['preferences']['transaction_fields_order'] 		= explode('|', $GLOBALS['vars']['transaction_fields_order']);
			$GLOBALS['preferences']['transaction_fields_to_show'] 	= (!empty($GLOBALS['vars']['transaction_fields_to_show'])) 			? $GLOBALS['vars']['transaction_fields_to_show'] : '' ; // in case all checkboxes are unchecked.
		}
		else { // Something wrong - record previous settings, ignore any new ones.
			$GLOBALS['preferences']['customer_fields_order'] 			= (!empty($GLOBALS['preferences']['customer_fields_order'])) 		? $GLOBALS['preferences']['customer_fields_order'] : '';
			$GLOBALS['preferences']['fields_to_show'] 					= (!empty($GLOBALS['preferences']['fields_to_show'])) 				? $GLOBALS['preferences']['fields_to_show'] : '';
			$GLOBALS['preferences']['transaction_fields_order'] 		= (!empty($GLOBALS['preferences']['transaction_fields_order'])) 	? $GLOBALS['preferences']['transaction_fields_order'] : '';
			$GLOBALS['preferences']['transaction_fields_to_show'] 	= (!empty($GLOBALS['preferences']['transaction_fields_to_show'])) ? $GLOBALS['preferences']['transaction_fields_to_show'] : '';
		}

		$GLOBALS['success']['preferences_updated'] = $GLOBALS['content']['success_preferences_updated'];
	}
}

function get_customer_info () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'customer_info';
	$data['account_id'] 			= $GLOBALS['account_id'];

	if (!empty($GLOBALS['vars']['customer_code'])) {
		$data['code'] 				= $GLOBALS['vars']['customer_code'];
	} elseif (isset($GLOBALS['vars']['customer_card'])) {
		$data['card_number'] 	= $GLOBALS['vars']['customer_card'];
	} elseif (isset($GLOBALS['vars']['card_number'])) {
		$data['card_number'] 	= $GLOBALS['vars']['card_number'];
	} else  {
		$data['card_number'] 	= '' ;
	}

	$result = submit_cURL ($data);

	if (!empty($result)) {
		$GLOBALS['customer_info'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
	}
	else {
		$GLOBALS['customer_info'] = new SimpleXMLElement();
	}

}

function get_customer_balance ($campaign_id, $cusomter_code, $card_number='') {
	$data = array();

	$data['user_id'] 			= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 				= 'customer_balance';
	$data['account_id'] 		= $GLOBALS['account_id'];
	$data['campaign_id']		= $campaign_id;
	
	if (!empty($cusomter_code)) {
		$data['code'] 			= $cusomter_code;
	} else {
		$data['card_number'] 	= $card_number ;
	}

	$response = '';

	$result = submit_cURL ($data);

	if (!empty($result)) {
		$response = new SimpleXMLElement(str_replace('&','&amp;',$result));
	}
	else {
		$response = new SimpleXMLElement();
	}
	
	return $response;
}

function get_custom_customer_fields () {

	// Get the list of custom fields:
	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_api_key'] 		= $GLOBALS['api_key'];
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['API'] 					= '1.5';
	$data['type'] 					= 'manage_fields';
	$data['action'] 				= 'list';

	$result = submit_cURL ($data);

	if (!empty($result)) {
		$customer_fields = new SimpleXMLElement(str_replace('&','&amp;',$result));
	}

	// make fields object into an array:
	if (!empty($customer_fields->account->fields->field) && sizeof($customer_fields->account->fields->field) > 0) {
		foreach ($customer_fields->account->fields->field as $ignore => $customer_field) {
			// Get the field name to assign as key in array:
			$custom_field_name = (string)$customer_field->name;

			foreach ($customer_field as $field_property_name => $field_property_data) {

				if ($field_property_name == 'choices') {
					foreach ($field_property_data->choice as $choice) {
						$GLOBALS['customer_fields'][$custom_field_name]['choices'][] = (string)$choice;
					}
				}
				else {
					$GLOBALS['customer_fields'][$custom_field_name][$field_property_name] = (string)$field_property_data;
				}
			}

			// Assign additional fields to the customer array, if there is a customer selected:
			if (isset($GLOBALS['customer_info'])) {
				if (strpos($custom_field_name, 'custom_field_') !== false // && $custom_field_name != 'custom_field_1'
				) {
					if (!isset($GLOBALS['customer_info']->customer->$custom_field_name->label)) {
						$GLOBALS['customer_info']->customer->$custom_field_name->label = (string)$customer_field->label;
					}
					$GLOBALS['customer_info']->customer->$custom_field_name->type = (string)$customer_field->type;
					$GLOBALS['customer_info']->customer->$custom_field_name->show = (string)$customer_field->show;
					if (isset($customer_field->choices)) {
						foreach ($field_property_data->choice as $choice) {
							$GLOBALS['customer_info']->customer->$custom_field_name->choices[] = $choice;
						}
					}
				}
				else { // these are not custom fields, but they could be marked as hidden:
					if (isset($customer_field->label)) {
						$GLOBALS['customer_normal_fields'][$custom_field_name]['label'] = (string)$customer_field->label;
					}
					if (isset($customer_field->show)) {
						$GLOBALS['customer_normal_fields'][$custom_field_name]['show'] = (string)$customer_field->show;
					}
				}
			}
		}
	}
}
function get_custom_transaction_fields () {

	// Get the list of custom fields:
	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_api_key'] 		= $GLOBALS['api_key'];
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['API'] 					= '1.5';
	$data['type'] 					= 'transaction_fields';
	$data['action'] 				= 'list';

	$result = submit_cURL ($data);

	$transaction_fields = new SimpleXMLElement(str_replace('&','&amp;',$result));

	// make fields object into an array:
	if (!empty($transaction_fields->account->fields->field) && sizeof($transaction_fields->account->fields->field) > 0) {
		foreach ($transaction_fields->account->fields->field as $ignore => $transaction_field) {
			// Get the field name to assign as key in array:
			$custom_field_name = (string)$transaction_field->name;

			foreach ($transaction_field as $field_property_name => $field_property_data) {

				if ($field_property_name == 'choices') {
					foreach ($field_property_data->choice as $choice) {
						$GLOBALS['transaction_fields'][$custom_field_name]['choices'][] = (string)$choice;
					}
				}
				else {
					$GLOBALS['transaction_fields'][$custom_field_name][$field_property_name] = (string)$field_property_data;
				}
			}
		}
	}

}

function get_custom_transaction_fields2($field_label) {

	// Get the list of custom fields:
	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_api_key'] 		= $GLOBALS['api_key'];
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['API'] 					= '1.5';
	$data['type'] 					= 'transaction_fields';
	$data['action'] 				= 'list';

	$result = submit_cURL ($data);

	$transaction_fields = new SimpleXMLElement(str_replace('&','&amp;',$result));
	$response = NULL;

	// make fields object into an array:
	if (!empty($transaction_fields->account->fields->field) && sizeof($transaction_fields->account->fields->field) > 0) {
		foreach ($transaction_fields->account->fields->field as $ignore => $transaction_field) {
			// Get the field name to assign as key in array:
			if((string)$transaction_field->label == $field_label)
			{
				$custom_field_name = (string)$transaction_field->name;
	
				foreach ($transaction_field as $field_property_name => $field_property_data) {
	
					if ($field_property_name == 'choices') {
						foreach ($field_property_data->choice as $choice) {
							$response['choices'][] = (string)$choice;
						}
					}
					else {
						$response[$field_property_name] = (string)$field_property_data;
					}
				}
				break;
			}
		}
	}
	return $response;
}

function find_customer () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'find_customer';
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['include_balances']	= 'Y';
	$data['find_customer'] 		= (isset($GLOBALS['vars']['customer_card'])) ? $GLOBALS['vars']['customer_card'] : '' ;

	$result = submit_cURL ($data);

	$GLOBALS['customer_search_results'] = new SimpleXMLElement(str_replace('&','&amp;',$result));

}
function update_customer_info () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'record_customer';
	$data['customer_action'] 	= 'edit';
	$data['account_id'] 			= $GLOBALS['account_id'];

	$data['card_number'] 		= (isset($GLOBALS['vars']['customer_card'])) ? $GLOBALS['vars']['customer_card'] : '' ;

	if (isset($GLOBALS['vars']['first_name'])) 	{ $data['first_name'] 			= $GLOBALS['vars']['first_name']		;}
	if (isset($GLOBALS['vars']['last_name'])) 	{ $data['last_name'] 			= $GLOBALS['vars']['last_name']		;}
	if (isset($GLOBALS['vars']['phone'])) 			{ $data['phone'] 					= $GLOBALS['vars']['phone']			;}
	if (isset($GLOBALS['vars']['email'])) 			{ $data['email'] 					= $GLOBALS['vars']['email'] 			;}

	if (isset($GLOBALS['vars']['custom_date'])) 	{ $data['custom_date']	 		= $GLOBALS['vars']['custom_date'] 	;}
	else {
		$date_year = (!empty($GLOBALS['vars']['date_year'])) ? $GLOBALS['vars']['date_year'] : '0000';
		$date_month = (!empty($GLOBALS['vars']['date_month'])) ? $GLOBALS['vars']['date_month'] : '00';
		$date_day = (!empty($GLOBALS['vars']['date_day'])) ? $GLOBALS['vars']['date_day'] : '00';
		if (in_array('custom_date', $GLOBALS['preferences']['fields_to_show']) || empty($GLOBALS['preferences']['fields_to_show'])) {
			$GLOBALS['vars']['custom_date'] = $date_year.'-'.$date_month.'-'.$date_day;
			$data['custom_date'] = $GLOBALS['vars']['custom_date'];
		}
	}

	if (isset($GLOBALS['vars']['street1'])) 		{ $data['street1'] 				= $GLOBALS['vars']['street1'] 		;}
	if (isset($GLOBALS['vars']['street2'])) 		{ $data['street2'] 				= $GLOBALS['vars']['street2'] 		;}
	if (isset($GLOBALS['vars']['city'])) 			{ $data['city'] 					= $GLOBALS['vars']['city'] 			;}
	if (isset($GLOBALS['vars']['state'])) 			{ $data['state'] 					= $GLOBALS['vars']['state']		 	;}
	if (isset($GLOBALS['vars']['postal_code'])) 	{ $data['postal_code']	 		= $GLOBALS['vars']['postal_code']	;}
	if (isset($GLOBALS['vars']['country'])) 		{ $data['country'] 				= $GLOBALS['vars']['country']	 		;}

	if (isset($GLOBALS['vars']['custom1'])) 		{ $data['custom1'] 				= $GLOBALS['vars']['custom1'] 		;}
	if (isset($GLOBALS['vars']['custom_field_1'])) 	{ $data['custom1'] 				= $GLOBALS['vars']['custom_field_1'];}

	if (!empty($GLOBALS['vars']['new_password'])
		&& !empty($GLOBALS['vars']['new_password2'])
		&& $GLOBALS['vars']['new_password'] == $GLOBALS['vars']['new_password2']) {
																  $data['customer_password']	= $GLOBALS['vars']['new_password'];
	}
	elseif (empty($GLOBALS['vars']['new_password']) && empty($GLOBALS['vars']['new_password2'])) {
		// Do nothing. New password not applicable.
	}
	else {
		$GLOBALS['errors']['myinfo_error_passwords_no_match'] = $GLOBALS['content']['myinfo_error_passwords_no_match'];
	}

	//

	foreach ($GLOBALS['customer_fields'] as $ignore => $customer_field_data) {
		$customer_field_name = $customer_field_data['name'];
		if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {
			if ($customer_field_data['show'] != 'N') {
				if ($customer_field_data['type'] == 'Text') {
					if (isset($GLOBALS['vars'][$customer_field_name])) { $data[$customer_field_name] = $GLOBALS['vars'][$customer_field_name] ;}
				}
				elseif ($customer_field_data['type'] == 'Date') {
					if (isset($GLOBALS['vars'][$customer_field_name.'_year'])
						 || isset($GLOBALS['vars'][$customer_field_name.'_month'])
						 || isset($GLOBALS['vars'][$customer_field_name.'_day'])) {
						$final_date = (!empty($GLOBALS['vars'][$customer_field_name.'_year'])) ? $GLOBALS['vars'][$customer_field_name.'_year'] : '0000' ;
						$final_date .= '-';
						$final_date .= (!empty($GLOBALS['vars'][$customer_field_name.'_month'])) ? $GLOBALS['vars'][$customer_field_name.'_month'] : '00' ;
						$final_date .= '-';
						$final_date .= (!empty($GLOBALS['vars'][$customer_field_name.'_day'])) ? $GLOBALS['vars'][$customer_field_name.'_day'] : '00' ;
						$data[$customer_field_name] = $final_date;
					}
				}
				elseif ($customer_field_data['type'] == 'Pick') {
					if (isset($GLOBALS['vars'][$customer_field_name])) { $data[$customer_field_name] = $GLOBALS['vars'][$customer_field_name] ;}
				}
				elseif ($customer_field_data['type'] == 'List') {
					if (isset($GLOBALS['vars'][$customer_field_name])) {
						if (is_array($GLOBALS['vars'][$customer_field_name])) {
							$item_count = 0;
							foreach ($GLOBALS['vars'][$customer_field_name] as $chosen_item) {
								if ($item_count == 0) {
									$items_to_record = $chosen_item;
								}
								else {
									$items_to_record .= ', '.$chosen_item;
								}
								$item_count++;
							}
						}
						else { // not array?
							$items_to_record = (string)$GLOBALS['vars'][$customer_field_name];
						}
						$data[$customer_field_name] = $items_to_record;
					}
				}
			}
		}
	}
	//
	if (isset($GLOBALS['errors']) && sizeof($GLOBALS['errors']) > 0) {
		// Error with the passwords.

	}
	else { // No errors - proceed to submit the data

		$result = submit_cURL ($data);

		if (!empty($result)) {
			$GLOBALS['customer_update'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
			return TRUE;
		} else {
			return FALSE;
		}

	}
}

function new_customer_info () {
	$data = array();
	$GLOBALS['new_customer_result'] = FALSE;

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'record_customer';
	$data['customer_action'] 	= 'new';
	$data['account_id'] 			= $GLOBALS['account_id'];

	if (!empty($GLOBALS['vars']['card_number'])) {
		$data['card_number'] 			= $GLOBALS['vars']['card_number'];
	} else {
		$GLOBALS['errors']['myinfo_error_no_card_number'] = $GLOBALS['content']['myinfo_error_no_card_number'];
	}
	// Clerks should not set password for customers. Also, if password ws allowed to be set, it shouldn't be required.
	/*
	if (!empty($GLOBALS['vars']['new_password'])) {
		if ($GLOBALS['vars']['new_password'] == $GLOBALS['vars']['new_password2']) {
			$data['customer_password']				= $GLOBALS['vars']['new_password'];
		} else {
			$GLOBALS['errors']['myinfo_error_passwords_no_match'] = $GLOBALS['content']['myinfo_error_passwords_no_match'];
		}
	} else {
		//$GLOBALS['errors']['myinfo_error_password_required'] = $GLOBALS['content']['myinfo_error_password_required'];
	}
	*/
	if (isset($GLOBALS['vars']['card_number'])) 	{ $data['card_number'] 			= $GLOBALS['vars']['card_number']		;}
	if (!empty($GLOBALS['vars']['first_name'])) 	{ $data['first_name'] 			= $GLOBALS['vars']['first_name']		;}
	//else {$GLOBALS['errors']['myinfo_error_first_name_required'] = $GLOBALS['content']['myinfo_error_first_name_required'];}
	if (!empty($GLOBALS['vars']['last_name'])) 	{ $data['last_name'] 			= $GLOBALS['vars']['last_name']		;}
	//else {$GLOBALS['errors']['myinfo_error_last_name_required'] = $GLOBALS['content']['myinfo_error_last_name_required'];}
	if (isset($GLOBALS['vars']['phone'])) 			{ $data['phone'] 					= $GLOBALS['vars']['phone']			;}
	if (isset($GLOBALS['vars']['email'])) 			{ $data['email'] 					= $GLOBALS['vars']['email'] 			;}

	if (isset($GLOBALS['vars']['custom_date'])) 	{ $data['custom_date']	 		= $GLOBALS['vars']['custom_date'] 	;}
	else {
		$date_year = (!empty($GLOBALS['vars']['date_year'])) ? $GLOBALS['vars']['date_year'] : '0000';
		$date_month = (!empty($GLOBALS['vars']['date_month'])) ? $GLOBALS['vars']['date_month'] : '00';
		$date_day = (!empty($GLOBALS['vars']['date_day'])) ? $GLOBALS['vars']['date_day'] : '00';
		if (in_array('custom_date', $GLOBALS['preferences']['fields_to_show']) || empty($GLOBALS['preferences']['fields_to_show'])) {
			$GLOBALS['vars']['custom_date'] = $date_year.'-'.$date_month.'-'.$date_day;
			$data['custom_date'] = $GLOBALS['vars']['custom_date'];
		}
	}

	if (isset($GLOBALS['vars']['street1'])) 		{ $data['street1'] 				= $GLOBALS['vars']['street1'] 		;}
	if (isset($GLOBALS['vars']['street2'])) 		{ $data['street2'] 				= $GLOBALS['vars']['street2'] 		;}
	if (isset($GLOBALS['vars']['city'])) 			{ $data['city'] 					= $GLOBALS['vars']['city'] 			;}
	if (isset($GLOBALS['vars']['state'])) 			{ $data['state'] 					= $GLOBALS['vars']['state']		 	;}
	if (isset($GLOBALS['vars']['postal_code'])) 	{ $data['postal_code']	 		= $GLOBALS['vars']['postal_code']	;}
	if (isset($GLOBALS['vars']['country'])) 		{ $data['country'] 				= $GLOBALS['vars']['country']	 		;}

	if (isset($GLOBALS['vars']['custom1'])) 			{ $data['custom1'] 				= $GLOBALS['vars']['custom1'] 		;}
	if (isset($GLOBALS['vars']['custom_field_1'])) 	{ $data['custom1'] 				= $GLOBALS['vars']['custom_field_1'];}

	foreach ($GLOBALS['customer_fields'] as $customer_field_name => $customer_field_data) {
		if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {
			if ((string)$customer_field_data['show'] != 'N') {
				if ((string)$customer_field_data['type'] == 'Text') {
					if (isset($GLOBALS['vars'][$customer_field_name])) { $data[$customer_field_name] = $GLOBALS['vars'][$customer_field_name] ;}
				}
				elseif ((string)$customer_field_data['type'] == 'Date') {
					if (isset($GLOBALS['vars'][$customer_field_name.'_year'])
						 || isset($GLOBALS['vars'][$customer_field_name.'_month'])
						 || isset($GLOBALS['vars'][$customer_field_name.'_day'])) {
						$final_date = (!empty($GLOBALS['vars'][$customer_field_name.'_year'])) ? $GLOBALS['vars'][$customer_field_name.'_year'] : '0000' ;
						$final_date .= '-';
						$final_date .= (!empty($GLOBALS['vars'][$customer_field_name.'_month'])) ? $GLOBALS['vars'][$customer_field_name.'_month'] : '00' ;
						$final_date .= '-';
						$final_date .= (!empty($GLOBALS['vars'][$customer_field_name.'_day'])) ? $GLOBALS['vars'][$customer_field_name.'_day'] : '00' ;
						$data[$customer_field_name] = $final_date;
					}
				}
				elseif ((string)$customer_field_data['type'] == 'Pick') {
					if (isset($GLOBALS['vars'][$customer_field_name])) { $data[$customer_field_name] = $GLOBALS['vars'][$customer_field_name] ;}
				}
				elseif ((string)$customer_field_data['type'] == 'List') {
					if (isset($GLOBALS['vars'][$customer_field_name])) {
						if (is_array($GLOBALS['vars'][$customer_field_name])) {
							$item_count = 0;
							foreach ($GLOBALS['vars'][$customer_field_name] as $chosen_item) {
								if ($item_count == 0) {
									$items_to_record = $chosen_item;
								}
								else {
									$items_to_record .= ', '.$chosen_item;
								}
								$item_count++;
							}
						}
						else { // not array?
							$items_to_record = (string)$GLOBALS['vars'][$customer_field_name];
						}
						$data[$customer_field_name] = $items_to_record;
					}
				}
			}
		}
	}
	//
	if (isset($GLOBALS['errors']) && sizeof($GLOBALS['errors']) > 0) {
		// Error occurred.
		$GLOBALS['new_customer_result'] = FALSE;

	}
	else { // No errors - proceed to submit the data
		$result = submit_cURL ($data);

		if (!empty($result)) {
			$GLOBALS['new_customer_result_xml'] 		= new SimpleXMLElement(str_replace('&','&amp;',$result));

			// Check if the result is an error or a duplicate customer:
			 if ($GLOBALS['new_customer_result_xml']->attributes()->status != 'success') {
				// Error occurred
				$GLOBALS['new_customer_result'] = FALSE;
				$GLOBALS['errors']['myinfo_error_duplicate_card_number'] = $GLOBALS['content']['myinfo_error_duplicate_card_number'];

			} else {
				$GLOBALS['vars']['customer_card'] 		= $GLOBALS['vars']['card_number'];
				$GLOBALS['new_customer_result'] = TRUE;
			}

		}

	}
}

function activate_account_info () {
	$data = array();
	$GLOBALS['activate_account_result'] = FALSE;

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'record_customer';
	$data['customer_action'] 	= 'register';
	$data['account_id'] 			= $GLOBALS['account_id'];

	// Validate Account (Card Number)
	if (!empty($GLOBALS['vars']['card_number'])) {
		$data['card_number'] 			= $GLOBALS['vars']['card_number'];
	} else {
		$GLOBALS['errors']['myinfo_error_no_card_number'] = $GLOBALS['content']['myinfo_error_no_card_number'];
	}

	if (!empty($GLOBALS['vars']['new_password'])) {
		if ($GLOBALS['vars']['new_password'] == $GLOBALS['vars']['new_password2']) {
			$data['customer_password']				= $GLOBALS['vars']['new_password'];
		} else {
			$GLOBALS['errors']['myinfo_error_passwords_no_match'] = $GLOBALS['content']['myinfo_error_passwords_no_match'];
		}
	} else {
		$GLOBALS['errors']['myinfo_error_password_required'] = $GLOBALS['content']['myinfo_error_password_required'];
	}
	if (isset($GLOBALS['vars']['card_number'])) 	{ $data['card_number'] 			= $GLOBALS['vars']['card_number']		;}
	if (!empty($GLOBALS['vars']['first_name'])) 	{ $data['first_name'] 			= $GLOBALS['vars']['first_name']		;}
	//else {$GLOBALS['errors']['myinfo_error_first_name_required'] = $GLOBALS['content']['myinfo_error_first_name_required'];}
	if (!empty($GLOBALS['vars']['last_name'])) 	{ $data['last_name'] 			= $GLOBALS['vars']['last_name']		;}
	//else {$GLOBALS['errors']['myinfo_error_last_name_required'] = $GLOBALS['content']['myinfo_error_last_name_required'];}
	if (isset($GLOBALS['vars']['phone'])) 			{ $data['phone'] 					= $GLOBALS['vars']['phone']			;}
	if (isset($GLOBALS['vars']['email'])) 			{ $data['email'] 					= $GLOBALS['vars']['email'] 			;}
	if (isset($GLOBALS['vars']['custom_date'])) 	{ $data['custom_date']	 		= $GLOBALS['vars']['custom_date'] 	;}
	else {
		$date_year = (!empty($GLOBALS['vars']['date_year'])) ? $GLOBALS['vars']['date_year'] : '0000';
		$date_month = (!empty($GLOBALS['vars']['date_month'])) ? $GLOBALS['vars']['date_month'] : '00';
		$date_day = (!empty($GLOBALS['vars']['date_day'])) ? $GLOBALS['vars']['date_day'] : '00';
		if (in_array('custom_date', $GLOBALS['preferences']['fields_to_show']) || empty($GLOBALS['preferences']['fields_to_show'])) {
			$GLOBALS['vars']['custom_date'] = $date_year.'-'.$date_month.'-'.$date_day;
			$data['custom_date'] = $GLOBALS['vars']['custom_date'];
		}
	}
	if (isset($GLOBALS['vars']['street1'])) 		{ $data['street1'] 				= $GLOBALS['vars']['street1'] 		;}
	if (isset($GLOBALS['vars']['street2'])) 		{ $data['street2'] 				= $GLOBALS['vars']['street2'] 		;}
	if (isset($GLOBALS['vars']['city'])) 			{ $data['city'] 					= $GLOBALS['vars']['city'] 			;}
	if (isset($GLOBALS['vars']['state'])) 			{ $data['state'] 					= $GLOBALS['vars']['state']		 	;}
	if (isset($GLOBALS['vars']['postal_code'])) 	{ $data['postal_code']	 		= $GLOBALS['vars']['postal_code']	;}
	if (isset($GLOBALS['vars']['country'])) 		{ $data['country'] 				= $GLOBALS['vars']['country']	 		;}

	if (isset($GLOBALS['vars']['custom1'])) 			{ $data['custom1'] 				= $GLOBALS['vars']['custom1'] 		;}
	if (isset($GLOBALS['vars']['custom_field_1'])) 	{ $data['custom1'] 				= $GLOBALS['vars']['custom_field_1'];}

	foreach ($GLOBALS['customer_fields'] as $customer_field_name => $customer_field_data) {
		if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {
			if ((string)$customer_field_data['show'] != 'N') {
				if ((string)$customer_field_data['type'] == 'Text') {
					if (isset($GLOBALS['vars'][$customer_field_name])) { $data[$customer_field_name] = $GLOBALS['vars'][$customer_field_name] ;}
				}
				elseif ((string)$customer_field_data['type'] == 'Date') {
					if (isset($GLOBALS['vars'][$customer_field_name.'_year'])
						 || isset($GLOBALS['vars'][$customer_field_name.'_month'])
						 || isset($GLOBALS['vars'][$customer_field_name.'_day'])) {
						$final_date = (!empty($GLOBALS['vars'][$customer_field_name.'_year'])) ? $GLOBALS['vars'][$customer_field_name.'_year'] : '0000' ;
						$final_date .= '-';
						$final_date .= (!empty($GLOBALS['vars'][$customer_field_name.'_month'])) ? $GLOBALS['vars'][$customer_field_name.'_month'] : '00' ;
						$final_date .= '-';
						$final_date .= (!empty($GLOBALS['vars'][$customer_field_name.'_day'])) ? $GLOBALS['vars'][$customer_field_name.'_day'] : '00' ;
						$data[$customer_field_name] = $final_date;
					}
				}
				elseif ((string)$customer_field_data['type'] == 'Pick') {
					if (isset($GLOBALS['vars'][$customer_field_name])) { $data[$customer_field_name] = $GLOBALS['vars'][$customer_field_name] ;}
				}
				elseif ((string)$customer_field_data['type'] == 'List') {
					if (isset($GLOBALS['vars'][$customer_field_name])) {
						if (is_array($GLOBALS['vars'][$customer_field_name])) {
							$item_count = 0;
							foreach ($GLOBALS['vars'][$customer_field_name] as $chosen_item) {
								if ($item_count == 0) {
									$items_to_record = $chosen_item;
								}
								else {
									$items_to_record .= ', '.$chosen_item;
								}
								$item_count++;
							}
						}
						else { // not array?
							$items_to_record = (string)$GLOBALS['vars'][$customer_field_name];
						}
						$data[$customer_field_name] = $items_to_record;
					}
				}
			}
		}
	}
	//
	if (isset($GLOBALS['errors']) && sizeof($GLOBALS['errors']) > 0) {
		// Error occurred.
		$GLOBALS['activate_account_result'] = FALSE;

	}
	else { // No errors - proceed to submit the data
		$result = submit_cURL ($data);

		if (!empty($result)) {
			$GLOBALS['activate_account_result_xml'] 		= new SimpleXMLElement(str_replace('&','&amp;',$result));

			// Check if the result is an error or a duplicate customer:
			if ($GLOBALS['activate_account_result_xml']->attributes()->status == 'success') {
				$GLOBALS['vars']['customer_card'] 		= $GLOBALS['vars']['card_number'];
				$GLOBALS['activate_account_result'] = TRUE;

			} else {
				// Error occurred
				$GLOBALS['activate_account_result'] = FALSE;
				//$GLOBALS['errors']['myinfo_error_duplicate_card_number'] = $GLOBALS['content']['myinfo_error_duplicate_card_number'];
				$GLOBALS['errors']['api_error'] = $GLOBALS['activate_account_result_xml']->error;
			}
		} else {
			$GLOBALS['errors']['myinfo_error'] = $GLOBALS['content']['myinfo_error'];
			$GLOBALS['activate_account_result'] = FALSE;
		}
	}
}
function get_balance_history () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'balance';
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['campaign_id']			= $GLOBALS['vars']['which_campaign_to_show'];
	$data['code'] 					= $GLOBALS['vars']['code'];

	$result = submit_cURL ($data);

	$results_list = new SimpleXMLElement(str_replace('&','&amp;',$result));

	if (!empty($results_list->campaign->customer->transactions->transaction)) {
		foreach ($results_list->campaign->customer->transactions->transaction as $discard => $transaction_info) {
			$sorting_id = (string)$transaction_info->date.'-'.(string)$transaction_info->id;
			$GLOBALS['balance_history']['transactions'][$sorting_id] = $transaction_info;
		}

		krsort($GLOBALS['balance_history']['transactions']);
	}
	else {
		$GLOBALS['balance_history']['transactions'] = array();
	}

	$GLOBALS['balance_history']['campaign_type'] = (string)$results_list->campaign->campaign_type;

}
function get_rewards () {

	if (!empty($GLOBALS['vars']['which_campaign_to_show'])) {
		$data = array();

		$data['user_id'] 				= $GLOBALS['api_user'];
		$data['user_password'] 		= $GLOBALS['api_key'];
		$data['type'] 					= 'campaign_rewards';
		$data['account_id'] 			= $GLOBALS['account_id'];
		$data['campaign_id']			= $GLOBALS['vars']['which_campaign_to_show'];

		$result = submit_cURL ($data);

		$GLOBALS['campaign_rewards'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
	}
}
function get_promotions () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'campaign_promos';
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['campaign_id']			= $GLOBALS['vars']['which_campaign_to_show'];

	$result = submit_cURL ($data);

	$GLOBALS['campaign_promos'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
}
function get_buyx_items () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'buyx_items_list';
	$data['account_id'] 			= $GLOBALS['account_id'];
	$data['campaign_id']			= $GLOBALS['vars']['which_campaign_to_show'];

	$result = submit_cURL ($data);

	$GLOBALS['campaign_buyx_items'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
}
function get_campaign_list () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'campaigns_list';
	$data['account_id'] 			= $GLOBALS['account_id'];

	$result = submit_cURL ($data);

	$GLOBALS['campaigns_list'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
}
function redeem_reward () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'redeem';
	$data['account_id'] 			= $GLOBALS['account_id'];

	$data['code']					= $GLOBALS['customer_info']->customer->code;
	$data['campaign_id']			= $GLOBALS['vars']['which_campaign'];
	if ($GLOBALS['vars']['which_campaign_type'] == 'giftcard' || $GLOBALS['vars']['which_campaign_type'] == 'earned') {
		$data['reward_to_redeem']	= $GLOBALS['vars']['amount'];
		$data['authorization']		= $GLOBALS['vars']['transaction_description'];
	}
	else {
		$data['reward_to_redeem']	= $GLOBALS['vars']['which_reward_id'];
		$data['authorization']		= '';
		//$data['authorization']		= urldecode($GLOBALS['vars']['which_reward_description']);
	}

	$result = submit_cURL ($data);

	$GLOBALS['redeem_rewards_result'] = new SimpleXMLElement(str_replace('&','&amp;',$result));


	// Send out an email to the preferred email, if any has been set
	if ($GLOBALS['redeem_rewards_result']->attributes()->status == 'success') {

		if (!empty($GLOBALS['preferences']['notification_email'])) {

			// Set the To field:
			$to = $GLOBALS['preferences']['notification_email'];
			$headers = 'From: '.(string)$GLOBALS['redeem_rewards_result']->receipt->agency->name.' <'.(string)$GLOBALS['redeem_rewards_result']->receipt->agency->email.'>' . "\r\n";
			ini_set('sendmail_from', 'server@stickystreet.com');
			$subject = $GLOBALS['hard_coded_content']['email_subject'];
			$body = '';

			// === EMAIL Body:
			$body .= $GLOBALS['hard_coded_content']['email_subject'].":\r\n";
			$body .= "\r\n";
			$body .= "\r\n";
			$body .= $GLOBALS['content']['confirm_product_header'].":\r\n";
			$body .= "\r\n";
			if ($GLOBALS['vars']['which_campaign_type'] == 'buyx') {
				$body .= "\t".'1 '.urldecode($GLOBALS['vars']['which_reward_description'])."\r\n";
			} else {
				$body .= "\t".urldecode($GLOBALS['vars']['which_reward_description'])."\r\n";
			}
			$body .= "\r\n";

			$body .= $GLOBALS['content']['confirm_address_header'].":\r\n";
			// First name
			if (!empty($GLOBALS['vars']['first_name'])) {
				$body .= "\t".$GLOBALS['vars']['first_name'].' ';
			} elseif (!empty($GLOBALS['customer_info']->customer->first_name)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->first_name.' ';
			} else {
				$body .= "\t";
			}
			// Last Name:
			if (!empty($GLOBALS['vars']['last_name'])) {
				$body .= $GLOBALS['vars']['last_name']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->last_name)) {
				$body .= $GLOBALS['customer_info']->customer->last_name."\r\n";
			} else {
				$body .= "\r\n";
			}
			// Card #
			if (!empty($GLOBALS['vars']['card_number'])) {
				$body .= "\t".$GLOBALS['vars']['card_number']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->card_number)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->card_number."\r\n";
			}
			$body .= "\r\n";
			// Phone #
			if (!empty($GLOBALS['vars']['phone'])) {
				$body .= "\t".$GLOBALS['vars']['phone']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->phone)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->phone."\r\n";
			}
			// Email
			if (!empty($GLOBALS['vars']['email'])) {
				$body .= "\t".$GLOBALS['vars']['email']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->email)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->email."\r\n";
			}
			$body .= "\r\n";
			// Address 1
			if (!empty($GLOBALS['vars']['street1'])) {
				$body .= "\t".$GLOBALS['vars']['street1']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->street1)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->street1."\r\n";
			}
			// Address 2
			if (!empty($GLOBALS['vars']['street2'])) {
				$body .= "\t".$GLOBALS['vars']['street2']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->street2)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->street2."\r\n";
			}
			// City
			if (!empty($GLOBALS['vars']['city'])) {
				$body .= "\t".$GLOBALS['vars']['city']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->city)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->city."\r\n";
			}
			// State
			if (!empty($GLOBALS['vars']['state'])) {
				$body .= "\t".$GLOBALS['vars']['state']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->state)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->state."\r\n";
			}
			// ZIP
			if (!empty($GLOBALS['vars']['postal_code'])) {
				$body .= "\t".$GLOBALS['vars']['postal_code']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->postal_code)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->postal_code."\r\n";
			}
			// Country
			if (!empty($GLOBALS['vars']['country'])) {
				$body .= "\t".$GLOBALS['vars']['country']."\r\n";
			} elseif (!empty($GLOBALS['customer_info']->customer->country)) {
				$body .= "\t".$GLOBALS['customer_info']->customer->country."\r\n";
			}

			$mail_result = mail( $to, $subject, $body, $headers );
		}
	}

}
function redeem_amount () {
	$data = array();

	$data['user_id'] 				= $GLOBALS['api_user'];
	$data['user_password'] 		= $GLOBALS['api_key'];
	$data['type'] 					= 'redeem';
	$data['account_id'] 			= $GLOBALS['account_id'];

	$data['code']					= $GLOBALS['customer_info']->customer->code;
	$data['campaign_id']			= $GLOBALS['vars']['which_campaign'];

	if ($GLOBALS['vars']['kind'] == 'points') {
		$data['custom_points_redeem']	= $GLOBALS['vars']['amount'];
		if (!empty($GLOBALS['vars']['transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['transaction_description']);
		} else {
			$data['authorization'] = '';
		}
	}
	elseif ($GLOBALS['vars']['kind'] == 'money') {
		$data['custom_dollars_redeem']	= $GLOBALS['vars']['money_amount'];
		if (!empty($GLOBALS['vars']['money_transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['money_transaction_description']);
		} else {
			$data['authorization'] = '';
		}
	}


	$result = submit_cURL ($data);

	$GLOBALS['redeem_amount_result'] = new SimpleXMLElement(str_replace('&','&amp;',$result));
}
function record_transaction ($type) {
	$data = array();

	$data['user_id'] 						= $GLOBALS['api_user'];
	$data['user_password'] 				= $GLOBALS['api_key'];
	$data['type'] 							= 'record_activity';
	$data['account_id'] 					= $GLOBALS['account_id'];

	$data['code']							= (string)$GLOBALS['customer_info']->customer->code;
	$data['campaign_id']					= $GLOBALS['vars']['which_campaign'];

	// Points Transactions
	if ($type == 'points') {

		if (!empty($GLOBALS['vars']['amount'])) {
			$data['amount']					= $GLOBALS['vars']['amount'];
		}

		if (!empty($GLOBALS['vars']['promo_id'])) {
			$data['promo_id'] = $GLOBALS['vars']['promo_id'];
		}

		if (!empty($GLOBALS['vars']['send_transaction_email'])) {
			$data['send_transaction_email'] = 'Y';
		}

		if (!empty($GLOBALS['vars']['transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['transaction_description']);
		} else {
			$data['authorization'] = '';
		}

	}
	elseif ($type == 'giftcard') {

		if (!empty($GLOBALS['vars']['amount'])) {
			$data['amount']					= $GLOBALS['vars']['amount'];
		}

		if (!empty($GLOBALS['vars']['send_transaction_email'])) {
			$data['send_transaction_email'] = 'Y';
		}

		if (!empty($GLOBALS['vars']['transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['transaction_description']);
		} else {
			$data['authorization'] = '';
		}

	}
	elseif ($type == 'events') {

		if (!empty($GLOBALS['vars']['send_transaction_email'])) {
			$data['send_transaction_email'] = 'Y';
		}

		if (!empty($GLOBALS['vars']['transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['transaction_description']);
		} else {
			$data['authorization'] = '';
		}

	}
	elseif ($type == 'earned') {

		if (!empty($GLOBALS['vars']['send_transaction_email'])) {
			$data['send_transaction_email'] = 'Y';
		}

		if (!empty($GLOBALS['vars']['transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['transaction_description']);
		} else {
			$data['authorization'] = '';
		}

	}
	elseif ($type == 'buyx') {

		$data['service_product'] = (!empty($GLOBALS['vars']['service_product'])) ? $GLOBALS['vars']['service_product'] : '';

		if (!empty($GLOBALS['vars']['amount'])) {
			$data['buyx_quantity'] = $GLOBALS['vars']['amount'];
		}

		if (!empty($GLOBALS['vars']['send_transaction_email'])) {
			$data['send_transaction_email'] = 'Y';
		}

		if (!empty($GLOBALS['vars']['transaction_description'])) {
			$data['authorization'] = urldecode($GLOBALS['vars']['transaction_description']);
		} else {
			$data['authorization'] = '';
		}

	}
	//
	// Also submit any additional custom fields:
	if (!empty($GLOBALS['transaction_fields'])) {
		foreach ($GLOBALS['transaction_fields'] as $transaction_field_name => $transaction_field_data) {
			if (isset($GLOBALS['vars'][$transaction_field_name])) {
				if (!is_array($GLOBALS['vars'][$transaction_field_name])) {
					$data[$transaction_field_name] = $GLOBALS['vars'][$transaction_field_name];
				} else {
					$data[$transaction_field_name] = implode(',', $GLOBALS['vars'][$transaction_field_name]);
				}
			}
		}
	}

	//
	$result = submit_cURL ($data);

	$GLOBALS['record_transaction_result'] = new SimpleXMLElement(str_replace('&','&amp;',$result));

	if ($GLOBALS['record_transaction_result']->attributes()->status == 'success') {
		return true;
	} else {
		return false;
	}
}

function record_transaction_master_campaign($code, $amount, $authorization='', $send_transaction_email='Y', $custom_fields=array()) {
	$data = array();
	$type = 'giftcard';

	$data['user_id'] 						= $GLOBALS['api_user'];
	$data['user_password'] 					= $GLOBALS['api_key'];
	$data['type'] 							= 'record_activity';
	$data['account_id'] 					= $GLOBALS['account_id'];

	$data['code']							= $code;
	$data['campaign_id']					= $GLOBALS['master_campaign_id'];
	
	if($type == 'giftcard')
	{
		$data['amount']					= $amount;
		$data['authorization']			= $authorization;
		$data['send_transaction_email'] = $send_transaction_email;
	}
	
	if(!empty($custom_fields))
	{
		foreach($custom_fields as $key=>$single_field)
		{
			$data[$key] = $single_field;
		}
	}
	
	$result = submit_cURL ($data);
	return new SimpleXMLElement(str_replace('&','&amp;',$result));
}

function get_all_transactions($selected_campaigns, $date_start, $date_end='')
{
	$data = array();
	
	$data['user_id'] 						= $GLOBALS['api_user'];
	$data['user_password'] 					= $GLOBALS['api_key'];
	$data['type'] 							= 'reports';
	$data['report'] 						= 'transactions_all';
	$data['account_id'] 					= $GLOBALS['account_id'];
	
	$data['selected_campaigns']				= $selected_campaigns; //Comma Separated List
	$data['date_start']						= $date_start;
	if($date_end!='')
	{
		$data['date_end']					= $date_end;
	}
	
	$result = submit_cURL ($data);
	return new SimpleXMLElement(str_replace('&','&amp;',$result));
}

function get_campaign_transactions($campaign_list_array, $campaign_id, $start_date, $show_master_campaign=false)
{
	$campaign_transactions = array();
	$campaigns_to_be_excluded = $GLOBALS['custom_campaigns_excluded_ids'];
	$consider_returned_purchases = $GLOBALS['consider_returned_purchases'];
	
	if(isset($campaign_list_array['campaigns']['campaign'][0]))
	{
		if($show_master_campaign == false)
		{
			$c = 0;
			foreach($campaign_list_array['campaigns']['campaign'] as $single_camp) 
			{
				if($campaigns_to_be_excluded!='' and strstr($campaigns_to_be_excluded, $single_camp['id'])==false)
				{
					if($single_camp['id'] != $GLOBALS['master_campaign_id'])
					{
						$key = $single_camp['id'];
						$campaign_transactions[$key] = $single_camp;
						if($c == 0 and $campaign_id == '')
						{
							$campaign_id = $key;
						}
						$c++;
					}
				}
			}
		} else {
			$c=0;
			foreach($campaign_list_array['campaigns']['campaign'] as $single_camp) 
			{
				if($campaigns_to_be_excluded!='' and strstr($campaigns_to_be_excluded, $single_camp['id'])==false)
				{
					$key = $single_camp['id'];
					$campaign_transactions[$key] = $single_camp;
					if($c==0 and $campaign_id=='')
					{
						$campaign_id = $key;
					}
					$c++;
				}
			}
		}
	} else {
		if($campaigns_to_be_excluded!='' and strstr($campaigns_to_be_excluded, $campaign_list_array['campaigns']['campaign']['id'])==false)
		{
			if($campaign_list_array['campaigns']['campaign']['id'] != $GLOBALS['master_campaign_id'])
			{
				$key = $campaign_list_array['campaigns']['campaign']['id'];
				$campaign_transactions[$key] = $campaign_list_array['campaigns']['campaign'];
				$campaign_id = $key;
			}
		}
	}
	
	
	foreach($campaign_transactions as $cid=>$single_campaign)
	{
		$cust_transac = json_decode(json_encode(get_all_transactions($cid, $start_date)), true);
		if($cust_transac['@attributes']['status'] == 'success')
		{
			if(isset($cust_transac['transaction'][0]))
			{
				foreach($cust_transac['transaction'] as $single_transaction)
				{
					if($consider_returned_purchases)
					{
						if($single_transaction['amount_number'] != 0)
						{
							$campaign_transactions[$cid]['transactions'][] = $single_transaction;
						}
					} else {
						if($single_transaction['amount_number'] >= 0)
						{
							$campaign_transactions[$cid]['transactions'][] = $single_transaction;
						}
					}
				}
			} else {
				if($consider_returned_purchases)
				{
					if(isset($cust_transac['transaction']['amount_number']) and $cust_transac['transaction']['amount_number'] != 0)
					{
						$campaign_transactions[$cid]['transactions'][] = $cust_transac['transaction'];
					}
				} else {
					if(isset($cust_transac['transaction']['amount_number']) and $cust_transac['transaction']['amount_number'] >= 0)
					{
						$campaign_transactions[$cid]['transactions'][] = $cust_transac['transaction'];
					}
				}
				
			}
		}
	}	
	
	return array($campaign_transactions, $campaign_id);
}

function build_campaign_transactions_array($campaign_transactions, $default_percentage, $contribution_percentage, $start_date_timestamp='', $show_master_campaign=false)
{
	//$campaign_transactions_array = $campaign_transactions;
	$campaign_transactions_array = array();
	$overall_spent = 0;
	$overall_invoice_amount = 0;
	if($start_date_timestamp != '')
	{
		foreach($campaign_transactions as $cid=>$curr_campaign)
		{
			$total_spent = 0;
			$invoice_amount = 0;
			$transactions_rows = '';
			$campaign_transactions_array[$cid] = $curr_campaign;
			$campaign_transactions_array[$cid]['transactions'] = array();
			$campaign_transactions_array[$cid]['customers'] = array();
			
			foreach($curr_campaign['transactions'] as $curr_transaction)
			{
				//echo $curr_transaction['record_timestamp'].'<br />';
				//echo $start_date_timestamp.'<br /><br />';
				if(strtotime($curr_transaction['record_timestamp']) > $start_date_timestamp)
				{
					$campaign_transactions_array[$cid]['transactions'][] = $curr_transaction;
					$customer_code_key = $curr_transaction['code'];
					$campaign_transactions_array[$cid]['customers'][$customer_code_key] = $curr_transaction['customer'];
					
					$amount = $curr_transaction['original_amount'];
					if($show_master_campaign)
					{
						$amount = $curr_transaction['amount_number'];
					}
					
					$utility_field = isset($curr_transaction['custom1']) ? $curr_transaction['custom1'] : $curr_transaction['first_name'];
				
					$total_spent = $total_spent + $curr_transaction['original_amount'];
					$transactions_rows .= '<tr>';
						$transactions_rows .= '<td>'.$curr_transaction['card_number'].'</td>';
						$transactions_rows .= '<td>'.$utility_field.'</td>';
						$transactions_rows .= '<td>$'.$amount.'</td>';
						$transactions_rows .= '<td>'.$curr_transaction['date'].'</td>';
					$transactions_rows .= '</tr>';
				}
			}
			
			$contrib_perc = isset($contribution_percentage[$cid]) ? $contribution_percentage[$cid] : $default_percentage ;
			
			$campaign_transactions_array[$cid]['total_amount_spent'] = $total_spent;
			$campaign_transactions_array[$cid]['invoice_amount'] = ($total_spent * $contrib_perc) / 100;
			$campaign_transactions_array[$cid]['transactions_rows'] = $transactions_rows;
			
			$overall_spent = $overall_spent + $total_spent;
			$overall_invoice_amount = $overall_invoice_amount + $campaign_transactions_array[$cid]['invoice_amount'];
		}
		$campaign_transactions_array['overall_spent'] = $overall_spent;
		$campaign_transactions_array['overall_invoice_amount'] = $overall_invoice_amount;
	} else {
		foreach($campaign_transactions as $cid=>$curr_campaign)
		{
			$total_spent = 0;
			$invoice_amount = 0;
			$transactions_rows = '';
			$campaign_transactions_array[$cid] = $curr_campaign;
			$campaign_transactions_array[$cid]['transactions'] = array();
			$campaign_transactions_array[$cid]['customers'] = array();
			
			foreach($curr_campaign['transactions'] as $curr_transaction)
			{
				$campaign_transactions_array[$cid]['transactions'][] = $curr_transaction;
				$customer_code_key = $curr_transaction['code'];
				$campaign_transactions_array[$cid]['customers'][$customer_code_key] = $curr_transaction['customer'];
				
				$amount = 0;
				
				$utility_field = isset($curr_transaction['custom1']) ? $curr_transaction['custom1'] : $curr_transaction['first_name'];
				
				if(isset($curr_transaction['original_amount']) and !empty($curr_transaction['original_amount']))
				{
					$total_spent = $total_spent + (float)$curr_transaction['original_amount'];
					$amount = (float) $curr_transaction['original_amount'];
					
					
				} elseif($curr_transaction['amount_type'] == '$' and isset($curr_transaction['amount_number'])) {
					$total_spent = $total_spent + (float)$curr_transaction['amount_number'];
					$amount = (float) $curr_transaction['amount_number'];
				}
				
				if($show_master_campaign)
				{
					$amount = $curr_transaction['amount_number'];
				}
				
				$transactions_rows .= '<tr>';
					$transactions_rows .= '<td>'.$curr_transaction['card_number'].'</td>';
					$transactions_rows .= '<td>'.$utility_field.'</td>';
					$transactions_rows .= '<td>$'.$amount.'</td>';
					$transactions_rows .= '<td>'.$curr_transaction['date'].'</td>';
				$transactions_rows .= '</tr>';
			}
			
			$contrib_perc = isset($contribution_percentage[$cid]) ? $contribution_percentage[$cid] : $default_percentage ;
			
			$campaign_transactions_array[$cid]['total_amount_spent'] = $total_spent;
			$campaign_transactions_array[$cid]['invoice_amount'] = ($total_spent * $contrib_perc) / 100;
			$campaign_transactions_array[$cid]['transactions_rows'] = $transactions_rows;
			
			$overall_spent = $overall_spent + $total_spent;
			$overall_invoice_amount = $overall_invoice_amount + $campaign_transactions_array[$cid]['invoice_amount'];
		}
		$campaign_transactions_array['overall_spent'] = $overall_spent;
		$campaign_transactions_array['overall_invoice_amount'] = $overall_invoice_amount;
	}
	
	return $campaign_transactions_array;
}

function account_info_api()
{
	$data = array();
	
	$data['user_id'] 						= $GLOBALS['api_user'];
	$data['user_password'] 					= $GLOBALS['api_key'];
	$data['type'] 							= 'account_info';
	$data['account_id'] 					= $GLOBALS['account_id'];
	
	$result = submit_cURL ($data);
	return new SimpleXMLElement(str_replace('&','&amp;',$result));
}
?>
