<?php

/**
 * Data model for a Step in a form.
 *
 * @author alex
 */
class EP_Mondula_Form_Wizard_Wizard_Step {

	private $_title;
	private $_headline;
	private $_copy_text;
	private $_parts;

	private $_settingColumn;
	private $_directionColumn;

	private $_customID;
	private $_customClass;

	public function __construct ($title, $headline, $copy_text, $parts) {
		$this->_title = $title;
		$this->_headline = $headline;
		$this->_copy_text = $copy_text;
		$this->_parts = $parts;

		// $this->_settingColumn = $settingColumn;
		// $this->_directionColumn = $directionColumn;

		// $this->_customID = $customID;
		// $this->_customClass = $customClass;
	}

	private function _get_class ($len) {
		// switch ($len) {
		//     case 3:
		//         return 'fw-one_third';
		//     case 2:
		//         return 'fw-one_half';
		//     default:
		//         return '';
		// }
		return '';
	}

	public function render($wizardId, $stepId) {
		$cnt = count($this->_parts);
		$width = $this->_get_class($cnt);
		$boxlayout = (EP_Mondula_Form_Wizard_Wizard::fw_get_option('boxlayout', 'fw_settings_styling', 'on') === 'on') ? '' : ' fw-plain-layout';
		$boxtitle = (EP_Mondula_Form_Wizard_Wizard::fw_get_option('boxtitle', 'fw_settings_styling', 'on') === 'on') ? '' : ' hidden-title';

		for ($i = 0; $i < $cnt; $i++) {
			$part = $this->_parts[$i];
			if ($i > 0 && $part->same_title($this->_parts[$i - 1])) {
				$hidden = ' fw-title-hidden';
			} else {
				$hidden = '';
			}
			?>
			<div id="<?php  $part->render_customID(); ?>" class="fw-step-part <?php echo $width; echo $boxlayout; ?> <?php  $part->render_customClass() ?> <?php  $part->render_customClass() ?>" data-partId="<?php echo $i ?>">
				<h2 class="fw-step-part-title <?php echo $hidden; ?> <?php echo $boxtitle;?>">
					<?php $part->render_title(); ?>
				</h2>
				<div class="fw-clearfix"></div>
				<div id="step-part-body-<?php  $part->render_customID(); ?>" class="fw-step-part-body  part-body-<?php  $part->render_customClass() ?>">
						<?php
						$part->render_body($wizardId, $stepId, $i);
						?>
				</div>
			</div>
			<?php
		}
	}

	public function render_title () {
		echo $this->_title;
	}

	public function render_customID() {
		echo $this->_customID;
	}

	public function render_customClass() {
		echo $this->_customClass;
	}

	public function render_settingColumn() {
		echo $this->_settingColumn;
	}

	public function render_directionColumn() {
		echo $this->_directionColumn;
	}


	public function render_headline () {
		echo $this->_headline;
	}

	public function render_copy_text () {
		echo $this->_copy_text;
	}

	public function as_aa() {
		$parts_aa = array();
		foreach ($this->_parts as $part) {
			$parts_aa[] = $part->as_aa();
		}
		return array(
			'title' => $this->_title,
			'headline' => $this->_headline,
			'copy_text' => $this->_copy_text,
			'parts' => $parts_aa,

			'settingColumn' => $parts_aa,
			'directionColumn' => $parts_aa,

			'customID' => $parts_aa,
			'customClass' => $parts_aa,
		);
	}

	public static function from_aa($aa, $current_version, $serialized_version) {
		// var_dump($aa);
		$title = isset($aa['title']) ? $aa['title'] : '';
		$headline = isset($aa['headline']) ? $aa['headline'] : '';
		$copy_text = isset($aa['copy_text']) ? $aa['copy_text'] : '';
		$parts = array();

		if (isset($aa['parts'])) {
			foreach ($aa['parts'] as $part) {
				$parts[] = EP_Mondula_Form_Wizard_Wizard_Step_Part::from_aa($part, $current_version, $serialized_version );
			}
		}

		return new EP_Mondula_Form_Wizard_Wizard_Step($title, $headline, $copy_text, $parts);
	}
}
