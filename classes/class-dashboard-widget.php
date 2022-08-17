<?php


class Dashboard_Widget {
	/**
	 * Static property to hold our singleton instance
	 */
	public static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	private function __construct() {
		add_action('wp_dashboard_setup', [ $this, 'setup_dashboard_widget' ] );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return ACF_Block_Placeholder
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Sets up the dashboard widget.
	 */
	public function setup_dashboard_widget() {
		global $wp_meta_boxes;
		wp_add_dashboard_widget('kitchen_sink_widget', 'Kitchen Sink', [ $this, 'dashboard_widget_markup' ] );
	}

	/**
	 * Outputs the markup for the dashboard widget.
	 */
	public function dashboard_widget_markup() { ?>
		<p>The Kitchen Sink plugin generates a page with examples of all your blocks on it. To generate this page, please click the button below.</p>

		<p><strong>WARNING: If you already a kitchen sink page, clicking this button will overwrite any customziations you have made.</strong></p>

		<form method="post" action="">
			<input class="button" type="submit" name="generate_kitchen_sink" value="Generate">
		</form>
	<?php }
}