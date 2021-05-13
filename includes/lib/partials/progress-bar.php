<?php
    if (!defined('ABSPATH')) exit;
    $progressbartitle =(EP_Mondula_Form_Wizard_Wizard::fw_get_option('progressbartitle', 'fw_settings_styling') == 'on') ? 'on' : 'off';
    $showlaststep =(EP_Mondula_Form_Wizard_Wizard::fw_get_option('showlaststep', 'fw_settings_styling')== 'on') ? 'on' : 'off';
    // var_dump($progressbartitle);
    // var_dump($showlaststep);
?>
<div class="fw-progress-wrap <?php echo 'show-title-'.$progressbartitle;?>" data-showLastStep ="<?php echo  $showlaststep;?>">
    <ul class="fw-progress-bar <?php echo 'show-title-'.$progressbartitle;?>"
        data-activecolor="<?php echo $this->fw_get_option('activecolor' ,'fw_settings_color', '#297f6d');?>"
        data-donecolor="<?php echo $this->fw_get_option('donecolor' ,'fw_settings_color', '#297f6d');?>"
        data-nextcolor="<?php echo $this->fw_get_option('nextcolor' ,'fw_settings_color', '#aaa');?>"
        data-buttonbackcolor="<?php echo $this->fw_get_option('buttonbackcolor' ,'fw_settings_color', '#297F6D');?>"
        data-buttonbackbgcolor="<?php echo $this->fw_get_option('buttonbackbgcolor' ,'fw_settings_color', '#A5CEC5');?>"
        data-buttonnextcolor="<?php echo $this->fw_get_option('buttonnextcolor' ,'fw_settings_color', '#ffffff');?>"
        data-buttonnextbgcolor="<?php echo $this->fw_get_option('buttonnextbgcolor' ,'fw_settings_color', '#297F6D');?>"
        data-buttoncolor="<?php echo $this->fw_get_option('buttoncolor' ,'fw_settings_color', '#ffffff');?>"
        data-buttonbgcolor="<?php echo $this->fw_get_option('buttonbgcolor', 'fw_settings_color', '#297F6D');?>">
        <?php
        // var_dump($cnt);
        if($showlaststep == 'on') {
            for ($i = 0; $i < ($cnt); $i++) {
                $step = $this->_steps[$i];
                ?>
                    <li class="fw-progress-step" data-id="<?php echo $i; ?>">
                        <?php if($progressbartitle == 'on') {?> 
                            <span class="fw-progress-bar-bar"></span>
                            <span class="fw-txt-ellipsis" data-title="<?php echo $step->render_title(); ?>"><?php echo $step->render_title(); ?></span>
                        <?php } ?>
                    </li>
                <?php
            }
        } else {
            for ($j = 0; $j < ($cnt - 1); $j++) {
                $step = $this->_steps[$j];
                ?>
                    <li class="fw-progress-step" data-id="<?php echo $j; ?>">
                        <?php if($progressbartitle == 'on') {?> 
                            <span class="fw-progress-bar-bar"></span>
                            <span class="fw-txt-ellipsis" data-title="<?php echo $step->render_title(); ?>"><?php echo $step->render_title(); ?></span>
                        <?php } ?>
                    </li>
                <?php
            }

        }

        ?>
    </ul>
</div>
