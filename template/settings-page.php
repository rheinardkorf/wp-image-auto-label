<?php
/**
 * Settings Page Template.
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<hr />
	<p>
		<?php echo wp_kses_post( __( 'This project uses <strong><a href="https://cloud.google.com/vision/">Google Cloud Vision</a></strong> to add relevant topic labels on images in content through the power of AI.', 'image-auto-label' ) ); ?>
		<ol>
			<li><?php echo wp_kses_post( __( 'Make sure you have a Google Cloud Platform (GCP) project to use this plugin.', 'image-auto-label' ) ); ?></li>
			<li><?php echo wp_kses_post( __( 'Enable the <strong>Cloud Vision API</strong> from your GCP project.', 'image-auto-label' ) ); ?></li>
			<li><?php echo wp_kses_post( __( 'Follow the instructions to <a href="https://cloud.google.com/docs/authentication/api-keys?hl=en&visit_id=636756342694560026-3564123596&rd=1#Creating%20an%20API%20key">create an API key for your GCP project.</a>', 'image-auto-label' ) ); ?></li>
		</ol>
	</p>
	<hr />
	<form method="post" action="options.php">
		<?php settings_fields( 'image_auto_label_settings' ); ?>
		<?php do_settings_sections( 'image-auto-label' ); ?>
		<?php submit_button(); ?>
	</form>

</div>
