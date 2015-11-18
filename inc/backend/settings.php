<?php
$wcsi_settings = $this->wcsi_setings;
$icons_reference = array( 'facebook' => array( 'label' => 'Facebook', 'placeholder' => 'http://facebook.com' ),
	'twitter' => array( 'label' => 'Twitter', 'placeholder' => 'http://twitter.com' ),
	'google-plus' => array( 'label' => 'GooglePlus', 'placeholder' => 'http://plus.google.com' ),
	'instagram' => array( 'label' => 'Instagram', 'placeholder' => 'http://instagram.com' ),
	'linkedin' => array( 'label' => 'LinkedIn', 'placeholder' => 'http://linkedin.com' ),
);
//$this->print_array($wcsi_settings);
?>
<div class="wrap">
	<div class="wcsi-header">
		<h1><?php _e( 'WC Social Icons', 'wc-social-icons' ); ?></h1>
	</div>
	<h3><?php _e( 'Configure Social Icons', 'wc-social-icons' ); ?></h3>
	<?php if ( isset( $_SESSION['wcsi_message'] ) ) {
		?>
		<div class="wcsi-message">
			<?php echo $_SESSION['wcsi_message'];
	unset( $_SESSION['wcsi_message'] ); ?><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.','wc-social-icons');?></span></button></div>
		<?php
	}
	?>
	<div class="wcsi-settings-wrap">
		<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
			<input type="hidden" name="action" value="wcsi_save_settings"/>
			<?php wp_nonce_field( 'wcsi_nonce', 'wcsi_nonce_field' ); ?>
			<div class="wcsi-icons-wrap">
				<?php
				foreach ( $wcsi_settings['icons_order'] as $icon ) {
					$status = isset( $wcsi_settings['icons'][$icon]['status'] ) ? esc_attr( $wcsi_settings['icons'][$icon]['status'] ) : 0;
					?>
					<div class="wcsi-each-icon-wrap">
						<span class="wcsi-drag-arrow"><i class="fa fa-arrows"></i></span>
						<span class="wcsi-icon"><?php echo $icons_reference[$icon]['label']; ?></span>
						<div class="wcsi-status-wrap wcsi-inline-block">
							<label><input type="checkbox" name="wcsi_settings[icons][<?php echo $icon; ?>][status]" value="1" <?php checked( $status, true ); ?>/><?php _e( 'Show/Hide', 'wc-social-icons' ); ?></label>
						</div>
						<div class="wcsi-url-wrap  wcsi-inline-block">
							<span class="wcsi-label"><?php _e( 'URL', 'wcsi_social_icons' ); ?></span>
							<input type="text" name="wcsi_settings[icons][<?php echo $icon; ?>][url]" value="<?php echo esc_url( $wcsi_settings['icons'][$icon]['url'] ); ?>" placeholder="<?php echo $icons_reference[$icon]['placeholder']; ?>"/>
						</div>
						<span class="wcsi-icon-preview"><i class="fa fa-<?php echo $icon?>"></i></span>
						<input type="hidden" name="wcsi_settings[icons_order][]" value="<?php echo $icon; ?>"/>

					</div>
				<?php } ?>
			</div>
			<div class="wpsi-form-submit-wrap">
				<input type="submit" value="<?php _e( 'Save Settings', 'wc-social-icons' ); ?>" name="wcsi_form_submitBtn" class="button-primary"/>
				<?php $restore_nonce = wp_create_nonce( 'wpsi-restore-nonce' ); ?>
				<a href="<?php echo admin_url( 'admin-post.php?action=wcsi_restore_settings&_wpnonce=' . $restore_nonce ) ?>" onclick="return confirm('<?php _e( 'Are you sure you want to restore the default settings?', 'wp-social-icons' ); ?>')"><input type="button" class="button-primary" value="<?php _e( 'Restore Default Settings', 'wc-social-icons' ); ?>"/></a>
			</div>
		</form>
	</div>
</div>
