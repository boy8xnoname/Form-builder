<?php
/**
 * WordPress settings API class
 *
 * @author Alexander Njemz
 */
class EP_Mondula_Form_Wizard_Settings {

	private $settings_api;
	private static $_instance = null;
	private $_parent = null;

	function __construct($parent) {
		$this->settings_api = new EP_Mondula_Form_Wizard_Settings_API;

		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'admin_menu'),10);

		$this->_parent = $parent;
	}

	/**
	 * Initialzie the settings and call the functions for fields and sections..
	 */
	function admin_init() {
		//set the settings
		$this->settings_api->set_sections($this->get_settings_sections());
		$this->settings_api->set_fields($this->get_settings_fields());
		//initialize settings
		$this->settings_api->admin_init();
	}

	/**
	 * Register the admin menu.
	 */
	function admin_menu() {
		add_submenu_page('ep-multistep-forms', 'Settings', 'Settings', 'manage_options', 'EP_Mondula_Form_Wizard_settings', array($this, 'plugin_page'));
		// old location: add_options_page('Multi Step Form', 'Multi Step Form', 'delete_posts', 'EP_Mondula_Form_Wizard_settings', array($this, 'plugin_page'));
	}

	/**
	 * Get the settings sections that are displayed in horizontal tabs.
	 */
	function get_settings_sections() {
		$sections = array(
			array(
				'id' => 'fw_settings_email',
				'title' => __('Email', 'multi-step-form'),
			),
			array(
				'id' => 'fw_settings_styling',
				'title' => __('Layout Styling', 'multi-step-form'),
			),
			array(
				'id' => 'fw_typography_styling',
				'title' => __('Typography', 'multi-step-form'),
			),
			array(
				'id' => 'fw_settings_color',
				'title' => __('Color', 'multi-step-form'),
			),
			array(
				'id' => 'fw_settings_captcha',
				'title' => __('Captcha', 'multi-step-form'),
			),
			// array(
			// 	'id' => 'fw_settings_help',
			// 	'title' => __('Help?', 'multi-step-form'),
			// ),
		);

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {
		$settings_fields = array(
			'fw_settings_email' => array(
				array(
					'name' => 'mailformat',
					'label' => __('Mail Format', 'multi-step-form'),
					'desc' => __('Choose formatting for form emails', 'multi-step-form'),
					'type' => 'radio',
					'options' => array(
						'html' => 'HTML',
						'text'  => 'Plain Text',
					),
					'default' => 'html',
				),
				array(
					'name'  => 'showsummary',
					'label' => __('Summary', 'multi-step-form'),
					'desc'  => __('Display Summary at the end of each form', 'multi-step-form'),
					'type'  => 'checkbox',
					'default' => 'off',
				),
			),
			'fw_settings_styling' => array(
				// array(
				// 	'name' => 'websitelogo',
				// 	'label' => __('Website Logo', 'multi-step-form'),
				// 	'desc' => __('Select logo for show on form multi steps question', 'multi-step-form'),
				// 	'type' => 'file',
				// ),
				array(
					'name' => 'progressbar',
					'label' => __('Progress Bar', 'multi-step-form'),
					'desc' => __('Show progress bar', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'on',
				),
				array(
					'name' => 'progressbartitle',
					'label' => __('Progress Bar Title', 'multi-step-form'),
					'desc' => __('Show progress bar title on each step <em style="color: red;">(When check to this input on each progress step will show label with tooltip)</em>', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'showlaststep',
					'label' => __('Show Last Step of Progress Bar', 'multi-step-form'),
					'desc' => __('Show Last Step <em style="color: red;">(When check to this input progressbar will show last step)</em>', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'boxlayout',
					'label' => __('Boxed Layout', 'multi-step-form'),
					'desc' => __('Boxed frontend styling. <em style="color: red;">(Check for content of each section show in boxed. Uncheck the checkbox to get a plain layout.)</em>', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'boxtitle',
					'label' => __('Show Box Tile', 'multi-step-form'),
					'desc' => __('Show Box Title on Each Section <em style="color: red;">(When check to this input on frontend each box of section in multi step form will show title)</em>', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'showfieldicon',
					'label' => __('Show Icon On Input Field', 'multi-step-form'),
					'desc' => __('Show Icon on input field <em style="color: red;">(When check to this input field on frontend will show icon on left of field)</em>', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),
			),

			'fw_typography_styling'  => array(
				array(
					'name' => 'label_font_size',
					'label' => __('Label Font Size', 'multi-step-form'),
					'desc' => __('This field for admin can setup font size label of each field on multi step form.', 'multi-step-form'),
					'type' => 'number',
					'default' => '',
				),
				array(
					'name' => 'option_font_size',
					'label' => __('Option Font Size', 'multi-step-form'),
					'desc' => __('This field for admin can setup radio/checkbox option font size', 'multi-step-form'),
					'type' => 'number',
					'default' => '',
				),
				array(
					'name' => 'tooltip_icon_font_size',
					'label' => __('Tooltip Icon Font Size', 'multi-step-form'),
					'desc' => __('This field for admin can setup font size of icon show tooltip', 'multi-step-form'),
					'type' => 'number',
					'default' => '',
				),
				array(
					'name' => 'tooltip_wrap_font_size',
					'label' => __('Tooltip Wrap Font Size', 'multi-step-form'),
					'desc' => __('This field for admin can setup font size of tooltip', 'multi-step-form'),
					'type' => 'number',
					'default' => '',
				),
			),

			'fw_settings_color'  => array(
				array(
					'name' => 'activecolor',
					'label' => __('Active Step Color', 'multi-step-form'),
					'desc' => __('Choose a color for the active step', 'multi-step-form'),
					'type' => 'color',
					'default' => '#1d7071',
				),
				array(
					'name' => 'donecolor',
					'label' => __('Visited Step Color', 'multi-step-form'),
					'desc' => __('Choose a color for the completed steps', 'multi-step-form'),
					'type' => 'color',
					'default' => '#43a047',
				),
				array(
					'name' => 'nextcolor',
					'label' => __('Next Step Color', 'multi-step-form'),
					'desc' => __('Choose a color for the steps to follow', 'multi-step-form'),
					'type' => 'color',
					'default' => '#aaa',
				),
				array(
					'name' => 'buttonbackcolor',
					'label' => __('Button Back Text Title Color', 'multi-step-form'),
					'desc' => __('Choose a color for the buttons', 'multi-step-form'),
					'type' => 'color',
					'default' => '#297F6D',
				),
				array(
					'name' => 'buttonbackbgcolor',
					'label' => __('Button Back Background Color', 'multi-step-form'),
					'desc' => __('Choose a color for the buttons', 'multi-step-form'),
					'type' => 'color',
					'default' => '#A5CEC5',
				),
				array(
					'name' => 'buttonnextcolor',
					'label' => __('Button Next Title Color', 'multi-step-form'),
					'desc' => __('Choose a color for the buttons', 'multi-step-form'),
					'type' => 'color',
					'default' => '#ffffff',
				),
				array(
					'name' => 'buttonnextbgcolor',
					'label' => __('Button Next Background Color', 'multi-step-form'),
					'desc' => __('Choose a color for the buttons', 'multi-step-form'),
					'type' => 'color',
					'default' => '#297F6D',
				),
				array(
					'name' => 'buttoncolor',
					'label' => __('Button Submit Title Color', 'multi-step-form'),
					'desc' => __('Choose a color for the buttons', 'multi-step-form'),
					'type' => 'color',
					'default' => '#ffffff',
				),
				array(
					'name' => 'buttonbgcolor',
					'label' => __('Button Submit Background Color', 'multi-step-form'),
					'desc' => __('Choose a color for the buttons', 'multi-step-form'),
					'type' => 'color',
					'default' => '#297F6D',
				),
			),
			'fw_settings_captcha' => array(
				array(
					'name' => 'recaptcha_enable',
					'label' => __('reCAPTCHA', 'multi-step-form'),
					'desc' => __('Enable reCAPTCHA v2', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),
				array(
					'name' => 'recaptcha_sitekey',
					'label' => __('reCAPTCHA site key', 'multi-step-form'),
					'desc' => __('Public reCAPTCHA site key', 'multi-step-form'),
					'type' => 'text',
					'default' => '',
				),
				array(
					'name' => 'recaptcha_secretkey',
					'label' => __('reCAPTCHA secret key', 'multi-step-form'),
					'desc' => __('Private reCAPTCHA validation key', 'multi-step-form'),
					'type' => 'text',
					'default' => '',
				),
				array(
					'name' => 'recaptcha_invisible',
					'label' => __('reCAPTCHA Invisible', 'multi-step-form'),
					'desc' => __('Use the invisible mode instead of the checkbox.<br/>Must be the same as in the reCAPTCHA admin console.', 'multi-step-form'),
					'type' => 'checkbox',
					'default' => 'off',
				),

			),
			// 'fw_settings_help' => array(
			// 	array(
			// 		'name' => 'helpcontent',
			// 		'label' => __('reCAPTCHA Invisible', 'multi-step-form'),
			// 		'desc' => __('Use the invisible mode instead of the checkbox.<br/>Must be the same as in the reCAPTCHA admin console.', 'multi-step-form'),
			// 		'type' => 'checkbox',
			// 		'default' => 'off',
			// 	),
			// ),
		);
		return $settings_fields;
	}

	/**
	 * Define the plugin page markup.
	 */
	function plugin_page() {
		echo '<div class="wrap">';
			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();
		echo '</div>';
	}

	/**
	 * Get all the pages
	 * @return array page names with key value pairs
	 */
	function get_pages() {
		$pages = get_pages();
		$pages_options = array();
		if ($pages) {
			foreach ($pages as $page) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}
		return $pages_options;
	}

	/**
	 * Main EP_Mondula_Form_Wizard_Settings Instance
	 * Ensures only one instance of EP_Mondula_Form_Wizard_Settings is loaded or can be loaded.
	 */
	public static function instance($parent) {
		if (is_null(self::$_instance)) {
			self::$_instance = new self($parent);
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->parent->_version);
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->parent->_version);
	}
}
