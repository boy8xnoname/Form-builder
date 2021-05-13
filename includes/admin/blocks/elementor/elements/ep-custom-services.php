<?php
namespace EP_Dental\Widgets;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Utils ;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class EP_Dental_Custom_Services extends Widget_Base {
	public function get_name() {
		return 'custom-services';
	}
	public function get_title() {
		return __( 'EP Dental - Custom Services', 'ep-dental-financial' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-form-horizontal';
	}
	public function get_categories() {
		return [ 'ep-dental-financial-elements' ];
	}
	protected function _register_controls() {
        
        $this->start_controls_section(
            'heading_setting',
            [
                'label' => __('Custom Services Heading Setting', 'ep-dental-financial')
            ]
        );
            $this->add_control(
                'sub_heading_title',
                [
                    'type' => Controls_Manager::TEXT,
                    'label' => __('Sub Heading Title', 'ep-dental-financial'),
                    'label_block' => true,
                    'separator' => 'before',
                    'default' => __('Brighten your day with our ', 'ep-dental-financial'),
                ]
            );

            $this->add_control(
                'main_heading_title',
                [
                    'type' => Controls_Manager::TEXT,
                    'label' => __('Main Heading Title', 'ep-dental-financial'),
                    'label_block' => true,
                    'separator' => 'before',
                    'default' => __('Adult Exam and Basic Cleaning with Free Teeth Whitening', 'ep-dental-financial'),
                ]
            );
            $this->add_control(
                'editor',
                [
                    'label' => '',
                    'type' => Controls_Manager::WYSIWYG,
                    'default' => __( 'Anything else that you are interested in?', 'ep-dental-financial' ),
                ]
            );
        $this->end_controls_section();
  
        $this->start_controls_section(
            'heading_color_setting',
            [
                'label' => __('Color setting', 'ep-dental-financial'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );     

            $this->add_control(
                'main_heading_color',
                [
                    'label' => __( 'Main Title Color', 'ep-dental-financial' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .vintech_city-Heading .block-title' => 'color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_control(
                'sub_heading_color',
                [
                    'label' => __( 'Sub Title Color', 'ep-dental-financial' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .vintech_city-Heading .block-sub-title' => 'color: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section();            

    }
	protected function render( $instance = [] ) {
        $settings = $this->get_settings();
        $siteLogo  = plugins_url().'/EP-multi-step-form-for-apply/dist/styles/images/EP_Logo_Symbols-Care.png';
		?>
		<div class="ep-dental-financial-custom-services">
            <div class="ep-dental-financial-custom-services-wrap">
                <div class="heading">
                    <div class="heading-main-title">
                        <span class="sub-title"><?php echo $settings['sub_heading_title']; ?></span>
                        <h2 class="main-title"><?php echo $settings['main_heading_title']; ?></h2>
                    </div>
                    <div class="heading-logo">
                        <img src="<?php echo $siteLogo; ?>" alt="website logo">
                    </div>
                </div>
                <div class="main-content">
                    <div class="price-from">
                        <label for="price-from"><?php esc_html_e( 'from', 'ep-dental-financial' ); ?></label>
                        <h2 class="price-from-main">$<?php echo get_option( 'ep_dental_service_base_price' ) ?: '0';?>/<?php esc_html_e( 'month', 'ep-dental-financial' ); ?></h2>
                        <span class="total-time-apply"><?php esc_html_e( 'for 12 months', 'ep-dental-financial' ); ?></span>                       
                    </div>
                    <div class="description">
                        <?php echo $settings['editor']; ?>
                    </div>
                    <?php echo do_shortcode('[ep_dental_services]');?>
                </div>
                                  
            </div>
		</div>
		<?php
	}
	protected function _content_template() {}
}