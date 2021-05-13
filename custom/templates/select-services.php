<?php
   
?>
<form
    class="epService"
    action="<?php echo esc_url( get_permalink( get_option( 'ep_dental_apply_page_id', 0 ) ) ); ?>"
    method="GET"
    data-base-price="<?php echo esc_attr( $base_price ); ?>"
>
    <ul class="epService__list">
		<?php foreach ( $services as $service ):
			if ( empty( $service['name'] ) || empty( $service['price'] ) ) {
				continue;
			}
			?>

            <li class="epService__item">
                <div class="epService__itemMainTitle">
                    <div class="epService__itemCheck">
                        <input
                            type="checkbox"
                            name="ep_services[]"
                            id="fp_product_<?php echo esc_attr( $service['uid'] ); ?>"
                            value="<?php echo esc_attr( $service['uid'] ); ?>"
                            data-price="<?php echo esc_attr( $service['price'] ); ?>"
                            <?php checked( $service['defaultSelected'] ?? false ) ?>
                        />
                        <label class="input-label epService__itemName"
                            for="fp_product_<?php echo esc_attr( $service['uid'] ); ?>">
                            <?php echo esc_html( $service['name'] ); ?>
                        </label>
                    </div>

                    <div class="epService__borderBetween">
                    </div>

                    <div class="epService__itemPrice">
                        <p>
                            $<?php echo esc_html( $service['price'] ?? '' ); ?>
                            <span>/<?php esc_html_e( 'month', 'ep-dental-plugin' ); ?></span>
                        </p>
                    </div>
                </div>
                    
                <?php if ( !empty( $service['description'] ) ) : ?>
                    <div class="epService__itemWrap">
                        <p><?php echo wp_kses_post( $service['description'] ); ?></p>
                    </div>
                <?php endif; ?>
            </li>
		<?php endforeach; ?>
    </ul>

    <div class="epService__buttons">
        <p class="epService__total">
            <label><?php esc_html_e( 'Total amount:', 'ep-dental-plugin' ); ?></label>
            <span class="total-price"><span data-text-total></span> <span>/<?php esc_html_e( 'month', 'ep-dental-plugin' ); ?></span><span class="important-note">*</span></span>
            <span><em><small>* <?php esc_html_e( 'Before interest and borrowing costs.', 'ep-dental-plugin' ); ?></small></em></span>
        </p>

        <button class="button" type="submit">
		    <?php esc_html_e( 'Apply Now', 'ep-dental-plugin' ); ?>
        </button>
    </div>
</form>
