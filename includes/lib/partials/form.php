<div id="multi-step-form" class="<?php echo $classes; ?>" data-stepCount="<?php echo count($this->_steps); ?>" data-wizardid="<?php echo $wizard_id; ?>">
    <?php 
    global $datal;
    $settings = $this->get_settings();
    $redirectURL = $redirectAction = '';
    $buttonTitle = 'Submit';
    $backTitle = 'Back';
    $nextTitle = 'Next';
    $redirectAction = $this->_settings['thankyouAction'];
    // Button Title
    if(!empty($settings['submitTitle'])) {
        $buttonTitle = $settings['submitTitle'];
    }
    // Back Title
    if(!empty($settings['backTitle'])) {
        $backTitle = $settings['backTitle'];
    }

    if(!empty($settings['nextTitle'])) {
        $nextTitle = $settings['nextTitle'];
    }


    if($redirectAction == 'show-thankyou-message') { ?>
        <div class="multi-step-form-wrap">
    <?php } else if ($redirectAction == 'redirect-new-page') {
        $redirectURL = $this->_settings['thankyou'];
    } else {
        // Do nothing
    } ?>

    <div class="fw-wizard-step-header-container">
        <div class="fw-container" data-redirect="<?php echo $redirectURL; ?>" data-redirect-action="<?php echo $redirectAction;?>">
        <?php
        $len = count($this->_steps);
        // $siteLogo = EP_Mondula_Form_Wizard_Wizard::fw_get_option('websitelogo', 'fw_settings_styling');
        $siteLogo  = apply_filters(
            'ep_wizard_site_logo',
            plugins_url().'/EP-multi-step-form-for-apply/dist/styles/images/EP_Logo_Symbols-Care.png'
        );
        // var_dump($siteLogo);
        for ($i = 0; $i < $len; $i++) {
            $step = $this->_steps[ $i ];
            ?>
        <div class="fw-wizard-step-header <?php if($i == $len - 1) {echo 'fw-wizard-step-header-last-step';} ?>" data-stepId="<?php echo $i; ?>">
            <div class="fw-wizard-step-header-wrap">
                <div class="heading-main-title">
                    <?php 
                    if($i < $len - 1) {?> 
                        <div class="main-step-heading">
                            <h2><?php echo esc_html( $this->_title ); ?></h2>
                        </div>
                    <?php } else { ?>
                        <div class="main-step-heading">
                            <h2><?php _e('Congratulations!', 'multi-step-form'); ?></h2>
                        </div>
                    <?php } ?>
                    <h3><?php echo $step->render_headline(); ?></h3>
                </div>
                <div class="heading-logo">
                    <img src="<?php echo $siteLogo; ?>" alt="website logo">
                </div>
            </div>
            <p class="fw-copytext"><?php $step->render_copy_text(); ?></p>
        </div>
        <?php
        }
        ?>
        </div>
    </div>

    <div class="fw-progress-bar-container <?php echo ($progressbar ? '' : ' fw-hide-progress-bar'); ?>">
        <div class="fw-container">
            <?php
                $this->render_progress_bar($this->_steps);
            ?>
        </div>
    </div>

    <div class="fw-wizard-step-container" 
    data-label-font-size="<?php echo  EP_Mondula_Form_Wizard_Wizard::fw_get_option('label_font_size' ,'fw_typography_styling', '');?>"
    data-option-font-size="<?php echo  EP_Mondula_Form_Wizard_Wizard::fw_get_option('option_font_size' ,'fw_typography_styling', '');?>"
    data-tooltip-icon-size="<?php echo  EP_Mondula_Form_Wizard_Wizard::fw_get_option('tooltip_icon_font_size' ,'fw_typography_styling', '');?>"
	data-tooltip-font-size="<?php echo  EP_Mondula_Form_Wizard_Wizard::fw_get_option('tooltip_wrap_font_size' ,'fw_typography_styling', '');?>">
        <div class="fw-container">
        <?php
        for ($i = 0; $i < $len; $i++) {
            $step = $this->_steps[ $i ];
            ?>
            <div class="fw-wizard-step <?php if( $i == ($len - 1)){echo 'fw-last-step';}?>" data-stepId="<?php echo $i; ?>">
                <?php
                $step->render($wizard_id, $i);
                ?>

                <?php
                 if ($i == $len - 1) {
                    if ($show_summary) {
                        ?>
                        <div class="fw-summary-container">
                            <button type="button" class="fw-toggle-summary"><?php _e('SHOW SUMMARY', 'multi-step-form') ?></button>
                            <div id="wizard-summary" class="fw-wizard-summary" style="display:none;" data-showsummary="on">
                            <div class="fw-summary-alert"><?php _e('Some required Fields are empty', 'multi-step-form'); ?><br><?php _e('Please check the highlighted fields.', 'multi-step-form') ?></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <button type="button" class="fw-btn-submit"><?php _e($buttonTitle, 'multi-step-form'); ?></button>
                <?php
                }
                ?>
               
                <div class="fw-clearfix"></div>
            </div>
            <?php
            }
        ?>
        </div>
    </div>
    <?php if (count($this->_steps) > 1) { ?>
    <div class="fw-wizard-button-container">
        <div class="fw-container">
            <div class="fw-wizard-buttons">
                <button class="fw-button-previous">
                    <!-- <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> &nbsp; -->
                    <?php _e($backTitle, 'multi-step-form') ?>
                </button>
                <button class="fw-button-next">
                    <?php _e($nextTitle, 'multi-step-form') ?> 
                    <!-- &nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i> -->
                </button>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="fw-alert-user" style="display:none;"></div>

    <?php  if( $redirectAction == 'show-thankyou-message') { ?>
        </div>
        <div class="thank-you-message" style="display:none;">
            <?php if(!empty($this->_settings['thankyouMessageHeadding'])) { ?>
                <h2><?php echo $this->_settings['thankyouMessageHeadding']; ?></h2>
            <?php } if(!empty($this->_settings['thankyouMessageDescription'])) { ?>
                <p class="thankyou-description"><?php echo $this->_settings['thankyouMessageDescription']; ?></p>
            <?php }?>
            <?php if(!empty($this->_settings['thankyouMessageButtonTitle'])) { ?>
                <p>
                    <a class="btn btn-primary btn-back-top-home" href="<?php echo home_url( '/' );?>" title=" <?php _e('Back to Home', 'multi-step-form') ?> ">
                        <?php echo $this->_settings['thankyouMessageButtonTitle']; ?>
                    </a>
                </p>
            <?php } else {?>
                <p>
                    <a class="btn btn-primary btn-back-top-home" href="<?php echo home_url( '/' );?>" title=" <?php _e('Back to Home', 'multi-step-form') ?> ">
                        <?php _e('Back to Home', 'multi-step-form') ?> 
                    </a>
                </p>
            <?php } ?> 
        </div>
    <?php } ?>
</div>
