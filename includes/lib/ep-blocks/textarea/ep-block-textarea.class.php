<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a textarea input field.
 *
 * @author alex
 */
class EP_Mondula_Form_Wizard_Block_Textarea extends EP_Mondula_Form_Wizard_Block {

	private $_label;
	private $_placeholder;
	private $_required;
	private $_fullwidth;
	private $_tooltipcontent;

	protected static $type = "fw-textarea";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required If true, Input for this field is required.
	 */
	public function __construct ($label, $tooltipcontent, $placeholder, $required, $fullwidth) {
		$this->_label = $label;
		$this->_placeholder = $placeholder;
		$this->_required = $required;
		$this->_fullwidth = $fullwidth;
		$this->_tooltipcontent = $tooltipcontent;
	}

	public function render($ids) {
		// var_dump($this->_fullwidth );
		$showFieldIcon = (EP_Mondula_Form_Wizard_Wizard::fw_get_option('showfieldicon', 'fw_settings_styling', 'on') === 'on') ? 'on' : 'off';
		?>
		<div class="fw-step-block fw-step-icon-<?php echo $showFieldIcon;?> <?php if($this->_fullwidth == 'true') {echo 'block-fullWidth';} else { echo 'block-halfWidth'; }?>" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-textarea" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<h3>
					<?php echo $this->_label ?>
					<?php if(!empty($this->_tooltipcontent)) { ?> 
						<span class="ep_tooltip" data-toggle="tooltip" data-placement="right" data-custom-class="ep-tooltip" title="<?php echo $this->_tooltipcontent;?>">
							<i class="fa fa-info-circle"></i>
						</span>
					<?php } ?>
				</h3>
				<textarea 
					class="fw-textarea"
					id="ep-textarea-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"
					placeholder="<?php echo $this->_placeholder ?>"
					data-id="textarea"></textarea>
				<?php if( $showFieldIcon == 'on') { ?>
					<span class="fa fa-pencil t-area form-control-feedback" aria-hidden="true"></span>
				<?php } ?>
			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'textarea',
			'label' => $this->_label,
			'placeholder' => $this->_placeholder,
			'required' => $this->_required,
			'fullwidth' => $this->_fullwidth,
			'tooltipcontent' => $this->_tooltipcontent
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$placeholder = $aa['placeholder'];
		$required = $aa['required'];
		$fullwidth = $aa['fullwidth'];
		$tooltipcontent = isset($aa['tooltipcontent']) ? $aa['tooltipcontent'] : array();
		return new EP_Mondula_Form_Wizard_Block_Textarea($label, $tooltipcontent, $placeholder, $required, $fullwidth);
	}

	public static function addType($types) {

		$types['textarea'] = array(
			'class' => 'EP_Mondula_Form_Wizard_Block_Textarea',
			'title' => __('Textarea', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'EP_Mondula_Form_Wizard_Block_Textarea::addType', 3);
