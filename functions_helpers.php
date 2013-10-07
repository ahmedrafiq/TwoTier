<?php
# ======================
# HTML DISPLAY FUNCTIONS
# ----------------------
// HTML helper functions
function editor($content_key, $box_size, $box_height) {

	if ($content_key == 'api_error') {
		$html = $GLOBALS['errors']['api_error'];

	} else {

		$html = '';
		$editor_box_size = ($box_size * 8) + 100;

		$content_to_show = $GLOBALS['content'][$content_key];
		$admin_content_to_show	= $GLOBALS['content'][$content_key];

		//if (isset($GLOBALS['customer_info'])) {

			if (isset($GLOBALS['customer_info']->customer->card_number)) {
				$content_to_show = str_ireplace("#member_id#", 		$GLOBALS['customer_info']->customer->card_number, 	$content_to_show);
			} else {
				$content_to_show = str_ireplace("#member_id#", 		'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->first_name)) {
				$content_to_show = str_ireplace("#first_name#", 	$GLOBALS['customer_info']->customer->first_name, 	$content_to_show);
			} else {
				$content_to_show = str_ireplace("#first_name#", 	'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->last_name)) {
				$content_to_show = str_ireplace("#last_name#", 		$GLOBALS['customer_info']->customer->last_name, 	$content_to_show);
			} else {
				$content_to_show = str_ireplace("#last_name#", 		'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->phone)) {
				$content_to_show = str_ireplace("#phone#", 			$GLOBALS['customer_info']->customer->phone, 			$content_to_show);
			} else {
				$content_to_show = str_ireplace("#phone#", 			'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->email)) {
				$content_to_show = str_ireplace("#email#", 			$GLOBALS['customer_info']->customer->email, 			$content_to_show);
			} else {
				$content_to_show = str_ireplace("#email#", 			'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->street1)) {
				$content_to_show = str_ireplace("#street1#", 		$GLOBALS['customer_info']->customer->street1, 		$content_to_show);
			} else {
				$content_to_show = str_ireplace("#street1#", 		'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->street2)) {
				$content_to_show = str_ireplace("#street2#", 		$GLOBALS['customer_info']->customer->street2, 		$content_to_show);
			} else {
				$content_to_show = str_ireplace("#street2#", 		'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->city)) {
				$content_to_show = str_ireplace("#city#", 			$GLOBALS['customer_info']->customer->city, 			$content_to_show);
			} else {
				$content_to_show = str_ireplace("#city#", 			'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->state)) {
				$content_to_show = str_ireplace("#state#", 			$GLOBALS['customer_info']->customer->state, 			$content_to_show);
			} else {
				$content_to_show = str_ireplace("#state#", 			'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->postal_code)) {
				$content_to_show = str_ireplace("#postal_code#", 	$GLOBALS['customer_info']->customer->postal_code, 	$content_to_show);
			} else {
				$content_to_show = str_ireplace("#postal_code#", 	'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->country)) {
				$content_to_show = str_ireplace("#country#", 		$GLOBALS['customer_info']->customer->country, 		$content_to_show);
			} else {
				$content_to_show = str_ireplace("#country#", 		'', 																$content_to_show);
			}
			if (isset($GLOBALS['customer_info']->customer->custom_date)) {
				$content_to_show = str_ireplace("#date#", 			$GLOBALS['customer_info']->customer->custom_date, 	$content_to_show);
			} else {
				$content_to_show = str_ireplace("#date#", 			'', 																$content_to_show);
			}
			//
			if (isset($GLOBALS['customer_info']->customer->custom_field)) {
				$content_to_show = str_ireplace("#custom_field#", 	$GLOBALS['customer_info']->customer->custom_field, $content_to_show);
				$content_to_show = str_ireplace("#custom_field_1#", 	$GLOBALS['customer_info']->customer->custom_field, $content_to_show);
			} else {
				$content_to_show = str_ireplace("#custom_field#", 		'', 															$content_to_show);
				$content_to_show = str_ireplace("#custom_field_1#", 		'', 															$content_to_show);
			}
			if (!empty($GLOBALS['customer_fields']->account->fields)) {
				foreach ($GLOBALS['customer_fields']->account->fields->field as $ignore => $customer_field_data) {
					$customer_field_name = (string)$customer_field_data->name;
					if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {
						if ((string)$customer_field_data->show != 'N') {
							if (isset($GLOBALS['customer_info']->customer->$customer_field_name->data)) {
								$content_to_show = str_ireplace('#'.$customer_field_name.'#', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data, $content_to_show);
							} else {
								$content_to_show = str_ireplace('#'.$customer_field_name.'#', '', $content_to_show);
							}
						}
					}
				}
			}
		//}

		// check if text editing is turned on:
		if (!empty($GLOBALS['preferences']['edit_text']) && $GLOBALS['preferences']['edit_text'] == 'true') {
			if (verify_admin()) {
				// removed: id="'.$content_key.'" from span tag below:
				$html .= '<div class="inline '.$content_key.'" onMouseOver="show_edit(\''.$content_key.'\', this.innerHTML, \''.$box_size.'\',  \''.$box_height.'\', this, event, \''.$editor_box_size.'px\');">'.$admin_content_to_show.'</div>';
			} else {
				$html .= $content_to_show;
			}
		} else {
			$html .= $content_to_show;
		}
	}

	return $html;
}

function editor_form () {

	$editor_box = '<div id="editor_box">';
	$editor_box .= 	'<form id="editor_form" action="index.php" method="POST" name="editor_form">';
	$editor_box .= 		'<input id="phrase" class="edit_textbox" type="text" name="phrase" value="" size="20" maxlength="255" border="0">';
	$editor_box .= 		'<input id="phrase_key" type="hidden" name="phrase_key" value="">';
	$editor_box .= 		'<input id="element_name" type="hidden" name="element_name" value="">';
	$editor_box .= 		'<input id="phrase_u" type="hidden" name="u" value="'.$GLOBALS['vars']['admin_name'].'">';
	$editor_box .= 		'<input id="phrase_p" type="hidden" name="p" value="'.$GLOBALS['vars']['admin_password'].'">';
	$editor_box .= 		'<input id="phrase_button" type="button" value="Save" class="button up" name="phrase_submit" onClick="submit_phrase_change()" onMouseOver="button_hilite(this);" onMouseOut="button_dim(this);">';
	$editor_box .= 	'</form>';
	$editor_box .= '</div>';

	return $editor_box;
}

function nav_link($where, $which, $selected=false) {
	$output = '';
	if (!$selected) {
		$output .= ' onMouseOver="'.$where.'_hilite(this);"';
		$output .= ' onMouseOut="'.$where.'_dim(this);"';
	}
	$output .= ' onClick="document.menu_form.action.value=\''.$which.'\';';
	if ($which == 'logout') {
		$output .= 			'document.menu_form.user_name.value=\'\';';
		$output .= 			'document.menu_form.user_password.value=\'\';';
	}
	$output .= 				'document.menu_form.submit();"';
	return $output;
}

function common_form_elements() {
	$output = '';
	if (isset($GLOBALS['vars']['user_name'])) 		{ $output .= '<input type="hidden" name="user_name" value="'.$GLOBALS['vars']['user_name'].'">'; }
	if (isset($GLOBALS['vars']['user_password'])) 	{ $output .= '<input type="hidden" name="user_password" value="'.$GLOBALS['vars']['user_password'].'">'; }
	if (isset($GLOBALS['vars']['admin_name'])) 		{ $output .= '<input type="hidden" name="admin_name" value="'.$GLOBALS['vars']['admin_name'].'">'; }
	if (isset($GLOBALS['vars']['admin_password']))	{ $output .= '<input type="hidden" name="admin_password" value="'.$GLOBALS['vars']['admin_password'].'">'; }
	if (isset($GLOBALS['vars']['customer_code']))	{ $output .= '<input type="hidden" name="customer_code" value="'.$GLOBALS['vars']['customer_code'].'">'; }
	if (isset($GLOBALS['vars']['customer_card']))	{ $output .= '<input type="hidden" name="customer_card" value="'.$GLOBALS['vars']['customer_card'].'">'; }
	if (isset($GLOBALS['vars']['debug']))				{ $output .= '<input type="hidden" name="debug" value="'.$GLOBALS['vars']['debug'].'">'; }

	return $output;
}
function Format_Date($date){

	if (!empty($date)) {

		if (isset($GLOBALS['european_dates']) && $GLOBALS['european_dates']) {

			$return_date_array 		= explode('-',	$date);
			$return_date_time_split = explode(' ',	$return_date_array[2]);

			$europeanized_date = $return_date_time_split[0].'-'.$return_date_array[1].'-'.$return_date_array[0];

			if (!empty($return_date_time_split[1])) {
				$europeanized_date .= ' '.$return_date_time_split[1];
			}
			return $europeanized_date;


		} else {
			return $date;
		}

	} else {
		return FALSE;
	}
}
function Format_Number(array $input) {
	if (isset($input['number'])) {

		/*
		 // transform exponential notation to numerical
		if(strpos($input['number'], 'E') !== FALSE) {  // ie: It has an 'E' in it so it's an exponential notation.
			list($significand, $exp) = explode('E', $input['number']);
			list($void, $decimal) = explode('.', "$significand");
			$decimal_len = strlen("$decimal");
			if (strpos($exp, '+') !== FALSE) {  // it's a positive exponential
echo 'positive'.'<br>';
				$exp = str_replace('+', '', "$exp");
				$exp -= $decimal_len;
				$append = str_pad('', $exp, '0');
				$tmp = str_replace('.', '', "$significand");
				$input['number'] = "$tmp" . "$append";
			} elseif (strpos($exp, '-') !== FALSE) {  // it's a negative exponential
echo 'negative'.'<br>';
				$exp = str_replace('-', '', "$exp");
				$exp -= $decimal_len;
				$append = str_pad('', $exp, '0');
				$input['number'] = "$significand" . "$append";
			}
		}
		*/

		if (!empty($input['decimals']) && is_numeric($input['decimals'])) {
			// decimal amount given, and it's a numeric value.
				$decimals = $input['decimals'];

		} else {
			// unprovided decimal amount: figure out its length.
			if (strpos($input['number'], '.') !== FALSE) {
				list($number_left, $number_right) = explode('.', $input['number']);
				$decimals = strlen("$number_right");
			} else {
				$decimals = 0;
			}

		}

		if ($GLOBALS['european_numbers']) {

			// number is output in European Decimal format
			$number_to_return = number_format($input['number'], $decimals, ',', '.');

		} else {
			// number can be output in U.S. Decimal Format
			$number_to_return = number_format($input['number'], $decimals, '.', ',');
		}

		return $number_to_return;

	} else {
		return FALSE;
	}

}
function left_nav_link($which) {
	$output = ' onMouseOver="left_nav_hilite(this);"';
	$output .= ' onMouseOut="left_nav_dim(this);"';
	$output .= ' onClick="document.left_nav_form_'.$which.'.submit();"';
	return $output;


}
function Show_Fader_Javascript ($which, $delay) {
		$code = '
						<script type="text/javascript">
							<!--
								var element_to_fade = document.getElementById(\''.$which.'\');
								var duration = 1000;  /* 1000 millisecond fade = 1 sec */
								var steps = 20;       /* number of opacity intervals   */
								var fullDelay = '.$delay.'*1000; /* delay given in seconds */
								var delay = fullDelay;     /* delay before fading out (in milliseconds) */

								function setOpacity(level) {
									element_to_fade.style.opacity = level;
									element_to_fade.style.MozOpacity = level;
									element_to_fade.style.KhtmlOpacity = level;
									element_to_fade.style.filter = "alpha(opacity=" + (level * 100) + ");";
								}

								function fadeOut() {
									for (var i = 0; i <= 1; i += (1 / steps)) {
										setTimeout("setOpacity(" + (1 - i) + ")", i * duration);
									}
									setTimeout("element_to_fade.style.display = \'none\';", duration);
								}

								setTimeout("fadeOut()", delay);
							// -->
						</script>';
		return $code;
	}
	
function x_week_range($date) 
{
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
    return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next sunday', $start)));
}
//

?>
