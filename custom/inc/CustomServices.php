<?php

namespace AdvesaGroup\EpDental;

class CustomServices {
	public function __construct() {
		add_action( 'multi-step-form/submenus', [ $this, 'register_submenu' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_shortcode( 'ep_dental_services', [ $this, 'shortcode' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_admin_script' ]);
	}

	public static function get_services() {
		$services = get_option( 'ep_dental_services_data' );

		if ( ! is_array( $services ) ) {
			return [];
		}

		return array_filter( $services, function ( $service ) {
			return isset( $service['name'], $service['price'] );
		} );
	}

	public static function get_service_base_price() {
		return get_option( 'ep_dental_service_base_price' ) ?: '0';
	}

	/**
	 * Format decimal numbers ready for DB storage.
	 *
	 * @param float|string $number
	 * @param mixed        $dp
	 * @return string
	 */
	public static function format_decimal( $number, $dp = false ) {
		$locale = localeconv();
		$decimals = [ '.', $locale['decimal_point'], $locale['mon_decimal_point'] ];

		// Remove locale from string.
		if ( ! is_float( $number ) ) {
			$number = str_replace( $decimals, '.', $number );

			// Convert multiple dots to just one.
			$number = preg_replace( '/\.(?![^.]+$)|[^0-9.-]/', '', sanitize_text_field( $number ) );
		}

		if ( false !== $dp ) {
			$dp = (int) ( '' === $dp ? 2 : $dp );
			$number = number_format( (float) $number, $dp, '.', '' );
		}

		return $number;
	}

	public function register_submenu() {
		$hook_suffix = add_submenu_page(
			'ep-multistep-forms',
			'Custom Services',
			'Services',
			'manage_options',
			'ep-multistep-forms-services',
			[ $this, 'render' ],
			10
		);

		add_action( 'load-' . $hook_suffix, [ $this, 'handle' ] );
		add_action( 'admin_print_scripts-' . $hook_suffix, [ $this, 'enqueue_admin_script' ] );
	}

	public function handle() {
		if ( ! empty( $_POST ) ) {
			check_admin_referer( 'save_services' );

			if ( isset( $_POST['base_price'] ) ) {
				update_option( 'ep_dental_service_base_price', self::format_decimal( $_POST['base_price'] ?? '' ) );
			}

			if ( isset( $_POST['apply_page_id'] ) ) {
				update_option( 'ep_dental_apply_page_id', absint( $_POST['apply_page_id'] ) );
			}

			if ( isset( $_POST['subTitle'] ) ) {
				update_option( 'ep_dental_subTitle', wp_unslash(sanitize_textarea_field( $_POST['subTitle'] )) );
			}

			if ( isset( $_POST['mainTitle'] ) ) {
				update_option( 'ep_dental_mainTitle',  wp_unslash(sanitize_textarea_field($_POST['mainTitle'])) );
			}

			if ( isset( $_POST['headingDescription'] ) ) {
				update_option( 'ep_dental_headingDescription',  $_POST['headingDescription']  );
			}

			if ( isset( $_POST['services'] ) ) {
				$services = array_filter(
					array_map(
						function ( $service ) {
							if ( ! isset( $service['uid'], $service['price'] ) ) {
								return null;
							}

							return [
								'uid' => sanitize_text_field( $service['uid'] ),
								'name' => sanitize_text_field( $service['name'] ),
								'price' => self::format_decimal( $service['price'] ),
								'description' => sanitize_text_field( $service['description'] ),
								'defaultSelected' => (bool) ( $service['defaultSelected'] ),
							];
						},
						$_POST['services']
					)
				);

				update_option( 'ep_dental_services_data', $services );
			}
		}
	}

	public function enqueue_admin_script() {
		$asset_url = plugins_url( '/custom/dist/', EP_Mondula_Form_Wizard()->file );
		$asset_file = dirname( __DIR__ ) . '/dist/admin-services.asset.php';

		$asset = file_exists( $asset_file )
			? include $asset_file
			: [ 'dependencies' => [], 'version' => '' ];

		wp_enqueue_style(
			'ep-multistep-forms-services',
			$asset_url . 'admin-services.css',
			[ 'wp-components' ],
			$asset['version']
		);

		wp_enqueue_script(
			'ep-multistep-forms-services',
			$asset_url . 'admin-services.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);
				
		wp_register_style(
			'ep-dental-select-services-style', 
			$asset_url . 'select-services.css'
		);
	}

	public function register_scripts() {
		$asset_url = plugins_url( '/custom/dist/', EP_Mondula_Form_Wizard()->file );
		$asset_file = dirname( __DIR__ ) . '/dist/select-services.asset.php';
		$asset = file_exists( $asset_file )
			? include $asset_file
			: [ 'dependencies' => [], 'version' => '' ];

		wp_register_script(
			'ep-dental-select-services',
			$asset_url . 'select-services.js',
			$asset['dependencies'],
			$asset['version']
		);		
		wp_register_style(
			'ep-dental-select-services-style', 
			$asset_url . 'select-services.css'
		);
	}

	public function render() {
		?>
        <div class="wrap">
            <h1 class="wp-heading-inline" style="margin-bottom: 1rem;">Services</h1>

            <form action="" method="POST">
				<?php 
					wp_nonce_field( 'save_services' ); 
					$subHeading = $mainTitle = $headingDescription = '';
					$subHeading = esc_attr(get_option( 'ep_dental_subTitle' ));
					$mainTitle = esc_attr(get_option( 'ep_dental_mainTitle' ));
					$headingDescription = esc_attr(get_option( 'ep_dental_headingDescription' ));
				?>
                <div class="ep-services-wrap">
                    <div id="ep-services-root"
                         data-base-price="<?php echo esc_attr( self::get_service_base_price() ); ?>"
                         data-services="<?php echo esc_attr( wp_json_encode( self::get_services() ) ); ?>">
                    </div>

                    <div class="ep-services-desc">
                        <div class="apply-page-setting">
                            <strong>Select the "Apply Now" page:</strong>
                            <p><?php
	                            wp_dropdown_pages( [
		                            'selected' => get_option( 'ep_dental_apply_page_id' ) ?: '0',
		                            'name' => 'apply_page_id',
								] );
								
	                            ?>
                            </p>
                        </div>

						<div class="heading-setting">
							<h4>Heading content setting</h4>
							<div class="form-input-field">
								<label for="sub-title">Sub Title</label> 
								<input type="text" name="subTitle" id="subTitle" placeholder="Brighten your day with our" value="<?php echo $subHeading;?> ">
							</div>
							<div class="form-input-field">
								<label for="main-title">Main Tile</label>
								<textarea name="mainTitle" id="mainTitle" placeholder="Adult Exam and Basic Cleaning with Free Teeth Whitening" cols="30" rows="4"><?php echo wp_unslash($mainTitle);?></textarea>
							</div>
							<div class="form-input-field">
								<label for="headingDescription">Custom Services Description</label>
								<textarea name="headingDescription" id="headingDescription" placeholder="Anything else that you're interested in?" cols="30" rows="10"><?php echo wp_unslash($headingDescription);?></textarea>
							</div>
						
						</div>

                        <div>
                            <strong>Shortcode:</strong>
                            <p><code>[ep_dental_services]</code> <span>OR</span> <code>[ep_dental_services heading="false"]</code></p>
							<p><em>Copy and paste this shortcode to page or post we need show custom services block (without heading)</em></p>
							<strong>Shortcode with heading:</strong>
							<p><code>[ep_dental_services heading="true"]</code></p>
							<p><em>Copy and paste this shortcode to page or post we need show custom services block (with heading)</em></p>

                        </div>

                        <div style="text-align: left">
                            <button type="submit" class="button button-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
		<?php
	}

	public function shortcode($atts, $content) {
		wp_enqueue_script( 'ep-dental-select-services' );
		wp_enqueue_style( 'ep-dental-select-services-style' );
		$heading = '';
		$attr = shortcode_atts( array(
            'heading' => false
        ), $atts );

		$base_price = self::get_service_base_price();
		$services = self::get_services();
		$isHeading = false;
		if(isset($atts['heading'])) {
			$isHeading = $atts['heading'];
		}
		ob_start();
			if($isHeading == 'true') {
				$subHeading = $mainTitle = $headingDescription = '';
				$siteLogo  = plugins_url().'/EP-multi-step-form-for-apply/dist/styles/images/EP_Logo_Symbols-Care.png';
				$subHeading = esc_attr(get_option( 'ep_dental_subTitle' ));
				$mainTitle = esc_attr(get_option( 'ep_dental_mainTitle' ));
				$headingDescription = esc_attr(get_option( 'ep_dental_headingDescription' ));
			?>
				<div class="ep-dental-financial-custom-services">
					<div class="ep-dental-financial-custom-services-wrap">
						<div class="heading">
							<div class="heading-main-title">
								<?php if(!empty($subHeading)) { ?>
								<span class="sub-title"><?php echo $subHeading; ?></span>
								<?php } if(!empty($mainTitle)) { ?> 
								<h2 class="main-title"><?php echo wp_unslash($mainTitle); ?></h2>
								<?php } ?>
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
							<?php if(!empty($headingDescription)) { ?>
							<div class="description">
								<?php echo wp_unslash($headingDescription); ?>
							</div>
							<?php } ?>
							<?php  include __DIR__ . '/../templates/select-services.php';?>
						</div>
										
					</div>
				</div>
			<?php
			} else {
				include __DIR__ . '/../templates/select-services.php';
			}
			?>
			<div class="about-ep-dental">
				<h3>ABOUT EP CARE FINANCING​</h3>
				<p>My Smile Family Dental is proud to offer dental products and service financing through EP Care, a division of Everyday People Financial Inc. 
					EP Care’s payment plans allow you to break down the cost of the products or services performed at My Smile Family Dental into a series of monthly installments, 
					which allows you to spread out the costs of your services over a period of time. Pre-approval is easy with a high approval rate and payments are automatic and manageable. 
					All financing is administered by the experienced and friendly team at EP Care.</p>
			</div>
		<?php

		return ob_get_clean();
	}
}
