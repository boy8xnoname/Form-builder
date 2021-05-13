<?php

if (!defined('ABSPATH')) exit;

/**
 * Representation of a date input field.
 *
 * @author alex
 */
class EP_Mondula_Form_Wizard_Block_Date extends EP_Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_format;
	private $_fullwidth;
	private $_tooltipcontent;

	protected static $type = "fw-date";

	/**
	 * Creates an Object of this Class.
	 * @param string $label The Label the Object is being created with.
	 * @param boolean $required The If true, Input for this field is required.
	 * @param string $format The Format the Date will be shown in.
	 */
	public function __construct ($label, $tooltipcontent, $required, $format,  $fullwidth) {
		$this->_label = $label;
		$this->_required = $required;
		if (!empty($format)) {
			$this->_format = $format;
		} else {
			$this->_format = 'yy-mm-dd';
		}
		$this->_tooltipcontent = $tooltipcontent;
		$this->_fullwidth = $fullwidth;
	}

	public function render($ids) {
		// var_dump($this->_fullwidth );
		$locale = substr(get_locale(), 0, 2) === 'de' ? 'de' : 'en'; // TODO this is possibly not a good idea
		$showFieldIcon = (EP_Mondula_Form_Wizard_Wizard::fw_get_option('showfieldicon', 'fw_settings_styling', 'on') === 'on') ? '' : 'off';
		?>
		<div class="fw-step-block fw-step-icon-<?php echo $showFieldIcon;?> <?php if($this->_fullwidth == 'true') {echo 'block-fullWidth';} else { echo 'block-halfWidth'; }?>" data-blockId="<?php echo $ids[0]; ?>" data-type="fw-date" data-required="<?php echo $this->_required; ?>">
			<div class="fw-input-container">
				<h3>
					<?php echo $this->_label ?>
					<?php if(!empty($this->_tooltipcontent)) { ?> 
						<span class="ep_tooltip" data-toggle="tooltip" data-placement="right" data-custom-class="ep-tooltip"  title="<?php echo $this->_tooltipcontent;?>">
							<i class="fa fa-info-circle"></i>
						</span>
					<?php } ?>
				</h3>
				<input type="text" id="ep-date-<?php echo str_replace(' ', '-', strtolower($this->_label)); ?>"  class="fw-text-input fw-datepicker-here" data-id="date" data-language="<?php echo $locale; ?>" data-dateformat="<?php echo $this->_format ?>" placeholder="mm/dd/yyyy">
				<?php if( $showFieldIcon == 'on') { ?>
					<span class="fa fa-calendar form-control-feedback" aria-hidden="true"></span>
				<?php } ?>

			</div>
			<div class="fw-clearfix"></div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'date',
			'label' => $this->_label,
			'required' => $this->_required,
			'format' => $this->_format,
			'fullwidth' => $this->_fullwidth,
			'tooltipcontent' => $this->_tooltipcontent
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = $aa['label'];
		$required = $aa['required'];
		$format = $aa['format'];
		$fullwidth = $aa['fullwidth'];
		$tooltipcontent = isset($aa['tooltipcontent']) ? $aa['tooltipcontent'] : array();
		return new EP_Mondula_Form_Wizard_Block_Date($label, $tooltipcontent, $required, $format, $fullwidth);
	}

	public static function addType($types) {

		$types['date'] = array(
			'class' => 'EP_Mondula_Form_Wizard_Block_Date',
			'title' => __('Date', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'EP_Mondula_Form_Wizard_Block_Date::addType', 7);
