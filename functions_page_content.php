<?php

# ========================
# HTML Content functions
# ------------------------
// Page Builder:
function output_HTML_page() {
	Show_HTML_Headers();
	Show_HTML_Top_Bar();
	Show_HTML_Main_Nav();
	if (isset($GLOBALS['customer_info'])) {
		Show_HTML_Customer_Nav();
	}
	Show_HTML_Content_Top();
	Show_HTML_Content();
	Show_HTML_Content_Bottom();
	Show_HTML_Footer();
	Show_HTML_Close();
}
//
// Page Content:
//
function Show_Login() {

	echo '
					<div class="grid_100 content_center">
						<div id="login">
							<form action="index.php" method="POST" name="login_form">
								<input type="hidden" name="action" value="home">';
	if (verify_admin()) {
		echo '
								<input type="hidden" name="admin_name" value="'.$GLOBALS['vars']['admin_name'].'">
								<input type="hidden" name="admin_password" value="'.$GLOBALS['vars']['admin_password'].'">';
	}
	if (isset($_REQUEST['debug'])) {
		echo '
								<input type="hidden" name="debug" value="'.$_REQUEST['debug'].'">';
	}
	echo '
								<table border="0" cellspacing="20" cellpadding="0" align="center" class="login_box">
									<tr>
										<td colspan="2" valign="top" class="header_large">'.editor('login_section_header', 12, 0).'</td>
									</tr>';

	if (!empty($GLOBALS['errors'])) {
		foreach ($GLOBALS['errors'] as $error_key => $error_message) {
			echo '
									<tr>
										<td></td>
										<td class="error">';
										if (!empty($GLOBALS['content'][$error_key])) {
											echo editor($error_key, 30, 2);
										} else {
											echo $error_message;
										}
										echo '</td>
									</tr>';
		}
	}
	echo '
									<tr>
										<td align="right">'.editor('login_section_username', 12, 0).':</td>
										<td align="left"><input class="text_field" type="text" name="user_name" size="24" maxlength="255" border="0" value="" autocomplete="off"></td>
									</tr>
									<tr>
										<td align="right">'.editor('login_section_password', 12, 0).':</td>
										<td align="left"><input class="text_field" type="password" name="user_password" size="24" maxlength="255" border="0" value="" autocomplete="off"></td>
									</tr>
									<tr>
										<td></td>
										<td align="right"><input type="button" value="'.$GLOBALS['hard_coded_content']['submit'].'" class="button up large" name="login_submit"
											onMouseOver="button_hilite(this);"
											onMouseOut="button_dim(this);"
											onClick="this.form.submit();"></td>
									</tr>
								</table>
							</form>
						</div>
					</div>';
}

function Show_New_Account() {

		echo '
					<div class="grid_100">
						<div class="content_left breathing_room full_page">
							<div class="section_header side_padding">'.editor('myinfo_title', 25, 0).'</div>
							<form action="index.php" method="POST" name="new_account_form" class="side_padding">
								<input type="hidden" name="action" value="record_new_account">
								'.common_form_elements().'
								<table cellspacing="0" cellpadding="4" border="0">';

		if (!empty($GLOBALS['errors'])) {
			echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>
									<tr>
										<td colspan="2" class="error">';
			foreach ($GLOBALS['errors'] as $error_code => $error_text) {
										if (!empty($GLOBALS['content'][$error_code])) {
											echo '
											'. editor($error_code, 30, 2).'<br>';
										} else {
											echo '
											'. $error_text.'<br>';
										}
			}
			echo '
										</td>
									</tr>';
		}

		// Separator
		echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';


		if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
			foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {
				if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])){

					// Normal fields:
					if (strpos($field_sorted, 'custom_field_') === false) {

						if ($field_sorted == 'card_number') {

							echo '
												<tr>
													<td>'.editor('myinfo_label_card_number', 20, 0).':</td>';
							$card_number = (!empty($GLOBALS['vars']['card_number'])) ? $GLOBALS['vars']['card_number'] : '' ;
							echo '
													<td>
														<input	class="text_field"
																	type="text"
																	name="card_number"
																	value="'.$card_number.'"
																	autocomplete="off"
																	size="20"
																	maxlength="255"
																	border="0"
																	onChange="new_change(this);">
													</td>
												</tr>';
						}
						elseif ($field_sorted == 'customer_password') {

							// Clerks should not put in the password: That's for customers to do.
							/*
							echo '
											<tr>
												<td>'.editor('myinfo_label_new_password', 20, 0).':</td>
												<td>
													<input	class="text_field"
																type="password"
																name="new_password"
																autocomplete="off"
																value=""
																size="16"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>
											<tr>
												<td>'.editor('myinfo_label_new_password2', 20, 0).':</td>
												<td>
													<input	class="text_field"
																type="password"
																name="new_password2"
																autocomplete="off"
																value=""
																size="16"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							*/
						}
						elseif ($field_sorted == 'first_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
												<td>';
							$first_name = (!empty($GLOBALS['vars']['first_name'])) ? $GLOBALS['vars']['first_name'] : '' ;
							echo '
													<input	class="text_field"
																type="text"
																name="first_name"
																value="'.$first_name.'"
																size="32"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
													</td>
											</tr>';
						}
						elseif ($field_sorted == 'last_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_lastname', 20, 0).':</td>';
							$last_name = (!empty($GLOBALS['vars']['last_name'])) ? $GLOBALS['vars']['last_name'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="last_name"
																value="'.$last_name.'"
																size="40"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'phone') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_phone', 20, 0).':</td>';
							$phone = (!empty($GLOBALS['vars']['phone'])) ? $GLOBALS['vars']['phone'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="phone"
																value="'.$phone.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'email') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_email', 20, 0).':</td>';
							$email = (!empty($GLOBALS['vars']['email'])) ? $GLOBALS['vars']['email'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="email"
																value="'.$email.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'custom_date') {
							// Get previous entries in case of error:
							$year 	= (!empty($GLOBALS['vars']['date_year'])) 	? $GLOBALS['vars']['date_year'] 	: '0000';
							$month 	= (!empty($GLOBALS['vars']['date_month'])) 	? $GLOBALS['vars']['date_month'] : '00';
							$day 		= (!empty($GLOBALS['vars']['date_day'])) 		? $GLOBALS['vars']['date_day'] 	: '00';

							echo '
											<tr>
												<td>'.editor('myinfo_label_date', 20, 0).':</td>
												<td>
													<table cellspacing="0" cellpadding="2" border="0">
														<tr>
															<td align="center">'.editor('myinfo_label_year', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_month', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_day', 12, 0).'</td>
														</tr>
														<tr>
															<td>
																<select name="date_year" size="1">';
																	echo '<option value=""';
																	if ($year == '0000') {
																		echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($y = 1900; $y <= 2050; $y += 1) {
																		if ($y == $year) {
																			echo'
																	<option value="'.$y.'" SELECTED>'.$y.'</option>';
																		} else {
																			echo'
																	<option value="'.$y.'">'.$y.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_month" size="1">';
																	echo '<option value=""';
																	if ($month == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($m = 1; $m <= 12; $m += 1) {
																		if ($m == $month) {
																			echo'
																	<option value="'.$m.'" SELECTED>'.$m.'</option>';
																		} else {
																			echo'
																	<option value="'.$m.'">'.$m.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_day" size="1">';
																	echo '<option value=""';
																	if ($day == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($d = 1; $d <= 31; $d += 1) {
																		if ($d == $day) {
																			echo'
																	<option value="'.$d.'" SELECTED>'.$d.'</option>';
																		} else {
																			echo'
																	<option value="'.$d.'">'.$d.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'address1' || $field_sorted == 'street1') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_address', 20, 0).':</td>';
							$street1 = (!empty($GLOBALS['vars']['street1'])) ? $GLOBALS['vars']['street1'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street1"
																value="'.$street1.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'address2' || $field_sorted == 'street2') {
							echo '
											<tr>
												<td></td>';
							$street2 = (!empty($GLOBALS['vars']['street2'])) ? $GLOBALS['vars']['street2'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street2"
																value="'.$street2.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'city') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_city', 20, 0).':</td>';
							$city = (!empty($GLOBALS['vars']['city'])) ? $GLOBALS['vars']['city'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="city"
																value="'.$city.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'state') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_state', 20, 0).':</td>';
							$state = (!empty($GLOBALS['vars']['state'])) ? $GLOBALS['vars']['state'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="state"
																value="'.$state.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'zip') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_zip', 20, 0).':</td>';
							$postal_code = (!empty($GLOBALS['vars']['postal_code'])) ? $GLOBALS['vars']['postal_code'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="postal_code"
																value="'.$postal_code.'"
																size="10"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'country') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_country', 20, 0).':</td>';
							$country = (!empty($GLOBALS['vars']['country'])) ? $GLOBALS['vars']['country'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="country"
																value="'.$country.'"
																size="15"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
					}
					else { // Custom fields:

						if ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'Text') {

							$text_data = (!empty($GLOBALS['vars'][$field_sorted])) ? $GLOBALS['vars'][$field_sorted] : '' ;

							echo '
										<tr>
											<td>'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="'.$field_sorted.'"
															value="'.$text_data.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
						}
						elseif ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'Date') {

							$year 	= (!empty($GLOBALS['vars'][$field_sorted.'_year'])) ? $GLOBALS['vars'][$field_sorted.'_year'] : '0000' ;
							$month 	= (!empty($GLOBALS['vars'][$field_sorted.'_month'])) ? $GLOBALS['vars'][$field_sorted.'_month'] : '00' ;
							$day 		= (!empty($GLOBALS['vars'][$field_sorted.'_day'])) ? $GLOBALS['vars'][$field_sorted.'_day'] : '00' ;

							echo '
										<tr>
											<td valign="middle">'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>';

							echo '
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="'.$field_sorted.'_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';

						}
						elseif ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'Pick') {

							echo '
										<tr>
											<td valign="middle">'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>';

							$choices_array = $GLOBALS['customer_fields'][$field_sorted]['choices'];

							echo '
												<select name="'.$field_sorted.'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$field_sorted]) && $GLOBALS['vars'][$field_sorted] == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
						}
						elseif ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'List') {
							echo '
										<tr>
											<td valign="top">'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>';

										$choices_array = $GLOBALS['customer_fields'][$field_sorted]['choices'];
										$raw_returned_choices = (!empty($GLOBALS['vars'][$field_sorted])) ? $GLOBALS['vars'][$field_sorted] : array() ;

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$field_sorted.'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}



					}
				}
			}
			// Separator
			echo '
								<tr>
									<td colspan="2"><hr></td>
								</tr>';
		}
		else { // preferences for field order have never been set.

			if (!isset($GLOBALS['customer_fields']['card_number']['show'])
				|| (isset($GLOBALS['customer_fields']['card_number']['show']) && $GLOBALS['customer_fields']['card_number']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_card_number', 20, 0).':</td>';
				$card_number = (!empty($_REQUEST['card_number'])) ? $_REQUEST['card_number'] : '' ;
				echo '
											<td>
												<input	class="text_field"
															type="text"
															name="card_number"
															value="'.$card_number.'"
															autocomplete="off"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											&nbsp;
											<input	type="button"
														value="'.$GLOBALS['hard_coded_content']['submit_quick'].'"
														class="button up large"
														name="myinfo_submit"
														onMouseOver="button_hilite(this);"
														onMouseOut="button_dim(this);"
														onClick="this.form.submit();">
											</td>
										</tr>';
				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}



			// Clerks should not put in the password: That's for customers to do.
			if (false) {
			/*
			echo '
									<tr>
										<td>'.editor('myinfo_label_new_password', 20, 0).':</td>
										<td>
											<input	class="text_field"
														type="password"
														name="new_password"
														autocomplete="off"
														value=""
														size="16"
														maxlength="255"
														border="0"
														onChange="new_change(this);">
										</td>
									</tr>
									<tr>
										<td>'.editor('myinfo_label_new_password2', 20, 0).':</td>
										<td>
											<input	class="text_field"
														type="password"
														name="new_password2"
														autocomplete="off"
														value=""
														size="16"
														maxlength="255"
														border="0"
														onChange="new_change(this);">
										</td>
									</tr>';

			// Separator
			echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';
			*/
			}






			$separator1 = false;
			if (!isset($GLOBALS['customer_fields']['first_name']['show'])
				|| (isset($GLOBALS['customer_fields']['first_name']['show']) && $GLOBALS['customer_fields']['first_name']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td>';
				$first_name = (!empty($_REQUEST['first_name'])) ? $_REQUEST['first_name'] : '' ;
				echo '
												<input	class="text_field"
															type="text"
															name="first_name"
															value="'.$first_name.'"
															size="32"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
												</td>
										</tr>';
				$separator1 = true;
			}
			if (!isset($GLOBALS['customer_fields']['last_name']['show'])
				|| (isset($GLOBALS['customer_fields']['last_name']['show']) && $GLOBALS['customer_fields']['last_name']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>';
				$last_name = (!empty($_REQUEST['last_name'])) ? $_REQUEST['last_name'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="last_name"
															value="'.$last_name.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator1 = true;
			}

			if (!isset($GLOBALS['customer_fields']['phone']['show'])
				|| (isset($GLOBALS['customer_fields']['phone']['show']) && $GLOBALS['customer_fields']['phone']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>';
				$phone = (!empty($_REQUEST['phone'])) ? $_REQUEST['phone'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="phone"
															value="'.$phone.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator1 = true;
			}

			if (!isset($GLOBALS['customer_fields']['email']['show'])
				|| (isset($GLOBALS['customer_fields']['email']['show']) && $GLOBALS['customer_fields']['email']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>';
				$email = (!empty($_REQUEST['email'])) ? $_REQUEST['email'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="email"
															value="'.$email.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator1 = true;
			}

			if (!isset($GLOBALS['customer_fields']['custom_date']['show'])
				|| (isset($GLOBALS['customer_fields']['custom_date']['show']) && $GLOBALS['customer_fields']['custom_date']['show'] != 'N')) {

				// Get previous entries in case of error:
				if (!empty($_REQUEST['custom_date'])) {
					// Breakdown the date:
					$date_parts = explode('-', $_REQUEST['custom_date']);
					$year 		= $date_parts[0];
					$month 		= $date_parts[1];
					$day 			= $date_parts[2];
				} else {
					$year = 0;
					$month = 0;
					$day = 0;
				}
				echo '
										<tr>
											<td>'.editor('myinfo_label_date', 20, 0).':</td>
											<td>
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center">'.editor('myinfo_label_year', 12, 0).'</td>
														<td align="center">'.editor('myinfo_label_month', 12, 0).'</td>
														<td align="center">'.editor('myinfo_label_day', 12, 0).'</td>
													</tr>
													<tr>
														<td>
															<select name="date_year" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="date_month" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="date_day" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
												<input 	type="hidden"
															name="custom_date"
															value="';
															if (!empty($_REQUEST['custom_date'])) {
																echo $_REQUEST['custom_date'];
															} else {
																echo '0000-00-00';
															}
															echo '"
															border="0">
											</td>
										</tr>';
				$separator1 = true;
			}

			if ($separator1) {
				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}
			$separator2 = false;
			if (!isset($GLOBALS['customer_fields']['address1']['show'])
				|| (isset($GLOBALS['customer_fields']['address1']['show']) && $GLOBALS['customer_fields']['address1']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>';
				$street1 = (!empty($_REQUEST['street1'])) ? $_REQUEST['street1'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="street1"
															value="'.$street1.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}

			if (!isset($GLOBALS['customer_fields']['address2']['show'])
				|| (isset($GLOBALS['customer_fields']['address2']['show']) && $GLOBALS['customer_fields']['address2']['show'] != 'N')) {
				echo '
										<tr>
											<td></td>';
				$street2 = (!empty($_REQUEST['street2'])) ? $_REQUEST['street2'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="street2"
															value="'.$street2.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['city']['show'])
				|| (isset($GLOBALS['customer_fields']['city']['show']) && $GLOBALS['customer_fields']['city']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>';
				$city = (!empty($_REQUEST['city'])) ? $_REQUEST['city'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="city"
															value="'.$city.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['state']['show'])
				|| (isset($GLOBALS['customer_fields']['state']['show']) && $GLOBALS['customer_fields']['state']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>';
				$state = (!empty($_REQUEST['state'])) ? $_REQUEST['state'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="state"
															value="'.$state.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['zip']['show'])
				|| (isset($GLOBALS['customer_fields']['zip']['show']) && $GLOBALS['customer_fields']['zip']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>';
				$postal_code = (!empty($_REQUEST['postal_code'])) ? $_REQUEST['postal_code'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="postal_code"
															value="'.$postal_code.'"
															size="10"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['country']['show'])
				|| (isset($GLOBALS['customer_fields']['country']['show']) && $GLOBALS['customer_fields']['country']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>';
				$country = (!empty($_REQUEST['country'])) ? $_REQUEST['country'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="country"
															value="'.$country.'"
															size="15"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}

			if ($separator2) {
				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}


			$separator3 = false;
			if (!empty($GLOBALS['customer_fields'])) {
				foreach ($GLOBALS['customer_fields'] as $ignore => $customer_field_data) {
					$customer_field_name = $customer_field_data['name'];
					if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {

						if ($customer_field_data['show'] != 'N') {

							if ($customer_field_data['type'] == 'Text') {

								$text_data = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : '' ;

								echo '
											<tr>
												<td>'.$customer_field_data['label'].':</td>
												<td>
													<input 	class="text_field"
																type="text"
																name="'.$customer_field_name.'"
																value="'.$text_data.'"
																size="40"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
								$separator3 = true;
							}
							elseif ($customer_field_data['type'] == 'Date') {

								$year 	= (!empty($GLOBALS['vars'][$customer_field_name.'_year'])) ? $GLOBALS['vars'][$customer_field_name.'_year'] : '0000' ;
								$month 	= (!empty($GLOBALS['vars'][$customer_field_name.'_month'])) ? $GLOBALS['vars'][$customer_field_name.'_month'] : '00' ;
								$day 		= (!empty($GLOBALS['vars'][$customer_field_name.'_day'])) ? $GLOBALS['vars'][$customer_field_name.'_day'] : '00' ;

								echo '
											<tr>
												<td valign="middle">'.$customer_field_data['label'].':</td>
												<td>';

								echo '
													<table cellspacing="0" cellpadding="2" border="0">
														<tr>
															<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
															<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
															<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
														</tr>
														<tr>
															<td>
																<select name="'.$customer_field_name.'_year" size="1">';
																	echo '<option value=""';
																	if ($year == '0000') {
																		echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($y = 1900; $y <= 2050; $y += 1) {
																		if ($y == $year) {
																			echo'
																	<option value="'.$y.'" SELECTED>'.$y.'</option>';
																		} else {
																			echo'
																	<option value="'.$y.'">'.$y.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="'.$customer_field_name.'_month" size="1">';
																	echo '<option value=""';
																	if ($month == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($m = 1; $m <= 12; $m += 1) {
																		if ($m == $month) {
																			echo'
																	<option value="'.$m.'" SELECTED>'.$m.'</option>';
																		} else {
																			echo'
																	<option value="'.$m.'">'.$m.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="'.$customer_field_name.'_day" size="1">';
																	echo '<option value=""';
																	if ($day == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($d = 1; $d <= 31; $d += 1) {
																		if ($d == $day) {
																			echo'
																	<option value="'.$d.'" SELECTED>'.$d.'</option>';
																		} else {
																			echo'
																	<option value="'.$d.'">'.$d.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>';
								$separator3 = true;
							}
							elseif ($customer_field_data['type'] == 'Pick') {

								echo '
											<tr>
												<td valign="middle">'.$customer_field_data['label'].':</td>
												<td>';

								$choices_array = $customer_field_data['choices'];

								echo '
													<select name="'.$customer_field_name.'">';
								foreach ($choices_array as $ignore => $choice_item) {
									echo '
														<option value="'.trim($choice_item).'"';
									if (isset($GLOBALS['vars'][$customer_field_name]) && $GLOBALS['vars'][$customer_field_name] == trim($choice_item)) {
										echo ' SELECTED';
									}
									echo '>'.$choice_item.'</option>';
								}
								echo '
													</select>
												</td>
											</tr>';
								$separator3 = true;
							}
							elseif ($customer_field_data['type'] == 'List') {
								echo '
											<tr>
												<td valign="top">'.$customer_field_data['label'].':</td>
												<td>';

											$choices_array = $customer_field_data['choices'];
											$raw_returned_choices = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : array() ;

											foreach ($raw_returned_choices as $returned_item) {
												$returned_choices[] = trim($returned_item);
											}

											foreach ($choices_array as $ignore => $choice_item) {
												echo '
													<input type="checkbox" name="'.$customer_field_name.'[]" value="'.trim($choice_item).'"';

												if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
													echo ' CHECKED';
												}

												echo ' border="0"> '.trim($choice_item).'<br>';
											}
											echo '
												</td>
											</tr>';
								$separator3 = true;
							}
						}
					}
				}
			}
			if ($separator3) {
				// Separator
				echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';
			}
		}
		echo '
									<tr>
										<td></td>
										<td>
											<input	type="button"
														value="'.$GLOBALS['hard_coded_content']['submit_information'].'"
														class="button up large"
														name="myinfo_submit"
														onMouseOver="button_hilite(this);"
														onMouseOut="button_dim(this);"
														onClick="this.form.submit();">
											<br>
											<br>
											<br>
										</td>
									</tr>
								</table>
							</form>
						</div>
					</div>';
}

function Show_Reports() {
	if (!isset($GLOBALS['campaigns_list'])) 
	{
		get_campaign_list ();
	}
	if(!isset($GLOBALS['customer_search_results']))
	{
		find_customer ();
	}
	date_default_timezone_set("Etc/GMT+4");
	//echo date('Y-m-d H:i:s', mktime(date('H'), date('i')-10, date('s'), date('m'), date('d'), date('Y')));
	
	$campaign_list_array = json_decode(json_encode($GLOBALS['campaigns_list']), true);
	$customers_list_array = json_decode(json_encode($GLOBALS['customer_search_results']), true);
	$campaigns_to_be_excluded = $GLOBALS['custom_campaigns_excluded_ids'];
	
	$campaign_customers_array_raw = array();
	$campaign_customers_array = array();
	$campaign_id = isset($_REQUEST['campaign_id']) ? $_REQUEST['campaign_id'] : '';
	$report_page_html = '';
	$message = '';
	$report_cutoff_timestamp = '';
	
	$report_page_html .= '<div class="grid_100">';
		$report_page_html .= '<div class="content_left breathing_room full_page">';
		if($campaign_list_array['@attributes']['status'] == 'success' and isset($campaign_list_array['campaigns'])) 
		{
			$report_page_html .= '<div class="campaigns_vertical_nav left"><ul>';
			
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '' ;
			$sub_action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : '' ;
			$default_contribution_percentage = 5;
			$contribution_percentage = isset($_REQUEST['contribution_percentage']) ? $_REQUEST['contribution_percentage'] : $default_contribution_percentage;
			
			class MyDB extends SQLite3
			{
				function __construct()
				{
					$this->open('mysqlitedb.db');
				}
			}
			$db = new MyDB();
			$db->exec('CREATE TABLE campaign_contribution_percentage (campaign_id varchar(50) NOT NULL, contribution_percentage int(11) DEFAULT NULL, PRIMARY KEY (campaign_id))');		
			if(isset($campaign_list_array['campaigns']['campaign'][0]))
			{
				$c=0;
				foreach($campaign_list_array['campaigns']['campaign'] as $single_camp) 
				{
					if($campaigns_to_be_excluded!='' and strstr($campaigns_to_be_excluded, $single_camp['id'])==false)
					{
						if($single_camp['id'] != $GLOBALS['master_campaign_id'])
						{
							$key = $single_camp['id'];
							if($c==0 and $campaign_id=='')
							{
								$campaign_id = $key;
							}
							$db->exec("INSERT INTO campaign_contribution_percentage (campaign_id, contribution_percentage) VALUES ('".$single_camp['id']."', ".$contribution_percentage.")");
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
						$campaign_id = $key;
						$db->exec("INSERT INTO campaign_contribution_percentage (campaign_id, contribution_percentage) VALUES ('".$campaign_id."', ".$contribution_percentage.")");
					}
				}
			}
			
			if($sub_action == 'set_contribution_percentage')
			{
				$db->exec("UPDATE campaign_contribution_percentage SET contribution_percentage=".$contribution_percentage." WHERE campaign_id = '".$campaign_id."'");
			}
			
			$result = $db->query("SELECT contribution_percentage FROM campaign_contribution_percentage WHERE campaign_id = '".$campaign_id."'");
			$campaign_contribution_percentage = $result->fetchArray(SQLITE3_ASSOC);
			$contribution_percentage = isset($campaign_contribution_percentage['contribution_percentage']) ? $campaign_contribution_percentage['contribution_percentage'] : $contribution_percentage;
			
			$result = $db->query("SELECT * FROM campaign_contribution_percentage");
			$contribution_percentage_array = array();
			while($res = $result->fetchArray(SQLITE3_ASSOC))
			{
				$key = $res['campaign_id'];
				$contribution_percentage_array[$key] = $res['contribution_percentage'];
			}
			
			
			/*list($campaign_customers_array_raw, $campaign_id) = get_campaign_customers($campaign_list_array, $customers_list_array, $campaign_customers_array, $campaign_id);
			$campaign_customers_array = campaign_customers_array_build($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, date('Y-m-d', $report_cutoff_timestamp));*/
			
			list($campaign_customers_array_raw, $campaign_id_new) = get_campaign_transactions($campaign_list_array, $campaign_id, '2013-09-23');
			$campaign_customers_array = build_campaign_transactions_array($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, $report_cutoff_timestamp);
			
			$total_spent = $campaign_customers_array[$campaign_id]['total_amount_spent'];
			$invoice_amount = $campaign_customers_array[$campaign_id]['invoice_amount'];
			$transactions_rows = $campaign_customers_array[$campaign_id]['transactions_rows'];
			
			$query = $db->exec('CREATE TABLE report_time (`id` int(11) NOT NULL, cut_off_time varchar(50) DEFAULT NULL, PRIMARY KEY (`id`))');
			//$query = $db->exec("UPDATE report_time SET cut_off_time = '".date('Y-m-d H:i:s', mktime(date('H'), date('i')-10, date('s'), date('m'), date('d'), date('Y')))."'");
			if($sub_action == 'run_reports')
			{
				$curr_date = date('Y-m-d H:i:s', mktime(date('H'), date('i')-10, date('s'), date('m'), date('d'), date('Y')));
				$curr_date_file_name = date('Y-m-d H i s', strtotime($curr_date));
				
				$result = $db->query("SELECT * FROM report_time");
				$response = $result->fetchArray(SQLITE3_ASSOC);
				if(isset($response['cut_off_time']))
				{
					$report_cutoff_timestamp = strtotime($response['cut_off_time']);
				}
				$campaign_customers_array = build_campaign_transactions_array($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, $report_cutoff_timestamp);
				
				/*$report_page_html .= '<pre>';
				$report_page_html .= print_r($campaign_customers_array, true);
				$report_page_html .= '</pre>';
				echo $report_page_html;
				exit;*/
				
				$query = $db->exec("INSERT INTO report_time (`id`, cut_off_time) VALUES ('1', '".$curr_date."')");
				$query = $db->exec("UPDATE report_time SET cut_off_time = '".$curr_date."'");
				
				
				$heading = "Campaign ID,Campaign Name,Contribution Percentage,Invoice Amount\n";
				$content = '';
				foreach($campaign_customers_array as $cid=>$single_campaign)
				{
					if(is_numeric($cid))
					{
						$content .= $cid.','.$single_campaign['name'].','.$contribution_percentage_array[$cid].','.$single_campaign['invoice_amount']."\n";
					}
				}
				$csvdata = $heading.$content;
				$fname = 'reports/report('.$curr_date_file_name.').csv';
				$fp = fopen($fname,'w');
				fwrite($fp,$csvdata);
				fclose($fp);
				

				foreach($campaign_customers_array as $cid=>$curr_campaign)
				{
					if(is_numeric($cid))
					{
						foreach($curr_campaign['customers'] as $curr_customer)
						{
							//$desc = $curr_campaign['name'].' has invoice amount $'.$curr_campaign['invoice_amount'];
							$desc = $curr_campaign['name'].' -- '.date('Y-m-d h:i a',strtotime($curr_date));
							$res = record_transaction_master_campaign($curr_customer['customer_code'], $curr_campaign['invoice_amount'], $desc, 'N');
							
						}
					}
				}
				
				$message .= '<div class="alert success">Successfully created.</div>';
			}
			$result = $db->query("SELECT * FROM report_time");
			$response = $result->fetchArray(SQLITE3_ASSOC);
			if(isset($response['cut_off_time']))
			{
				$report_cutoff_timestamp = strtotime($response['cut_off_time']);
			}
			/*$campaign_customers_array = campaign_customers_array_build($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, date('Y-m-d', $report_cutoff_timestamp));*/
			$campaign_customers_array = build_campaign_transactions_array($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, $report_cutoff_timestamp);
			
			$total_spent = $campaign_customers_array[$campaign_id]['total_amount_spent'];
			$invoice_amount = $campaign_customers_array[$campaign_id]['invoice_amount'];
			$transactions_rows = $campaign_customers_array[$campaign_id]['transactions_rows'];
			
				if($campaign_id == 'none')
				{
					$active_class = 'class=\'active\'';
				}
				$report_page_html .= '<li '.$active_class.' onclick="document.campaign_customers.action.value=\'reports\';document.campaign_customers.campaign_id.value=\'none\';document.campaign_customers.submit();">Admin</li>';
				if(isset($campaign_list_array['campaigns']['campaign'][0]))
				{
					foreach($campaign_list_array['campaigns']['campaign'] as $single_camp) 
					{
						if($campaigns_to_be_excluded!='' and strstr($campaigns_to_be_excluded, $single_camp['id'])==false)
						{
							if($single_camp['id'] != $GLOBALS['master_campaign_id'])
							{
								$key = $single_camp['id'];	
								$active_class = '';
								if($key == $campaign_id)
								{
									$active_class = 'class=\'active\'';
								}
								$report_page_html .= '<li '.$active_class.' onclick="document.campaign_customers.action.value=\'reports\';document.campaign_customers.campaign_id.value=\''.$key.'\';document.campaign_customers.submit();">'.$single_camp['name'].'<div class="contribution">$'.number_format($campaign_customers_array[$key]['invoice_amount'], 2).' owed</div></li>';
							}
						}
					}
				} else {
					if($campaigns_to_be_excluded!='' and strstr($campaigns_to_be_excluded, $campaign_list_array['campaigns']['campaign']['id'])==false)
					{
						if($campaign_list_array['campaigns']['campaign']['id'] != $GLOBALS['master_campaign_id'])
						{
							$key = $campaign_list_array['campaigns']['campaign']['id'];
							$report_page_html .= '<li class="active" onclick="document.campaign_customers.action.value=\'reports\';document.campaign_customers.campaign_id.value=\''.$key.'\';document.campaign_customers.submit();">'.$campaign_list_array['campaigns']['campaign']['name'].'<div class="contribution">$'.number_format($campaign_customers_array[$key]['invoice_amount'], 2).' owed</div></li>';
						}
					}
				}
				/*if($campaign_id == $GLOBALS['master_campaign_id'])
				{
					$active_class = 'class=\'active\'';
				} else {
					$active_class = '';
				}
				$report_page_html .= '<li '.$active_class.' onclick="document.campaign_customers.action.value=\'reports\';document.campaign_customers.campaign_id.value=\''.$GLOBALS['master_campaign_id'].'\';document.campaign_customers.submit();">Master Campaign</li>';*/
			$report_page_html .= '</ul></div>';
			
			$report_page_html .= '<form action="index.php" method="post" name="campaign_customers">'.common_form_elements().'<input type="hidden" name="campaign_id" value="" border="0"><input type="hidden" name="action" value="none" border="0"></form>';
			
			$report_page_html .= '<div class="vertical_tab_content left">';	
				/*$report_page_html .= '<pre>';
				$report_page_html .= print_r($campaign_customers_array, true);
				$report_page_html .= '</pre>';*/
				
				if($campaign_id == $GLOBALS['master_campaign_id'])
				{
					/*list($campaign_customers_array_raw, $campaign_id) = get_campaign_customers($campaign_list_array, $customers_list_array, $campaign_customers_array, $campaign_id, true);
					$campaign_customers_array = campaign_customers_array_build($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, '', true);*/
					list($campaign_customers_array_raw, $campaign_id_new) = get_campaign_transactions($campaign_list_array, $campaign_id, '2013-09-23', true);
					$campaign_customers_array = build_campaign_transactions_array($campaign_customers_array_raw, $default_contribution_percentage, $contribution_percentage_array, '', true);
					
					$transactions_rows = $campaign_customers_array[$campaign_id]['transactions_rows'];
				}
			
				if($campaign_id == 'none')
				{
					$disable_btn = '';
					if($campaign_customers_array['overall_spent'] == 0 and $campaign_customers_array['overall_invoice_amount'] == 0)
					{
						$disable_btn = 'disabled="disabled"';
					}
					$report_page_html .= $message;
					$report_page_html .= '<center><form action="index.php" method="post" name="run_report_frm">'.common_form_elements().'<input type="hidden" name="campaign_id" value="none"><input type="hidden" name="action" value="reports"><input type="hidden" name="sub_action" value="run_reports"><input type="submit" '.$disable_btn.' value="Run Report"></form></center>';
					if($report_cutoff_timestamp!='')
					{
						$report_page_html .= '<center>Last time successfully processed '.date('Y-m-d H:i:s', $report_cutoff_timestamp).'</center>';
					}
				} else {
					$report_page_html .= '<center><strong>Campaign Name: '.$campaign_customers_array[$campaign_id]['name'].'</strong></center>';
						$report_page_html .= '<div style="font-size:14px;">';
						if($campaign_id != $GLOBALS['master_campaign_id'])
						{
							$report_page_html .= '<center>Transactions since '.date('Y-m-d H:i:s', $report_cutoff_timestamp).'</center>';
							$report_page_html .= '<center>Contribution Percentage: <form action="index.php" method="post" name="contribution_percentage_frm" style="display: inline-block;">'.common_form_elements().'<input type="text" name="contribution_percentage" value="'.$contribution_percentage.'" size="1" style="text-align:center;" />%&nbsp;<input type="hidden" name="campaign_id" value="'.$campaign_id.'" /><input type="hidden" name="action" value="reports" border="0"><input type="hidden" name="sub_action" value="set_contribution_percentage" border="0"><input type="submit" value="Set" /></form></center>';
							$report_page_html .= '<center><strong>Total: </strong>$'.number_format($total_spent, 2).'</center>';
							$report_page_html .= '<center><strong>Week '.date('W').' Invoice Amount: </strong>$'.number_format($invoice_amount, 2).'<center>';
						}
						
						$report_page_html .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin-top:10px;">';
							$report_page_html .= '<tr>';
								$report_page_html .= '<td><strong>Card #</strong></td>';
								$report_page_html .= '<td><strong>Utility ID</strong></td>';
								$report_page_html .= '<td><strong>Orginal Amount</strong></td>';
								$report_page_html .= '<td><strong>Date</strong></td>';
							$report_page_html .= '</tr>';
							
							$report_page_html .= $transactions_rows;
	
						$report_page_html .= '</table>';
						
					$report_page_html .= '</div>';
				}
				
				
			$report_page_html .= '</div>';
			$report_page_html .= '<div class="clear"></div>';
		}
		$report_page_html .= '</div>';
	$report_page_html .= '</div>';
	echo $report_page_html;
}
function Show_Activate_Acount() {

	// Get the list of custom fields.
	if (empty($GLOBALS['customer_fields'])) {
		get_custom_customer_fields ();
	}

		echo '
					<div class="grid_100">
						<div class="content_left breathing_room full_page">
							<div class="section_header side_padding">'.editor('activate_title', 25, 0).'</div>
							<form action="index.php" method="POST" name="activate_account_form" class="side_padding">
								<input type="hidden" name="action" value="record_activate_account">
								'.common_form_elements().'
								<table cellspacing="0" cellpadding="4" border="0">';

		if (!empty($GLOBALS['errors'])) {
			echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>
									<tr>
										<td colspan="2" class="error">';
			foreach ($GLOBALS['errors'] as $error_code => $error_text) {
										if (!empty($GLOBALS['content'][$error_code])) {
											echo '
											'. editor($error_code, 30, 2).'<br>';
										} else {
											echo '
											'. $error_text.'<br>';
										}
			}
			echo '
										</td>
									</tr>';
		}

		// Separator
		echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';

		if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
			$password_field_shown = false;
			$last_separator = false;
			foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {
				if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show']) || $field_sorted == 'customer_password'){

					// Normal fields:
					if (strpos($field_sorted, 'custom_field_') === false) {

						if ($field_sorted == 'card_number') {

							echo '
												<tr>
													<td>'.editor('activate_label_account_code', 20, 0).':</td>';
							$card_number = (!empty($GLOBALS['vars']['card_number'])) ? $GLOBALS['vars']['card_number'] : '' ;
							echo '
													<td>
														<input	class="text_field"
																	type="text"
																	name="card_number"
																	value="'.$card_number.'"
																	autocomplete="off"
																	size="20"
																	maxlength="255"
																	border="0"
																	onChange="new_change(this);">
													</td>
												</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'customer_password') {

							// PASSWORD FIELDS - REQUIRED for the "REGISTER" card function.
							echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';
							echo '
											<tr>
												<td>'.editor('myinfo_label_new_password', 20, 0).':</td>
												<td>
													<input	class="text_field"
																type="password"
																name="new_password"
																autocomplete="off"
																value=""
																size="16"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>
											<tr>
												<td>'.editor('myinfo_label_new_password2', 20, 0).':</td>
												<td>
													<input	class="text_field"
																type="password"
																name="new_password2"
																autocomplete="off"
																value=""
																size="16"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';
							$password_field_shown = true;
							$last_separator = false;
						}
						elseif ($field_sorted == 'first_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
												<td>';
							$first_name = (!empty($GLOBALS['vars']['first_name'])) ? $GLOBALS['vars']['first_name'] : '' ;
							echo '
													<input	class="text_field"
																type="text"
																name="first_name"
																value="'.$first_name.'"
																size="32"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
													</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'last_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_lastname', 20, 0).':</td>';
							$last_name = (!empty($GLOBALS['vars']['last_name'])) ? $GLOBALS['vars']['last_name'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="last_name"
																value="'.$last_name.'"
																size="40"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'phone') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_phone', 20, 0).':</td>';
							$phone = (!empty($GLOBALS['vars']['phone'])) ? $GLOBALS['vars']['phone'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="phone"
																value="'.$phone.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'email') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_email', 20, 0).':</td>';
							$email = (!empty($GLOBALS['vars']['email'])) ? $GLOBALS['vars']['email'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="email"
																value="'.$email.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'custom_date') {
							// Get previous entries in case of error:
							$year 	= (!empty($GLOBALS['vars']['date_year'])) 	? $GLOBALS['vars']['date_year'] 	: '0000';
							$month 	= (!empty($GLOBALS['vars']['date_month'])) 	? $GLOBALS['vars']['date_month'] : '00';
							$day 		= (!empty($GLOBALS['vars']['date_day'])) 		? $GLOBALS['vars']['date_day'] 	: '00';

							echo '
											<tr>
												<td>'.editor('myinfo_label_date', 20, 0).':</td>
												<td>
													<table cellspacing="0" cellpadding="2" border="0">
														<tr>
															<td align="center">'.editor('myinfo_label_year', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_month', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_day', 12, 0).'</td>
														</tr>
														<tr>
															<td>
																<select name="date_year" size="1">';
																	echo '<option value=""';
																	if ($year == '0000') {
																		echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($y = 1900; $y <= 2050; $y += 1) {
																		if ($y == $year) {
																			echo'
																	<option value="'.$y.'" SELECTED>'.$y.'</option>';
																		} else {
																			echo'
																	<option value="'.$y.'">'.$y.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_month" size="1">';
																	echo '<option value=""';
																	if ($month == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($m = 1; $m <= 12; $m += 1) {
																		if ($m == $month) {
																			echo'
																	<option value="'.$m.'" SELECTED>'.$m.'</option>';
																		} else {
																			echo'
																	<option value="'.$m.'">'.$m.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_day" size="1">';
																	echo '<option value=""';
																	if ($day == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($d = 1; $d <= 31; $d += 1) {
																		if ($d == $day) {
																			echo'
																	<option value="'.$d.'" SELECTED>'.$d.'</option>';
																		} else {
																			echo'
																	<option value="'.$d.'">'.$d.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'address1' || $field_sorted == 'street1') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_address', 20, 0).':</td>';
							$street1 = (!empty($GLOBALS['vars']['street1'])) ? $GLOBALS['vars']['street1'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street1"
																value="'.$street1.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'address2' || $field_sorted == 'street2') {
							echo '
											<tr>
												<td></td>';
							$street2 = (!empty($GLOBALS['vars']['street2'])) ? $GLOBALS['vars']['street2'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street2"
																value="'.$street2.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'city') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_city', 20, 0).':</td>';
							$city = (!empty($GLOBALS['vars']['city'])) ? $GLOBALS['vars']['city'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="city"
																value="'.$city.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'state') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_state', 20, 0).':</td>';
							$state = (!empty($GLOBALS['vars']['state'])) ? $GLOBALS['vars']['state'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="state"
																value="'.$state.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'zip') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_zip', 20, 0).':</td>';
							$postal_code = (!empty($GLOBALS['vars']['postal_code'])) ? $GLOBALS['vars']['postal_code'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="postal_code"
																value="'.$postal_code.'"
																size="10"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
						elseif ($field_sorted == 'country') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_country', 20, 0).':</td>';
							$country = (!empty($GLOBALS['vars']['country'])) ? $GLOBALS['vars']['country'] : '' ;
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="country"
																value="'.$country.'"
																size="15"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
							$last_separator = true;
						}
					}
					else { // Custom fields:

						if ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'Text') {

							$text_data = (!empty($GLOBALS['vars'][$field_sorted])) ? $GLOBALS['vars'][$field_sorted] : '' ;

							echo '
										<tr>
											<td>'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="'.$field_sorted.'"
															value="'.$text_data.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
							$last_separator = true;
						}
						elseif ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'Date') {

							$year 	= (!empty($GLOBALS['vars'][$field_sorted.'_year'])) ? $GLOBALS['vars'][$field_sorted.'_year'] : '0000' ;
							$month 	= (!empty($GLOBALS['vars'][$field_sorted.'_month'])) ? $GLOBALS['vars'][$field_sorted.'_month'] : '00' ;
							$day 		= (!empty($GLOBALS['vars'][$field_sorted.'_day'])) ? $GLOBALS['vars'][$field_sorted.'_day'] : '00' ;

							echo '
										<tr>
											<td valign="middle">'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>';

							echo '
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="'.$field_sorted.'_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';
							$last_separator = true;
						}
						elseif ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'Pick') {

							echo '
										<tr>
											<td valign="middle">'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>';

							$choices_array = $GLOBALS['customer_fields'][$field_sorted]['choices'];

							echo '
												<select name="'.$field_sorted.'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$field_sorted]) && $GLOBALS['vars'][$field_sorted] == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
							$last_separator = true;
						}
						elseif ($GLOBALS['customer_fields'][$field_sorted]['type'] == 'List') {
							echo '
										<tr>
											<td valign="top">'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>
											<td>';

										$choices_array = $GLOBALS['customer_fields'][$field_sorted]['choices'];
										$raw_returned_choices = (!empty($GLOBALS['vars'][$field_sorted])) ? $GLOBALS['vars'][$field_sorted] : array() ;

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$field_sorted.'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
							$last_separator = true;
						}
					}
				}
			}
			if (!$password_field_shown) {
				echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';

				// PASSWORD FIELDS - REQUIRED for the "REGISTER" card function.
				echo '
											<tr>
												<td>'.editor('myinfo_label_new_password', 20, 0).':</td>
												<td>
													<input	class="text_field"
																type="password"
																name="new_password"
																autocomplete="off"
																value=""
																size="16"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>
											<tr>
												<td>'.editor('myinfo_label_new_password2', 20, 0).':</td>
												<td>
													<input	class="text_field"
																type="password"
																name="new_password2"
																autocomplete="off"
																value=""
																size="16"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
			// Separator
			if ($last_separator) {
				echo '
									<tr>
										<td colspan="2"><hr></td>
									</tr>';
			}
		}
		else { // preferences for field order have never been set.

			if (!isset($GLOBALS['customer_fields']['card_number']['show'])
				|| (isset($GLOBALS['customer_fields']['card_number']['show']) && $GLOBALS['customer_fields']['card_number']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('activate_label_account_code', 20, 0).':</td>';
				$card_number = (!empty($_REQUEST['card_number'])) ? $_REQUEST['card_number'] : '' ;
				echo '
											<td>
												<input	class="text_field"
															type="text"
															name="card_number"
															value="'.$card_number.'"
															autocomplete="off"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			// Separator
			echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}
			//
			// PASSWORD FIELDS - REQUIRED for Card Registrations
			if (true) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_new_password', 20, 0).':</td>
											<td>
												<input	class="text_field"
															type="password"
															name="new_password"
															autocomplete="off"
															value=""
															size="16"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>
										<tr>
											<td>'.editor('myinfo_label_new_password2', 20, 0).':</td>
											<td>
												<input	class="text_field"
															type="password"
															name="new_password2"
															autocomplete="off"
															value=""
															size="16"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';

				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}
			//
			$separator1 = false;
			if (!isset($GLOBALS['customer_fields']['first_name']['show'])
				|| (isset($GLOBALS['customer_fields']['first_name']['show']) && $GLOBALS['customer_fields']['first_name']['show'] != 'N')) {

			echo '
										<tr>
											<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td>';
			$first_name = (!empty($_REQUEST['first_name'])) ? $_REQUEST['first_name'] : '' ;
			echo '
												<input	class="text_field"
															type="text"
															name="first_name"
															value="'.$first_name.'"
															size="32"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
												</td>
										</tr>';
				$separator1 = true;
			}
			if (!isset($GLOBALS['customer_fields']['last_name']['show'])
				|| (isset($GLOBALS['customer_fields']['last_name']['show']) && $GLOBALS['customer_fields']['last_name']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>';
				$last_name = (!empty($_REQUEST['last_name'])) ? $_REQUEST['last_name'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="last_name"
															value="'.$last_name.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator1 = true;
			}
			if (!isset($GLOBALS['customer_fields']['phone']['show'])
				|| (isset($GLOBALS['customer_fields']['phone']['show']) && $GLOBALS['customer_fields']['phone']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>';
				$phone = (!empty($_REQUEST['phone'])) ? $_REQUEST['phone'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="phone"
															value="'.$phone.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator1 = true;
			}

			if (!isset($GLOBALS['customer_fields']['email']['show'])
				|| (isset($GLOBALS['customer_fields']['email']['show']) && $GLOBALS['customer_fields']['email']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>';
				$email = (!empty($_REQUEST['email'])) ? $_REQUEST['email'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="email"
															value="'.$email.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator1 = true;
			}

			if (!isset($GLOBALS['customer_fields']['custom_date']['show'])
				|| (isset($GLOBALS['customer_fields']['custom_date']['show']) && $GLOBALS['customer_fields']['custom_date']['show'] != 'N')) {


				// Get previous entries in case of error:
				if (!empty($_REQUEST['custom_date'])) {
					// Breakdown the date:
					$date_parts = explode('-', $_REQUEST['custom_date']);
					$year 		= $date_parts[0];
					$month 		= $date_parts[1];
					$day 			= $date_parts[2];
				} else {
					$year = 0;
					$month = 0;
					$day = 0;
				}
				echo '
										<tr>
											<td>'.editor('myinfo_label_date', 20, 0).':</td>
											<td>
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center">'.editor('myinfo_label_year', 12, 0).'</td>
														<td align="center">'.editor('myinfo_label_month', 12, 0).'</td>
														<td align="center">'.editor('myinfo_label_day', 12, 0).'</td>
													</tr>
													<tr>
														<td>
															<select name="date_year" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="date_month" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="date_day" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
												<input 	type="hidden"
															name="custom_date"
															value="'.$GLOBALS['customer_info']->customer->custom_date.'"
															border="0">
											</td>
										</tr>';
				$separator1 = true;
			}

			if ($separator1) {
				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}
			$separator2 = false;
			if (!isset($GLOBALS['customer_fields']['address1']['show'])
				|| (isset($GLOBALS['customer_fields']['address1']['show']) && $GLOBALS['customer_fields']['address1']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>';
				$street1 = (!empty($_REQUEST['street1'])) ? $_REQUEST['street1'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="street1"
															value="'.$street1.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}

			if (!isset($GLOBALS['customer_fields']['address2']['show'])
				|| (isset($GLOBALS['customer_fields']['address2']['show']) && $GLOBALS['customer_fields']['address2']['show'] != 'N')) {
				echo '
										<tr>
											<td></td>';
				$street2 = (!empty($_REQUEST['street2'])) ? $_REQUEST['street2'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="street2"
															value="'.$street2.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['city']['show'])
				|| (isset($GLOBALS['customer_fields']['city']['show']) && $GLOBALS['customer_fields']['city']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>';
			$city = (!empty($_REQUEST['city'])) ? $_REQUEST['city'] : '' ;
			echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="city"
															value="'.$city.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['state']['show'])
				|| (isset($GLOBALS['customer_fields']['state']['show']) && $GLOBALS['customer_fields']['state']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>';
				$state = (!empty($_REQUEST['state'])) ? $_REQUEST['state'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="state"
															value="'.$state.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['zip']['show'])
				|| (isset($GLOBALS['customer_fields']['zip']['show']) && $GLOBALS['customer_fields']['zip']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>';
				$postal_code = (!empty($_REQUEST['postal_code'])) ? $_REQUEST['postal_code'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="postal_code"
															value="'.$postal_code.'"
															size="10"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}
			if (!isset($GLOBALS['customer_fields']['country']['show'])
				|| (isset($GLOBALS['customer_fields']['country']['show']) && $GLOBALS['customer_fields']['country']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>';
				$country = (!empty($_REQUEST['country'])) ? $_REQUEST['country'] : '' ;
				echo '
											<td>
												<input 	class="text_field"
															type="text"
															name="country"
															value="'.$country.'"
															size="15"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
				$separator2 = true;
			}

			if ($separator2) {
				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}
			$separator3 = false;
			if (!empty($GLOBALS['customer_fields'])) {
				foreach ($GLOBALS['customer_fields'] as $ignore => $customer_field_data) {
					$customer_field_name = $customer_field_data['name'];
					if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {

						if ($customer_field_data['show'] != 'N') {

							if ($customer_field_data['type'] == 'Text') {

								$text_data = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : '' ;

								echo '
											<tr>
												<td>'.$customer_field_data['label'].':</td>
												<td>
													<input 	class="text_field"
																type="text"
																name="'.$customer_field_name.'"
																value="'.$text_data.'"
																size="40"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
								$separator3 = true;
							}
							elseif ($customer_field_data['type'] == 'Date') {

								$year 	= (!empty($GLOBALS['vars'][$customer_field_name.'_year'])) ? $GLOBALS['vars'][$customer_field_name.'_year'] : '0000' ;
								$month 	= (!empty($GLOBALS['vars'][$customer_field_name.'_month'])) ? $GLOBALS['vars'][$customer_field_name.'_month'] : '00' ;
								$day 		= (!empty($GLOBALS['vars'][$customer_field_name.'_day'])) ? $GLOBALS['vars'][$customer_field_name.'_day'] : '00' ;

								echo '
											<tr>
												<td valign="middle">'.$customer_field_data['label'].':</td>
												<td>';

								echo '
													<table cellspacing="0" cellpadding="2" border="0">
														<tr>
															<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
															<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
															<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
														</tr>
														<tr>
															<td>
																<select name="'.$customer_field_name.'_year" size="1">';
																	echo '<option value=""';
																	if ($year == '0000') {
																		echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($y = 1900; $y <= 2050; $y += 1) {
																		if ($y == $year) {
																			echo'
																	<option value="'.$y.'" SELECTED>'.$y.'</option>';
																		} else {
																			echo'
																	<option value="'.$y.'">'.$y.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="'.$customer_field_name.'_month" size="1">';
																	echo '<option value=""';
																	if ($month == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($m = 1; $m <= 12; $m += 1) {
																		if ($m == $month) {
																			echo'
																	<option value="'.$m.'" SELECTED>'.$m.'</option>';
																		} else {
																			echo'
																	<option value="'.$m.'">'.$m.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="'.$customer_field_name.'_day" size="1">';
																	echo '<option value=""';
																	if ($day == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($d = 1; $d <= 31; $d += 1) {
																		if ($d == $day) {
																			echo'
																	<option value="'.$d.'" SELECTED>'.$d.'</option>';
																		} else {
																			echo'
																	<option value="'.$d.'">'.$d.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>';
								$separator3 = true;
							}
							elseif ($customer_field_data['type'] == 'Pick') {

								echo '
											<tr>
												<td valign="middle">'.$customer_field_data['label'].':</td>
												<td>';

								$choices_array = $customer_field_data['choices'];

								echo '
													<select name="'.$customer_field_name.'">';
								foreach ($choices_array as $ignore => $choice_item) {
									echo '
														<option value="'.trim($choice_item).'"';
									if (isset($GLOBALS['vars'][$customer_field_name]) && $GLOBALS['vars'][$customer_field_name] == trim($choice_item)) {
										echo ' SELECTED';
									}
									echo '>'.$choice_item.'</option>';
								}
								echo '
													</select>
												</td>
											</tr>';
								$separator3 = true;
							}
							elseif ($customer_field_data['type'] == 'List') {
								echo '
											<tr>
												<td valign="top">'.$customer_field_data['label'].':</td>
												<td>';

											$choices_array = $customer_field_data['choices'];
											$raw_returned_choices = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : array() ;

											foreach ($raw_returned_choices as $returned_item) {
												$returned_choices[] = trim($returned_item);
											}

											foreach ($choices_array as $ignore => $choice_item) {
												echo '
													<input type="checkbox" name="'.$customer_field_name.'[]" value="'.trim($choice_item).'"';

												if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
													echo ' CHECKED';
												}

												echo ' border="0"> '.trim($choice_item).'<br>';
											}
											echo '
												</td>
											</tr>';
								$separator3 = true;
							}
						}
					}
				}
			}
			if ($separator3) {
				// Separator
				echo '
										<tr>
											<td colspan="2"><hr></td>
										</tr>';
			}
		}



		echo '
									<tr>
										<td></td>
										<td>
											<input	type="button"
														value="'.$GLOBALS['hard_coded_content']['save_changes'].'"
														class="button up large"
														name="activate_submit"
														onMouseOver="button_hilite(this);"
														onMouseOut="button_dim(this);"
														onClick="this.form.submit();">
											<br>
											<br>
											<br>
										</td>
									</tr>
								</table>
							</form>
						</div>
					</div>';
}
function Show_New_Account_How() {

		echo '
					<div class="grid_100">
						<div class="content_left breathing_room full_page">
							<div class="section_header side_padding">'.editor('how_to_be_a_member_title', 40, 0).'</div>
							<div class="right">'.editor('how_to_be_a_member_sidebar', 30, 20).'</div>
							<div class="how_to_member side_padding">
								'.editor('how_to_be_a_member_content', 60, 20).'
							</div>
						</div>
					</div>';
}
//
function Show_Admin_Login() {
	echo '
					<div class="grid_100 content_center">
						<div id="login">
							<form action="index.php" method="POST" name="login_form">
								'.common_form_elements().'
								<input type="hidden" name="action" value="admin_logged_in">
								<table border="0" cellspacing="20" cellpadding="0" align="center" class="admin_login_box">
									<tr>
										<td colspan="2" valign="top" class="header_large">'.editor('login_admin_section_header', 12, 0).'</td>
									</tr>';

	if (!empty($GLOBALS['errors'])) {
		foreach ($GLOBALS['errors'] as $error_key => $error_message) {
			echo '
									<tr>
										<td></td>
										<td class="error">';
										if (!empty($GLOBALS['content'][$error_key])) {
											echo editor($error_key, 30, 2);
										} else {
											echo $error_message;
										}
										echo '</td>
									</tr>';
		}
	}
	echo '
									<tr>
										<td align="right">';
										if (!empty($GLOBALS['content']['login_section_admin'])) {
											echo editor('login_section_admin', 12, 0);
										} else {
											echo editor('login_section_username', 12, 0);
										}
										echo ':</td>
										<td align="left"><input class="text_field" type="text" name="admin_name" size="24" maxlength="255" border="0" value=""></td>
									</tr>
									<tr>
										<td align="right">'.editor('login_section_password', 12, 0).':</td>
										<td align="left"><input class="text_field" type="password" name="admin_password" size="24" maxlength="255" border="0" value=""></td>
									</tr>
									<tr>
										<td></td>
										<td align="right"><input type="button" value="'.$GLOBALS['hard_coded_content']['submit'].'" class="button up large" name="login_submit"
											onMouseOver="button_hilite(this);"
											onMouseOut="button_dim(this);"
											onClick="this.form.submit();"></td>
									</tr>
								</table>
							</form>
						</div>
					</div>';
}
function Show_Admin() {

	echo '
					<div class="grid_100 full_page">
						<div class="content_left home_breathing_room">';

	// ==== ACCOUNT NOT PROPERLY CONFIGURED ERROR MESSAGE:
	if (!isset($GLOBALS['customer_fields']) || empty($GLOBALS['customer_fields'])) {
		echo '
						<div class="error content_center big" style="margin-top: 10px;">'.editor('configuration_incorrect', 30, 0).'</div>';
	}

	// ==== SUCCESS MESSAGES
	if (!empty($GLOBALS['success']) && (!empty($GLOBALS['vars']['final_submit']) && $GLOBALS['vars']['final_submit'] == 'yes')) {
		foreach ($GLOBALS['success'] as $success_key => $success_message) {
			echo '
						<div id="'.$success_key.'" class="success centered content_center" style="width: 250px; margin-bottom: 10px;">'.$success_message.'</div>';
			echo Show_Fader_Javascript ($success_key, '2');
		}
	}

	//
	// ==== PAGE FORM DEFINITION
	echo '
							<form action="index.php" method="POST" name="site_preferences_form">
								<input type="hidden" name="action" value="record_site_preferences">
								<input type="hidden" name="fields_tab" value="';
								echo (!empty($GLOBALS['vars']['fields_tab'])) ? $GLOBALS['vars']['fields_tab'] : 'customers';
								echo '">
								<input type="hidden" name="final_submit" value="yes">
								'.common_form_elements();

	// ===== PAGE TITLE
	echo '
								<div class="section_header content_left grid_40 left">'.editor('admin_preferences_header', 30, 0).'</div>';
	// ===== SUBMIT BUTTON
	echo '
								<div class="grid_40 right content_right" style="padding-top: 20px;">
									<input	type="button"
										value="'.$GLOBALS['hard_coded_content']['save_changes'].'"
										class="button up large"
										name="myinfo_submit"
										onMouseOver="button_hilite(this);"
										onMouseOut="button_dim(this);"
										onClick="this.form.submit();">
								</div>';

	echo '
								<div style="clear:both;"></div>';

	// ===== TEXT EDITING CHECKBOX
	if (true) {
	echo'
								<div class="admin_preferences larger">
									'.editor('admin_preferences_text_edit', 30, 0).'
									<input type="checkbox" name="edit_text" value="true" border="0"';
									if (!empty($GLOBALS['vars']['edit_text']) && $GLOBALS['vars']['edit_text'] == 'true') { echo ' CHECKED';} else
									if (!empty($GLOBALS['preferences']['edit_text']) && $GLOBALS['preferences']['edit_text'] == 'true') { echo ' CHECKED';}
									echo '>
								</div>';
	}

	//

	// ===== MENU AND FOOTER ITEMS
	if (true) {
		echo '
								<div class="admin_preferences">

									<table cellspacing="0" cellpadding="0" border="0">
										<tr>
											<td colspan="2" class="larger">'.editor('admin_preferences_public_top_nav', 30, 0).'</td>
											<td width="100">&nbsp;</td>
											<td colspan="2" class="larger">'.editor('admin_preferences_public_footer_nav', 30, 0).'</td>
										</tr>
										<tr>
											<td width="20">&nbsp;</td>
											<td valign="top" align="left" class="big">';

		foreach ($GLOBALS['menus']['public_top_nav'] as $public_top_nav_item) {
			echo '
												<input type="checkbox" name="public_top_nav_item[]" value="'.$public_top_nav_item.'" border="0"';
			if (in_array($public_top_nav_item, $GLOBALS['preferences']['public_top_nav'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('nav_item_'.$public_top_nav_item, 20, 0).'<br>';
		}
		echo '
											</td>
											<td></td>
											<td width="20">&nbsp;</td>
											<td valign="top" align="left" class="big">';
		foreach ($GLOBALS['menus']['public_footer_links'] as $public_footer_nav_item) {
			echo '
												<input type="checkbox" name="public_footer_nav_item[]" value="'.$public_footer_nav_item.'" border="0"';
			if (in_array($public_footer_nav_item, $GLOBALS['preferences']['public_footer_links'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('nav_item_'.$public_footer_nav_item, 20, 0).'<br>';
		}
		echo '
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td colspan="2" class="larger">'.editor('admin_preferences_loggedin_top_nav', 30, 0).'</td>
											<td width="100">&nbsp;</td>
											<td colspan="2" class="larger">'.editor('admin_preferences_loggedin_footer_nav', 30, 0).'</td>
										</tr>
										<tr>
											<td width="20">&nbsp;</td>
											<td valign="top" align="left" class="big">';
		foreach ($GLOBALS['menus']['loggedin_top_nav'] as $loggedin_top_nav_item) {
			echo '
												<input type="checkbox" name="loggedin_top_nav_item[]" value="'.$loggedin_top_nav_item.'" border="0"';
			if (in_array($loggedin_top_nav_item, $GLOBALS['preferences']['loggedin_top_nav'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('nav_item_'.$loggedin_top_nav_item, 20, 0).'<br>';
		}
		echo '
											</td>
											<td></td>
											<td width="20">&nbsp;</td>
											<td valign="top" align="left" class="big">';
		foreach ($GLOBALS['menus']['loggedin_footer_links'] as $loggedin_footer_nav_item) {
			echo '
												<input type="checkbox" name="loggedin_footer_nav_item[]" value="'.$loggedin_footer_nav_item.'" border="0"';
			if (in_array($loggedin_footer_nav_item, $GLOBALS['preferences']['loggedin_footer_links'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('nav_item_'.$loggedin_footer_nav_item, 20, 0).'<br>';
		}
		echo '
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td colspan="2" class="larger">'.editor('admin_preferences_loggedin_customer_top_nav', 30, 0).'</td>
											<td width="100">&nbsp;</td>
											<td colspan="2" class="larger">'.editor('admin_preferences_loggedin_customer_footer_nav', 30, 0).'</td>
										</tr>
										<tr>
											<td width="20">&nbsp;</td>
											<td valign="top" align="left" class="big">';
		foreach ($GLOBALS['menus']['loggedin_customer_top_nav'] as $loggedin_top_nav_item) {
			echo '
												<input type="checkbox" name="loggedin_customer_top_nav_item[]" value="'.$loggedin_top_nav_item.'" border="0"';
			if (in_array($loggedin_top_nav_item, $GLOBALS['preferences']['loggedin_customer_top_nav'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('nav_item_'.$loggedin_top_nav_item, 20, 0).'<br>';
		}
		echo '
											</td>
											<td></td>
											<td width="20">&nbsp;</td>
											<td valign="top" align="left" class="big">';
		foreach ($GLOBALS['menus']['loggedin_customer_footer_links'] as $loggedin_footer_nav_item) {
			echo '
												<input type="checkbox" name="loggedin_customer_footer_nav_item[]" value="'.$loggedin_footer_nav_item.'" border="0"';
			if (in_array($loggedin_footer_nav_item, $GLOBALS['preferences']['loggedin_customer_footer_links'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('nav_item_'.$loggedin_footer_nav_item, 20, 0).'<br>';
		}
		echo '
											</td>
										</tr>
									</table>
								</div>';
	}
	//

	// ===== REDEMPTION TYPE
	if (true) {
		echo '
								<div class="admin_preferences larger">
									<div class="larger" style="margin-bottom: 5px;">'.editor('redeem_type_setting_header', 30, 0).'</div>
									<div class="big" style="margin-left: 20px; padding-bottom: 10px;">';

		// Regular Rewards listing
		echo '
										<input type="checkbox" name="redemption_types[]" value="rewards" border="0"';
			if (in_array('rewards', $GLOBALS['preferences']['redeem_types'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('redeem_type_setting_rewards', 20, 0).'<br>';

		// Amount of Points
		echo '
										<input type="checkbox" name="redemption_types[]" value="points" border="0"';
			if (in_array('points', $GLOBALS['preferences']['redeem_types'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('redeem_type_setting_points', 20, 0).'<br>';

		// Monetary amount
		echo '
										<input type="checkbox" name="redemption_types[]" value="money" border="0"';
			if (in_array('money', $GLOBALS['preferences']['redeem_types'])) { echo ' CHECKED';}
			echo '> ';
											echo editor('redeem_type_setting_money', 20, 0).'<br>';


		echo '
									</div>
								</div>';
	}
	//

	// ===== FIELDS ORDERING

	// --- Load the Javascript:
	if (true) {
		echo '
										<script language="JavaScript" type="text/javascript" src="sorting/core.js"></script>
										<script language="JavaScript" type="text/javascript" src="sorting/events.js"></script>
										<script language="JavaScript" type="text/javascript" src="sorting/css.js"></script>
										<script language="JavaScript" type="text/javascript" src="sorting/coordinates.js"></script>
										<script language="JavaScript" type="text/javascript" src="sorting/drag.js"></script>
										<script language="JavaScript" type="text/javascript" src="sorting/dragsort.js"></script>
										<script language="JavaScript" type="text/javascript" src="sorting/cookies.js"></script>
										<script language="JavaScript" type="text/javascript"><!--
											var dragsort = ToolMan.dragsort();
											var junkdrawer = ToolMan.junkdrawer();

											window.onload = function() {';
		if (!isset($GLOBALS['vars']['fields_tab']) || (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'customers')) {
			echo '
												junkdrawer.restoreListOrder("customer_sorted_fields");
												dragsort.makeListSortable(document.getElementById("customer_sorted_fields"), verticalOnly, saveOrder);';
		}
		else {  // transactions fields to be sorted instead
			echo '
												junkdrawer.restoreListOrder("transaction_sorted_fields");
												dragsort.makeListSortable(document.getElementById("transaction_sorted_fields"), verticalOnly, saveOrder);';
		}
		echo '
											}

											function verticalOnly(item) {
												item.toolManDragGroup.verticalOnly();
											}

											function speak(id, what) {
												var element = document.getElementById(id);
												element.innerHTML = \'Clicked \' + what;
											}

											function saveOrder(item) {
												var group = item.toolManDragGroup;
												var list = group.element.parentNode;
												var id = list.getAttribute("id");
												if (id == null) return;

												group.register(\'dragend\', function() {
													//ToolMan.cookies().set("list-" + id, junkdrawer.serializeList(list), 365)';
		if (!isset($GLOBALS['vars']['fields_tab']) || (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'customers')) {
			echo '
													document.getElementById("customer_fields_order").value = junkdrawer.serializeList(list);';
		}
		else {  // transactions fields to be sorted instead
			echo '
													document.getElementById("transaction_fields_order").value = junkdrawer.serializeList(list);';
		}
		echo '
												});
											}

											//-->
										</script>';
	}
	//

	// --- Left Side: CUSTOMERS field order:
	if (true) {
		echo '
								<div class="admin_preferences half_page left">
									<div class="larger left" style="margin-bottom: 5px;">'.editor('admin_preferences_custom_fields_customer_order', 30, 0).'</div>';
		if (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'transactions') {
			echo '
									<div class="larger right breathing_room" style="margin-bottom: 5px;">
										<input	type="button"
												value="'.$GLOBALS['hard_coded_content']['Edit'].'"
												class="button up"
												onMouseOver="button_hilite(this);"
												onMouseOut="button_dim(this);"
												onClick="this.form.fields_tab.value=\'customers\';this.form.final_submit.value=\'no\';this.form.submit();">
									</div>';
		}
		else {
			echo '
									<br clear="all"><div class="normal">'.editor('admin_preferences_custom_fields_instructions', 40, 3).'</div>';
		}

		// Output the Customers fields:
		echo '
									<br clear="all">';

		if (!isset($GLOBALS['vars']['fields_tab']) || (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'customers')) {
			echo '
									<div>
										<ul id="customer_sorted_fields" class="sort_box">';

			// if preferences set, start with those
			if (!empty($GLOBALS['preferences']['customer_fields_order'])) {

				// itterate through each preference.
				foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {

					if (!empty($GLOBALS['customer_fields'][$field_sorted])) {
						if ($GLOBALS['customer_fields'][$field_sorted]['show'] != 'N') {
							echo '
											<li itemID="'.$field_sorted.'">
												<input type="checkbox" ';
												if (is_array($GLOBALS['preferences']['fields_to_show']) && in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])) {
													echo 'CHECKED ';
												}
												echo 'name="fields_to_show[]" value="'.$field_sorted.'"> '.$GLOBALS['customer_fields'][$field_sorted]['label'].'
											</li>';
						}
					}
				}
			}
			// Also go through each field not already set in preferences.
			if (!empty($GLOBALS['customer_fields'])) {
				foreach ($GLOBALS['customer_fields'] as $customer_field_name => $customer_field_data) {
					if ($customer_field_data['show'] != 'N') {
						if (empty($GLOBALS['preferences']['customer_fields_order'])
							 || (is_array($GLOBALS['preferences']['customer_fields_order'])
								  && !in_array($customer_field_name, $GLOBALS['preferences']['customer_fields_order']))) {
							echo '
											<li itemID="'.$customer_field_name.'">
												<input type="checkbox" ';
												if (is_array($GLOBALS['preferences']['fields_to_show']) && in_array($customer_field_name, $GLOBALS['preferences']['fields_to_show'])) {
													echo 'CHECKED ';
												}
							echo 'name="fields_to_show[]" value="'.$customer_field_name.'"> '.$customer_field_data['label'].'</li>';
						}
					}
				}
			}


			echo '
										</ul>
										<input type="hidden" name="customer_fields_order" id="customer_fields_order" value="';
			if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
				echo implode('|', $GLOBALS['preferences']['customer_fields_order']);
			}
			echo '">
									</div>';
		}
		else { // Not selected for editing
			echo '
									<div class="unselected_field_list">';

			// if preferences set, start with those
			if (!empty($GLOBALS['preferences']['customer_fields_order'])) {

				// itterate through each preference.
				foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {

					if (!empty($GLOBALS['customer_fields'][$field_sorted])) {
						if ($GLOBALS['customer_fields'][$field_sorted]['show'] != 'N') {
							if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])) {
								echo '
										<div class="unselected_field">'.$GLOBALS['customer_fields'][$field_sorted]['label'].'</div>';
							}
							else { // not selected to be shown.
								echo '
										<div class="unselected_field grey">'.$GLOBALS['customer_fields'][$field_sorted]['label'].'</div>';
							}
						}
					}
				}
			}
			// Also go through each field not already set in preferences.
			if (!empty($GLOBALS['customer_fields'])) {
				foreach ($GLOBALS['customer_fields'] as $customer_field_name => $customer_field_data) {
					if ($customer_field_data['show'] != 'N') {
						if (empty($GLOBALS['preferences']['customer_fields_order'])
							 || (is_array($GLOBALS['preferences']['customer_fields_order'])
								  && !in_array($customer_field_name, $GLOBALS['preferences']['customer_fields_order']))) {
							echo '
										<div class="unselected_field grey">'.$customer_field_data['label'].'</div>';
						}
					}
				}
			}
			echo '
									</div>';
		}
		echo '
								</div>';
	}

	//

	// --- Right Side: TRANSACTIONS field order:
	if (true) {
		echo '
								<div class="admin_preferences half_page right">
									<div class="larger left" style="margin-bottom: 5px;">'.editor('admin_preferences_custom_fields_transaction_order', 30, 0).'</div>';
		if (!isset($GLOBALS['vars']['fields_tab']) || (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'customers')) {
			echo '
									<div class="larger right breathing_room" style="margin-bottom: 5px;">
										<input	type="button"
												value="'.$GLOBALS['hard_coded_content']['Edit'].'"
												class="button up"
												onMouseOver="button_hilite(this);"
												onMouseOut="button_dim(this);"
												onClick="this.form.fields_tab.value=\'transactions\';this.form.final_submit.value=\'no\';this.form.submit();">
									</div>';
		}
		else {
			echo '
									<br clear="all"><div class="normal">'.editor('admin_preferences_custom_fields_instructions', 40, 3).'</div>';
		}

		// Output the previously ordered Transaction fields:
		echo '
									<br clear="all">';
		//
		if (!empty($GLOBALS['vars']['fields_tab']) && $GLOBALS['vars']['fields_tab'] == 'transactions') {
			echo '
									<div>
										<ul id="transaction_sorted_fields" class="sort_box">';

			// if preferences set, start with those
			if (!empty($GLOBALS['preferences']['transaction_fields_order'])) {

				// itterate through each preference.
				foreach ($GLOBALS['preferences']['transaction_fields_order'] as $field_sorted) {

					if ($field_sorted == 'amount') {
						echo '
												<li itemID="amount">
													<input type="checkbox" ';
						if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('amount', $GLOBALS['preferences']['transaction_fields_to_show'])) {
							echo 'CHECKED ';
						}
						echo 'name="transaction_fields_to_show[]" value="amount"> '.$GLOBALS['content']['add_amount_label'].'
												</li>';
					}
					elseif ($field_sorted == 'promo_id') {
						echo '
												<li itemID="promo_id">
													<input type="checkbox" ';
						if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('promo_id', $GLOBALS['preferences']['transaction_fields_to_show'])) {
							echo 'CHECKED ';
						}
						echo 'name="transaction_fields_to_show[]" value="promo_id"> '.$GLOBALS['content']['add_promotion_label'].'
												</li>';
					}
					elseif ($field_sorted == 'service_product') {
						echo '
												<li itemID="service_product">
													<input type="checkbox" ';
						if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('service_product', $GLOBALS['preferences']['transaction_fields_to_show'])) {
							echo 'CHECKED ';
						}
						echo 'name="transaction_fields_to_show[]" value="service_product"> '.$GLOBALS['content']['add_buyx_item_label'].'
												</li>';
					}
					elseif ($field_sorted == 'transaction_description') {
						echo '
												<li itemID="transaction_description">
													<input type="checkbox" ';
						if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_to_show'])) {
							echo 'CHECKED ';
						}
						echo 'name="transaction_fields_to_show[]" value="transaction_description"> '.$GLOBALS['content']['transaction_description'].'
												</li>';
					}
					elseif ($field_sorted == 'send_transaction_email') {
						echo '
												<li itemID="send_transaction_email">
													<input type="checkbox" ';
						if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_to_show'])) {
							echo 'CHECKED ';
						}
						echo 'name="transaction_fields_to_show[]" value="send_transaction_email"> '.$GLOBALS['content']['send_email_label'].'
												</li>';
					}
					elseif (!empty($GLOBALS['transaction_fields'][$field_sorted])) {
						if ($GLOBALS['transaction_fields'][$field_sorted]['show'] != 'N') {
							echo '
												<li itemID="'.$field_sorted.'">
													<input type="checkbox" ';
													if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array($field_sorted, $GLOBALS['preferences']['transaction_fields_to_show'])) {
														echo 'CHECKED ';
													}
													echo 'name="transaction_fields_to_show[]" value="'.$field_sorted.'"> '.$GLOBALS['transaction_fields'][$field_sorted]['label'].'
												</li>';
						}
					}
				}
			}

			// Also go through each field not already set in preferences.
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('amount', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
												<li itemID="amount">
													<input type="checkbox" ';
				if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('amount', $GLOBALS['preferences']['transaction_fields_to_show'])) {
					echo ' CHECKED';
				}
				echo 'name="transaction_fields_to_show[]" value="amount"> '.$GLOBALS['content']['add_amount_label'].'</li>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('promo_id', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
												<li itemID="promo_id">
													<input type="checkbox" ';
				if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('promo_id', $GLOBALS['preferences']['transaction_fields_to_show'])) {
					echo ' CHECKED';
				}
				echo 'name="transaction_fields_to_show[]" value="promo_id"> '.$GLOBALS['content']['add_promotion_label'].'</li>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('service_product', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
												<li itemID="service_product">
													<input type="checkbox" ';
				if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('service_product', $GLOBALS['preferences']['transaction_fields_to_show'])) {
					echo ' CHECKED';
				}
				echo 'name="transaction_fields_to_show[]" value="service_product"> '.$GLOBALS['content']['add_buyx_item_label'].'</li>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
												<li itemID="transaction_description">
													<input type="checkbox" ';
				if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_to_show'])) {
					echo ' CHECKED';
				}
				echo 'name="transaction_fields_to_show[]" value="transaction_description"> '.$GLOBALS['content']['transaction_description'].'</li>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
												<li itemID="send_transaction_email">
													<input type="checkbox" ';
				if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_to_show'])) {
					echo ' CHECKED';
				}
				echo 'name="transaction_fields_to_show[]" value="send_transaction_email"> '.$GLOBALS['content']['send_email_label'].'</li>';
			}
			if (!empty($GLOBALS['transaction_fields'])) {
				foreach ($GLOBALS['transaction_fields'] as $transaction_field_name => $transaction_field_data) {
					if ($transaction_field_data['show'] != 'N') {
						if (empty($GLOBALS['preferences']['transaction_fields_order'])
							 || (is_array($GLOBALS['preferences']['transaction_fields_order'])
								  && !in_array($transaction_field_name, $GLOBALS['preferences']['transaction_fields_order']))) {
							echo '
												<li itemID="'.$transaction_field_name.'">
													<input type="checkbox" ';
													if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array($transaction_field_name, $GLOBALS['preferences']['transaction_fields_to_show'])) {
														echo 'CHECKED ';
													}
							echo 'name="transaction_fields_to_show[]" value="'.$transaction_field_name.'"> '.$transaction_field_data['label'].'</li>';
						}
					}
				}
			}


			echo '
											</ul>
											<input type="hidden" name="transaction_fields_order" id="transaction_fields_order" value="';
			if (!empty($GLOBALS['preferences']['transaction_fields_order'])) {
				echo implode('|', $GLOBALS['preferences']['transaction_fields_order']);
			}
			echo '">
										</div>';
		}
		else { // Not selected for editing
			echo '
									<div class="unselected_field_list">';

			// if preferences set, start with those
			if (!empty($GLOBALS['preferences']['transaction_fields_order'])) {

				// itterate through each preference.
				foreach ($GLOBALS['preferences']['transaction_fields_order'] as $field_sorted) {
					if ($field_sorted == 'amount') {
						echo '
										<div class="unselected_field';
						if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
						 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('amount', $GLOBALS['preferences']['transaction_fields_to_show']))) {
							echo ' grey';
						}
						echo '">'.$GLOBALS['content']['add_amount_label'].'</div>';
					}
					elseif ($field_sorted == 'promo_id') {
						echo '
										<div class="unselected_field';
						if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
						 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('promo_id', $GLOBALS['preferences']['transaction_fields_to_show']))) {
							echo ' grey';
						}
						echo '">'.$GLOBALS['content']['add_promotion_label'].'</div>';
					}
					elseif ($field_sorted == 'service_product') {
						echo '
										<div class="unselected_field';
						if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
						 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('service_product', $GLOBALS['preferences']['transaction_fields_to_show']))) {
							echo ' grey';
						}
						echo '">'.$GLOBALS['content']['add_buyx_item_label'].'</div>';
					}
					elseif ($field_sorted == 'transaction_description') {
						echo '
										<div class="unselected_field';
						if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
						 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_to_show']))) {
							echo ' grey';
						}
						echo '">'.$GLOBALS['content']['transaction_description'].'</div>';
					}
					elseif ($field_sorted == 'send_transaction_email') {
						echo '
										<div class="unselected_field';
						if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
						 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_to_show']))) {
							echo ' grey';
						}
						echo '">'.$GLOBALS['content']['send_email_label'].'</div>';
					}
					elseif (!empty($GLOBALS['transaction_fields'][$field_sorted])) {
						if ($GLOBALS['transaction_fields'][$field_sorted]['show'] != 'N') {
							if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array($field_sorted, $GLOBALS['preferences']['transaction_fields_to_show'])) {
								echo '
										<div class="unselected_field">'.$GLOBALS['transaction_fields'][$field_sorted]['label'].'</div>';
							}
							else { // not selected to be shown.
								echo '
										<div class="unselected_field grey">'.$GLOBALS['transaction_fields'][$field_sorted]['label'].'</div>';
							}
						}
					}
				}
			}
			// Also go through each field not already set in preferences.
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('amount', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
										<div class="unselected_field';
				if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
				 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('amount', $GLOBALS['preferences']['transaction_fields_to_show']))) {
					echo ' grey';
				}
				echo '">'.$GLOBALS['content']['add_amount_label'].'</div>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('promo_id', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
										<div class="unselected_field';
				if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
				 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('promo_id', $GLOBALS['preferences']['transaction_fields_to_show']))) {
					echo ' grey';
				}
				echo '">'.$GLOBALS['content']['add_promotion_label'].'</div>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('service_product', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
										<div class="unselected_field';
				if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
				 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('service_product', $GLOBALS['preferences']['transaction_fields_to_show']))) {
					echo ' grey';
				}
				echo '">'.$GLOBALS['content']['add_buyx_item_label'].'</div>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
										<div class="unselected_field';
				if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
				 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_to_show']))) {
					echo ' grey';
				}
				echo '">'.$GLOBALS['content']['transaction_description'].'</div>';
			}
			if (empty($GLOBALS['preferences']['transaction_fields_order'])
			|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_order']))) {
				echo '
										<div class="unselected_field';
				if (empty($GLOBALS['preferences']['transaction_fields_to_show'])
				 ||(is_array($GLOBALS['preferences']['transaction_fields_to_show']) && !in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_to_show']))) {
					echo ' grey';
				}
				echo '">'.$GLOBALS['content']['send_email_label'].'</div>';
			}
			if (!empty($GLOBALS['transaction_fields'])) {
				foreach ($GLOBALS['transaction_fields'] as $transaction_field_name => $transaction_field_data) {
					if ($transaction_field_data['show'] != 'N') {
						if (empty($GLOBALS['preferences']['transaction_fields_order'])
							 || (is_array($GLOBALS['preferences']['transaction_fields_order'])
								  && !in_array($transaction_field_name, $GLOBALS['preferences']['transaction_fields_order']))) {
							echo '
										<div class="unselected_field grey">'.$transaction_field_data['label'].'</div>';
						}
					}
				}
			}
			echo '
									</div>';
		}
		echo '
								</div>';
	}

	//

	echo '<br clear="all">';

	// ===== EMAIL ADDRESS FOR NOTIFICATIONS
	if (true) {
		echo'
								<div class="admin_preferences larger">
									<div class="larger" style="margin-bottom: 5px;">'.editor('admin_preferences_email_notification', 30, 0).'</div>
									<div style="margin-left: 20px; padding-bottom: 10px;"><input class="text_field" type="text" name="notification_email" size="40" maxlength="255" border="0" value="';
		if (!empty($GLOBALS['vars']['notification_email'])) {
			echo $GLOBALS['vars']['notification_email'];
		} else if (!empty($GLOBALS['preferences']['notification_email'])) {
			echo $GLOBALS['preferences']['notification_email'];
		}
		echo '"></div>
								</div>';
	}
	//

	// ===== SUBMIT FORM BUTTON
	echo '
								<div class="content_center" style="padding-top: 10px;">
									<input	type="button"
										value="'.$GLOBALS['hard_coded_content']['save_changes'].'"
										class="button up large"
										name="myinfo_submit"
										onMouseOver="button_hilite(this);"
										onMouseOut="button_dim(this);"
										onClick="this.form.submit();">
								</div>';

	echo '
							</form>';
	echo '
							<br>
							<br>
							<br>
							<br>';

	echo '
						</div>
					</div>';
}

//
function Show_Program_List() {

	//include the localization file:
	include_once('localization_data.php');

	$GLOBALS['campaign_counter'] = 0;
	$GLOBALS['buyx_counter'] = 0;

	if (!isset($GLOBALS['campaigns_list'])) {
		get_campaign_list ();
	}

	echo '
					<div class="grid_33 right">
						<div class="balance_box_header">';
	if (!empty($GLOBALS['campaigns_list']->campaigns->campaign) && sizeof($GLOBALS['campaigns_list']->campaigns->campaign > 1)) {
		echo editor('balance_box_header_plural', 20, 0);
	} else {
		echo editor('balance_box_header_singular', 20, 0);
	}
	echo '
						</div>';

	echo '
						<div class="balance_box">';

	if ($GLOBALS['campaigns_list']) {

			// Assign the campaign data of the customer to an array:
			$customer_campaigns_array = array ();
			if (!empty($GLOBALS['customer_info']->campaigns->campaign)) {
				foreach ($GLOBALS['customer_info']->campaigns->campaign as $discard => $customer_campaign_info) {
					$customer_campaigns_array[(string)$customer_campaign_info->id] = $customer_campaign_info;
				}
			}

			if (isset($GLOBALS['vars']['which_campaign_to_show'])) {
				// do nothing. all good.
			}
			elseif (!empty($GLOBALS['vars']['which_campaign'])) {
				$GLOBALS['vars']['which_campaign_to_show'] = $GLOBALS['vars']['which_campaign'];
			}

			if (!empty($GLOBALS['campaigns_list']->campaigns->campaign)) {
				foreach ($GLOBALS['campaigns_list']->campaigns->campaign as $discard => $campaign_info ) {

					// Skip campaigns not listed in the preferences:
					// Disabled for Clerks Hotsite: The campaigns shown when a customer is looked up should be determined
					// by the permissions of the clerk who logged in.
					//if (in_array((string)$campaign_info->id, $GLOBALS['preferences']['campaigns_to_show'])) {

						// If a campaign has not been assigned yet, choose the first one:
						if (empty($GLOBALS['vars']['which_campaign_to_show'])) {
							$GLOBALS['vars']['which_campaign_to_show'] = (string)$campaign_info->id;
						}

					// Skip the GiftCard and Earn-per-event campaigns on redeem tab - they have no rewards:
					//if ($GLOBALS['vars']['what_to_show'] == 'redeem' && ($campaign_info->type == 'giftcard' || $campaign_info->type == 'earned')) {
						// skip this campaign

					//} else {
						// Show this campaign
						if ($campaign_info->type == 'buyx') {

							$sorted_buyx_items = array();
							$sorted_buyx_items_itterator = 0;

							if (!empty($customer_campaigns_array[(string)$campaign_info->id]->balances->item)) {
								$balance_output = '
								<table id="buyx_balance_table" cellspacing="0" cellpadding="0" border="0">';

								foreach ($customer_campaigns_array[(string)$campaign_info->id]->balances->item as $discard => $item_info) {
									if (!empty($sorted_buyx_items[(string)$item_info->name])) {
										$sorted_buyx_items[strtolower((string)$item_info->name).$sorted_buyx_items_itterator] = $item_info;
									} else {
										$sorted_buyx_items[(string)$item_info->name] = $item_info;
									}
									$sorted_buyx_items_itterator++;
								}
								ksort($sorted_buyx_items);
								foreach ($sorted_buyx_items as $discard => $item_info) {
									if ($item_info->balance != 0) {
										$balance_output .= '
										<tr>
											<td align="right" valign="bottom" class="sidenav_list_item">'.$item_info->name.':</td>
											<td width="5"></td>
											<td align="right" valign="top" class="super_large">'.$item_info->balance.'</td>
										<tr>';

										//$balance_output .= '<div class="sidenav_list_item"><span class="super_large">'.$item_info->balance.'</span> '.$item_info->name.'</div>';
										$GLOBALS['buyx_counter'] ++;
									}
									$buyx_balances["{$item_info->name}"] = "{$item_info->balance}";
								}
								$balance_output .= '
								</table>';

							} else {
								$balance_output = editor('nav_item_add', 20, 0);
							}

						}
						elseif ($campaign_info->type == 'giftcard' || $campaign_info->type == 'earned' ) {

							$converted_amount = str_replace(",", ".", (string)$customer_campaigns_array[(string)$campaign_info->id]->balance);

							if (isset($customer_campaigns_array[(string)$campaign_info->id]->balance)) {
								$balance_output = $GLOBALS['currency_data'][(string)$customer_campaigns_array[(string)$campaign_info->id]->currency]['glyph'];
								if ($GLOBALS['european_numbers']) {
									$balance_output .= '<span class="super_large">'.number_format((float)$converted_amount, 2, ',', '.').'</span>';
								}
								else { // US Style numbers:
									$balance_output .= '<span class="super_large">'.number_format((float)$converted_amount, 2, '.', ',').'</span>';
								}
							} else {
								$balance_output = editor('nav_item_add', 20, 0);
							}
						}
						elseif ($campaign_info->type == 'points') {
							if (isset($customer_campaigns_array[(string)$campaign_info->id]->balance)) {
								$balance_output = '<span class="super_large">'.$customer_campaigns_array[(string)$campaign_info->id]->balance.'</span> ';
								$balance_output .= (intval($customer_campaigns_array[(string)$campaign_info->id]->balance) == 1) ? editor('label_point', 20, 0) : editor('label_points', 20, 0);
							} else {
								$balance_output = editor('nav_item_add', 20, 0);
							}
						}
						elseif ($campaign_info->type == 'events') {
							if (isset($customer_campaigns_array[(string)$campaign_info->id]->balance)) {
								$balance_output = '<span class="super_large">'.$customer_campaigns_array[(string)$campaign_info->id]->balance.'</span> ';
								$balance_output .= (intval($customer_campaigns_array[(string)$campaign_info->id]->balance) == 1) ? editor('label_event', 20, 0) : editor('label_events', 20, 0);
							} else {
								$balance_output = editor('nav_item_add', 20, 0);
							}
						}

						// Adjust name to make up when "id" and "name" were the same:
						$name_to_show = (!empty($campaign_info->name)) ? $campaign_info->name : $campaign_info->id;

						if ($campaign_info->id == $GLOBALS['vars']['which_campaign_to_show']) {
							$GLOBALS['current_campaign_type'] = $campaign_info->type;
							if ($GLOBALS['current_campaign_type'] == 'buyx') {
								if (isset($buyx_balances)) {
									$GLOBALS['current_campaign_balances'] = $buyx_balances;
								} else {
									$GLOBALS['current_campaign_balances'] = array();
								}
							} else {
								if (!empty($customer_campaigns_array[(string)$campaign_info->id]->balance)) {
									$GLOBALS['current_campaign_balance'] = $customer_campaigns_array[(string)$campaign_info->id]->balance;
								} else {
									$GLOBALS['current_campaign_balance'] = 0;
								}
							}
							if ($GLOBALS['campaign_counter'] == 0) {
								echo '
									<div class="content_left breathing_room left_nav_selected_top">';
							} else {
								echo '
									<div class="content_left breathing_room left_nav_selected">';
							}
							echo '
										<div class="xtra_large sidenav_title">'.$name_to_show.'</div>
										<div class="content_right large">'.$balance_output.'</div>
									</div>';
						} else {
							if ($GLOBALS['vars']['what_to_show'] == 'home') {
								$where_to_go = 'balance';
							} elseif ($GLOBALS['vars']['what_to_show'] == 'add') {
								$where_to_go = 'selected_customer';
							} elseif ($GLOBALS['vars']['what_to_show'] == 'confirm_redeem' || $GLOBALS['vars']['what_to_show'] == 'redeem_result' ) {
								$where_to_go = 'redeem';
							} else {
								$where_to_go = $GLOBALS['vars']['what_to_show'];
							}
							//$where_to_go = ($GLOBALS['vars']['what_to_show'] == 'home') ? 'balance' : $GLOBALS['vars']['what_to_show'];
							//if ($where_to_go == 'confirm_redeem'
							//	 || $where_to_go == 'redeem_result' ) {
							//	$where_to_go = 'redeem';
							//}
							echo '
									<form action="index.php" method="POST" name="left_nav_form_'.$GLOBALS['campaign_counter'].'">
										<input type="hidden" name="action" value="'.$where_to_go.'">
										<input type="hidden" name="which_campaign" value="'.$campaign_info->id.'">
										'.common_form_elements().'
										<div class="content_left breathing_room left_nav_normal" '.left_nav_link($GLOBALS['campaign_counter']).'>
											<div class="xtra_large sidenav_title">'.$name_to_show.'</div>
											<div class="content_right large">'.$balance_output.'</div>
										</div>
									</form>';
						}
						$GLOBALS['campaign_counter']++;
					//}
					//}
				}
			} else {
				echo '
								<div class="content_left breathing_room left_nav_selected">
									<div class="xtra_large">'.editor('balance_label_no_campaign', 25, 0).'</div>
									<div class="content_right large">'.editor('balance_msg_no_balance', 15, 0).'</div>
								</div>';
			}
	}
	else { // No campaigns active
		echo '
							<div class="content_left breathing_room">
								<div class="xtra_large">'.editor('balance_msg_no_balance', 25, 0).'</div>
							</div>';
	}
	echo '
						</div>';
	echo '
						<div class="grid_33 left_rule filler"></div>';
	echo '
					</div>';

}
//
function Show_Home() {

		# ==============================
		# Customer Search
		# ==============================

		echo '
					<div class="grid_100 content_center">
						<div id="login">
							<div class="content_center home_breathing_room">';

		if (!empty($GLOBALS['success'])) {
			foreach ($GLOBALS['success'] as $success_key => $success_message) {
				echo '
								<div id="'.$success_key.'" class="success centered" style="width: 250px; margin-bottom: 10px;">'.$success_message.'</div>';
				echo Show_Fader_Javascript ($success_key, '2');
			}
		}

		echo '
								<form method="POST">
									<input type="hidden" name="action" value="add">
								'.common_form_elements().'
									<table border="0" cellspacing="20" cellpadding="0" align="center" class="login_box">
										<tr>
											<td class="section_header content_center">'.editor('welcome_header', 30, 0).'</td>
										</tr>';

		if (!empty($GLOBALS['errors'])) {
			foreach ($GLOBALS['errors'] as $error_key => $error_message) {
				echo '
										<tr>
											<td class="error centered" style="margin-bottom: 10px;">';
											if (!empty($GLOBALS['content'][$error_key])) {
												echo editor($error_key, 30, 2);
											} else {
												echo $error_message;
											}
											echo '</td>
										</tr>';
			}
		}
		echo '
										<tr>
											<td>
												<input	id="find_customer_textbox"
															class="text_field"
															type="text"
															name="customer_card"
															value=""
															autocomplete="off"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);"
															style="margin-bottom: 15px;">
												<script type="text/javascript">
															var add_box = document.getElementById("find_customer_textbox");
															add_box.focus();
														</script>
												<br>
												<input type="button" value="'.$GLOBALS['hard_coded_content']['submit_find'].'" class="button up large" name="find_submit"
														onMouseOver="button_hilite(this);"
														onMouseOut="button_dim(this);"
														onClick="this.form.submit();">
											</td>
										</tr>
									</table>
								</form>
							</div>
						</div>
					</div>';



}
function Show_MyInfo_Summary() {

	// RELOAD the customer's information
	if (!isset($GLOBALS['customer_info'])) {
		get_customer_info();
	}

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		$GLOBALS['vars']['which_campaign_to_show'] = '';
		Show_Program_List();

		# ==============================
		# Rules Content
		# ==============================
		$content_height = $GLOBALS['campaign_counter'] * 90;
		if ($GLOBALS['buyx_counter'] > 1) {
			$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
		}
		echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room">
							<div class="section_header content_center">'.editor('myinfo_title_updated', 25, 0).'</div>';

		echo '
							<form action="index.php" method="POST" name="myinfo_form">
								<input type="hidden" name="action" value="add">
								'.common_form_elements().'
								<table cellspacing="0" cellpadding="4" border="0" align="center">';

		if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
			foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {
				if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])) {

					// Normal fields:
					if (strpos($field_sorted, 'custom_field_') === false || $field_sorted == 'custom_field_1') {

						if ($field_sorted == 'first_name') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->first_name.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'last_name') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->last_name.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'phone') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->phone.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'email') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->email.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'custom_date') {
							// Breakdown the date:
							$date_parts = explode('-', $GLOBALS['customer_info']->customer->custom_date);
							$year 		= $date_parts[0];
							$month 		= $date_parts[1];
							$day 			= $date_parts[2];

							echo '
										<tr>
											<td>'.editor('myinfo_label_date', 20, 0).':</td>
											<td class="address_confirm">';
											if ($year == '0000' && $month == '00' && $day=='00') {
												echo '';
											} else {
												if ($GLOBALS['european_dates']) {
													echo $day.'/'.$month;
												} else {
													echo $month.'/'.$day;
												}
												if ($year != '0000') { echo '/'.$year; }
											}
											echo '</td>
										</tr>';
						}
						elseif ($field_sorted == 'address1' || $field_sorted == 'street1') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->street1.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'address2' || $field_sorted == 'street2') {
							if (!empty($GLOBALS['customer_info']->customer->street2)) {
								echo '
										<tr>
											<td></td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->street2.'</td>
										</tr>';
							}
						}
						elseif ($field_sorted == 'city') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->city.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'state') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->state.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'zip') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->postal_code.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'country') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->country.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'custom_field_1' ) {
							echo '
										<tr>';
							//echo '<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>';
							echo '<td>'.$GLOBALS['customer_fields'][$field_sorted]['label'].':</td>';
							echo '
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->custom_field.'</td>
										</tr>';
						}

					}
					else { // Custom fields:
						if ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Text') {
							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->data.'</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Date') {
							// Breakdown the date:
							$date_parts_raw = explode(' ', (string)$GLOBALS['customer_info']->customer->$field_sorted->data);
							$date_parts = explode('-', $date_parts_raw[0]);
							$year 		= $date_parts[0];
							$month 		= $date_parts[1];
							$day 			= $date_parts[2];

							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">';
											if ($year == '0000' && $month == '00' && $day=='00') {
												echo '';
											} else {
												if ($GLOBALS['european_dates']) {
													echo $day.'/'.$month;
												} else {
													echo $month.'/'.$day;
												}
												if ($year != '0000') { echo '/'.$year; }
											}
											echo '</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Pick') {
							echo '
										<tr>
											<td valign="middle">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->$field_sorted->data.'</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">';

										$choices_array = $GLOBALS['customer_info']->customer->$field_sorted->choices;
										$raw_selected_array = (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) ? explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->data) : array();
										$raw_returned_choices = (!empty($GLOBALS['vars'][$field_sorted])) ? $GLOBALS['vars'][$field_sorted] : array() ;

										foreach ($raw_selected_array as $selected_item) {
											$selected_array[] = trim($selected_item);
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($selected_array as $ignore => $custom_item) {
											echo '
												'.trim($custom_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}
					}
				}
			}
		}
		else {  // preferences for field order have never been set

			if (!isset($GLOBALS['customer_normal_fields']['first_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['first_name']['show']) && $GLOBALS['customer_normal_fields']['first_name']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->first_name.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['last_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['last_name']['show']) && $GLOBALS['customer_normal_fields']['last_name']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->last_name.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['phone']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['phone']['show']) && $GLOBALS['customer_normal_fields']['phone']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->phone.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['email']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['email']['show']) && $GLOBALS['customer_normal_fields']['email']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->email.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['custom_date']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['custom_date']['show']) && $GLOBALS['customer_normal_fields']['custom_date']['show'] != 'N')) {

				// Breakdown the date:
				$date_parts = explode('-', $GLOBALS['customer_info']->customer->custom_date);
				$year 		= $date_parts[0];
				$month 		= $date_parts[1];
				$day 			= $date_parts[2];

				echo '
										<tr>
											<td>'.editor('myinfo_label_date', 20, 0).':</td>
											<td class="address_confirm">';
											if ($year == '0000' && $month == '00' && $day=='00') {
												echo '';
											} else {
												if ($GLOBALS['european_dates']) {
													echo $day.'/'.$month;
												} else {
													echo $month.'/'.$day;
												}
												if ($year != '0000') { echo '/'.$year; }
											}
											echo '</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['address1']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address1']['show']) && $GLOBALS['customer_normal_fields']['address1']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->street1.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['address2']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address2']['show']) && $GLOBALS['customer_normal_fields']['address2']['show'] != 'N')) {
									if (!empty($GLOBALS['customer_info']->customer->street2)) {
										echo '
										<tr>
											<td></td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->street2.'</td>
										</tr>';
									}
			}
			if (!isset($GLOBALS['customer_normal_fields']['city']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['city']['show']) && $GLOBALS['customer_normal_fields']['city']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->city.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['state']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['state']['show']) && $GLOBALS['customer_normal_fields']['state']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->state.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['zip']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['zip']['show']) && $GLOBALS['customer_normal_fields']['zip']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->postal_code.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['country']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['country']['show']) && $GLOBALS['customer_normal_fields']['country']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->country.'</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_fields']['customer_field_1']['show'])
				|| (isset($GLOBALS['customer_fields']['customer_field_1']['show']) && $GLOBALS['customer_fields']['customer_field_1']['show'] != 'N')) {
				echo '
										<tr>
											<td>'.$GLOBALS['customer_fields']['custom_field_1']['label'].':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->custom_field.'</td>
										</tr>';
			}
			//

			foreach ($GLOBALS['customer_fields'] as $ignore => $customer_field_data) {
				$customer_field_name = (string)$customer_field_data['name'];
				if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {

					if ($customer_field_data['show'] != 'N') {

						if ($customer_field_data['type'] == 'Text') {
							echo '
										<tr>
											<td>'.$customer_field_data['label'].':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->$customer_field_name->data.'</td>
										</tr>';
						}
						elseif ($customer_field_data['type'] == 'Date') {
							echo '
										<tr>
											<td valign="middle">'.$customer_field_data['label'].':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->$customer_field_name->data.'</td>
										</tr>';

						}
						elseif ($customer_field_data['type'] == 'Pick') {
							echo '
										<tr>
											<td valign="middle">'.$customer_field_data['label'].':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->$customer_field_name->data.'</td>
										</tr>';
						}
						elseif ($customer_field_data['type'] == 'List') {
							echo '
										<tr>
											<td valign="top">'.$customer_field_data['label'].':</td>
											<td class="address_confirm">';

										$choices_array = $customer_field_data['choices'];
										$raw_selected_array = (!empty($GLOBALS['customer_info']->customer->$customer_field_name->data)) ? explode(',', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data) : array();
										$raw_returned_choices = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : array() ;

										foreach ($raw_selected_array as $selected_item) {
											$selected_array[] = trim($selected_item);
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($selected_array as $ignore => $custom_item) {
											echo '
												'.trim($custom_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}
					}
				}
			}
		}

		echo '
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2" align="center">
											</td>
										</td>
									</tr>

								</table>
								<div class="content_center" style="margin-bottom: 20px;">
									<input type="button" value="'.$GLOBALS['hard_coded_content']['OK'].'" class="button up large" name="myinfo_OK"
														onMouseOver="button_hilite(this);"
														onMouseOut="button_dim(this);"
														onClick="document.myinfo_form.submit();">
								</div>
							</form>
						</div>
					</div>';

	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">'.$GLOBALS['content']['myinfo_error_access_info'].'</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}

function Show_MyInfo() {

	// Get the customer's information
	if (empty($GLOBALS['customer_info']->customer->code)) {
		get_customer_info();
	}

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		$GLOBALS['vars']['which_campaign_to_show'] = 'none';
		Show_Program_List();

		# ==============================
		# Rules Content
		# ==============================
		$content_height = $GLOBALS['campaign_counter'] * 90;
		if ($GLOBALS['buyx_counter'] > 1) {
			$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
		}
		echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room">
							<div class="section_header content_center">'.editor('myinfo_title', 25, 0).'</div>';

		echo '
							<form action="index.php" method="POST" name="myinfo_form">
								<input type="hidden" name="action" value="record_myinfo">
								'.common_form_elements();

		if (!empty($GLOBALS['errors'])) {
			foreach ($GLOBALS['errors'] as $error_key => $error_message) {
				echo '
										<div  class="error" style="margin-bottom: 10px;">';
											if (!empty($GLOBALS['content'][$error_key])) {
												echo editor($error_key, 30, 2);
											} else {
												echo $error_message;
											}
											echo '
										</div>';
			}
		}
		//
		echo '
								<table cellspacing="0" cellpadding="4" border="0" class="form_table" align="center">';

		if (!empty($GLOBALS['preferences']['customer_fields_order'])) {

			foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {
				if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])) {

					// Normal fields:
					if (strpos($field_sorted, 'custom_field_') === false || $field_sorted == 'custom_field_1') {

						if ($field_sorted == 'card_number') {

							echo '
												<tr>
													<td>'.editor('activate_label_account_code', 20, 0).':</td>
													<td>';
							if (!empty($GLOBALS['vars']['card_number'])) {
								$card_number = $GLOBALS['vars']['card_number'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->card_number)) {
								$card_number = (string)$GLOBALS['customer_info']->customer->card_number;
							}
							else {
								$card_number = '';
							}
							echo '
													<input	class="text_field"
																type="text"
																name="card_number"
																value="'.$card_number.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
													</td>
												</tr>';
						}
						elseif ($field_sorted == 'customer_password') {
							// Clerk should not be able to enter a customer's password
							/*
							echo '
										<tr>
											<td colspan="2">
												<table border="0" cellspacing="8" cellpadding="0" class="callout_table">';
							if (!empty($GLOBALS['errors']['myinfo_error_passwords_no_match'])) {
								echo '
													<tr>
														<td colspan="2">class="error">'.editor('myinfo_error_passwords_no_match', 30, 0).'</td>
													</tr>';
							}
							echo '
													<tr>
														<td>'.editor('myinfo_label_change_password', 20, 0).':</td>
														<td>
															<input 	class="text_field"
																		autocomplete="off"
																		type="password"
																		name="new_password"
																		value=""
																		size="16"
																		maxlength="255"
																		border="0"
																		onChange="new_change(this);">
														</td>
													</tr>
													<tr>
														<td>'.editor('myinfo_label_change_password2', 20, 0).':</td>
														<td>

															<input 	class="text_field"
																		autocomplete="off"
																		type="password"
																		name="new_password2"
																		value=""
																		size="16"
																		maxlength="255"
																		border="0"
																		onChange="new_change(this);">
														</td>
													</tr>
												</table>
											</td>
										</tr>';
							*/
						}
						elseif ($field_sorted == 'first_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
												<td>';
							if (!empty($GLOBALS['vars']['first_name'])) {
								$first_name = $GLOBALS['vars']['first_name'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->first_name)) {
								$first_name = (string)$GLOBALS['customer_info']->customer->first_name;
							}
							else {
								$first_name = '';
							}
							echo '
													<input	class="text_field"
																type="text"
																name="first_name"
																value="'.$first_name.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
													</td>
											</tr>';
						}
						elseif ($field_sorted == 'last_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_lastname', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['last_name'])) {
								$last_name = $GLOBALS['vars']['last_name'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->last_name)) {
								$last_name = (string)$GLOBALS['customer_info']->customer->last_name;
							}
							else {
								$last_name = '';
							}
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="last_name"
																value="'.$last_name.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'phone') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_phone', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['phone'])) {
								$phone = $GLOBALS['vars']['phone'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->phone)) {
								$phone = (string)$GLOBALS['customer_info']->customer->phone;
							}
							else {
								$phone = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="phone"
																value="'.$phone.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'email') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_email', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['email'])) {
								$email = $GLOBALS['vars']['email'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->email)) {
								$email = (string)$GLOBALS['customer_info']->customer->email;
							}
							else {
								$email = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="email"
																value="'.$email.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'custom_date') {

							if (!empty($GLOBALS['customer_info']->customer->custom_date)) {
								$date_array = explode ('-', (string)$GLOBALS['customer_info']->customer->custom_date);
							}

							if (!empty($GLOBALS['vars']['date_year'])) {
								$year = $GLOBALS['vars']['date_year'];
							}
							elseif (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}

							if (!empty($GLOBALS['vars']['date_month'])) {
								$month = $GLOBALS['vars']['date_month'];
							}
							elseif (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}

							if (!empty($GLOBALS['vars']['date_day'])) {
								$day = $GLOBALS['vars']['date_day'];
							}
							elseif (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							}

							echo '
											<tr>
												<td>'.editor('myinfo_label_date', 20, 0).':</td>
												<td>
													<table cellspacing="0" cellpadding="2" border="0">
														<tr>
															<td align="center">'.editor('myinfo_label_year', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_month', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_day', 12, 0).'</td>
														</tr>
														<tr>
															<td>
																<select name="date_year" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																	echo '<option value=""';
																	if ($year == '0000') {
																		echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($y = 1900; $y <= 2050; $y += 1) {
																		if ($y == $year) {
																			echo'
																	<option value="'.$y.'" SELECTED>'.$y.'</option>';
																		} else {
																			echo'
																	<option value="'.$y.'">'.$y.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_month" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																	echo '<option value=""';
																	if ($month == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($m = 1; $m <= 12; $m += 1) {
																		if ($m == $month) {
																			echo'
																	<option value="'.$m.'" SELECTED>'.$m.'</option>';
																		} else {
																			echo'
																	<option value="'.$m.'">'.$m.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_day" size="1">';
																	echo '<option value=""';
																	if ($day == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($d = 1; $d <= 31; $d += 1) {
																		if ($d == $day) {
																			echo'
																	<option value="'.$d.'" SELECTED>'.$d.'</option>';
																		} else {
																			echo'
																	<option value="'.$d.'">'.$d.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'address1'  || $field_sorted == 'street1') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_address', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['street1'])) {
								$street1 = $GLOBALS['vars']['street1'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->street1)) {
								$street1 = (string)$GLOBALS['customer_info']->customer->street1;
							}
							else {
								$street1 = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street1"
																value="'.$street1.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'address2' || $field_sorted == 'street2') {
							echo '
											<tr>
												<td></td>';

							if (!empty($GLOBALS['vars']['street2'])) {
								$street2 = $GLOBALS['vars']['street2'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->street2)) {
								$street2 = (string)$GLOBALS['customer_info']->customer->street2;
							}
							else {
								$street2 = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street2"
																value="'.$street2.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'city') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_city', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['city'])) {
								$city = $GLOBALS['vars']['city'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->city)) {
								$city = (string)$GLOBALS['customer_info']->customer->city;
							}
							else {
								$city = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="city"
																value="'.$city.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'state') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_state', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['state'])) {
								$state = $GLOBALS['vars']['state'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->state)) {
								$state = (string)$GLOBALS['customer_info']->customer->state;
							}
							else {
								$state = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="state"
																value="'.$state.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'zip') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_zip', 20, 0).':</td>';
							if (!empty($GLOBALS['vars']['postal_code'])) {
								$postal_code = $GLOBALS['vars']['postal_code'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->postal_code)) {
								$postal_code = (string)$GLOBALS['customer_info']->customer->postal_code;
							}
							else {
								$postal_code = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="postal_code"
																value="'.$postal_code.'"
																size="10"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'country') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_country', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['country'])) {
								$country = $GLOBALS['vars']['country'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->country)) {
								$country = (string)$GLOBALS['customer_info']->customer->country;
							}
							else {
								$country = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="country"
																value="'.$country.'"
																size="15"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'custom_field_1') {

							// get label:
							foreach ($GLOBALS['customer_fields'] as $field_key => $field_data) {
								if ($field_data['name'] == 'custom_field_1') {
									$which_field_name_label = (string)$field_data['label'];
								}
							}

							if (!empty($which_field_name_label)) {

								echo '
												<tr>
													<td>'.$which_field_name_label.':</td>';

								if (!empty($GLOBALS['vars']['custom_field_1'])) {
									$custom_field = $GLOBALS['vars']['custom_field_1'];
								}
								elseif (!empty($GLOBALS['customer_info']->customer->custom_field)) {
									$custom_field = $GLOBALS['customer_info']->customer->custom_field;
								}
								else {
									$custom_field = '';
								}

								echo '
													<td>
														<input 	class="text_field"
																	type="text"
																	name="custom_field_1"
																	value="'.$custom_field.'"
																	size="30"
																	maxlength="255"
																	border="0"
																	onChange="new_change(this);">
													</td>
												</tr>';
							}
						}
					}
					else { // Custom fields:

						if ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Text') {

							if (!empty($GLOBALS['vars'][$field_sorted])) {
								$text_data = $GLOBALS['vars'][$field_sorted];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) {
								$text_data = (string)$GLOBALS['customer_info']->customer->$field_sorted->data;
							}
							else {
								$text_data = '';
							}

							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="'.$field_sorted.'"
															value="'.$text_data.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Date') {

							if (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) {
								$date_array = explode ('-', (string)$GLOBALS['customer_info']->customer->$field_sorted->data);
							}

							if (!empty($GLOBALS['vars'][$field_sorted.'_year'])) {
								$year = $GLOBALS['vars'][$field_sorted.'_year'];
							}
							elseif (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}

							if (!empty($GLOBALS['vars'][$field_sorted.'_month'])) {
								$month = $GLOBALS['vars'][$field_sorted.'_month'];
							}
							elseif (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}

							if (!empty($GLOBALS['vars'][$field_sorted.'_day'])) {
								$day = $GLOBALS['vars'][$field_sorted.'_day'];
							}
							elseif (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							}

							echo '
										<tr>
											<td valign="middle">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>';

							echo '
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="'.$field_sorted.'_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';

						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Pick') {

							echo '
										<tr>
											<td valign="middle">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>';

							$choices_array = $GLOBALS['customer_info']->customer->$field_sorted->choices;

							echo '
												<select name="'.$field_sorted.'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$field_sorted]) && $GLOBALS['vars'][$field_sorted] == trim($choice_item)) {
									echo ' SELECTED';
								}
								elseif (isset($GLOBALS['customer_info']->customer->$field_sorted->data) && (string)$GLOBALS['customer_info']->customer->$field_sorted->data == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>';

										$choices_array = $GLOBALS['customer_info']->customer->$field_sorted->choices;

										if (!empty($GLOBALS['vars'][$field_sorted])) {
											$raw_returned_choices = $GLOBALS['vars'][$field_sorted];
										}
										elseif (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) {

											$raw_returned_choices = explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->data);
										}
										else {
											$raw_returned_choices = array();
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$field_sorted.'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}



					}
				}
			}
		}
		else {  // preferences for field order have never been set.

			if (!isset($GLOBALS['customer_normal_fields']['card_number']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['card_number']['show']) && $GLOBALS['customer_normal_fields']['card_number']['show'] != 'N')) {
				echo '
										<tr>
											<td class="form_divider">'.editor('activate_label_account_code', 20, 0).':</td>
											<td class="form_divider">
												<input	class="text_field"
															type="text"
															name="first_name"
															value="'.$GLOBALS['customer_info']->customer->card_number.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['first_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['first_name']['show']) && $GLOBALS['customer_normal_fields']['first_name']['show'] != 'N')) {
				echo '
										<tr>
											<td class="form_divider">'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td class="form_divider">
												<input	class="text_field"
															type="text"
															name="first_name"
															value="'.$GLOBALS['customer_info']->customer->first_name.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['last_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['last_name']['show']) && $GLOBALS['customer_normal_fields']['last_name']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="last_name"
															value="'.$GLOBALS['customer_info']->customer->last_name.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['customer_password']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['customer_password']['show']) && $GLOBALS['customer_normal_fields']['customer_password']['show'] != 'N')) {

				// Clerks should not be able to change customers' password:
				/*
				echo '
										<tr>
											<td colspan="2">
												<table border="0" cellspacing="8" cellpadding="0" class="callout_table">';
				if (!empty($GLOBALS['errors']['myinfo_error_passwords_no_match'])) {
					echo '
													<tr>
														<td colspan="2">class="error">'.editor('myinfo_error_passwords_no_match', 30, 0).'</td>
													</tr>';
				}
				echo '
													<tr>
														<td>'.editor('myinfo_label_change_password', 20, 0).':</td>
														<td>
															<input 	class="text_field"
																		autocomplete="off"
																		type="password"
																		name="new_password"
																		value=""
																		size="16"
																		maxlength="255"
																		border="0"
																		onChange="new_change(this);">
														</td>
													</tr>
													<tr>
														<td>'.editor('myinfo_label_change_password2', 20, 0).':</td>
														<td>

															<input 	class="text_field"
																		autocomplete="off"
																		type="password"
																		name="new_password2"
																		value=""
																		size="16"
																		maxlength="255"
																		border="0"
																		onChange="new_change(this);">
														</td>
													</tr>
												</table>
											</td>
										</tr>';
				*/
			}
			if (!isset($GLOBALS['customer_normal_fields']['phone']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['phone']['show']) && $GLOBALS['customer_normal_fields']['phone']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="phone"
															value="'.$GLOBALS['customer_info']->customer->phone.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['email']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['email']['show']) && $GLOBALS['customer_normal_fields']['email']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="email"
															value="'.$GLOBALS['customer_info']->customer->email.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['custom_date']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['custom_date']['show']) && $GLOBALS['customer_normal_fields']['custom_date']['show'] != 'N')) {

							if (!empty($GLOBALS['customer_info']->customer->custom_date)) {
								$date_array = explode ('-', (string)$GLOBALS['customer_info']->customer->custom_date);
							}

							if (!empty($GLOBALS['vars']['date_year'])) {
								$year = $GLOBALS['vars']['date_year'];
							}
							elseif (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}

							if (!empty($GLOBALS['vars']['date_month'])) {
								$month = $GLOBALS['vars']['date_month'];
							}
							elseif (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}

							if (!empty($GLOBALS['vars']['date_day'])) {
								$day = $GLOBALS['vars']['date_day'];
							}
							elseif (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							}

				echo '
										<tr>
											<td>'.editor('myinfo_label_date', 20, 0).':</td>
											<td>
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="date_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="date_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="date_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['address1']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address1']['show']) && $GLOBALS['customer_normal_fields']['address1']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="street1"
															value="'.$GLOBALS['customer_info']->customer->street1.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['address2']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address2']['show']) && $GLOBALS['customer_normal_fields']['address2']['show'] != 'N')) {

				echo '
										<tr>
											<td></td>
											<td>
												<input 	class="text_field"
															type="text"
															name="street2"
															value="'.$GLOBALS['customer_info']->customer->street2.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['city']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['city']['show']) && $GLOBALS['customer_normal_fields']['city']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="city"
															value="'.$GLOBALS['customer_info']->customer->city.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['state']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['state']['show']) && $GLOBALS['customer_normal_fields']['state']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="state"
															value="'.$GLOBALS['customer_info']->customer->state.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['zip']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['zip']['show']) && $GLOBALS['customer_normal_fields']['zip']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="postal_code"
															value="'.$GLOBALS['customer_info']->customer->postal_code.'"
															size="10"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			if (!isset($GLOBALS['customer_normal_fields']['country']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['country']['show']) && $GLOBALS['customer_normal_fields']['country']['show'] != 'N')) {

				echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="country"
															value="'.$GLOBALS['customer_info']->customer->country.'"
															size="15"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}


			//
			foreach ($GLOBALS['customer_fields'] as $ignore => $customer_field_data) {
				$customer_field_name = (string)$customer_field_data['name'];
				if (strpos($customer_field_name, 'custom_field_') !== false ) {

					if ((string)$customer_field_data['show'] != 'N') {

						if ((string)$customer_field_data['type'] == 'Text') {
							echo '
										<tr>
											<td>'.(string)$customer_field_data['label'].':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="'.$customer_field_name.'"
															value="';
															if ($customer_field_name == 'custom_field_1') {
																echo (string)$GLOBALS['customer_info']->customer->custom_field;
															}
															else {
																echo (string)$GLOBALS['customer_info']->customer->$customer_field_name->data;
															}
															echo '"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
						}
						elseif ((string)$customer_field_data['type'] == 'Date') {
							echo '
										<tr>
											<td valign="middle">'.(string)$customer_field_data['label'].':</td>
											<td>';

							// Breakdown the date:
							$date_parts = explode('-', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data);
							$year 		= (!empty($date_parts[0])) ? $date_parts[0] : '0000';
							$month 		= (!empty($date_parts[1])) ? $date_parts[1] : '00';
							$day 			= (!empty($date_parts[2])) ? $date_parts[2] : '00';

							echo '
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="'.$customer_field_name.'_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$customer_field_name.'_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$customer_field_name.'_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';

						}
						elseif ((string)$customer_field_data['type'] == 'Pick') {
							echo '
										<tr>
											<td valign="middle">'.(string)$customer_field_data['label'].':</td>
											<td>';

							//$choices_array = explode(',', (string)$customer_field_data['choices']);
							$choices_array = $customer_field_data['choices'];

							echo '
												<select name="'.$customer_field_name.'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$customer_field_name]) && $GLOBALS['vars'][$customer_field_name] == trim($choice_item)) {
									echo ' SELECTED';
								}
								elseif (!empty($GLOBALS['customer_info']->customer->$customer_field_name->data) && $GLOBALS['customer_info']->customer->$customer_field_name->data == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
						}
						elseif ((string)$customer_field_data['type'] == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$customer_field_data['label'].':</td>
											<td>';

										//$choices_array = explode(',', (string)$customer_field_data->choices->choice);
										$choices_array = $customer_field_data['choices'];

										$raw_selected_array = (!empty($GLOBALS['customer_info']->customer->$customer_field_name->data)) ? explode(',', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data) : array();
										$raw_returned_choices = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : array() ;

										foreach ($raw_selected_array as $selected_item) {
											$selected_array[] = trim($selected_item);
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$customer_field_name.'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}
											elseif (!empty($selected_array) && in_array($choice_item, $selected_array)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}
					}
				}
			}
		}

		echo '
									<tr>
										<td></td>
									</tr>
									<tr>
										<td colspan="2" height="1" class="form_divider"></td>
									</tr>
									<tr>
										<td colspan="2" align="center" >
											<input type="button" value="'.$GLOBALS['hard_coded_content']['save_changes'].'" class="button up large" name="myinfo_submit"
													onMouseOver="button_hilite(this);"
													onMouseOut="button_dim(this);"
													onClick="this.form.submit();">
										</td>
									</tr>
								</table>
							</form>
						</div>
					</div>';

	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">'.$GLOBALS['content']['myinfo_error_access_info'].'</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}

function Show_Balances() {

	// Get the customer's information
	get_customer_info();

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		Show_Program_List();

		# ==================
		# Transactions List
		# ==================

		if (!empty($GLOBALS['vars']['which_campaign_to_show'])) {
			$content_height = $GLOBALS['campaign_counter'] * 90;
			if ($GLOBALS['buyx_counter'] > 1) {
				$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
			}
			echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room">
							<div class="section_header content_center">'.editor('balance_heading_trans_history', 15, 0).'</div>';

			// Show the "$which_campaign_to_show" transactions
			$GLOBALS['vars']['code'] = $GLOBALS['customer_info']->customer->code;
			get_balance_history ();

			echo '
							<table cellspacing="0" cellpadding="5" borders="0" align="center" class="transaction_table">';
			if (!empty($GLOBALS['balance_history']['transactions'])) {
			foreach ($GLOBALS['balance_history']['transactions'] as $discard => $transaction_info) {
				echo '
								<tr>
									<td align="center" valign="top" class="list">'.Format_Date($transaction_info->date).': </td>';

				if (in_array($GLOBALS['balance_history']['campaign_type'], array('giftcard','earned'))) {

					$converted_amount = str_replace(",", ".", (string)$transaction_info->amount);

					if ($GLOBALS['european_numbers']) {
						$amount_to_show = number_format((float)$converted_amount, 2, ',', '.');
					}
					else {
						$amount_to_show = number_format((float)$converted_amount, 2, '.', ',');
					}
				} else {
					$amount_to_show = $transaction_info->amount;
				}
				if (!empty($transaction_info->service_product)) {
					if ($transaction_info->redeemed == 'Y') {
						echo '
									<td align="right" valign="top" class="amount_negative list">-'.$amount_to_show.'</td>';
					} else {
						echo '
									<td align="right" valign="top" class="amount_positive list">+'.$amount_to_show.'</td>';
					}
				} elseif ($transaction_info->amount < 0) {
					echo '
									<td align="right" valign="top" class="amount_negative list">'.$amount_to_show.'</td>';
				} elseif (!empty($transaction_info->amount)) {
					echo '
									<td align="right" valign="top" class="amount_positive list">+'.$amount_to_show.'</td>';
				} else {
					echo '
									<td align="right" valign="top" class="list">&nbsp;</td>';
				}
				echo'
									<td align="left" valign="top" class="list">';
				if (!empty($transaction_info->service_product)) {
					if ($transaction_info->redeemed == 'Y') {
						echo '<span class="amount_negative">';
					} else {
						echo '<span class="amount_positive">';
					}
					echo $transaction_info->service_product;
					echo '</span>';
					if (!empty($transaction_info->authorization) && $transaction_info->authorization != $transaction_info->service_product) {
						echo ' &#8211; '.$transaction_info->authorization;
					}
				} else {
					if (!empty($transaction_info->authorization)) {
						echo '&#8211; '.$transaction_info->authorization;
					} else {
						echo '&nbsp;';
					}
				}
				echo '</td>
								</tr>';
			}
			echo '
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>';
			}
			else {
				echo '
								<tr>
									<td class="list" height="1">&nbsp;</td>
									<td class="list" height="1">&nbsp;</td>
									<td class="list" height="1">&nbsp;</td>
								</tr>';
			}
			echo '
							</table>
						</div>
					</div>';
		} else {
			echo '
					<div class="grid_66 content_selected">
						<div class="content_left breathing_room">
							<div class="section_header content_center">'.editor('balance_heading_trans_history', 15, 0).'</div>
							<br><br>
							<div class="content_center">'.editor('myinfo_error_no_transactions', 25, 0).'</div><br>
							<br><br><br><br><br><br><br><br>
						</div>
					</div>';
		}

	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">'.editor('myinfo_error_access_info', 25, 0).'</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
	//
}

function Show_Add_Transaction() {

	// Get the customer's information
	if (empty($GLOBALS['customer_info'])) {
		get_customer_info();
	}

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		Show_Program_List();

		# ==================
		# Add Transactions
		# ==================

		$content_height = $GLOBALS['campaign_counter'] * 90;
		if ($GLOBALS['buyx_counter'] > 1) {
			$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
		}
		echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room content_center">
							<div class="section_header content_center">';

		// Show appropriate Title
		if ($GLOBALS['current_campaign_type'] == 'points') {
			echo editor('add_section_title_points', 20, 0);
		} elseif ($GLOBALS['current_campaign_type'] == 'giftcard') {
			echo editor('add_section_title_giftcard', 20, 0);
		} elseif ($GLOBALS['current_campaign_type'] == 'events') {
			echo editor('add_section_title_events', 20, 0);
		} elseif ($GLOBALS['current_campaign_type'] == 'earned') {
			echo editor('add_section_title_earned', 20, 0);
		} elseif ($GLOBALS['current_campaign_type'] == 'buyx') {
			echo editor('add_section_title_buyx', 20, 0);
		}

		echo '
							</div>';

		if (!empty($GLOBALS['errors'])) {
			foreach ($GLOBALS['errors'] as $error_key => $error_message) {
				echo '
							<div  class="error content_center" style="margin-bottom: 5px;">';
								if (!empty($GLOBALS['content'][$error_key])) {
									echo editor($error_key, 30, 2);
								} else {
									echo $error_message;
								}
								echo '
							</div>';
			}
		}

		//
		echo '
								<form action="index.php" method="POST" name="add_transaction_form">
									<input type="hidden" name="action" value="add_transaction">
									<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
									<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['current_campaign_type'].'">
									'.common_form_elements().'
									';

		echo '
									<div class="add_box">
										<table align="center" border="0" cellpadding="5" cellspacing="0">';


		// --- SHOW FIELDS:

		if (!empty($GLOBALS['preferences']['transaction_fields_order'])) {
			// itterate through each preference.
			foreach ($GLOBALS['preferences']['transaction_fields_order'] as $field_sorted) {
				if ($field_sorted == 'amount' && in_array($GLOBALS['current_campaign_type'], array ('points', 'giftcard'))) {
					Show_HTML_Amount_Box ();
				}
				elseif ($field_sorted == 'promo_id' && in_array($GLOBALS['current_campaign_type'], array ('points'))) {
					Show_HTML_Promo_Box ();
				}
				elseif ($field_sorted == 'service_product' && in_array($GLOBALS['current_campaign_type'], array ('buyx'))) {
					Show_HTML_Items_Pulldown ();
					Show_HTML_Quantity_Box ();
				}
				elseif ($field_sorted == 'transaction_description' && in_array($GLOBALS['current_campaign_type'], array ('points', 'giftcard', 'events', 'earned', 'buyx'))) {
					Show_HTML_Description_Box ();
				}
				elseif ($field_sorted == 'send_transaction_email' && in_array($GLOBALS['current_campaign_type'], array ('points', 'giftcard', 'events', 'earned', 'buyx'))) {
					Show_HTML_Email_Receipt_Checkbox ();
				}
				elseif (!empty($GLOBALS['transaction_fields'][$field_sorted])) {
					if ($GLOBALS['transaction_fields'][$field_sorted]['show'] != 'N') {
						if (is_array($GLOBALS['preferences']['transaction_fields_to_show']) && in_array($field_sorted, $GLOBALS['preferences']['transaction_fields_to_show'])) {
							if ($GLOBALS['transaction_fields'][$field_sorted]['type'] == 'Text') {
								Show_HTML_Text_Box ($GLOBALS['transaction_fields'][$field_sorted]);
							}
							elseif ($GLOBALS['transaction_fields'][$field_sorted]['type'] == 'Date') {
								Show_HTML_Date_Selector ($GLOBALS['transaction_fields'][$field_sorted]);
							}
							elseif ($GLOBALS['transaction_fields'][$field_sorted]['type'] == 'Pick') {
								Show_HTML_Pick_List_Pulldown ($GLOBALS['transaction_fields'][$field_sorted]);
							}
							elseif ($GLOBALS['transaction_fields'][$field_sorted]['type'] == 'List') {
								Show_HTML_List ($GLOBALS['transaction_fields'][$field_sorted]);
							}
							elseif ($GLOBALS['transaction_fields'][$field_sorted]['type'] == 'Number') {
								Show_HTML_Number_Box ($GLOBALS['transaction_fields'][$field_sorted]);
							}
							elseif ($GLOBALS['transaction_fields'][$field_sorted]['type'] == 'Monetary') {
								Show_HTML_Monetary_Box ($GLOBALS['transaction_fields'][$field_sorted]);
							}
						}
					}
				}
			}
		}
		// Also go through each field not already set in preferences.
		if (empty($GLOBALS['preferences']['transaction_fields_order'])
		|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('amount', $GLOBALS['preferences']['transaction_fields_order']))) {
			if (in_array($GLOBALS['current_campaign_type'], array ('points', 'giftcard'))) {
				Show_HTML_Amount_Box ();
			}
		}
		if (empty($GLOBALS['preferences']['transaction_fields_order'])
		|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('promo_id', $GLOBALS['preferences']['transaction_fields_order']))) {
			if (in_array($GLOBALS['current_campaign_type'], array ('points'))) {
				Show_HTML_Promo_Box ();
			}
		}
		if (empty($GLOBALS['preferences']['transaction_fields_order'])
		|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('service_product', $GLOBALS['preferences']['transaction_fields_order']))) {
			if (in_array($GLOBALS['current_campaign_type'], array ('buyx'))) {
				Show_HTML_Items_Pulldown ();
				Show_HTML_Quantity_Box ();
			}
		}
		if (empty($GLOBALS['preferences']['transaction_fields_order'])
		|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('transaction_description', $GLOBALS['preferences']['transaction_fields_order']))) {
			if (in_array($GLOBALS['current_campaign_type'], array ('points', 'giftcard', 'events', 'earned', 'buyx'))) {
				Show_HTML_Description_Box ();
			}
		}
		if (!empty($GLOBALS['transaction_fields'])) {
			foreach ($GLOBALS['transaction_fields'] as $transaction_field_name => $transaction_field_data) {
				if ($transaction_field_data['show'] != 'N') {
					if (empty($GLOBALS['preferences']['transaction_fields_order'])
						 || (is_array($GLOBALS['preferences']['transaction_fields_order'])
							  && !in_array($transaction_field_name, $GLOBALS['preferences']['transaction_fields_order']))) {
									if ($transaction_field_data['type'] == 'Text') {
										Show_HTML_Text_Box ($transaction_field_data);
									}
									elseif ($transaction_field_data['type'] == 'Date') {
										Show_HTML_Date_Selector ($transaction_field_data);
									}
									elseif ($transaction_field_data['type'] == 'Pick') {
										Show_HTML_Pick_List_Pulldown ($transaction_field_data);
									}
									elseif ($transaction_field_data['type'] == 'List') {
										Show_HTML_List ($transaction_field_data);
									}
									elseif ($transaction_field_data['type'] == 'Number') {
										Show_HTML_Number_Box ($transaction_field_data);
									}
									elseif ($transaction_field_data['type'] == 'Monetary') {
										Show_HTML_Monetary_Box ($transaction_field_data);
									}
					}
				}
			}
		}

		if (empty($GLOBALS['preferences']['transaction_fields_order'])
		|| (is_array($GLOBALS['preferences']['transaction_fields_order']) && !in_array('send_transaction_email', $GLOBALS['preferences']['transaction_fields_order']))) {
			if (in_array($GLOBALS['current_campaign_type'], array ('points', 'giftcard', 'events', 'earned', 'buyx'))) {
				Show_HTML_Email_Receipt_Checkbox ();
			}
		}


		//
		// --- SHOW RECORD BUTTON -
		if ($GLOBALS['current_campaign_type'] == 'points') {
			// Submit button
			Show_HTML_Submit_Add ('add_amount_textbox');
		}
		elseif ($GLOBALS['current_campaign_type'] == 'giftcard') {
			// Submit button
			Show_HTML_Submit_Add ('add_amount_textbox');
		}
		elseif ($GLOBALS['current_campaign_type'] == 'events' || $GLOBALS['current_campaign_type'] == 'earned') {
			// Submit button
			Show_HTML_Submit_Add ('add_description_textbox');
		}
		elseif ($GLOBALS['current_campaign_type'] == 'buyx') {
			// Submit button
			Show_HTML_Submit_Add ('service_product');
		}
		// ------------------

		echo '
									</table>
								</div>
							</form>
						</div>
					</div>';
	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">'.editor('myinfo_error_access_info', 25, 0).'</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}
function Show_Customer_List() {

	if (!empty($GLOBALS['customer_search_results']->customer)) {

		echo '
					<div class="grid_100 content_center ">
						<div class="content_left breathing_room content_center full_page">
							<div class="section_header content_center">'.editor('choose_customer_header', 20, 0).'</div>

							<div class="centered">
								<table cellspacing="0" cellpadding="5" border="0" align="center">';

		foreach ($GLOBALS['customer_search_results']->customer as $customer_data) {
			echo '
									<tr>
										<td align="left" valign="top" class="list_row list_left larger list_button" onClick="';
										echo 'document.choose_'.(string)$customer_data->code.'_form.submit();">';
			$customer_name = '';
			if (!empty($customer_data->first_name)) {
				$customer_name .= (string)$customer_data->first_name;
			}
			if (!empty($customer_data->last_name)) {
				if (!empty($customer_data->first_name)) {
					$customer_name .= ' ';
				}
				$customer_name .= (string)$customer_data->last_name;
			}
										echo $customer_name.'</td>
										<td width="10" class="list_row list_middle list_button" onClick="';
										echo 'document.choose_'.(string)$customer_data->code.'_form.submit();"></td>
										<td align="right" valign="bottom" class="list_row list_right xtra_large list_button" onClick="';
										echo 'document.choose_'.(string)$customer_data->code.'_form.submit();">';
										echo $customer_data->card_number.'</td>
									</tr>
									<tr>
										<td colspan="3" height="0"><td>
									</tr>';
		}


		echo '
								</table>';
		//
		// Output all the forms
		foreach ($GLOBALS['customer_search_results']->customer as $customer_data) {
			echo '
								<form action="index.php" method="POST" name="choose_'.(string)$customer_data->code.'_form">
									<input type="hidden" name="action" value="selected_customer">
									<input type="hidden" name="customer_code" value="'.(string)$customer_data->code.'">
									'.common_form_elements().'
								</form>';
		}
		//
		// Close content
		echo '
							</div>
 						</div>
 					</div>';
 	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">'.editor('myinfo_error_access_info', 25, 0).'</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}

function Show_Redeem() {

	// Get the customer's information.
	if (!isset($GLOBALS['customer_info'])) {
		get_customer_info();
	}

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		Show_Program_List();

		# ==================
		# Rewards List
		# ==================
		get_rewards();


		$content_height = $GLOBALS['campaign_counter'] * 90;
		if ($GLOBALS['buyx_counter'] > 1) {
			$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
		}
		echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room content_center">';

		if (isset($GLOBALS['campaign_rewards']) && sizeof($GLOBALS['campaign_rewards']->rewards->reward) > 0) {

			echo '
							<div class="section_header content_center">'.editor('nav_item_redeem', 15, 0).'</div>';

			if (!empty($GLOBALS['errors'])) {
				foreach ($GLOBALS['errors'] as $error_key => $error_message) {
					echo '
								<div  class="error content_center" style="margin-bottom: 5px;">';
									if (!empty($GLOBALS['content'][$error_key])) {
										echo editor($error_key, 30, 2);
									} else {
										echo $error_message;
									}
									echo '
								</div>';
				}
			}


			echo '
							<table border="0" cellpadding="0" cellspacing="10" width="100%">
								<tr>';

			if ($GLOBALS['current_campaign_type'] == 'points' && in_array('points', $GLOBALS['preferences']['redeem_types'])) {

					echo '
									<td valign="top" width="50%">';
					echo '
										<span class="larger">'.editor('redeem_type_setting_points', 20, 0).':</span><br>';

					echo '
											<form action="index.php" method="POST" name="add_transaction_points_form">
												<input type="hidden" name="action" value="redeem_amount">
												<input type="hidden" name="kind" value="points">
												<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
												<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['current_campaign_type'].'">
												'.common_form_elements().'
												';

					// Amount Box:
					$prev_amount = (!empty($GLOBALS['vars']['amount'])) ? $GLOBALS['vars']['amount'] : '';
					echo '
												<input id="add_amount_textbox"
														class="text_field content_right super_large"
														autocomplete="off"
														type="text"
														name="amount"
														value="'.$prev_amount.'"
														size="10"
														maxlength="255"
														border="0">
												<script type="text/javascript">
													var add_box = document.getElementById("add_amount_textbox");
													add_box.focus();
												</script>
												<br>';

					// Description Text box
					$prev_desc = (!empty($GLOBALS['vars']['transaction_description'])) ? $GLOBALS['vars']['transaction_description'] : '';
					echo '
												<span class="big">'.editor('transaction_description', 20, 0).'<br>
												<textarea id="add_description_textbox"
													align="top"
													class="content_left big"
													autocomplete="on"
													spellcheck="on"
													name="transaction_description" onkeyup="setSubmit();" rows="2" cols="20">'.$prev_desc.'</textarea><br>';

					echo '
												<input type="button" value="'.$GLOBALS['hard_coded_content']['deduct'].'" class="button up large add_submit" name="transaction_submit"
													onMouseOver="button_hilite(this);"
													onMouseOut="button_dim(this);"
													onClick="this.form.submit();">';

					echo '
											</form>';

				//redeem amount end

				if (($GLOBALS['current_campaign_type'] == 'points') && intval($GLOBALS['current_campaign_balance']) > 0) {


				}

				echo '
										<br>
										<br>
									</td>';
			}
			//
			if ($GLOBALS['current_campaign_type'] == 'points' && in_array('money', $GLOBALS['preferences']['redeem_types'])) {

				echo '
									<td valign="top">
										<span class="larger">'.editor('redeem_type_setting_money', 20, 0).':</span>';

				echo '
											<form action="index.php" method="POST" name="add_transaction_money_form">
												<input type="hidden" name="action" value="redeem_amount">
												<input type="hidden" name="kind" value="money">
												<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
												<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['current_campaign_type'].'">
												'.common_form_elements().'
												';

					// Amount Box:
					$prev_amount = (!empty($GLOBALS['vars']['money_amount'])) ? $GLOBALS['vars']['money_amount'] : '';
					echo '
												<input id="add_amount_textbox"
														class="text_field content_right super_large"
														autocomplete="off"
														type="text"
														name="money_amount"
														value="'.$prev_amount.'"
														size="10"
														maxlength="255"
														border="0">
												<script type="text/javascript">
													var add_box = document.getElementById("add_amount_textbox");
													add_box.focus();
												</script>
												<br>';

					// Description Text box
					$prev_desc = (!empty($GLOBALS['vars']['money_transaction_description'])) ? $GLOBALS['vars']['money_transaction_description'] : '';
					echo '
												<span class="big">'.editor('transaction_description', 20, 0).'<br>
												<textarea id="add_description_textbox"
													align="top"
													class="content_left big"
													autocomplete="on"
													spellcheck="on"
													name="money_transaction_description" onkeyup="setSubmit();" rows="2" cols="20">'.$prev_desc.'</textarea><br>';

					echo '
												<input type="button" value="'.$GLOBALS['hard_coded_content']['deduct'].'" class="button up large add_submit" name="transaction_submit"
													onMouseOver="button_hilite(this);"
													onMouseOut="button_dim(this);"
													onClick="this.form.submit();">';

					echo '
											</form>';

				//redeem amount end

				echo '
										<br>
										<br>
									</td>';
			}
			//
			echo '
								</tr>';
			if (in_array('rewards', $GLOBALS['preferences']['redeem_types'])) {

				echo '
									<tr>
										<td colspan="2">
											<div class="larger content_left">'.editor('redeem_section_title', 20, 0).':</div>';

				// Sort the rewards list:
				$reward_sorting_counter = 0;
				foreach ($GLOBALS['campaign_rewards']->rewards->reward as $discard => $reward_info_for_sort) {
					$reward_array_to_sort[$reward_sorting_counter] = (string)$reward_info_for_sort->level;
					$reward_sorting_counter ++;
				}
				asort($reward_array_to_sort);

				foreach ($reward_array_to_sort as $reward_item => $discard) {
					$reward_info = $GLOBALS['campaign_rewards']->rewards->reward[$reward_item];

					if (!empty($reward_info->description) && !empty($reward_info->level)) {  // ignore blank entries

						if ($GLOBALS['current_campaign_type'] == 'buyx') {
							if (isset($GLOBALS['current_campaign_balances']["{$reward_info->description}"])
								 && intval($GLOBALS['current_campaign_balances']["{$reward_info->description}"]) >= intval($reward_info->level)) {
								echo '
											<div class="reward_item reward_allowed content_center"
												onMouseOver="reward_hilite(this);"
												onMouseOut="reward_dim(this);"
												onClick="document.reward_form_'.$reward_info->id.'.submit();">';
							} else {
								echo '
											<div class="reward_item reward_not_allowed content_center">';
							}
						}
						else {
							if (intval($GLOBALS['current_campaign_balance']) >= intval($reward_info->level)) {
								echo '
											<div class="reward_item reward_allowed content_center"
												onMouseOver="reward_hilite(this);"
												onMouseOut="reward_dim(this);"
												onClick="document.reward_form_'.$reward_info->id.'.submit();">';
							} else {
								echo '
											<div class="reward_item reward_not_allowed content_center">';
							}
						}
						echo '
												<form action="index.php" method="POST" name="reward_form_'.$reward_info->id.'">
													<input type="hidden" name="action" value="redeem_direct">
													<input type="hidden" name="which_reward_id" value="'.$reward_info->id.'">
													<input type="hidden" name="which_reward_description" value="'.urlencode($reward_info->description).'">
													<input type="hidden" name="which_reward_level" value="'.$reward_info->level.'">
													<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
													<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['campaign_rewards']->campaign->type.'">
													'.common_form_elements().'	';
						if ($GLOBALS['current_campaign_type'] == 'buyx') {
							echo '
													<div class="content_left xtra_large reward_description">1 '.$reward_info->description.'</div>';
							echo '
													<div class="content_right reward_amount">
														'.editor('label_needs', 20, 0).': <span class="large">'.$reward_info->level.'</span>
														'.$reward_info->description.'
													</div>';
						}
						if ($GLOBALS['current_campaign_type'] == 'events') {
							echo '
													<div class="content_left xtra_large reward_description">'.$reward_info->description.'</div>';
							echo '
													<div class="content_right reward_amount">
														<span class="large">'.$reward_info->level.'</span> ';
														echo (intval($reward_info->level) == 1) ? editor('label_event', 20, 0) : editor('label_events', 20, 0);
							echo '
													</div>';
						}
						if ($GLOBALS['current_campaign_type'] == 'points') {
							echo '
													<div class="content_left xtra_large reward_description">'.$reward_info->description.'</div>';
							echo '
													<div class="content_right reward_amount">
														<span class="large">'.$reward_info->level.'</span> ';
														echo (intval($reward_info->level) == 1) ? editor('label_point', 20, 0) : editor('label_points', 20, 0);
							echo '
													</div>';
						}

						echo '
												</form>
											</div>';

					}
				}
				//
				echo '
										</td>
									</tr>';
			}

			//
			echo '
							</table>';
		}
		//
		elseif ($GLOBALS['current_campaign_type'] == 'giftcard' || $GLOBALS['current_campaign_type'] == 'earned') {

			echo '
							<div class="section_header content_center">'.editor('redeem_deduct', 15, 0).'</div>';

			if (!empty($GLOBALS['errors'])) {
				foreach ($GLOBALS['errors'] as $error_key => $error_message) {
					echo '
								<div  class="error content_center" style="margin-bottom: 5px;">';
									if (!empty($GLOBALS['content'][$error_key])) {
										echo editor($error_key, 30, 2);
									} else {
										echo $error_message;
									}
									echo '
								</div>';
				}
			}

			echo '
							<form action="index.php" method="POST" name="add_transaction_form">
								<input type="hidden" name="action" value="redeem_direct">
								<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
								<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['current_campaign_type'].'">
								'.common_form_elements().'
								';

			echo '
								<div class="add_box">
									<table align="center" border="0" cellpadding="5" cellspacing="0">';

			// Amount Box:
			$prev_amount = (!empty($GLOBALS['vars']['amount'])) ? $GLOBALS['vars']['amount'] : '';
			echo '
											<tr>
												<td align="left" valign="top" class="xtra_large">'.editor('add_amount_label', 20, 0).'</td>
												<td align="left" valign="top"><input id="add_amount_textbox"
														class="text_field content_right super_large"
														autocomplete="off"
														type="text"
														name="amount"
														value="'.$prev_amount.'"
														size="10"
														maxlength="255"
														border="0">
													<script type="text/javascript">
														var add_box = document.getElementById("add_amount_textbox");
														add_box.focus();
													</script>
												</td>
											</tr>';

			// Description Text box option
			if (true) {
				$prev_desc = (!empty($GLOBALS['vars']['authorization'])) ? $GLOBALS['vars']['authorization'] : '';
				echo '
											<tr>
												<td align="left" valign="middle" class="big">'.editor('transaction_description', 20, 0).'<br>';
												echo '<span class="faded">('.editor('optional', 20, 0).')</span></td>
												<td align="left" valign="top"><textarea id="add_description_textbox"
														 align="top"
														 class="content_left big"
														 autocomplete="on"
														 spellcheck="on"
														 name="transaction_description" rows="2" cols="20">'.$prev_desc.'</textarea></td>
											</tr>';
			}

			// EMAIL Option
			// (Not avail on redemptions)
			/*
			if (!empty($GLOBALS['customer_info']->customer->email)) {

				// OPTION: To pre-check the email checkbox, add "CHECKED" right after the '<input ' on the line below:
				// ex:  <input CHECKED type=checkbox ...
				echo '
											<tr>
												<td colspan="2" align="left" valign="middle" class="big">'.editor('send_email_label', 20, 0).'
												<input type="checkbox" name="send_transaction_email" value="Y" border="0"> '.editor('send_email_yes', 20, 0).'</td>
											</tr>';
			} else {
				// No email address in customer record
			}
			*/

			echo '
										<tr>
											<td colspan="2" align="center" valign="top"><input type="button" value="'.$GLOBALS['hard_coded_content']['record'].'" class="button up large add_submit" name="transaction_submit"
												onMouseOver="button_hilite(this);"
												onMouseOut="button_dim(this);"
												onClick="this.form.submit();"></td>
										</tr>';
			echo '
									</table>
								</div>
							</form>';

		}
		//
		else { // No rewards available to claim.
			echo '
							<br>
							<div class="content_center">'.editor('redeem_no_rewards_avail', 25, 0).'</div>';
		}
		//

		// Close content
		echo '
						</div>
					</div>';
	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">'.editor('myinfo_error_access_info', 25, 0).'</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}

function Show_Confirm_Redeem() {

	// Get the customer's information
	if (!isset($GLOBALS['customer_info'])) {
		get_customer_info();
	}

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		Show_Program_List();

		# ==============================
		# Content
		# ==============================

		// Set a minimum content size based on the side-content.
		$content_height = $GLOBALS['campaign_counter'] * 90;
		if ($GLOBALS['buyx_counter'] > 1) {
			$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
		}

		echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room">
							<div class="section_header content_center">'.editor('confirm_redeem_title', 25, 0).'</div>';

		echo '
							<form action="index.php" method="POST" name="confirm_form">
								<input type="hidden" name="action" value="redeem_reward">
								<input type="hidden" name="which_reward_id" value="'.$GLOBALS['vars']['which_reward_id'].'">
								<input type="hidden" name="which_reward_description" value="'.$GLOBALS['vars']['which_reward_description'].'">
								<input type="hidden" name="which_reward_level" value="'.$GLOBALS['vars']['which_reward_level'].'">
								<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
								<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['vars']['which_campaign_type'].'">
								'.common_form_elements().'
								<table cellspacing="0" cellpadding="4" border="0" width="100%">';

		// Show product to be shipped:
		// --------------------------
		// Show product confirmation header:
		echo '
									<tr>
										<td colspan="2">
											<span class="large bold">'.editor('confirm_product_header', 20, 0).'</span>
											<hr>
											<span class="normal">'.editor('confirm_product_text', 30, 3).'</span>
										</td>
									</tr>';

		// Show product to be ordered:
		echo '
									<tr>
										<td colspan="2" align="center">';

		echo '
											<table cellpadding="0" cellspacing="10" border="0" class="reward_table">
												<tr>';

								// Show Image
								//echo '
								//					<td rowspan="2" align="center" valign="top">
								//						<img src="'.$GLOBALS['vars']['which_reward_image_url'].'">.'">
								//					</td>';

								// Show Title
								if ($GLOBALS['vars']['which_campaign_type'] == 'buyx') {
									echo '
													<td align="left" valign="top" class="reward_title">
													<span class="large">1</span> '.urldecode($GLOBALS['vars']['which_reward_description']).'</td>';
								} else {
									echo '
													<td align="left" valign="top" class="reward_title">'.urldecode($GLOBALS['vars']['which_reward_description']).'</td>';
								}
								echo '
												</tr>
												<tr>';
								// Show Points
								echo '
													<td align="right" valign="top" class="reward_amount">';
								if ($GLOBALS['vars']['which_campaign_type'] == 'points') {
									if ($GLOBALS['vars']['which_reward_level'] > 0) {
										echo '
														<span class="large">-'.number_format($GLOBALS['vars']['which_reward_level']).'</span> ';
									} elseif ($GLOBALS['vars']['which_reward_level'] == 0) {
										echo '
														<span class="large">'.number_format($GLOBALS['vars']['which_reward_level']).'</span> ';
									} else {
										echo '
														<span class="large">+'.number_format($GLOBALS['vars']['which_reward_level']).'</span> ';
									}
									echo (intval($GLOBALS['vars']['which_reward_level']) == 1) ? editor('label_point', 20, 0) : editor('label_points', 20, 0);
								} else
								if ($GLOBALS['vars']['which_campaign_type'] == 'events') {
									if ($GLOBALS['vars']['which_reward_level'] > 0) {
										echo '
														<span class="large">-'.number_format($GLOBALS['vars']['which_reward_level']).'</span> ';
									} elseif ($GLOBALS['vars']['which_reward_level'] == 0) {
										echo '
														<span class="large">'.number_format($GLOBALS['vars']['which_reward_level']).'</span> ';
									} else {
										echo '
														<span class="large">+'.number_format($GLOBALS['vars']['which_reward_level']).'</span> ';
									}
									echo (intval($GLOBALS['vars']['which_reward_level']) == 1) ? editor('label_event', 20, 0) : editor('label_events', 20, 0);
								} else
								if ($GLOBALS['vars']['which_campaign_type'] == 'buyx') {
									// no need to show
								}
								echo '</td>';

								echo '
												</tr>
											</table>';

		echo '
										</td>
									</tr>';

		// Spacer
		echo '
									<tr>
										<td colspan="2" class="tiny">&nbsp;</td>
									</tr>';

		// Show address confirmation
		// -------------------------
		// Show header:
		echo '
									<tr>
										<td colspan="2">
											<span class="large bold">'.editor('confirm_address_header', 20, 0).'</span>
											<hr>
											<span class="normal">'.editor('confirm_address_text', 30, 3).'</span>
										</td>
									</tr>';

		// Error Updating:

		if (!empty($GLOBALS['errors'])) {
			foreach ($GLOBALS['errors'] as $error_key => $error_message) {
				echo '
										<tr>
											<td colspan="2" class="error">';
											if (!empty($GLOBALS['content'][$error_key])) {
												echo editor($error_key, 30, 2);
											} else {
												echo $error_message;
											}
											echo '</td>
										</tr>';
			}
		}

		if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
			foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {
				if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])
					 || $field_sorted == 'card_number'
					 || $field_sorted == 'customer_password') {

					// Normal fields:
					if (strpos($field_sorted, 'custom_field_') === false || $field_sorted == 'custom_field_1') {

						if ($field_sorted == 'card_number') {

							echo '
												<tr>
													<td>'.editor('activate_label_account_code', 20, 0).':</td>
													<td><span class="larger">'.$GLOBALS['customer_info']->customer->card_number.'</span></td>
												</tr>';
						}

						elseif ($field_sorted == 'first_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
												<td>';
							if (!empty($GLOBALS['vars']['first_name'])) {
								$first_name = $GLOBALS['vars']['first_name'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->first_name)) {
								$first_name = (string)$GLOBALS['customer_info']->customer->first_name;
							}
							else {
								$first_name = '';
							}
							echo '
													<input	class="text_field"
																type="text"
																name="first_name"
																value="'.$first_name.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
													</td>
											</tr>';
						}
						elseif ($field_sorted == 'last_name') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_lastname', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['last_name'])) {
								$last_name = $GLOBALS['vars']['last_name'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->last_name)) {
								$last_name = (string)$GLOBALS['customer_info']->customer->last_name;
							}
							else {
								$last_name = '';
							}
							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="last_name"
																value="'.$last_name.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'phone') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_phone', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['phone'])) {
								$phone = $GLOBALS['vars']['phone'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->phone)) {
								$phone = (string)$GLOBALS['customer_info']->customer->phone;
							}
							else {
								$phone = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="phone"
																value="'.$phone.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'email') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_email', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['email'])) {
								$email = $GLOBALS['vars']['email'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->email)) {
								$email = (string)$GLOBALS['customer_info']->customer->email;
							}
							else {
								$email = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="email"
																value="'.$email.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'custom_date') {

							if (!empty($GLOBALS['customer_info']->customer->custom_date)) {
								$date_array = explode ('-', (string)$GLOBALS['customer_info']->customer->custom_date);
							}

							if (!empty($GLOBALS['vars']['date_year'])) {
								$year = $GLOBALS['vars']['date_year'];
							}
							elseif (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}

							if (!empty($GLOBALS['vars']['date_month'])) {
								$month = $GLOBALS['vars']['date_month'];
							}
							elseif (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}

							if (!empty($GLOBALS['vars']['date_day'])) {
								$day = $GLOBALS['vars']['date_day'];
							}
							elseif (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							}

							echo '
											<tr>
												<td>'.editor('myinfo_label_date', 20, 0).':</td>
												<td>
													<table cellspacing="0" cellpadding="2" border="0">
														<tr>
															<td align="center">'.editor('myinfo_label_year', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_month', 12, 0).'</td>
															<td align="center">'.editor('myinfo_label_day', 12, 0).'</td>
														</tr>
														<tr>
															<td>
																<select name="date_year" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																	echo '<option value=""';
																	if ($year == '0000') {
																		echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($y = 1900; $y <= 2050; $y += 1) {
																		if ($y == $year) {
																			echo'
																	<option value="'.$y.'" SELECTED>'.$y.'</option>';
																		} else {
																			echo'
																	<option value="'.$y.'">'.$y.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_month" size="1" onBlur="date_set(this);" onChange="new_change(this);">';
																	echo '<option value=""';
																	if ($month == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($m = 1; $m <= 12; $m += 1) {
																		if ($m == $month) {
																			echo'
																	<option value="'.$m.'" SELECTED>'.$m.'</option>';
																		} else {
																			echo'
																	<option value="'.$m.'">'.$m.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
															<td>
																<select name="date_day" size="1">';
																	echo '<option value=""';
																	if ($day == '00') {
																			echo' SELECTED';
																	}
																	echo '>&nbsp;</option>';
																	for ($d = 1; $d <= 31; $d += 1) {
																		if ($d == $day) {
																			echo'
																	<option value="'.$d.'" SELECTED>'.$d.'</option>';
																		} else {
																			echo'
																	<option value="'.$d.'">'.$d.'</option>';
																		}
																	}
																	echo'
																</select>
															</td>
														</tr>
													</table>
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'address1' || $field_sorted == 'street1') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_address', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['street1'])) {
								$street1 = $GLOBALS['vars']['street1'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->street1)) {
								$street1 = (string)$GLOBALS['customer_info']->customer->street1;
							}
							else {
								$street1 = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street1"
																value="'.$street1.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'address2' || $field_sorted == 'street2') {
							echo '
											<tr>
												<td></td>';

							if (!empty($GLOBALS['vars']['street2'])) {
								$street2 = $GLOBALS['vars']['street2'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->street2)) {
								$street2 = (string)$GLOBALS['customer_info']->customer->street2;
							}
							else {
								$street2 = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="street2"
																value="'.$street2.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'city') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_city', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['city'])) {
								$city = $GLOBALS['vars']['city'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->city)) {
								$city = (string)$GLOBALS['customer_info']->customer->city;
							}
							else {
								$city = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="city"
																value="'.$city.'"
																size="24"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'state') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_state', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['state'])) {
								$state = $GLOBALS['vars']['state'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->state)) {
								$state = (string)$GLOBALS['customer_info']->customer->state;
							}
							else {
								$state = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="state"
																value="'.$state.'"
																size="20"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'zip') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_zip', 20, 0).':</td>';
							if (!empty($GLOBALS['vars']['postal_code'])) {
								$postal_code = $GLOBALS['vars']['postal_code'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->postal_code)) {
								$postal_code = (string)$GLOBALS['customer_info']->customer->postal_code;
							}
							else {
								$postal_code = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="postal_code"
																value="'.$postal_code.'"
																size="10"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'country') {
							echo '
											<tr>
												<td>'.editor('myinfo_label_country', 20, 0).':</td>';

							if (!empty($GLOBALS['vars']['country'])) {
								$country = $GLOBALS['vars']['country'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->country)) {
								$country = (string)$GLOBALS['customer_info']->customer->country;
							}
							else {
								$country = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="country"
																value="'.$country.'"
																size="15"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
						elseif ($field_sorted == 'custom_field_1' ) {
							echo '
											<tr>
												<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>';

							if (!empty($GLOBALS['vars']['custom_field_1'])) {
								$custom_field = $GLOBALS['vars']['custom_field_1'];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->custom_field)) {
								$custom_field = (string)$GLOBALS['customer_info']->customer->custom_field;
							}
							else {
								$custom_field = '';
							}

							echo '
												<td>
													<input 	class="text_field"
																type="text"
																name="custom_field_1"
																value="'.$custom_field.'"
																size="30"
																maxlength="255"
																border="0"
																onChange="new_change(this);">
												</td>
											</tr>';
						}
					}
					else { // Custom fields:

						if ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Text') {

							if (!empty($GLOBALS['vars'][$field_sorted])) {
								$text_data = $GLOBALS['vars'][$field_sorted];
							}
							elseif (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) {
								$text_data = (string)$GLOBALS['customer_info']->customer->$field_sorted->data;
							}
							else {
								$text_data = '';
							}

							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="'.$field_sorted.'"
															value="'.$text_data.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Date') {

							if (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) {
								$date_array = explode ('-', (string)$GLOBALS['customer_info']->customer->$field_sorted->data);
							}

							if (!empty($GLOBALS['vars'][$field_sorted.'_year'])) {
								$year = $GLOBALS['vars'][$field_sorted.'_year'];
							}
							elseif (isset($date_array)) {
								$year = $date_array[0];
							}
							else {
								$year = '0000';
							}

							if (!empty($GLOBALS['vars'][$field_sorted.'_month'])) {
								$month = $GLOBALS['vars'][$field_sorted.'_month'];
							}
							elseif (isset($date_array)) {
								$month = $date_array[1];
							}
							else {
								$month = '00';
							}

							if (!empty($GLOBALS['vars'][$field_sorted.'_day'])) {
								$day = $GLOBALS['vars'][$field_sorted.'_day'];
							}
							elseif (isset($date_array)) {
								$day = $date_array[2];
							}
							else {
								$day = '00';
							}

							echo '
										<tr>
											<td valign="middle">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>';

							echo '
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="'.$field_sorted.'_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$field_sorted.'_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';

						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Pick') {

							echo '
										<tr>
											<td valign="middle">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>';

							$choices_array = explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->choices);

							echo '
												<select name="'.$field_sorted.'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$field_sorted]) && $GLOBALS['vars'][$field_sorted] == trim($choice_item)) {
									echo ' SELECTED';
								}
								elseif (isset($GLOBALS['customer_info']->customer->$field_sorted->data) && (string)$GLOBALS['customer_info']->customer->$field_sorted->data == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td>';

										$choices_array = explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->choices);

										if (!empty($GLOBALS['vars'][$field_sorted])) {
											$raw_returned_choices = $GLOBALS['vars'][$field_sorted];
										}
										elseif (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) {

											$raw_returned_choices = explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->data);
										}
										else {
											$raw_returned_choices = array();
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$field_sorted.'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}



					}
				}
			}
		}
		else {  // preferences for field order have never been set.

			// First Name:
			if (!isset($GLOBALS['customer_normal_fields']['first_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['first_name']['show']) && $GLOBALS['customer_normal_fields']['first_name']['show'] != 'N')) {
					if (!empty($GLOBALS['vars']['first_name'])) {
						$first_name_to_ship = $GLOBALS['vars']['first_name'];
					} elseif (!empty($GLOBALS['customer_info']->customer->first_name)) {
						$first_name_to_ship = $GLOBALS['customer_info']->customer->first_name;
					} else {
						$first_name_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td>
												<input	class="text_field"
															type="text"
															name="first_name"
															value="'.$first_name_to_ship.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// Last Name:
			if (!isset($GLOBALS['customer_normal_fields']['last_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['last_name']['show']) && $GLOBALS['customer_normal_fields']['last_name']['show'] != 'N')) {
					if (!empty($GLOBALS['vars']['last_name'])) {
						$last_name_to_ship = $GLOBALS['vars']['last_name'];
					} elseif (!empty($GLOBALS['customer_info']->customer->last_name)) {
						$last_name_to_ship = $GLOBALS['customer_info']->customer->last_name;
					} else {
						$last_name_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="last_name"
															value="'.$last_name_to_ship.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// Address 1
			if (!isset($GLOBALS['customer_normal_fields']['address1']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address1']['show']) && $GLOBALS['customer_normal_fields']['address1']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['street1'])) {
						$address1_to_ship = $GLOBALS['vars']['street1'];
					} elseif (!empty($GLOBALS['customer_info']->customer->street1)) {
						$address1_to_ship = $GLOBALS['customer_info']->customer->street1;
					} else {
						$address1_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="street1"
															value="'.$address1_to_ship.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// Address 2
			if (!isset($GLOBALS['customer_normal_fields']['address2']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address2']['show']) && $GLOBALS['customer_normal_fields']['address2']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['street2'])) {
						$address2_to_ship = $GLOBALS['vars']['street2'];
					} elseif (!empty($GLOBALS['customer_info']->customer->street2)) {
						$address2_to_ship = $GLOBALS['customer_info']->customer->street2;
					} else {
						$address2_to_ship = '';
					}
					echo '
										<tr>
											<td></td>
											<td>
												<input 	class="text_field"
															type="text"
															name="street2"
															value="'.$address2_to_ship.'"
															size="30"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// City
			if (!isset($GLOBALS['customer_normal_fields']['city']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['city']['show']) && $GLOBALS['customer_normal_fields']['city']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['city'])) {
						$city_to_ship = $GLOBALS['vars']['city'];
					} elseif (!empty($GLOBALS['customer_info']->customer->city)) {
						$city_to_ship = $GLOBALS['customer_info']->customer->city;
					} else {
						$city_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="city"
															value="'.$city_to_ship.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// State
			if (!isset($GLOBALS['customer_normal_fields']['state']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['state']['show']) && $GLOBALS['customer_normal_fields']['state']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['state'])) {
						$state_to_ship = $GLOBALS['vars']['state'];
					} elseif (!empty($GLOBALS['customer_info']->customer->state)) {
						$state_to_ship = $GLOBALS['customer_info']->customer->state;
					} else {
						$state_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="state"
															value="'.$state_to_ship.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// ZIP
			if (!isset($GLOBALS['customer_normal_fields']['zip']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['zip']['show']) && $GLOBALS['customer_normal_fields']['zip']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['postal_code'])) {
						$postal_code_to_ship = $GLOBALS['vars']['postal_code'];
					} elseif (!empty($GLOBALS['customer_info']->customer->postal_code)) {
						$postal_code_to_ship = $GLOBALS['customer_info']->customer->postal_code;
					} else {
						$postal_code_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="postal_code"
															value="'.$postal_code_to_ship.'"
															size="10"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// Country
			if (!isset($GLOBALS['customer_normal_fields']['country']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['country']['show']) && $GLOBALS['customer_normal_fields']['country']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['country'])) {
						$country_ship_to = $GLOBALS['vars']['country'];
					} elseif (!empty($GLOBALS['customer_info']->customer->country)) {
						$country_ship_to = $GLOBALS['customer_info']->customer->country;
					} else {
						$country_ship_to = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="country"
															value="'.$country_ship_to.'"
															size="15"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}

			// Phone:
			if (!isset($GLOBALS['customer_normal_fields']['phone']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['phone']['show']) && $GLOBALS['customer_normal_fields']['phone']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['phone'])) {
						$phone_to_ship = $GLOBALS['vars']['phone'];
					} elseif (!empty($GLOBALS['customer_info']->customer->phone)) {
						$phone_to_ship = $GLOBALS['customer_info']->customer->phone;
					} else {
						$phone_to_ship = '';
					}
					echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="phone"
															value="'.$phone_to_ship.'"
															size="20"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// Email:
			if (!isset($GLOBALS['customer_normal_fields']['email']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['email']['show']) && $GLOBALS['customer_normal_fields']['email']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['email'])) {
						$email_to_ship = $GLOBALS['vars']['email'];
					} elseif (!empty($GLOBALS['customer_info']->customer->email)) {
						$email_to_ship = $GLOBALS['customer_info']->customer->email;
					} else {
						$email_to_ship = '';
					}
					echo '
												<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="email"
															value="'.$email_to_ship.'"
															size="24"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
			}
			// CUSTOM FIELDS
			foreach ($GLOBALS['customer_fields']->account->fields->field as $ignore => $customer_field_data) {
				$customer_field_name = (string)$customer_field_data->name;
				if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {

					if ((string)$customer_field_data->show != 'N') {

						if ((string)$customer_field_data->type == 'Text') {
							echo '
										<tr>
											<td>'.(string)$customer_field_data->label.':</td>
											<td>
												<input 	class="text_field"
															type="text"
															name="'.$customer_field_name.'"
															value="'.(string)$GLOBALS['customer_info']->customer->$customer_field_name->data.'"
															size="40"
															maxlength="255"
															border="0"
															onChange="new_change(this);">
											</td>
										</tr>';
						}
						elseif ((string)$customer_field_data->type == 'Date') {
							echo '
										<tr>
											<td valign="middle">'.(string)$customer_field_data->label.':</td>
											<td>';

							// Breakdown the date:
							$date_parts = explode('-', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data);
							$year 		= (!empty($date_parts[0])) ? $date_parts[0] : '0000';
							$month 		= (!empty($date_parts[1])) ? $date_parts[1] : '00';
							$day 			= (!empty($date_parts[2])) ? $date_parts[2] : '00';

							echo '
												<table cellspacing="0" cellpadding="2" border="0">
													<tr>
														<td align="center"><span class="normal">'.editor('myinfo_label_year', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_month', 12, 0).'</span></td>
														<td align="center"><span class="normal">'.editor('myinfo_label_day', 12, 0).'</span></td>
													</tr>
													<tr>
														<td>
															<select name="'.$customer_field_name.'_year" size="1">';
																echo '<option value=""';
																if ($year == '0000') {
																	echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($y = 1900; $y <= 2050; $y += 1) {
																	if ($y == $year) {
																		echo'
																<option value="'.$y.'" SELECTED>'.$y.'</option>';
																	} else {
																		echo'
																<option value="'.$y.'">'.$y.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$customer_field_name.'_month" size="1">';
																echo '<option value=""';
																if ($month == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($m = 1; $m <= 12; $m += 1) {
																	if ($m == $month) {
																		echo'
																<option value="'.$m.'" SELECTED>'.$m.'</option>';
																	} else {
																		echo'
																<option value="'.$m.'">'.$m.'</option>';
																	}
																}
																echo'
															</select>
														</td>
														<td>
															<select name="'.$customer_field_name.'_day" size="1">';
																echo '<option value=""';
																if ($day == '00') {
																		echo' SELECTED';
																}
																echo '>&nbsp;</option>';
																for ($d = 1; $d <= 31; $d += 1) {
																	if ($d == $day) {
																		echo'
																<option value="'.$d.'" SELECTED>'.$d.'</option>';
																	} else {
																		echo'
																<option value="'.$d.'">'.$d.'</option>';
																	}
																}
																echo'
															</select>
														</td>
													</tr>
												</table>
											</td>
										</tr>';

						}
						elseif ((string)$customer_field_data->type == 'Pick') {
							echo '
										<tr>
											<td valign="middle">'.(string)$customer_field_data->label.':</td>
											<td>';

							$choices_array = explode(',', (string)$customer_field_data->choices->choice);

							echo '
												<select name="'.$customer_field_name.'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$customer_field_name]) && $GLOBALS['vars'][$customer_field_name] == trim($choice_item)) {
									echo ' SELECTED';
								}
								elseif (!empty($GLOBALS['customer_info']->customer->$customer_field_name->data) && $GLOBALS['customer_info']->customer->$customer_field_name->data == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
						}
						elseif ((string)$customer_field_data->type == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$customer_field_data->label.':</td>
											<td>';

										$choices_array = explode(',', (string)$customer_field_data->choices->choice);
										$raw_selected_array = (!empty($GLOBALS['customer_info']->customer->$customer_field_name->data)) ? explode(',', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data) : array();
										$raw_returned_choices = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : array() ;

										foreach ($raw_selected_array as $selected_item) {
											$selected_array[] = trim($selected_item);
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$customer_field_name.'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}
											elseif (!empty($selected_array) && in_array($choice_item, $selected_array)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}
					}
				}
			}
		}

	// Submit button
	echo '
									<tr>
										<td colspan="2" align="center">
											<hr>
											<input type="button" value="'.$GLOBALS['hard_coded_content']['submit_order'].'" class="button up large" name="myinfo_submit" style="margin:15px 0px"
													onMouseOver="button_hilite(this);"
													onMouseOut="button_dim(this);"
													onClick="this.form.submit();"></td>
										</td>
									</tr>';
	// close table and divs:
	echo '
								</table>
							</form>
						</div>
					</div>';

	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">Error accessing your information</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}
function Show_Redeem_Result() {

	// Get the customer's information
	if (!isset($GLOBALS['customer_info'])) {
		get_customer_info();
	}

	if (!empty($GLOBALS['customer_info'])) {

		# ==============================
		# Balances / Campaigns List Tabs
		# ==============================
		Show_Program_List();

		# ==============================
		# Content
		# ==============================

		// Set a minimum content size based on the side-content.
		$content_height = $GLOBALS['campaign_counter'] * 90;
		if ($GLOBALS['buyx_counter'] > 1) {
			$content_height = $content_height + (($GLOBALS['buyx_counter'] - 1)*40);
		}

		echo '
					<div class="grid_66 left content_selected" style="min-height: '.$content_height.'px;">
						<div class="content_left breathing_room">
							<div class="section_header content_center">'.editor('redeem_result_success_title', 25, 0).'</div>';

		echo '
							<form action="index.php" method="POST" name="confirm_form">
								<input type="hidden" name="action" value="redeem">
								<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign_to_show'].'">
								<input type="hidden" name="which_campaign_type" value="'.$GLOBALS['vars']['which_campaign_type'].'">
								'.common_form_elements().'
								<table cellspacing="0" cellpadding="4" border="0" width="100%">';

		// Show product to be shipped:
		// --------------------------
		// Show product confirmation header:
		echo '
									<tr>
										<td colspan="2">
											<span class="large bold">'.editor('redeem_shipped_header', 20, 0).'</span>
											<hr>
											<span class="normal">'.editor('redeem_shipped_text', 30, 3).'</span>
										</td>
									</tr>';

		// Show product to be shipped:
		echo '
									<tr>
										<td colspan="2" align="center">';

		echo '
											<table cellpadding="0" cellspacing="10" border="0" class="reward_table">
												<tr>';

								// Show Image
								//echo '
								//					<td rowspan="2" align="center" valign="top">
								//						<img src="'.$GLOBALS['vars']['which_reward_image_url'].'">
								//					</td>';

								// Show Title
								if ($GLOBALS['vars']['which_campaign_type'] == 'buyx') {
									echo '
													<td align="left" valign="top" class="reward_title">
													<span class="large">1</span> '.urldecode($GLOBALS['vars']['which_reward_description']).'</td>';
								} else {
									echo '
													<td align="left" valign="top" class="reward_title">'.urldecode($GLOBALS['vars']['which_reward_description']).'</td>';
								}
								echo '
												</tr>
											</table>';

		echo '
										</td>
									</tr>';

		// Spacer
		echo '
									<tr>
										<td colspan="2" class="tiny">&nbsp;</td>
									</tr>';

		// Show address Shipped to:
		// -------------------------
		// Show header:
		echo '
									<tr>
										<td colspan="2">
											<span class="large bold">'.editor('redeem_shipped_address_header', 20, 0).'</span>
											<hr>
											<span class="normal">'.editor('redeem_shipped_address_text', 30, 3).'</span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<table cellpadding="0" cellspacing="4" border="0">';

		if (!empty($GLOBALS['preferences']['customer_fields_order'])) {
			foreach ($GLOBALS['preferences']['customer_fields_order'] as $field_sorted) {
				if (in_array($field_sorted, $GLOBALS['preferences']['fields_to_show'])
					 || $field_sorted == 'card_number'
					 || $field_sorted == 'customer_password') {

					// Normal fields:
					if (strpos($field_sorted, 'custom_field_') === false || $field_sorted == 'custom_field_1') {

						if ($field_sorted == 'first_name') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->first_name.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'last_name') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_lastname', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->last_name.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'phone') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_phone', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->phone.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'email') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_email', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->email.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'custom_date') {
							// Breakdown the date:
							$date_parts = explode('-', $GLOBALS['customer_info']->customer->custom_date);
							$year 		= $date_parts[0];
							$month 		= $date_parts[1];
							$day 			= $date_parts[2];

							echo '
										<tr>
											<td>'.editor('myinfo_label_date', 20, 0).':</td>
											<td class="address_confirm">';
											if ($year == '0000' && $month == '00' && $day=='00') {
												echo '';
											} else {
												if ($GLOBALS['european_dates']) {
													echo $day.'/'.$month;
												} else {
													echo $month.'/'.$day;
												}
												if ($year != '0000') { echo '/'.$year; }
											}
											echo '</td>
										</tr>';
						}
						elseif ($field_sorted == 'address1' || $field_sorted == 'street1') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_address', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->street1.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'address2' || $field_sorted == 'street2') {
							if (!empty($GLOBALS['customer_info']->customer->street2)) {
								echo '
										<tr>
											<td></td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->street2.'</td>
										</tr>';
							}
						}
						elseif ($field_sorted == 'city') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_city', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->city.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'state') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_state', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->state.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'zip') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_zip', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->postal_code.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'country') {
							echo '
										<tr>
											<td>'.editor('myinfo_label_country', 20, 0).':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->country.'</td>
										</tr>';
						}
						elseif ($field_sorted == 'custom_field_1' ) {
							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->custom_field.'</td>
										</tr>';
						}

					}
					else { // Custom fields:

						if ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Text') {
							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->data.'</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Date') {

							// Breakdown the date:
							$date_parts_raw = explode(' ', (string)$GLOBALS['customer_info']->customer->$field_sorted->data);
							$date_parts = explode('-', $date_parts_raw[0]);
							$year 		= $date_parts[0];
							$month 		= $date_parts[1];
							$day 			= $date_parts[2];

							echo '
										<tr>
											<td>'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">';
											if ($year == '0000' && $month == '00' && $day=='00') {
												echo '';
											} else {
												if ($GLOBALS['european_dates']) {
													echo $day.'/'.$month;
												} else {
													echo $month.'/'.$day;
												}
												if ($year != '0000') { echo '/'.$year; }
											}
											echo '</td>
										</tr>';

						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'Pick') {
							echo '
										<tr>
											<td valign="middle">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->$field_sorted->data.'</td>
										</tr>';
						}
						elseif ((string)$GLOBALS['customer_info']->customer->$field_sorted->type == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$GLOBALS['customer_info']->customer->$field_sorted->label.':</td>
											<td class="address_confirm">';

										$choices_array = explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->choices);
										$raw_selected_array = (!empty($GLOBALS['customer_info']->customer->$field_sorted->data)) ? explode(',', (string)$GLOBALS['customer_info']->customer->$field_sorted->data) : array();
										$raw_returned_choices = (!empty($GLOBALS['vars'][$field_sorted])) ? $GLOBALS['vars'][$field_sorted] : array() ;

										foreach ($raw_selected_array as $selected_item) {
											$selected_array[] = trim($selected_item);
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($selected_array as $ignore => $custom_item) {
											echo '
												'.trim($custom_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}
					}
				}
			}
		}
		else {  // preferences for field order have never been set

			// First Name:
			if (!isset($GLOBALS['customer_normal_fields']['first_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['first_name']['show']) && $GLOBALS['customer_normal_fields']['first_name']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['first_name'])) {
						$first_name_to_ship = $GLOBALS['vars']['first_name'];
					} elseif (!empty($GLOBALS['customer_info']->customer->first_name)) {
						$first_name_to_ship = $GLOBALS['customer_info']->customer->first_name;
					} else {
						$first_name_to_ship = '';
					}
					echo '
													<tr>
														<td>'.editor('myinfo_label_firstname', 20, 0).':</td>
														<td class="address_confirm">'.$first_name_to_ship.'</td>
													</tr>';
			}
			// Last Name:
			if (!isset($GLOBALS['customer_normal_fields']['last_name']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['last_name']['show']) && $GLOBALS['customer_normal_fields']['last_name']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['last_name'])) {
						$last_name_to_ship = $GLOBALS['vars']['last_name'];
					} elseif (!empty($GLOBALS['customer_info']->customer->last_name)) {
						$last_name_to_ship = $GLOBALS['customer_info']->customer->last_name;
					} else {
						$last_name_to_ship = '';
					}
					echo '
													<tr>
														<td>'.editor('myinfo_label_lastname', 20, 0).':</td>
														<td class="address_confirm">'.$last_name_to_ship.'</td>
													</tr>';
			}
			// Address 1
			if (!isset($GLOBALS['customer_normal_fields']['address1']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address1']['show']) && $GLOBALS['customer_normal_fields']['address1']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['street1'])) {
						$address1_to_ship = $GLOBALS['vars']['street1'];
					} elseif (!empty($GLOBALS['customer_info']->customer->street1)) {
						$address1_to_ship = $GLOBALS['customer_info']->customer->street1;
					} else {
						$address1_to_ship = '';
					}
					echo '
													<tr>
														<td>'.editor('myinfo_label_address', 20, 0).':</td>
														<td class="address_confirm">'.$address1_to_ship.'</td>
													</tr>';
			}
			// Address 2
			if (!isset($GLOBALS['customer_normal_fields']['address2']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['address2']['show']) && $GLOBALS['customer_normal_fields']['address2']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['street2'])) {
						$address2_to_ship = $GLOBALS['vars']['street2'];
					} elseif (!empty($GLOBALS['customer_info']->customer->street2)) {
						$address2_to_ship = $GLOBALS['customer_info']->customer->street2;
					}
					if (!empty($address2_to_ship)) {
						echo '
													<tr>
														<td></td>
														<td class="address_confirm">'.$address2_to_ship.'</td>
													</tr>';
					}
			}
			// City
			if (!isset($GLOBALS['customer_normal_fields']['city']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['city']['show']) && $GLOBALS['customer_normal_fields']['city']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['city'])) {
						$city_to_ship = $GLOBALS['vars']['city'];
					} elseif (!empty($GLOBALS['customer_info']->customer->city)) {
						$city_to_ship = $GLOBALS['customer_info']->customer->city;
					} else {
						$city_to_ship = '';
					}
					echo '
													<tr>
														<td>'.editor('myinfo_label_city', 20, 0).':</td>
														<td class="address_confirm">'.$city_to_ship.'</td>
													</tr>';
			}
			// State
			if (!isset($GLOBALS['customer_normal_fields']['state']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['state']['show']) && $GLOBALS['customer_normal_fields']['state']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['state'])) {
						$state_to_ship = $GLOBALS['vars']['state'];
					} elseif (!empty($GLOBALS['customer_info']->customer->state)) {
						$state_to_ship = $GLOBALS['customer_info']->customer->state;
					} else {
						$state_to_ship = '';
					}
					echo '
													<tr>
														<td>'.editor('myinfo_label_state', 20, 0).':</td>
														<td class="address_confirm">'.$state_to_ship.'</td>
													</tr>';
			}
			// ZIP
			if (!isset($GLOBALS['customer_normal_fields']['zip']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['zip']['show']) && $GLOBALS['customer_normal_fields']['zip']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['postal_code'])) {
						$postal_code_to_ship = $GLOBALS['vars']['postal_code'];
					} elseif (!empty($GLOBALS['customer_info']->customer->postal_code)) {
						$postal_code_to_ship = $GLOBALS['customer_info']->customer->postal_code;
					} else {
						$postal_code_to_ship = '';
					}
					echo '
													<tr>
														<td>'.editor('myinfo_label_zip', 20, 0).':</td>
														<td class="address_confirm">'.$postal_code_to_ship.'</td>
													</tr>';
			}
			// Country
			if (!isset($GLOBALS['customer_normal_fields']['country']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['country']['show']) && $GLOBALS['customer_normal_fields']['country']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['country'])) {
						$country_ship_to = $GLOBALS['vars']['country'];
					} elseif (!empty($GLOBALS['customer_info']->customer->country)) {
						$country_ship_to = $GLOBALS['customer_info']->customer->country;
					} else {
						$country_ship_to = '';
					}

					echo '
													<tr>
														<td>'.editor('myinfo_label_country', 20, 0).':</td>
														<td class="address_confirm">'.$country_ship_to.'</td>
													</tr>';
			}
			// Phone
			if (!isset($GLOBALS['customer_normal_fields']['phone']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['phone']['show']) && $GLOBALS['customer_normal_fields']['phone']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['phone'])) {
						$phone_ship_to = $GLOBALS['vars']['phone'];
					} elseif (!empty($GLOBALS['customer_info']->customer->phone)) {
						$phone_ship_to = $GLOBALS['customer_info']->customer->phone;
					} else {
						$phone_ship_to = '';
					}

					echo '
													<tr>
														<td>'.editor('myinfo_label_phone', 20, 0).':</td>
														<td class="address_confirm">'.$phone_ship_to.'</td>
													</tr>';
			}
			// Email
			if (!isset($GLOBALS['customer_normal_fields']['email']['show'])
				|| (isset($GLOBALS['customer_normal_fields']['email']['show']) && $GLOBALS['customer_normal_fields']['email']['show'] != 'N')) {

					if (!empty($GLOBALS['vars']['email'])) {
						$email_ship_to = $GLOBALS['vars']['email'];
					} elseif (!empty($GLOBALS['customer_info']->customer->email)) {
						$email_ship_to = $GLOBALS['customer_info']->customer->email;
					} else {
						$email_ship_to = '';
					}

					echo '
													<tr>
														<td>'.editor('myinfo_label_email', 20, 0).':</td>
														<td class="address_confirm">'.$email_ship_to.'</td>
													</tr>';
			}

			foreach ($GLOBALS['customer_fields']->account->fields->field as $ignore => $customer_field_data) {
				$customer_field_name = (string)$customer_field_data->name;
				if (strpos($customer_field_name, 'custom_field_') !== false && $customer_field_name != 'custom_field_1') {

					if ((string)$customer_field_data->show != 'N') {

						if ((string)$customer_field_data->type == 'Text') {
							echo '
										<tr>
											<td>'.(string)$customer_field_data->label.':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->$customer_field_name->data.'</td>
										</tr>';
						}
						elseif ((string)$customer_field_data->type == 'Date') {
							echo '
										<tr>
											<td valign="middle">'.(string)$customer_field_data->label.':</td>
											<td class="address_confirm">'.(string)$GLOBALS['customer_info']->customer->$customer_field_name->data.'</td>
										</tr>';

						}
						elseif ((string)$customer_field_data->type == 'Pick') {
							echo '
										<tr>
											<td valign="middle">'.(string)$customer_field_data->label.':</td>
											<td class="address_confirm">'.$GLOBALS['customer_info']->customer->$customer_field_name->data.'</td>
										</tr>';
						}
						elseif ((string)$customer_field_data->type == 'List') {
							echo '
										<tr>
											<td valign="top">'.(string)$customer_field_data->label.':</td>
											<td class="address_confirm">';

										$choices_array = explode(',', (string)$customer_field_data->choices->choice);
										$raw_selected_array = (!empty($GLOBALS['customer_info']->customer->$customer_field_name->data)) ? explode(',', (string)$GLOBALS['customer_info']->customer->$customer_field_name->data) : array();
										$raw_returned_choices = (!empty($GLOBALS['vars'][$customer_field_name])) ? $GLOBALS['vars'][$customer_field_name] : array() ;

										foreach ($raw_selected_array as $selected_item) {
											$selected_array[] = trim($selected_item);
										}

										foreach ($raw_returned_choices as $returned_item) {
											$returned_choices[] = trim($returned_item);
										}

										foreach ($selected_array as $ignore => $custom_item) {
											echo '
												'.trim($custom_item).'<br>';
										}
										echo '
											</td>
										</tr>';
						}
					}
				}
			}
		}
		//

		echo '
											</table>
										</td>
									</tr>';

	// OK button
	echo '
									<tr>
										<td colspan="2" align="center">
											<hr>
											<input type="button" value="'.$GLOBALS['hard_coded_content']['OK'].'" class="button up large" name="myinfo_submit" style="margin:15px 0px"
													onMouseOver="button_hilite(this);"
													onMouseOut="button_dim(this);"
													onClick="this.form.submit();"></td>
										</td>
									</tr>';
	// close table and divs:
	echo '
								</table>
							</form>
						</div>
					</div>';

	}
	else {
		echo '
					<div class="grid_100 content_center">
						<br><br><br><br><br>
						<div class="error">Error accessing your information</div><br>
						<br><br><br><br><br><br><br><br>
					</div>';
	}
}
function Show_Rules() {

	# ==============================
	# Rules Content
	# ==============================
		echo '
					<div class="grid_100 left content_selected">
						<div class="content_left home_breathing_room">
							<div class="section_header">'.editor('rules_title', 25, 0).'</div>
							'.editor('rules_content', 60, 30).'
						</div>
					</div>';
}



//
# ======================

?>
