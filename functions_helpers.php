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

function get_timezone_array()
{
	$timezone_gmt = array(
		1	=> array('gmt' => '-12:00', 'area' => 'International Date Line West', 'php_timezone' => 'Pacific/Wake'),
		2	=> array('gmt' => '-11:00', 'area' => 'Midway Island Samoa', 'php_timezone' => 'Pacific/Apia'),
		3	=> array('gmt' => '-10:00', 'area' => 'Hawaii', 'php_timezone' => 'Pacific/Honolulu'),
		4	=> array('gmt' => '-09:00', 'area' => 'Alaska', 'php_timezone' => 'America/Anchorage'),
		5	=> array('gmt' => '-08:00', 'area' => 'Pacific Time (US & Canada); Tijuana', 'php_timezone' => 'America/Los_Angeles'),
		6	=> array('gmt' => '-07:00', 'area' => 'Arizona', 'php_timezone' => 'America/Phoenix'),
		7	=> array('gmt' => '-07:00', 'area' => 'Chihuahua, La Paz, Mazatlan', 'php_timezone' => 'America/Chihuahua'),
		8	=> array('gmt' => '-07:00', 'area' => 'Mountain Time (US & Canada)', 'php_timezone' => 'America/Denver'),
		9	=> array('gmt' => '-06:00', 'area' => 'Central America', 'php_timezone' => 'America/Managua'),
		10	=> array('gmt' => '-06:00', 'area' => 'Central Time (US & Canada)', 'php_timezone' => 'America/Chicago'),
		11	=> array('gmt' => '-06:00', 'area' => 'Guadalajara, Mexico City, Monterrey', 'php_timezone' => 'America/Mexico_City'),
		12	=> array('gmt' => '-06:00', 'area' => 'Saskatchewan', 'php_timezone' => 'America/Regina'),
		13	=> array('gmt' => '-05:00', 'area' => 'Bogota, Lime, Quito', 'php_timezone' => 'America/Bogota'),
		14	=> array('gmt' => '-05:00', 'area' => 'Eastern Time (US & Canada)', 'php_timezone' => 'America/New_York'),
		15	=> array('gmt' => '-05:00', 'area' => 'Indiana (East)', 'php_timezone' => 'America/Indiana/Indianapolis'),
		16	=> array('gmt' => '-04:00', 'area' => 'Atlantic Time (Canada)', 'php_timezone' => 'America/Halifax'),
		17	=> array('gmt' => '-04:00', 'area' => 'Caracas, La Paz', 'php_timezone' => 'America/Caracas'),
		18	=> array('gmt' => '-04:00', 'area' => 'Santiago', 'php_timezone' => 'America/Santiago'),
		19	=> array('gmt' => '-03:30', 'area' => 'Newfoundland', 'php_timezone' => 'America/St_Johns'),
		20	=> array('gmt' => '-03:00', 'area' => 'Brasilia', 'php_timezone' => 'America/Sao_Paulo'),
		21	=> array('gmt' => '-03:00', 'area' => 'Buenos Aires, Georgetown', 'php_timezone' => 'America/Argentina/Buenos_Aires'),
		22	=> array('gmt' => '-03:00', 'area' => 'Greenland', 'php_timezone' => 'America/Godthab'),
		23	=> array('gmt' => '-02:00', 'area' => 'Mid-Atlantic', 'php_timezone' => 'America/Noronha'),
		24	=> array('gmt' => '-01:00', 'area' => 'Azores', 'php_timezone' => 'Atlantic/Azores'),
		25	=> array('gmt' => '-01:00', 'area' => 'Cape Verde Is.', 'php_timezone' => 'Atlantic/Cape_Verde'),
		26	=> array('gmt' => 'GMT', 'area' => 'Dublin, Edinburgh, Lisbon, London', 'php_timezone' => 'Europe/London'),
		27	=> array('gmt' => 'GMT', 'area' => 'Casablanca, Monrovia', 'php_timezone' => 'Africa/Casablanca'),
		28	=> array('gmt' => '+01:00', 'area' => 'Amsterdam, Berlin, Bern, Brussels', 'php_timezone' => 'Europe/Berlin'),
		29	=> array('gmt' => '+01:00', 'area' => 'Belgrade, Bratislava, Budapest', 'php_timezone' => 'Europe/Belgrade'),
		30	=> array('gmt' => '+01:00', 'area' => 'Copenhagen, Ljubljana, Madrid', 'php_timezone' => 'Europe/Paris'),
		31	=> array('gmt' => '+01:00', 'area' => 'Paris, Prague, Rome, Stockholm', 'php_timezone' => 'Europe/Berlin'),
		32	=> array('gmt' => '+01:00', 'area' => 'Sarajevo, Skopje, Vienna, Warsaw', 'php_timezone' => 'Europe/Sarajevo'),
		33	=> array('gmt' => '+01:00', 'area' => 'West Central Africa, Zagreb', 'php_timezone' => 'Africa/Lagos'),
		34	=> array('gmt' => '+02:00', 'area' => 'Athens, Bucharest, Cairo', 'php_timezone' => 'Europe/Bucharest'),
		35	=> array('gmt' => '+02:00', 'area' => 'Harare, Helsinki, Istanbul', 'php_timezone' => 'Europe/Helsinki'),
		36	=> array('gmt' => '+02:00', 'area' => 'Jerusalem, Kyiv, Minsk, Pretoria', 'php_timezone' => 'Asia/Jerusalem'),
		37	=> array('gmt' => '+02:00', 'area' => 'Riga, Sofia, Tallinn, Vilnius', 'php_timezone' => 'Europe/Helsinki'),
		38	=> array('gmt' => '+02:00', 'area' => '', 'php_timezone' => 'Europe/Helsinki'),
		39	=> array('gmt' => '+03:00', 'area' => 'Baghdad', 'php_timezone' => 'Asia/Baghdad'),
		40	=> array('gmt' => '+03:00', 'area' => 'Kuwait, Riyadh', 'php_timezone' => 'Asia/Riyadh'),
		41	=> array('gmt' => '+03:00', 'area' => 'Moscow, St. Petersburg, Volgograd', 'php_timezone' => 'Europe/Moscow'),
		42	=> array('gmt' => '+03:00', 'area' => 'Nairobi', 'php_timezone' => 'Africa/Nairobi'),
		43	=> array('gmt' => '+03:30', 'area' => 'Tehran', 'php_timezone' => 'Asia/Tehran'),
		44	=> array('gmt' => '+04:00', 'area' => 'Abu Dhabi, Muscat', 'php_timezone' => 'Asia/Muscat'),
		45	=> array('gmt' => '+04:00', 'area' => 'Baku, Tbilisi, Yerevan', 'php_timezone' => 'Asia/Tbilisi'),
		46	=> array('gmt' => '+04:30', 'area' => 'Kabul', 'php_timezone' => 'Asia/Kabul'),
		47	=> array('gmt' => '+05:00', 'area' => 'Ekaterinburg', 'php_timezone' => 'Asia/Yekaterinburg'),
		48	=> array('gmt' => '+05:00', 'area' => 'Islamabad, Karachi, Tashkent', 'php_timezone' => 'Asia/Karachi'),
		49	=> array('gmt' => '+05:30', 'area' => 'Chennai, Kolkata, Mumbai, New Delhi', 'php_timezone' => 'Asia/Calcutta'),
		50	=> array('gmt' => '+05:45', 'area' => 'Kathmandu', 'php_timezone' => 'Asia/Katmandu'),
		51	=> array('gmt' => '+06:00', 'area' => 'Almaty, Novosibirsk', 'php_timezone' => 'Asia/Novosibirsk'),
		52	=> array('gmt' => '+06:00', 'area' => 'Astana, Dhaka', 'php_timezone' => 'Asia/Dhaka'),
		53	=> array('gmt' => '+06:00', 'area' => 'Sri Jayawardenepura', 'php_timezone' => 'Asia/Calcutta'),
		54	=> array('gmt' => '+06:30', 'area' => 'Rangoon', 'php_timezone' => 'Asia/Rangoon'),
		55	=> array('gmt' => '+07:00', 'area' => 'Bangkok, Hanoi, Jakarta', 'php_timezone' => 'Asia/Bangkok'),
		56	=> array('gmt' => '+07:00', 'area' => 'Krasnoyarsk', 'php_timezone' => 'Asia/Krasnoyarsk'),
		57	=> array('gmt' => '+08:00', 'area' => 'Beijing, Chongging, Hong Kong, Urumgi', 'php_timezone' => 'Asia/Hong_Kong'),
		58	=> array('gmt' => '+08:00', 'area' => 'Irkutsk, Ulaan Bataar', 'php_timezone' => 'Asia/Ulan_Bator'),
		59	=> array('gmt' => '+08:00', 'area' => 'Kuala Lumpur, Singapore', 'php_timezone' => 'Asia/Kuala_Lumpur'),
		60	=> array('gmt' => '+08:00', 'area' => 'Perth', 'php_timezone' => 'Australia/Perth'),
		61	=> array('gmt' => '+08:00', 'area' => 'Taipei', 'php_timezone' => 'Asia/Taipei'),
		62	=> array('gmt' => '+09:00', 'area' => 'Osaka, Sapporo, Tokyo', 'php_timezone' => 'Asia/Tokyo'),
		63	=> array('gmt' => '+09:00', 'area' => 'Seoul', 'php_timezone' => 'Asia/Seoul'),
		64	=> array('gmt' => '+09:00', 'area' => 'Yakutsk', 'php_timezone' => 'Asia/Yakutsk'),
		65	=> array('gmt' => '+09:30', 'area' => 'Adelaide', 'php_timezone' => 'Australia/Adelaide'),
		66	=> array('gmt' => '+09:30', 'area' => 'Darwin', 'php_timezone' => 'Australia/Darwin'),
		67	=> array('gmt' => '+10:00', 'area' => 'Brisbane', 'php_timezone' => 'Australia/Brisbane'),
		68	=> array('gmt' => '+10:00', 'area' => 'Canberra, Melbourne, Sydney', 'php_timezone' => 'Australia/Canberra'),
		69	=> array('gmt' => '+10:00', 'area' => 'Guam, Port Moresby', 'php_timezone' => 'Pacific/Guam'),
		70	=> array('gmt' => '+10:00', 'area' => 'Hobart', 'php_timezone' => 'Australia/Hobart'),
		71	=> array('gmt' => '+10:00', 'area' => 'Vladivostok', 'php_timezone' => 'Asia/Vladivostok'),
		72	=> array('gmt' => '+11:00', 'area' => 'Magadan, Solomon Is., New Caledonia', 'php_timezone' => 'Asia/Magadan'),
		73	=> array('gmt' => '+12:00', 'area' => 'Auckland, Wellington', 'php_timezone' => 'Pacific/Auckland'),
		74	=> array('gmt' => '+12:00', 'area' => 'Fiji, Kamchatka, Marshall Is.', 'php_timezone' => 'Pacific/Fiji'),
		75	=> array('gmt' => '+13:00', 'area' => "Nuku\'alofa", 'php_timezone' => 'Pacific/Tongatapu')
	);
	return $timezone_gmt;
}
?>
