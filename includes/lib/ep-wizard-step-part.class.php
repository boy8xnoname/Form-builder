<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Description of class-ep-multistep-forms-wizard-step
 *
 * @author alex
 */
class EP_Mondula_Form_Wizard_Wizard_Step_Part {

	private $_title;

	protected $_blocks;

	private $_settingColumn;
	private $_directionColumn;
	
	private $_customID;
	private $_customClass;

	public function __construct($title, $blocks, $settingColumn, $directionColumn, $customID, $customClass) {
		$this->_title = $title;
		$this->_blocks = $blocks;
		$this->_settingColumn = $settingColumn;
		$this->_directionColumn = $directionColumn;
		
		$this->_customID = $customID;
		$this->_customClass = $customClass;
	}

	public function same_title(EP_Mondula_Form_Wizard_Wizard_Step_Part $that) {
		return $this->_title === $that->_title;
	}

	public function render_title() {
		echo $this->_title;
	}

	public function render_settingColumn() {
		echo $this->_settingColumn;
	}

	public function render_directionColumn() {
		echo $this->_directionColumn;
	}

	public function render_customID() {
		echo $this->_customID;
	}

	public function render_customClass() {
		echo $this->_customClass;
	}

	public function render_body($wizard_id, $step_id, $part_id) {
		$cnt = count($this->_blocks);		
		// var_dump($cnt);
		$ids = array($wizard_id, $step_id, $part_id);
		$widthClass ='';
		switch ($cnt) {
			case 3:
				$widthClass = 'fw-one_third';
				break;
		    case 2:
				$widthClass = 'fw-one_half';
				break;
			default:
				$widthClass = 'fw-one_full';
				break;
		}
		?>
			<div class="fw-step-part-body-wrap <?php echo $this->_customClass; ?> <?php echo $this->_customID; ?> <?php echo 'flex-'.$this->_settingColumn; ?> <?php echo 'flex-direction-'.$this->_directionColumn; ?> <?php echo $widthClass; ?>">
			<?php
			for ($i = 0; $i < $cnt; $i++) {
				$block = $this->_blocks[ $i ];
				?>
					<?php
					if (isset($block)) {
						array_push($ids, $i);
						$block->render($ids);
						array_pop($ids);
					}
					?>
				<?php
			}
			?>
			</div>
		<?php
	}

	public function as_aa() {
		$blocks_aa = array();
		foreach ($this->_blocks as $block) {
			if ($block) {
				$blocks_aa[] = $block->as_aa();
			}
		}
		return array(
			'title' => $this->_title,
			'blocks' => $blocks_aa,
			'settingColumn' => $this->_settingColumn,
			'directionColumn' => $this->_directionColumn,
			'customID' => $this->_customID,
			'customClass' => $this->_customClass
		);
	}

	public static function from_aa($aa, $current_version, $serialized_version) {
		$title = isset($aa['title']) ? $aa['title'] : '';
		$settingColumn = isset($aa['settingColumn']) ? $aa['settingColumn'] : 'one-column';
		$directionColumn = isset($aa['directionColumn']) ? $aa['directionColumn'] : 'row';

		$customID = isset($aa['customID']) ? $aa['customID'] : '';
		$customClass = isset($aa['customClass']) ? $aa['customClass'] : '';
		$blocks = array();

		if (isset($aa['blocks'])) {
			foreach ($aa['blocks'] as $block) {
				$blocks[] = EP_Mondula_Form_Wizard_Block::from_aa($block, $current_version, $serialized_version);
			}
		}

		return new EP_Mondula_Form_Wizard_Wizard_Step_Part($title, $blocks, $settingColumn, $directionColumn, $customID, $customClass);
	}
}
