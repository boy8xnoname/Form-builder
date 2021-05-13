<?php
    if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h2>Multi Step Forms
        <a href="<?php echo $edit_url; ?>" class="page-title-action">
            <?php _e('Add New', 'multi-step-form'); ?>
        </a>
    </h2>
    <form id="fw-wizard-table" method="get">
        <input type="hidden" name="page" value="ep-multistep-forms" />
        <?php $table->display(); ?>
    </form>

    <div class="import-container">
        <h2 class="nav-tab-wrapper">
            <a href="#import_from_json_file" class="nav-tab" id="import_from_json_file-tab">Import Form (Form File Json)</a>
        </h2>
        <div class="metabox-holder">        
            <div id="import_from_json_file" class="group" style="">
                <h2><?php echo __('Import a Form', 'multi-step-form'); ?></h2>
                <form id="ep-json-import" method="post" enctype="multipart/form-data">
                    <input type='file' id='json-import' name='json-import' accept='application/json,.json'>
                    <input name="submit" id="submit" class="button button-primary"
                        value="<?php echo __('Upload & Import', 'multi-step-form'); ?>" type="submit">
                </form>             
            </div>
        </div>

        <script>
			jQuery(document).ready(function($) {           
				
				$('#one-import-submit').on('click', function() {	
					$.ajax({
						url: "<?php echo get_home_url(); ?>/wp-admin/admin-ajax.php",
						type:'POST',
						data:{
							action: 'setupFormApplyNowFromDemoData'
						},
						success:function(results) {
							alert(results);
							// alert("Setup demo data success");
						}
					});
				});

				// Switches option sections
				$('.group').hide();
				var activetab = '';
				if (typeof(localStorage) != 'undefined' ) {
					activetab = localStorage.getItem("activetab");
				}
				if (activetab != '' && $(activetab).length ) {
					$(activetab).fadeIn();
				} else {
					$('.group:first').fadeIn();
				}
				$('.group .collapsed').each(function(){
					$(this).find('input:checked').parent().parent().parent().nextAll().each(
					function(){
						if ($(this).hasClass('last')) {
							$(this).removeClass('hidden');
							return false;
						}
						$(this).filter('.hidden').removeClass('hidden');
					});
				});
				if (activetab != '' && $(activetab + '-tab').length ) {
					$(activetab + '-tab').addClass('nav-tab-active');
				}
				else {
					$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
				}
				$('.nav-tab-wrapper a').click(function(evt) {
					$('.nav-tab-wrapper a').removeClass('nav-tab-active');
					$(this).addClass('nav-tab-active').blur();
					var clicked_group = $(this).attr('href');
					if (typeof(localStorage) != 'undefined' ) {
						localStorage.setItem("activetab", $(this).attr('href'));
					}
					$('.group').hide();
					$(clicked_group).fadeIn();
					evt.preventDefault();
				});
				$('.wpsa-browse').on('click', function (event) {
					event.preventDefault();
					var self = $(this);
					// Create the media frame.
					var file_frame = wp.media.frames.file_frame = wp.media({
						title: self.data('uploader_title'),
						button: {
							text: self.data('uploader_button_text'),
						},
						multiple: false
					});
					file_frame.on('select', function () {
						attachment = file_frame.state().get('selection').first().toJSON();
						self.prev('.wpsa-url').val(attachment.url);
					});
					// Finally, open the modal
					file_frame.open();
				});
		});
		</script>
    </div>
    <!-- container -->
</div>