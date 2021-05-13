<?php

use AdvesaGroup\EpDental\CustomServices;

class EP_Form_Wizard_Block_Services extends EP_Mondula_Form_Wizard_Block {

	private $_label;

	protected static $type = 'fw-services';

	/**
	 * @param string $label
	 */
	public function __construct( $label ) {
		$this->_label = $label;
	}

	public function render( $ids ) {
		$all_services = CustomServices::get_services();

		$value = '';
		if ( isset( $_REQUEST['ep_services'] ) && is_array( $_REQUEST['ep_services'] ) ) {
			$_services = wp_parse_list( $_REQUEST['ep_services'] );

			$services = array_filter( $all_services, function ( $service ) use ( $_services ) {
				return in_array( $service['uid'], $_services, true );
			} );

			$services = array_map( function ( $service ) {
				return sprintf( '%s ($%s)', $service['name'], $service['price'] );
			}, $services );

			$value = implode( ', ', $services );
		}

		?>

        <div
            class="fw-step-block"
            data-blockId="<?php echo $ids[0]; ?>"
            data-type="fw-services"
            data-label="<?php echo $this->_label ?>"
        >
            <input
                type="hidden"
                id="ep-text-<?php echo str_replace( ' ', '-', strtolower( $this->_label ) ); ?>"
                class="fw-text-input"
                value="<?php echo esc_attr( $value ); ?>"
            />
        </div>
		<?php
	}

	public function as_aa() {
		return [
			'type' => 'services',
			'label' => $this->_label,
		];
	}

	public static function from_aa( $aa, $current_version, $serialized_version ) {
		return new static( $aa['label'] );
	}

	public static function addType( $types ) {
		$types['services'] = [
			'class' => static::class,
			'title' => __( 'Services (Drag to Form for show result)', 'multi-step-form' ),
			'show_admin' => true,
		];

		return $types;
	}
}

add_filter( 'multi-step-form/block-types', [ EP_Form_Wizard_Block_Services::class, 'addType' ], 20 );
