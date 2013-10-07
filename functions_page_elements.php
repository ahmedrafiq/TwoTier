<?php

# ========================
# HTML Elements functions
# ------------------------

// Page Elements
function Show_HTML_Headers() {
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8">
		<title>'.$GLOBALS['site_window_name'].'</title>
		<link href="styles.css" rel="stylesheet" media="screen">
		<script type="text/javascript" src="display.js"></script>';
	if (verify_admin()) {
		echo '
		<link href="editor.css" rel="stylesheet" media="screen">
		<script type="text/javascript" src="editor.js"></script>';
	}
	echo '
	</head>
	<body onKeyDown="if(event.which==27){hide_edit();}">
		<div id="wrapper">';
}

function Show_HTML_Top_Bar() {

	if (verify_admin()) {
		echo editor_form();
	}
	echo '
			<div id="top_banding">
				<div class="container">';
	if (!empty($GLOBALS['vars']['user_name'])
		 && (!empty($GLOBALS['user']['verified']) && $GLOBALS['user']['verified'] === true)) {
		echo '
					<div class="grid_50 left">'.editor('label_user', 12, 0).' '.(string)$GLOBALS['user']['user_name'].'</div>';
	}
	else {
		echo '
					<div class="grid_50 left">'.editor('top_bar_upper_left', 12, 0).'</div>';
	}
	echo '
					<div class="grid_50 left content_right">'.editor('top_bar_upper_right', 40, 0).'</div>
				</div>
			</div>';
}

function Show_HTML_Main_Nav() {

	echo '
			<div id="main_nav_bar" style="height: '.$GLOBALS['navigation_height'].'px;">
				<div class="container">
					<div class="grid_25 left left_rule nav_bg">
						<div id="logo" style="height: '.$GLOBALS['navigation_height'].'px;">
							<img src="'.$GLOBALS['logo_location'].'" alt="" width="'.$GLOBALS['logo_width'].'" height="'.$GLOBALS['logo_height'].'" border="0" style="margin-top: '.$GLOBALS['logo_offset_top'].'px; margin-left: '.$GLOBALS['logo_offset_left'].'px;">
						</div>
					</div>
					<div id="main_nav" class="grid_75 left right_rule content_right nav_bg" style="height: '.$GLOBALS['navigation_height'].'px;">';

	// Public Nav
	if (empty($GLOBALS['vars']['public_nav']) || $GLOBALS['vars']['public_nav'] != 'N') {
		echo '
						<ul>';

		$menu_size = sizeof($GLOBALS['preferences']['public_top_nav']);
		$menu_counter = 1;
		foreach ($GLOBALS['preferences']['public_top_nav'] as $nav_item_to_show) {

			if (empty($GLOBALS['vars']['action']) && $nav_item_to_show == 'login') {
				echo '
							<li class="';
				echo ($menu_counter < $menu_size) ? 'separator ' : '';
				echo 'hilite" '.nav_link('menu','login',true).'>'.editor('nav_item_login', 12, 0).'</li>';
			} else
			if (!empty($GLOBALS['vars']['action']) && $GLOBALS['vars']['action'] == $nav_item_to_show) {
				echo 	'
							<li class="';
				echo ($menu_counter < $menu_size) ? 'separator ' : '';
				echo 'hilite" '.nav_link('menu',$nav_item_to_show,true).'>'.editor('nav_item_'.$nav_item_to_show, 20, 0).'</li>';
			}
			else {
				echo 	'
							<li class="';
				echo ($menu_counter < $menu_size) ? 'separator ' : '';
				echo 'dim" '.nav_link('menu',$nav_item_to_show,false).'>'.editor('nav_item_'.$nav_item_to_show, 20, 0).'</li>';
			}
			$menu_counter ++;
		}

		echo 			'</ul>';
		// Output the menu submission form:
		echo '
						<form action="index.php" method="post" name="menu_form">
							'.common_form_elements().'
							<input type="hidden" name="action" value="none" border="0">
						</form>';
	}
	// Validated customer
	else {
		echo '
						<ul>';

		/*
		 if ($GLOBALS['vars']['action'] == 'home') {
				echo '
							<li class="separator hilite" '.nav_link('menu','home',true).'>'.editor('nav_item_home', 20, 0).'</li>';
		} else {
			echo '
							<li class="separator dim" '.nav_link('menu','home',false).'>'.editor('nav_item_home', 20, 0).'</li>';
		}
		*/

		$menu_size = sizeof($GLOBALS['preferences']['loggedin_top_nav']);
		$menu_counter = 1;

		foreach ($GLOBALS['preferences']['loggedin_top_nav'] as $nav_item_to_show) {
			if ($GLOBALS['vars']['action'] == $nav_item_to_show) {
				echo '
							<li class="';
				echo ($menu_counter < $menu_size) ? 'separator ' : '';
				echo 'hilite" '.nav_link('menu',$nav_item_to_show,true).'>'.editor('nav_item_'.$nav_item_to_show, 20, 0).'</li>';
			} else {
				echo '
							<li class="';
				echo ($menu_counter < $menu_size) ? 'separator ' : '';
				echo 'dim" '.nav_link('menu',$nav_item_to_show,false).'>'.editor('nav_item_'.$nav_item_to_show, 20, 0).'</li>';
			}
			$menu_counter ++;
		}

		/*
		if ($GLOBALS['vars']['action'] == 'logout') {
				echo '
							<li class="hilite" '.nav_link('menu','logout',true).'>'.editor('nav_item_logout', 20, 0).'</li>';
		} else {
			echo '
							<li class="dim" '.nav_link('menu','logout',false).'>'.editor('nav_item_logout', 20, 0).'</li>';
		}
		*/


		echo 			'</ul>';

		// ================================
		// Output the menu submission form:
		// ================================
		echo '
						<form action="index.php" method="post" name="menu_form">
							'.common_form_elements().'
							<input type="hidden" name="action" value="none" border="0">';
		if (isset($GLOBALS['vars']['which_campaign']))	{
			echo '
							<input type="hidden" name="which_campaign" value="'.$GLOBALS['vars']['which_campaign'].'">';
		}
		echo '
						</form>';
	}
	//
	echo '
					</div>
				</div>
			</div>';
}

function Show_HTML_Customer_Nav() {

	if (!empty($GLOBALS['customer_info']->customer->code) && (string)$GLOBALS['customer_info']->attributes()->status == 'success') {
			// Customer has been found and loaded into "memory"

		echo '
			<div id="customer_nav_bar">
				<div class="container">
					<div class="grid_33_3 right content_right customer_nav right_rule">
						<div class="customer_name">
							'.editor('myinfo_label_client', 20, 0).': <span class="bold">';
		if (!empty($GLOBALS['customer_info']->customer->first_name)) {
			echo $GLOBALS['customer_info']->customer->first_name;
		}
		if (!empty($GLOBALS['customer_info']->customer->last_name)) {
			echo ' '.$GLOBALS['customer_info']->customer->last_name;
		}
		if (empty($GLOBALS['customer_info']->customer->first_name)
			 && empty($GLOBALS['customer_info']->customer->first_name)) {
			echo $GLOBALS['customer_info']->customer->card_number;
		}
		echo '</span>
						</div>
					</div>
					<div class="grid_66 right content_left customer_nav left_rule">';

		if (empty($GLOBALS['vars']['public_nav']) || $GLOBALS['vars']['public_nav'] != 'N') {
			// This can only appear when a user is logged in. so ignore.
		}
		else {  // Validated user
			echo '
						<ul>';

			$menu_size = sizeof($GLOBALS['preferences']['loggedin_customer_top_nav']);
			$menu_counter = 1;
			$default_campaign_type = 1;

			get_campaign_list ();

			foreach ($GLOBALS['campaigns_list']->campaigns->campaign as $customer_campaign_data) {
				if ($default_campaign_type == 1) {
					$campaign_type = (string)$customer_campaign_data->type;
				}
				if (!empty($GLOBALS['vars']['which_campaign']) && (string)$customer_campaign_data->id == $GLOBALS['vars']['which_campaign']) {
					$campaign_type = (string)$customer_campaign_data->type;
				}
				$default_campaign_type ++;
			}

			foreach ($GLOBALS['preferences']['loggedin_customer_top_nav'] as $nav_item_to_show) {
				if ($nav_item_to_show == 'add') { $nav_item_to_show = 'selected_customer'; }
				if ($GLOBALS['vars']['action'] == 'add') { $GLOBALS['vars']['action'] = 'selected_customer'; }
				if ($GLOBALS['vars']['what_to_show'] == $nav_item_to_show || $GLOBALS['vars']['action'] == $nav_item_to_show) {
					echo '
							<li class="';
					echo ($menu_counter < $menu_size) ? 'separator ' : '';
					echo 'customer_hilite" '.nav_link('menu',$nav_item_to_show,true).'>';
					if ($nav_item_to_show == 'selected_customer') {
						if ($campaign_type == 'points') {
							echo editor('add_section_title_points', 20, 0);
						} elseif ($campaign_type == 'giftcard') {
							echo editor('add_section_title_giftcard', 20, 0);
						} elseif ($campaign_type == 'events') {
							echo editor('add_section_title_events', 20, 0);
						} elseif ($campaign_type == 'earned') {
							echo editor('add_section_title_earned', 20, 0);
						} elseif ($campaign_type == 'buyx') {
							echo editor('add_section_title_buyx', 20, 0);
						}
					} else {
						echo editor('nav_item_'.$nav_item_to_show, 20, 0);
					}

					echo '</li>';
				} else {
					echo '
							<li class="';
					echo ($menu_counter < $menu_size) ? 'separator ' : '';
					echo 'customer_dim" '.nav_link('menu',$nav_item_to_show,false).'>';
					if ($nav_item_to_show == 'selected_customer') {
						if ($campaign_type == 'points') {
							echo editor('add_section_title_points', 20, 0);
						} elseif ($campaign_type == 'giftcard') {
							echo editor('add_section_title_giftcard', 20, 0);
						} elseif ($campaign_type == 'events') {
							echo editor('add_section_title_events', 20, 0);
						} elseif ($campaign_type == 'earned') {
							echo editor('add_section_title_earned', 20, 0);
						} elseif ($campaign_type == 'buyx') {
							echo editor('add_section_title_buyx', 20, 0);
						}
					} else {
						echo editor('nav_item_'.$nav_item_to_show, 20, 0);
					}

					echo '</li>';
				}
				$menu_counter ++;
			}
			echo 			'</ul>';
		}
		echo '
					</div>
				</div>
			</div>';
	}
	else {
			// No customer. Ignore. Show nothing.
	}
}

function Show_HTML_Content_Top() {
	echo '
			<div id="main_content">
				<div class="container">';
}
function Show_HTML_Content() {
	if (empty($GLOBALS['vars']['what_to_show']) || $GLOBALS['vars']['what_to_show'] == 'login') {
		Show_Login();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'new_account_how') {
		Show_New_Account_How();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'activate_account') {
		Show_Activate_Acount();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'new_account') {
		Show_New_Account();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'reports') {
		Show_Reports();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'home') {
		Show_Home();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'myinfo') {
		Show_MyInfo();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'myinfo_changed') {
		Show_MyInfo_Summary();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'balance') {
		Show_Balances();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'customer_list') {
		Show_Customer_List();
	}
	 if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'add') {
		Show_Add_Transaction();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'redeem') {
		Show_Redeem();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'confirm_redeem') {
		Show_Confirm_Redeem();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'redeem_result') {
		Show_Redeem_Result();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'rules') {
		Show_Rules();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'admin_login') {
		Show_Admin_Login();
	}
	if (!empty($GLOBALS['vars']['what_to_show']) && $GLOBALS['vars']['what_to_show'] == 'admin') {
		Show_Admin();
	}
}
function Show_HTML_Content_Bottom() {
	echo '
				</div>
			</div>';
}

function Show_HTML_Footer() {

	echo '
			<div id="footer_bar">
				<div class="container">
					<div class="grid_33_3 left content_left left_rule footer_box footer_separator">
						<div class="breathing_room">
							'.editor('footer_section_left_content', 20, 0).'
						</div>
					</div>
					<div class="grid_33_3 left content_left footer_box footer_separator footer_shim">
						<div class="breathing_room">
							'.editor('footer_sitemap_title', 12, 0).':';
	// Public Nav:
	if (empty($GLOBALS['vars']['public_nav']) || $GLOBALS['vars']['public_nav'] != 'N') {
		echo '
							<ul>';

		foreach ($GLOBALS['preferences']['public_footer_links'] as $footer_item_to_show) {
			echo '
								<li class="footer_dim" '.nav_link('footer',$footer_item_to_show).'>'.editor('nav_item_'.$footer_item_to_show, 20, 0).'</li>';
		}

		if (verify_admin()) {
			echo '
								<li class="footer_dim">&nbsp;</li>
								<li class="footer_dim" '.nav_link('footer','admin_logged_in').'>'.editor('nav_item_admin_logged_in', 12, 0).'</li>
								<li class="footer_dim" '.nav_link('footer','admin_logout').'>'.editor('nav_item_admin_logout', 15, 0).'</li>';
		} else {
			echo '
								<li class="footer_dim">&nbsp;</li>
								<li class="footer_dim" '.nav_link('footer','admin_login').'>'.editor('nav_item_admin_login', 12, 0).'</li>';
		}
		echo '
							</ul>';
	}
	//
	// User Logged-in Nav:
	else {
		echo '
							<ul>';

		// Customer Nav Items
		if (!empty($GLOBALS['customer_info']->customer->code)) {
			if ((string)$GLOBALS['customer_info']->attributes()->status == 'success') {
				foreach ($GLOBALS['preferences']['loggedin_customer_footer_links'] as $footer_item_to_show) {
					echo '
								<li class="footer_dim" '.nav_link('footer',$footer_item_to_show).'>'.editor('nav_item_'.$footer_item_to_show, 20, 0).'</li>';
				}
				echo '
								<li class="footer_dim">&nbsp;</li>';
			}
		}

		// User Nav Items: Show only search and exit menu items, and rules if selected:
		//echo '
		//						<li class="footer_dim" '.nav_link('footer','home').'>'.editor('nav_item_home', 20, 0).'</li>';
		foreach ($GLOBALS['preferences']['loggedin_footer_links'] as $footer_item_to_show) {
			//if ($footer_item_to_show == 'rules') {
				echo '
								<li class="footer_dim" '.nav_link('footer',$footer_item_to_show).'>'.editor('nav_item_'.$footer_item_to_show, 25, 0).'</li>';
			//}
		}
		//echo '
		//						<li class="footer_dim" '.nav_link('footer','logout').'>'.editor('nav_item_logout', 20, 0).'</li>';


		// Admin Nav Items:
		if (verify_admin()) {
			echo '
								<li class="footer_dim">&nbsp;</li>
								<li class="footer_dim" '.nav_link('footer','admin_logged_in').'>'.editor('nav_item_admin_logged_in', 12, 0).'</li>
								<li class="footer_dim" '.nav_link('footer','admin_logout').'>'.editor('nav_item_admin_logout', 12, 0).'</li>';
		}
		else {
			echo '
								<li class="footer_dim">&nbsp;</li>
								<li class="footer_dim" '.nav_link('footer','admin_login').'>'.editor('nav_item_admin_login', 12, 0).'</li>';
		}

		echo '
							</ul>';

	}
	echo '
						</div>
					</div>
					<div class="grid_33_3 left right_rule content_left footer_box">
						<div class="breathing_room">
							'.editor('footer_contact_title', 20, 0).':<br>
							'.editor('footer_contact_content', 30, 5).'
						</div>
					</div>
				</div>
			</div>';
}

function Show_HTML_Close() {
	echo '
		</div>';

	echo '
	</body>
</html>';
}

//
function Show_HTML_Amount_Box () {
	$prev_amount = (!empty($GLOBALS['vars']['amount'])) ? $GLOBALS['vars']['amount'] : '';

	// Error message:
	if (!empty($GLOBALS['error']['points_amount'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error']['points_amount'], 20, 0).'</td>
											</tr>';
	}
	if (!empty($GLOBALS['error']['giftcard_amount'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error']['giftcard_amount'], 20, 0).'</td>
											</tr>';
	}

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
												</td>
											</tr>';
}
function Show_HTML_Promo_Box () {

	get_promotions ();

	if ($GLOBALS['campaign_promos']['status'] == 'success') {
		echo '
											<tr>
												<td align="left" valign="top" class="large">'.editor('add_promotion_label', 20, 0).'</td>
												<td align="left" valign="top">
													<select class="promo_select" name="promo_id" size="1">
														<option value="">'.$GLOBALS['hard_coded_content']['None'].'</option>';
		$prev_promo = (!empty($GLOBALS['vars']['promo_id'])) ? $GLOBALS['vars']['promo_id'] : '';
		foreach ($GLOBALS['campaign_promos']->promotions->promotion as $discard => $promo_info) {
			echo '
														<option value="'.(string)$promo_info->id.'"';
			if (!empty($prev_promo) && $prev_promo == (string)$promo_info->id) {
				echo ' SELECTED';
			}
			echo '>'.$promo_info->description.'</option>';
		}
		echo '
													</select></td>
											</tr>';
	} else {
		// Promotions size is zero: No promotions defined.
	}
}
function Show_HTML_Items_Pulldown () {

	// Show any error message
	if (!empty($GLOBALS['error']['service_product'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error']['service_product'], 20, 0).'</td>
											</tr>';
	}

	get_buyx_items ();

	// Items Pulldown
	if ($GLOBALS['campaign_buyx_items']['status'] == 'success') {

		// Sort the list:
		$sorted_buyx_items = array();
		$sorted_buyx_items_itterator = 0;
		foreach ($GLOBALS['campaign_buyx_items']->buyx_items->item as $item_info) {
			if (!empty($sorted_buyx_items[(string)$item_info->name])) {
				$sorted_buyx_items[strtolower((string)$item_info->name).$sorted_buyx_items_itterator] = $item_info;
			} else {
				$sorted_buyx_items[(string)$item_info->name] = $item_info;
			}
			$sorted_buyx_items_itterator++;
		}
		ksort($sorted_buyx_items);



		echo '
											<tr>
												<td align="left" valign="middle" class="large">'.editor('add_buyx_item_label', 20, 0).'</td>
												<td align="left" valign="top">
													<select class="promo_select" id="service_product" name="service_product" size="1">';

		$prev_item = (!empty($GLOBALS['vars']['service_product'])) ? $GLOBALS['vars']['service_product'] : '';
		foreach ($sorted_buyx_items as $discard => $item_info) {
			echo '
														<option ';
			if (!empty($prev_item) && $prev_item == (string)$item_info->id) {
				echo ' SELECTED';
			}
			echo 'value="'.$item_info->id.'">'.$item_info->description.'</option>';
		}
		echo '
													</select></td>
											</tr>';
	} else {
		// Items list size is zero: No BuyX items defined!
	}
}
function Show_HTML_Quantity_Box () {

	$prev_amount = (!empty($GLOBALS['vars']['amount'])) ? $GLOBALS['vars']['amount'] : '';
	echo '
											<tr>
												<td align="left" valign="middle" class="large">'.editor('quantity_label', 20, 0).'</td>
												<td align="left" valign="top"><input id="add_quantity_textbox"
														class="text_field content_right larger"
														autocomplete="off"
														type="text"
														name="amount"
														value="'.$prev_amount.'"
														size="5"
														maxlength="255"
														border="0">
												</td>
											</tr>';
}
function Show_HTML_Description_Box () {
	$prev_desc = (!empty($GLOBALS['vars']['transaction_description'])) ? $GLOBALS['vars']['transaction_description'] : '';
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
function Show_HTML_Email_Receipt_Checkbox () {
	if (!empty($GLOBALS['customer_info']->customer->email)) {
		$prev_email = (!empty($GLOBALS['vars']['send_transaction_email']) && $GLOBALS['vars']['send_transaction_email'] == 'Y') ? true : false;
		echo '
											<tr>
												<td align="left" valign="middle" class="big">'.editor('send_email_label', 20, 0).'</td>
												<td align="left" valign="top" class="big">
													<input ';
		if ($prev_email) {
			echo 'SELECTED ';
		}
		echo 'type="checkbox" name="send_transaction_email" value="Y" border="0"> '.editor('send_email_yes', 20, 0).'</td>
												</td>
											</tr>';
	} else {
		// No email address in customer record
	}
}

//
function Show_HTML_Text_Box ($field_data) {

	$prev_amount = (!empty($GLOBALS['vars'][$field_data['name']])) ? $GLOBALS['vars'][$field_data['name']] : '';
	echo '
											<tr>
												<td align="left" valign="top" class="big">'.$field_data['label'].'</td>
												<td align="left" valign="top">
													<input 	class="text_field"
																type="text"
																name="'.$field_data['name'].'"
																value="'.$prev_amount.'"
																size="30"
																maxlength="255"
																border="0"
																autocomplete="off"
																spellcheck="off">
												</td>
											</tr>';
}
function Show_HTML_Date_Selector ($field_data) {

	// Show any error message
	if (!empty($GLOBALS['error'][$field_data['name']]['custom_date'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error'][$field_data['name']]['custom_date'], 20, 0).'</td>
											</tr>';
	}

	if (!empty($GLOBALS['vars'][$field_data['name'].'_year'])) {
		$year = $GLOBALS['vars'][$field_data['name'].'_year'];
	}
	elseif (isset($date_array)) {
		$year = $date_array[0];
	}
	else {
		$year = '0000';
	}

	if (!empty($GLOBALS['vars'][$field_data['name'].'_month'])) {
		$month = $GLOBALS['vars'][$field_data['name'].'_month'];
	}
	elseif (isset($date_array)) {
		$month = $date_array[1];
	}
	else {
		$month = '00';
	}

	if (!empty($GLOBALS['vars'][$field_data['name'].'_day'])) {
		$day = $GLOBALS['vars'][$field_data['name'].'_day'];
	}
	elseif (isset($date_array)) {
		$day = $date_array[2];
	}
	else {
		$day = '00';
	}
	$prev_value = $year.'-'.$month.'-'.$day;

	echo '
										<tr>
											<td align="left" valign="middle" class="big">'.$field_data['label'].':</td>
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
															<select name="'.$field_data['name'].'_year" size="1" id="'.$field_data['name'].'_year" onBlur="custom_date_set(\''.$field_data['name'].'\');">';
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
															<select name="'.$field_data['name'].'_month" size="1" id="'.$field_data['name'].'_month" onBlur="custom_date_set(\''.$field_data['name'].'\');">';
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
															<select name="'.$field_data['name'].'_day" size="1" id="'.$field_data['name'].'_day" onBlur="custom_date_set(\''.$field_data['name'].'\');">';
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
												<input type="hidden" name="'.$field_data['name'].'" id="'.$field_data['name'].'" value="'.$prev_value.'">
											</td>
										</tr>';
}
function Show_HTML_List ($field_data) {

	// Show any error message
	if (!empty($GLOBALS['error'][$field_data['name']]['custom_list'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error'][$field_data['name']]['custom_list'], 20, 0).'</td>
											</tr>';
	}

	// These are all the choices for this custom field:
	$choices_array = $field_data['choices'];


	// These are the previously selected choices, in case the page is returned to with an error:
	if (!empty($GLOBALS['vars'][$field_data['name']])) {
		$raw_returned_choices = $GLOBALS['vars'][$field_data['name']];
	}
	else {
		$raw_returned_choices = array();
	}
	// trim any extra white spaces:
	foreach ($raw_returned_choices as $returned_item) {
		$returned_choices[] = trim($returned_item);
	}

	echo '
										<tr>
											<td align="left" valign="top" class="big">'.$field_data['label'].':</td>
											<td align="left" valign="top" class="big">';
										foreach ($choices_array as $ignore => $choice_item) {
											echo '
												<input type="checkbox" name="'.$field_data['name'].'[]" value="'.trim($choice_item).'"';

											if (!empty($returned_choices) && in_array(trim($choice_item), $returned_choices)) {
												echo ' CHECKED';
											}

											echo ' border="0"> '.trim($choice_item).'<br>';
										}
										echo '
											</td>
										</tr>';
}
function Show_HTML_Pick_List_Pulldown ($field_data) {

	// Show any error message
	if (!empty($GLOBALS['error'][$field_data['name']]['custom_pick'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error'][$field_data['name']]['custom_pick'], 20, 0).'</td>
											</tr>';
	}

	// These are all the choices for this custom field:
	$choices_array = $field_data['choices'];

	echo '
										<tr>
											<td align="left" valign="middle" class="big">'.$field_data['label'].':</td>
											<td align="left">
												<select name="'.$field_data['name'].'">';
							foreach ($choices_array as $ignore => $choice_item) {
								echo '
													<option value="'.trim($choice_item).'"';
								if (isset($GLOBALS['vars'][$field_data['name']]) && $GLOBALS['vars'][$field_data['name']] == trim($choice_item)) {
									echo ' SELECTED';
								}
								echo '>'.$choice_item.'</option>';
							}
							echo '
												</select>
											</td>
										</tr>';
}
function Show_HTML_Number_Box ($field_data) {

	// Show any error message
	if (!empty($GLOBALS['error'][$field_data['name']]['custom_number'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error'][$field_data['name']]['custom_number'], 20, 0).'</td>
											</tr>';
	}

	$prev_amount = (!empty($GLOBALS['vars'][$field_data['name']])) ? $GLOBALS['vars'][$field_data['name']] : '';

	echo '
											<tr>
												<td align="left" valign="top" class="big">'.$field_data['label'].'</td>
												<td align="left" valign="top">
													<input 	class="text_field"
																type="number"
																name="'.$field_data['name'].'"
																value="'.$prev_amount.'"
																size="15"
																maxlength="255"
																border="0"
																autocomplete="off"
																spellcheck="off">
												</td>
											</tr>';
}
function Show_HTML_Monetary_Box ($field_data) {

	// Show any error message
	if (!empty($GLOBALS['error'][$field_data['name']]['custom_monetary'])) {
		echo '
											<tr>
												<td></td>
												<td align="left" class="error">'.editor($GLOBALS['error'][$field_data['name']]['custom_monetary'], 20, 0).'</td>
											</tr>';
	}

	$prev_amount = (!empty($GLOBALS['vars'][$field_data['name']])) ? $GLOBALS['vars'][$field_data['name']] : '';

	echo '
											<tr>
												<td align="left" valign="top" class="big">'.$field_data['label'].'</td>
												<td align="left" valign="top">
													<input 	class="text_field"
																type="number"
																step="0.01"
																name="'.$field_data['name'].'"
																value="'.$prev_amount.'"
																size="15"
																maxlength="255"
																border="0"
																autocomplete="off"
																spellcheck="off">
												</td>
											</tr>';
}
//
function Show_HTML_Submit_Add ($to_hilite) {
	echo '
											<tr>
												<td colspan="2" align="center" valign="top">
													<script type="text/javascript">
														var add_box = document.getElementById("'.$to_hilite.'");
														add_box.focus();
													</script>
													<input type="button" value="'.$GLOBALS['hard_coded_content']['record'].'" class="button up large add_submit" name="transaction_submit"
														onMouseOver="button_hilite(this);"
														onMouseOut="button_dim(this);"
														onClick="this.form.submit();"></td>
											</tr>';
}
# ======================

?>
