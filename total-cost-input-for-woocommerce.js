function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_select_money_input_product_type() {
    var is_money_input = jQuery( '#_total_cost_input_for_woocommerce_money_input_product_type' ).prop('checked');
    if (is_money_input) {
        jQuery( '.money_input_options' ).show();
        jQuery( '.money_input-product-for-woocommerce-settings-wrapper' ).show();
        jQuery( '.show_if_total_cost_input_for_woocommerce_money_input_product_type' ).show();
    } else {
        jQuery( '.money_input_options' ).hide();
        jQuery( '.general_options > a' ).trigger('click');
        jQuery( 'li > a.general' ).trigger('click');
        jQuery( '.money_input-product-for-woocommerce-settings-wrapper' ).hide();
        jQuery( '.show_if_total_cost_input_for_woocommerce_money_input_product_type' ).hide();
    }
}

function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init() {
	if ("undefined" !== typeof window.total_cost && window.total_cost.initialized === true) {
        return;
    }
    window.total_cost.initialized = true;
}

jQuery(document).ready(TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init);
// proper init if loaded by ajax
jQuery(document).ajaxComplete(function( event, xhr, settings ) {
    TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init();
});
