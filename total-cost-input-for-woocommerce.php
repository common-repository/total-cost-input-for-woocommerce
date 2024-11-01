<?php

/*
Plugin Name: Total Cost Input for WooCommerce
Plugin URI: https://wordpress.org/plugins/total-cost-input-for-woocommerce
Description: Total Cost Input for WooCommerce plugin enables customers to input the amount of money they want to spend and let the system calculate the resulting quantity.
Version: 1.1.0
WC requires at least: 5.5.0
WC tested up to: 8.0.3
Author: ethereumicoio
Author URI: https://ethereumico.io
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: total-cost-input-for-woocommerce
Domain Path: /languages
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Explicitly globalize to support bootstrapped WordPress
global 
    $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_basename,
    $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options,
    $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_dir,
    $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_url_path,
    $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_product
;
if ( !function_exists( 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_deactivate' ) ) {
    function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_deactivate()
    {
        if ( !current_user_can( 'activate_plugins' ) ) {
            return;
        }
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }

}

if ( version_compare( phpversion(), '7.0', '<' ) ) {
    add_action( 'admin_init', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_deactivate' );
    add_action( 'admin_notices', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_admin_notice' );
    function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_admin_notice()
    {
        if ( !current_user_can( 'activate_plugins' ) ) {
            return;
        }
        echo  '<div class="error"><p><strong>Total Cost Input for WooCommerce</strong> requires PHP version 7.0 or above.</p></div>' ;
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }

} else {
    /**
     * Check if WooCommerce is active
     * https://wordpress.stackexchange.com/a/193908/137915
     **/
    
    if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action( 'admin_init', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_deactivate' );
        add_action( 'admin_notices', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_admin_notice_woocommerce' );
        function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_admin_notice_woocommerce()
        {
            if ( !current_user_can( 'activate_plugins' ) ) {
                return;
            }
            echo  '<div class="error"><p><strong>Total Cost Input for WooCommerce</strong> requires WooCommerce plugin to be installed and activated.</p></div>' ;
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }
    
    } else {
        
        if ( function_exists( 'total_cost_input_for_woocommerce_freemius_init' ) ) {
            total_cost_input_for_woocommerce_freemius_init()->set_basename( false, __FILE__ );
        } else {
            // Create a helper function for easy SDK access.
            function total_cost_input_for_woocommerce_freemius_init()
            {
                global  $total_cost_input_for_woocommerce_freemius_init ;
                
                if ( !isset( $total_cost_input_for_woocommerce_freemius_init ) ) {
                    // Activate multisite network integration.
                    if ( !defined( 'WP_FS__PRODUCT_10543_MULTISITE' ) ) {
                        define( 'WP_FS__PRODUCT_10543_MULTISITE', true );
                    }
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';
                    $total_cost_input_for_woocommerce_freemius_init = fs_dynamic_init( array(
                        'id'              => '10543',
                        'slug'            => 'total-cost-input-for-woocommerce',
                        'type'            => 'plugin',
                        'public_key'      => 'pk_3d26b9418c3a59a827abb36fa13e3',
                        'is_premium'      => false,
                        'has_addons'      => false,
                        'has_paid_plans'  => true,
                        'trial'           => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                        'has_affiliation' => 'all',
                        'menu'            => array(
                        'slug'   => 'total-cost-input-for-woocommerce',
                        'parent' => array(
                        'slug' => 'options-general.php',
                    ),
                    ),
                        'is_live'         => true,
                    ) );
                }
                
                return $total_cost_input_for_woocommerce_freemius_init;
            }
            
            // Init Freemius.
            total_cost_input_for_woocommerce_freemius_init();
            // Signal that SDK was initiated.
            do_action( 'total_cost_input_for_woocommerce_freemius_init_loaded' );
            // ... Your plugin's main file logic ...
            $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_basename = plugin_basename( dirname( __FILE__ ) );
            $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
            $plugin_url_path = untrailingslashit( plugin_dir_url( __FILE__ ) );
            // HTTPS?
            $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_url_path = ( is_ssl() ? str_replace( 'http:', 'https:', $plugin_url_path ) : $plugin_url_path );
            // Set plugin options
            $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options = get_option( 'total-cost-input-for-woocommerce_options', array() );
            require $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_dir . '/vendor/autoload.php';
            // Function to check if a product is a money_input
            function _TOTAL_COST_INPUT_FOR_WOOCOMMERCE_is_money_input( $product_id )
            {
                return true;
            }
            
            function _TOTAL_COST_INPUT_FOR_WOOCOMMERCE__before_POST_processing_action_impl()
            {
                do_action( 'total_cost_input_for_woocommerce_before_POST_processing_action' );
            }
            
            function _TOTAL_COST_INPUT_FOR_WOOCOMMERCE__get_hide_element_classes( $classes )
            {
                return trim( $classes . ' ' . 'hidden hide-all' );
            }
            
            add_filter( 'total_cost_input_for_woocommerce__get_hide_element_classes', '_TOTAL_COST_INPUT_FOR_WOOCOMMERCE__get_hide_element_classes' );
            /**
             * Show pricing fields for money_input product.
             */
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_custom_js()
            {
                global  $post ;
                if ( 'product' != get_post_type() ) {
                    return;
                }
                TOTAL_COST_INPUT_FOR_WOOCOMMERCE_custom_js_aux();
            }
            
            /**
             * Show pricing fields for money_input product.
             */
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_custom_js_aux()
            {
                global  $post ;
                wp_enqueue_script( 'total-cost-input-for-woocommerce' );
                ?><script type='text/javascript'>
                    jQuery(document).ready(function() {

                        TOTAL_COST_INPUT_FOR_WOOCOMMERCE_init();
                        <?php 
                ?>

                    });
                </script><?php 
            }
            
            add_action( 'admin_footer', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_custom_js' );
            // @see https://stackoverflow.com/a/51809271/4256005
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_wc_get_template(
                $located,
                $template_name,
                $args,
                $template_path,
                $default_path
            )
            {
                global  $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_dir ;
                $custom_template_name = 'global/quantity-input.php';
                if ( $custom_template_name !== $template_name ) {
                    return $located;
                }
                // wp_enqueue_script( 'total-cost-input-for-woocommerce' );
                wp_enqueue_style( 'total-cost-input-for-woocommerce' );
                $located = $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_dir . '/templates/' . $custom_template_name;
                return $located;
            }
            
            add_filter(
                'wc_get_template',
                'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_wc_get_template',
                10,
                5
            );
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_woocommerce_quantity_input_args( $args, $product )
            {
                
                if ( $product ) {
                    $args['product_id'] = $product->get_id();
                    $price = $product->get_price( 'edit' );
                    $args['product_price'] = $price;
                    $price_decimals = wc_get_price_decimals();
                    $args['total_step'] = round( $price * $args['step'], $price_decimals );
                    if ( 0 == $args['total_step'] ) {
                        $args['total_step'] = 1 / pow( 10, $price_decimals );
                    }
                    $args['total_input_value'] = round( $price * $args['input_value'], $price_decimals );
                    if ( 0 < $args['input_value'] && 0 == $args['total_input_value'] ) {
                        $args['total_input_value'] = 1 / pow( 10, $price_decimals );
                    }
                    $args['total_min_value'] = round( $price * $args['min_value'], $price_decimals );
                    if ( 0 < $args['min_value'] && 0 == $args['total_min_value'] ) {
                        $args['total_min_value'] = 1 / pow( 10, $price_decimals );
                    }
                    
                    if ( 0 < $args['max_value'] ) {
                        $args['total_max_value'] = round( $price * $args['max_value'], $price_decimals );
                        if ( 0 == $args['total_max_value'] ) {
                            $args['total_max_value'] = 1 / pow( 10, $price_decimals );
                        }
                    } else {
                        $args['total_max_value'] = -1;
                    }
                
                }
                
                return $args;
            }
            
            add_filter(
                'woocommerce_quantity_input_args',
                'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_woocommerce_quantity_input_args',
                10,
                2
            );
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_stylesheet()
            {
                global  $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_url_path ;
                
                if ( !wp_style_is( 'total-cost-input-for-woocommerce', 'queue' ) && !wp_style_is( 'total-cost-input-for-woocommerce', 'done' ) ) {
                    wp_dequeue_style( 'total-cost-input-for-woocommerce' );
                    wp_deregister_style( 'total-cost-input-for-woocommerce' );
                    wp_register_style(
                        'total-cost-input-for-woocommerce',
                        $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_url_path . '/total-cost-input-for-woocommerce.css',
                        array(),
                        '1.1.0'
                    );
                }
            
            }
            
            add_action( 'wp_enqueue_scripts', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_stylesheet', 20 );
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_enqueue_script()
            {
                global  $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_url_path ;
                // global $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options;
                global  $post ;
                // $options = $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options;
                $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
                
                if ( !wp_script_is( 'total-cost-input-for-woocommerce', 'queue' ) && !wp_script_is( 'total-cost-input-for-woocommerce', 'done' ) ) {
                    wp_dequeue_script( 'total-cost-input-for-woocommerce' );
                    wp_deregister_script( 'total-cost-input-for-woocommerce' );
                    wp_register_script(
                        'total-cost-input-for-woocommerce',
                        $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_url_path . "/total-cost-input-for-woocommerce{$min}.js",
                        array( 'jquery' ),
                        '1.1.0'
                    );
                }
                
                wp_localize_script( 'total-cost-input-for-woocommerce', 'total_cost', apply_filters( 'cryptocurrency_product_for_woocommerce_wp_localize_script', [
                    'currency_symbol' => esc_html( get_woocommerce_currency_symbol() ),
                ] ) );
            }
            
            add_action( 'admin_enqueue_scripts', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_enqueue_script' );
            add_action( 'wp_enqueue_scripts', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_enqueue_script' );
            //----------------------------------------------------------------------------//
            //                               Admin Options                                //
            //----------------------------------------------------------------------------//
            if ( is_admin() ) {
                include_once $TOTAL_COST_INPUT_FOR_WOOCOMMERCE_plugin_dir . '/total-cost-input-for-woocommerce.admin.php';
            }
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_add_menu_link()
            {
                $page = add_options_page(
                    __( 'Total Cost Input Settings', 'total-cost-input-for-woocommerce' ),
                    __( 'Total Cost Input', 'total-cost-input-for-woocommerce' ),
                    'manage_options',
                    'total-cost-input-for-woocommerce',
                    'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options_page'
                );
            }
            
            add_filter( 'admin_menu', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_add_menu_link' );
            // Place in Option List on Settings > Plugins page
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_actlinks( $links, $file )
            {
                // Static so we don't call plugin_basename on every plugin row.
                static  $this_plugin ;
                if ( !$this_plugin ) {
                    $this_plugin = plugin_basename( __FILE__ );
                }
                
                if ( $file == $this_plugin ) {
                    $settings_link = '<a href="options-general.php?page=total-cost-input-for-woocommerce">' . __( 'Settings' ) . '</a>';
                    array_unshift( $links, $settings_link );
                    // before other links
                }
                
                return $links;
            }
            
            add_filter(
                'plugin_action_links',
                'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_actlinks',
                10,
                2
            );
            //----------------------------------------------------------------------------//
            //                                   L10n                                     //
            //----------------------------------------------------------------------------//
            function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_load_textdomain()
            {
                /**
                 * Localise.
                 */
                load_plugin_textdomain( 'total-cost-input-for-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
            }
            
            add_action( 'plugins_loaded', 'TOTAL_COST_INPUT_FOR_WOOCOMMERCE_load_textdomain' );
        }
        
        //if ( ! function_exists( 'total_cost_input_for_woocommerce_freemius_init' ) ) {
    }
    
    // WooCommerce activated
}

// PHP version