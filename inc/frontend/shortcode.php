<?php
$wcsi_settings = $this->wcsi_setings;
//$this->print_array( $wcsi_settings );
?>
<div class="wcsi-front-wrap">
	<?php
	foreach ( $wcsi_settings['icons_order'] as $icon ) {  //running this loop to display as per the backend icons order

		// Get social link
		$site_url = sanitize_text_field( $wcsi_settings['icons'][$icon]['url'] ) ;

		if ( isset( $wcsi_settings['icons'][$icon]['status'] ) && $wcsi_settings['icons'][$icon]['status'] == 1 && '' != $site_url ) {  // checking if the icon is enabled from backend 
			?>
			<div class="wcsi-each-icon">
				<a href="<?php echo esc_url( $site_url ); ?>" target="_blank">
					<span class="wcsi-icon">
						<i class="fa fa-<?php echo $icon;?>"></i>
					</span>
				</a>
			</div>
			<?php
		}
	}
	?>
</div>