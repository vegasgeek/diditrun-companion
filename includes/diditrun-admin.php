<?php
/**
 * Admin functionality for Did It Run Companion.
 *
 * @package diditrun-companion
 */

/**
 * Add admin menu item for Did It Run Companion settings.
 *
 * Creates a new menu item under Settings in the WordPress admin
 * allowing users to configure the Did It Run Companion plugin.
 */
function diditrun_companion_admin_menu() {
	add_options_page(
		'Did It Run Companion Settings',
		'Did It Run Companion',
		'manage_options',
		'diditrun-companion',
		'diditrun_companion_settings_page'
	);
}
add_action( 'admin_menu', 'diditrun_companion_admin_menu' );

/**
 * Register the settings for the Did It Run Companion plugin.
 *
 * Registers a new setting for the Did It Run Companion plugin,
 * allowing users to configure the API key for the Did It Run service.
 */
function diditrun_companion_register_settings() {
	register_setting( 'diditrun_companion_settings', 'diditrun_api_key' );
}
add_action( 'admin_init', 'diditrun_companion_register_settings' );

/**
 * Render the settings page for the Did It Run Companion plugin.
 *
 * Displays a form allowing users to configure the API key for the Did It Run service.
 * Also includes a test connection button to verify the API key is working.
 */
function diditrun_companion_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<form method="post" action="options.php">
			<?php
			settings_fields( 'diditrun_companion_settings' );
			do_settings_sections( 'diditrun_companion_settings' );
			?>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="diditrun_api_key"><?php esc_html_e( 'API Key', 'diditrun-companion' ); ?></label>
					</th>
					<td>
						<input type="text" 
								id="diditrun_api_key" 
								name="diditrun_api_key" 
								value="<?php echo esc_attr( get_option( 'diditrun_api_key' ) ); ?>" 
								class="regular-text">
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<hr>

		<h2><?php esc_html_e( 'Test Connection', 'diditrun-companion' ); ?></h2>
		<form id="diditrun-test-connection-form">
			<?php wp_nonce_field( 'diditrun_test_connection', 'diditrun_test_connection_nonce' ); ?>
			<button type="submit" 
					id="diditrun-test-connection-button" 
					class="button button-secondary" 
					<?php echo empty( get_option( 'diditrun_api_key' ) ) ? 'disabled' : ''; ?>>
				<?php esc_html_e( 'Test Connection', 'diditrun-companion' ); ?>
			</button>
		</form>
		<div id="diditrun-test-connection-result"></div>
	</div>
	<?php
}

/**
 * Enqueue admin scripts for the Did It Run Companion plugin.
 *
 * Enqueues the dirms.js file for the Did It Run Companion plugin,
 * which contains the JavaScript code for the test connection button.
 *
 * @param string $hook The current admin page.
 * @return void
 */
function diditrun_companion_admin_scripts( $hook ) {
	if ( 'settings_page_diditrun-companion' !== $hook ) {
		return;
	}

	wp_enqueue_script(
		'diditrun-companion-admin',
		DIDITRUN_COMPANION_URL . 'includes/diditrun.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);

	wp_localize_script(
		'diditrun-companion-admin',
		'dirmsCompanion',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'diditrun_test_connection' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'diditrun_companion_admin_scripts' );

/**
 * Handle the test connection AJAX request.
 *
 * Handles the AJAX request for testing the connection to the Did It Run service.
 * Verifies the API key is set and sends a test request to the Did It Run service.
 * Returns a JSON response indicating success or failure.
 */
function diditrun_companion_test_connection() {
	check_ajax_referer( 'diditrun_test_connection', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Unauthorized' );
	}

	$api_key = get_option( 'diditrun_api_key' );
	if ( empty( $api_key ) ) {
		wp_send_json_error( 'API key not set. Save your API key before testing.' );
	}

	$response = wp_remote_post(
		'https://diditrun.dev/wp-json/dirms/v1/test-connection',
		array(
			'method'    => 'POST',
			'body'      => array( 'api_key' => $api_key ),
			'sslverify' => false,
		)
	);

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( 'Connection test failed. Please try again.' );  }

	$body = json_decode( wp_remote_retrieve_body( $response ) );
	if ( 'success' === $body->status ) {
		wp_send_json_success( 'Connection successful' );
	} else {
		wp_send_json_error( 'Connection failed. Please check your API key.' );
	}
}
add_action( 'wp_ajax_diditrun_companion_test_connection', 'diditrun_companion_test_connection' );
