<?php
if (!defined('ABSPATH')) exit;

/**
 * Representation of a Agree/checkbox input field.
 *
 * @author alex
 */
class EP_Mondula_Form_Wizard_Block_Agree extends EP_Mondula_Form_Wizard_Block {

	private $_label;
	private $_required;
	private $_agreecontent;
	private $_tooltipcontent;

	protected static $type = "fw-Agree";

	/**
	 * Creates an Object of this Class.
	 * @param array $label Array of the Input-Options.
	 * @param boolean $required $required If true, Input for this field is required. 
	 */
	public function __construct ($label, $tooltipcontent, $required, $agreecontent) {
		$this->_label = $label;
		$this->_required = $required;
		$this->_agreecontent = $agreecontent;
		$this->_tooltipcontent = $tooltipcontent;
	}

	public function render($ids) {
		// var_dump($this->_agreecontent );
		$data_ids = $this->generate_id($ids);
		?>
		<div class="fw-step-block " data-blockId="<?php echo $data_ids; ?>" data-type="fw-Agree" data-required="<?php echo $this->_required; ?>">
			<?php
			// var_dump($this->_label);
			// var_dump($this->_agreecontent);
			?>
			<h3>
				<?php echo $this->_label ?>
				<?php if(!empty($this->_tooltipcontent)) { ?> 
					<span class="ep_tooltip" data-toggle="tooltip" data-placement="right" data-custom-class="ep-tooltip" title="<?php echo $this->_tooltipcontent;?>">
						<i class="fa fa-info-circle"></i>
					</span>
				<?php } ?>
			</h3>
			<div class="fw-agree fw-choice fw-input-container" data-type="fw-agree-checkbox">
				<input type="checkbox" class="fw-checkbox" name="agree_form_<?php echo $data_ids; ?>" id="agree_form_<?php echo $data_ids; ?>"> 
				<label class="fw-agree-content"> 
					<?php echo $GLOBALS['wp_embed']->run_shortcode($this->_agreecontent); ?>
				</label>
			</div>
		</div>
		<?php
	}

	public function as_aa() {
		return array(
			'type' => 'Agree',
			'label' => $this->_label,
			'agreecontent' => str_replace('&quot;', '"', $this->_agreecontent),
			'required' => $this->_required,
			'tooltipcontent' => $this->_tooltipcontent
			
		);
	}

	public static function from_aa($aa , $current_version, $serialized_version) {
		$label = isset($aa['label']) ? $aa['label'] : array();
		$tooltipcontent = isset($aa['tooltipcontent']) ? $aa['tooltipcontent'] : array();
		$required = $aa['required'];
		$agreecontent = $aa['agreecontent'];
		return new EP_Mondula_Form_Wizard_Block_Agree($label, $tooltipcontent, $required, $agreecontent);
	}

	public static function sanitize_admin($block) {
		$allowedTags = wp_kses_allowed_html('post');
		unset($allowedTags['textarea']);

		$block['agreecontent'] = wp_kses($block['agreecontent'], $allowedTags);		
		$block['tooltipcontent'] = sanitize_text_field($block['tooltipcontent']);
		$block['required'] = sanitize_text_field($block['required']);

		return $block;
	}

	public static function addType($types) {

		$types['Agree'] = array(
			'class' => 'EP_Mondula_Form_Wizard_Block_Agree',
			'title' => __('Agree Checkbox', 'multi-step-form'),
			'show_admin' => true,
		);

		return $types;
	}
}

add_filter('multi-step-form/block-types', 'EP_Mondula_Form_Wizard_Block_Agree::addType', 0);
