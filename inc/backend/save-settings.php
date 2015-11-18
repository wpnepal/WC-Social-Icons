<?php

$temp_icons_array = array();
foreach ( $_POST['wcsi_settings']['icons'] as $icon => $icon_detail ) {
	$temp_icons_array[$icon] = array_map( 'sanitize_text_field', $icon_detail );
}
$icons_order = array_map( 'sanitize_text_field', $_POST['wcsi_settings']['icons_order'] );
$wcsi_settings = array( 'icons' => $temp_icons_array, 'icons_order' => $icons_order );
update_option('wcsi_settings',$wcsi_settings);
$_SESSION['wcsi_message'] = __('Settings saved successfully','wc-social-icons');
wp_redirect(admin_url('admin.php?page=wc-social-icons'));
exit;

