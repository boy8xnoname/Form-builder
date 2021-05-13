<?php

class Test_ep_Admin_Validation extends WP_UnitTestCase {

	function test_valid_date() {
        $actual = array(
            'type' => 'date',
			'label' => 'The date',
			'required' => '',
			'format' => ''
        );
        $expected = array(
            'type' => 'date',
			'label' => 'The date',
			'required' => '',
			'format' => ''
        );

        EP_Mondula_Form_Wizard_Admin::sanitize_form_block($actual);

        $this->assertEquals($expected, $actual);
    }

    function test_valid_email() {
        $actual = array(
			'type' => 'email',
			'label' => 'The email',
			'required' => 'checked'
		);
        $expected = array(
			'type' => 'email',
			'label' => 'The email',
			'required' => 'checked'
		);

        EP_Mondula_Form_Wizard_Admin::sanitize_form_block($actual);

        $this->assertEquals($expected, $actual);
    }

    function test_valid_file() {
        $actual = array(
			'type' => 'file',
			'label' => 'The file',
			'required' => '',
			'multi' => 'true',
		);
        $expected = array(
			'type' => 'file',
			'label' => 'The file',
			'required' => '',
			'multi' => 'true',
		);

        EP_Mondula_Form_Wizard_Admin::sanitize_form_block($actual);

        $this->assertEquals($expected, $actual);
    }

    function test_valid_paragraph() {
        $actual = array(
			'type' => 'paragraph',
			'text' => 'Multiline\nText with <b>Tags!</b>'
		);
        $expected = array(
			'type' => 'paragraph',
			'text' => 'Multiline\nText with <b>Tags!</b>'
		);

        EP_Mondula_Form_Wizard_Admin::sanitize_form_block($actual);

        $this->assertEquals($expected, $actual);
    }

    function test_invalid_paragraph_xss1() {
        $actual = array(
			'type' => 'paragraph',
			'text' => 'Multiline\nText with <b>Tags!</b></textarea><script>alert(1);</script>'
		);
        $expected = array(
			'type' => 'paragraph',
			'text' => 'Multiline\nText with <b>Tags!</b>alert(1);'
		);

        EP_Mondula_Form_Wizard_Admin::sanitize_form_block($actual);

        $this->assertEquals($expected, $actual);
    }

    function test_invalid_paragraph_xss2() {
        $actual = array(
			'type' => 'paragraph',
			'text' => 'Multiline\nText with <b>Tags!</b><img src=x onerror=alert(1);/>'
		);
        $expected = array(
			'type' => 'paragraph',
			'text' => 'Multiline\nText with <b>Tags!</b><img src="x" />'
		);

        EP_Mondula_Form_Wizard_Admin::sanitize_form_block($actual);

        $this->assertEquals($expected, $actual);
    }
}
