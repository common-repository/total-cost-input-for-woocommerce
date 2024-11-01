<?php

/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */
defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
    ?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php 
    echo  esc_attr( $input_id ) ;
    ?>" class="qty" name="<?php 
    echo  esc_attr( $input_name ) ;
    ?>" value="<?php 
    echo  esc_attr( $min_value ) ;
    ?>" />
    </div>
    <?php 
} else {
    global  $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options ;
    $is_money_input = true;
    
    if ( !is_cart() && $is_money_input ) {
        /* translators: %s: Total. */
        $total_label = ( !empty($args['product_name']) ? sprintf( esc_html__( 'Total cost to pay for %s', 'total-cost-input-for-woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Total Cost', 'total-cost-input-for-woocommerce' ) );
        $total_input_id = $input_id . '_total';
        ?>
        <script type="text/javascript">
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init_<?php 
        echo  esc_attr( $total_input_id ) ;
        ?>() {
                if ("undefined" === typeof window.total_cost) {
                    window.total_cost = {};
                }
                if (window.total_cost.initialized_<?php 
        echo  esc_attr( $total_input_id ) ;
        ?> === true) {
                    return;
                }
                window.total_cost.initialized_<?php 
        echo  esc_attr( $total_input_id ) ;
        ?> = true;

                // pSBC - Shade Blend Convert - Version 4.1 - 01/7/2021
                // https://github.com/PimpTrizkit/PJs/blob/master/pSBC.js

                const pSBC = (p, c0, c1, l) => {
                    let r, g, b, P, f, t, h, m = Math.round,
                        a = typeof(c1) == "string";
                    if (typeof(p) != "number" || p < -1 || p > 1 || typeof(c0) != "string" || (c0[0] != 'r' && c0[0] != '#') || (c1 && !a)) return null;
                    h = c0.length > 9, h = a ? c1.length > 9 ? true : c1 == "c" ? !h : false : h, f = pSBC.pSBCr(c0), P = p < 0, t = c1 && c1 != "c" ? pSBC.pSBCr(c1) : P ? {
                        r: 0,
                        g: 0,
                        b: 0,
                        a: -1
                    } : {
                        r: 255,
                        g: 255,
                        b: 255,
                        a: -1
                    }, p = P ? p * -1 : p, P = 1 - p;
                    if (!f || !t) return null;
                    if (l) r = m(P * f.r + p * t.r), g = m(P * f.g + p * t.g), b = m(P * f.b + p * t.b);
                    else r = m((P * f.r ** 2 + p * t.r ** 2) ** 0.5), g = m((P * f.g ** 2 + p * t.g ** 2) ** 0.5), b = m((P * f.b ** 2 + p * t.b ** 2) ** 0.5);
                    a = f.a, t = t.a, f = a >= 0 || t >= 0, a = f ? a < 0 ? t : t < 0 ? a : a * P + t * p : 0;
                    if (h) return "rgb" + (f ? "a(" : "(") + r + "," + g + "," + b + (f ? "," + m(a * 1000) / 1000 : "") + ")";
                    else return "#" + (4294967296 + r * 16777216 + g * 65536 + b * 256 + (f ? m(a * 255) : 0)).toString(16).slice(1, f ? undefined : -2)
                }

                pSBC.pSBCr = (d) => {
                    const i = parseInt;
                    let n = d.length,
                        x = {};
                    if (n > 9) {
                        const [r, g, b, a] = (d = d.split(','));
                        n = d.length;
                        if (n < 3 || n > 4) return null;
                        x.r = i(r[3] == "a" ? r.slice(5) : r.slice(4)), x.g = i(g), x.b = i(b), x.a = a ? parseFloat(a) : -1
                    } else {
                        if (n == 8 || n == 6 || n < 4) return null;
                        if (n < 6) d = "#" + d[1] + d[1] + d[2] + d[2] + d[3] + d[3] + (n > 4 ? d[4] + d[4] : "");
                        d = i(d.slice(1), 16);
                        if (n == 9 || n == 5) x.r = d >> 24 & 255, x.g = d >> 16 & 255, x.b = d >> 8 & 255, x.a = Math.round((d & 255) / 0.255) / 1000;
                        else x.r = d >> 16, x.g = d >> 8 & 255, x.b = d & 255, x.a = -1
                    }
                    return x
                };

                var product_price = parseFloat('<?php 
        echo  esc_attr( $product_price ) ;
        ?>');
                var price_decimals = parseFloat('<?php 
        echo  esc_attr( wc_get_price_decimals() ) ;
        ?>');
                var $total_input = jQuery('#' + '<?php 
        echo  esc_attr( $total_input_id ) ;
        ?>');
                var $input = jQuery('#' + '<?php 
        echo  esc_attr( $input_id ) ;
        ?>');
                $total_input.on('change', function() {
                    const total = $total_input.val();
                    const new_qty = parseFloat(total) / product_price;
                    const step = $input.prop('step')
                    const digits0 = Math.ceil(-Math.log10(step));
                    const digits = 0 < digits0 ? digits0 : 0;
                    $input.val(parseFloat((step * Math.round(new_qty / step)).toFixed(digits)));
                });
                $input.on('change', function() {
                    const new_qty = $input.val();
                    const total = parseFloat(new_qty) * product_price;
                    const step = $total_input.prop('step')
                    const digits = price_decimals;
                    $total_input.val(parseFloat((step * Math.round(total / step)).toFixed(digits)));
                });

                // CSS
                var $total_input_parent = $total_input.parent();
                [
                    'padding',
                    'background-color',
                    'color',
                    'border',
                    'border-bottom',
                    'border-top',
                    'border-left',
                    'border-right',
                    'border-radius',
                    '-webkit-appearance',
                    'box-sizing',
                    'font-weight',
                    'box-shadow',
                    'font-family',
                ].forEach(function(prop) {
                    $total_input_parent.css(prop, $input.css(prop));
                });
                $total_input.css('box-shadow', 'none');
                $total_input.css('border-bottom', 'none');
                $total_input.css('border-top', 'none');
                $total_input.css('border-left', 'none');
                $total_input.css('border-right', 'none');
                $total_input.css('padding', '0');

                var $prefix = jQuery('.quantity .total-cost-input-prefix');
                $prefix.css('font-size', parseInt($total_input.css('font-size')) - 2);
                // make it 42% lighter
                $prefix.css('color', pSBC(0.42, $total_input.css('color')));
            }

            jQuery(document).ready(TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init_<?php 
        echo  esc_attr( $total_input_id ) ;
        ?>);
            // proper init if loaded by ajax
            jQuery(document).ajaxComplete(function(event, xhr, settings) {
                TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init_<?php 
        echo  esc_attr( $total_input_id ) ;
        ?>();
            });
        </script>
        <div class="quantity">
            <?php 
        /**
         * Hook to output something before the total quantity input field.
         *
         * @since 1.0.2
         */
        do_action( 'woocommerce_before_total_cost_money_input_field' );
        ?>
            <label class="screen-reader-text" for="<?php 
        echo  esc_attr( $total_input_id ) ;
        ?>"><?php 
        echo  esc_attr( $total_label ) ;
        ?></label>
            <span class="total-cost-input-prefix"><?php 
        echo  esc_attr( get_woocommerce_currency_symbol() ) ;
        ?></span>
            <input type="<?php 
        echo  esc_attr( $type ) ;
        ?>" <?php 
        echo  ( $readonly ? 'readonly="readonly"' : '' ) ;
        ?> id="<?php 
        echo  esc_attr( $total_input_id ) ;
        ?>" class="<?php 
        echo  esc_attr( join( ' ', (array) $classes ) ) ;
        ?>" name="<?php 
        echo  esc_attr( 'total_' . $input_name ) ;
        ?>" value="<?php 
        echo  esc_attr( $total_input_value ) ;
        ?>" aria-label="<?php 
        echo  esc_attr_x( 'Total Cost', 'Product total input tooltip', 'total-cost-input-for-woocommerce' ) ;
        ?>" size="4" min="<?php 
        echo  esc_attr( $total_min_value ) ;
        ?>" max="<?php 
        echo  esc_attr( ( 0 < $total_max_value ? $total_max_value : '' ) ) ;
        ?>" <?php 
        
        if ( !$readonly ) {
            ?> step="<?php 
            echo  esc_attr( $total_step ) ;
            ?>" placeholder="<?php 
            echo  esc_attr( $placeholder ) ;
            ?>" inputmode="<?php 
            echo  esc_attr( $inputmode ) ;
            ?>" autocomplete="<?php 
            echo  esc_attr( ( isset( $autocomplete ) ? $autocomplete : 'on' ) ) ;
            ?>" <?php 
        }
        
        ?> />
            <?php 
        /**
         * Hook to output something after total quantity input field
         *
         * @since 1.0.2
         */
        do_action( 'woocommerce_after_total_cost_money_input_field' );
        ?>
        </div>
    <?php 
    }
    
    // !is_cart
    $is_show_quantity_input_field = false;
    $nodisplay = '';
    if ( !is_cart() && !$is_show_quantity_input_field && $is_money_input ) {
        $nodisplay = 'style="visibility:hidden;padding:0;margin:0;width:0;height:0;"';
    }
    // TODO: https://github.com/woocommerce/woocommerce/blob/3611d4643791bad87a0d3e6e73e031bb80447417/plugins/woocommerce/templates/cart/cart.php#L127
    /* translators: %s: Quantity. */
    $label = ( !empty($args['product_name']) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' ) );
    ?>
    <div class="quantity" <?php 
    echo  $nodisplay ;
    ?>>
        <?php 
    /**
     * Hook to output something before the quantity input field.
     *
     * @since 7.2.0
     */
    do_action( 'woocommerce_before_quantity_input_field' );
    ?>
        <label class="screen-reader-text" for="<?php 
    echo  esc_attr( $input_id ) ;
    ?>"><?php 
    echo  esc_attr( $label ) ;
    ?></label>
        <input type="<?php 
    echo  esc_attr( $type ) ;
    ?>" <?php 
    echo  ( $readonly ? 'readonly="readonly"' : '' ) ;
    ?> id="<?php 
    echo  esc_attr( $input_id ) ;
    ?>" class="<?php 
    echo  esc_attr( join( ' ', (array) $classes ) ) ;
    ?>" name="<?php 
    echo  esc_attr( $input_name ) ;
    ?>" value="<?php 
    echo  esc_attr( $input_value ) ;
    ?>" aria-label="<?php 
    esc_attr_e( 'Product quantity', 'woocommerce' );
    ?>" size="4" min="<?php 
    echo  esc_attr( $min_value ) ;
    ?>" max="<?php 
    echo  esc_attr( ( 0 < $max_value ? $max_value : '' ) ) ;
    ?>" <?php 
    
    if ( !$readonly ) {
        ?> step="<?php 
        echo  esc_attr( $step ) ;
        ?>" placeholder="<?php 
        echo  esc_attr( $placeholder ) ;
        ?>" inputmode="<?php 
        echo  esc_attr( $inputmode ) ;
        ?>" autocomplete="<?php 
        echo  esc_attr( ( isset( $autocomplete ) ? $autocomplete : 'on' ) ) ;
        ?>" <?php 
    }
    
    ?> />
        <?php 
    /**
     * Hook to output something after quantity input field
     *
     * @since 3.6.0
     */
    do_action( 'woocommerce_after_quantity_input_field' );
    ?>
    </div>
<?php 
}
