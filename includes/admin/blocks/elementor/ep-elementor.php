<?php
namespace Cantec_Elements;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class EPElementorCustomElement {
	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		add_action( 'elementor/init', array( $this, 'add_elementor_category' ));
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'on_widgets_registered' ) );

		add_action( 'elementor/frontend/after_register_styles', array( $this, 'widget_styles' ));
		
		add_action( 'elementor/preview/enqueue_styles', function() {
			wp_enqueue_style( 'select-services-style' );
		} );
	}

	public function widget_styles() {
		wp_register_style( 'select-services-style', plugins_url('/EP-multi-step-form-for-apply/custom/dist/select-services.css'));
	}

	public function add_elementor_category()
	{
		\Elementor\Plugin::instance()->elements_manager->add_category( 'ep-dental-financial-elements', array(
			'title' => __( 'EP Financial Elements', 'ep-dental-financial' ),
			'icon' => 'fa fa-plug',
		), 1 );
	}
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}
	private function includes()
	{
		// Theme Elements
		require_once __DIR__ . '/elements/ep-custom-services.php';
	}
	private function register_widget() {	
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \EP_Dental\Widgets\EP_Dental_Custom_Services());		
	}
}
new EPElementorCustomElement();