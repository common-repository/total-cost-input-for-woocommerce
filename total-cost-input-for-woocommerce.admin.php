<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
function TOTAL_COST_INPUT_FOR_WOOCOMMERCE_options_page()
{
    // Require admin privs
    if ( !current_user_can( 'manage_options' ) ) {
        return false;
    }
    $new_options = array();
    // Which tab is selected?
    $possible_screens = array(
        'default' => esc_html( __( 'Standard', 'total-cost-input-for-woocommerce' ) ),
    );
    $possible_screens = apply_filters( 'total_cost_input_for_woocommerce_settings_tabs', $possible_screens );
    asort( $possible_screens );
    $current_screen = ( isset( $_GET['tab'] ) && isset( $possible_screens[$_GET['tab']] ) ? sanitize_url( $_GET['tab'] ) : 'default' );
    
    if ( isset( $_POST['Submit'] ) ) {
        // Nonce verification
        check_admin_referer( 'total-cost-input-for-woocommerce-update-options' );
        // Standard options screen
        if ( 'default' == $current_screen ) {
        }
        $new_options = apply_filters( 'total_cost_input_for_woocommerce_get_save_options', $new_options, $current_screen );
        // Get all existing Total Cost Input options
        $existing_options = get_option( 'total-cost-input-for-woocommerce_options', array() );
        // Merge $new_options into $existing_options to retain Total Cost Input options from all other screens/tabs
        if ( $existing_options ) {
            $new_options = array_merge( $existing_options, $new_options );
        }
        
        if ( false !== get_option( 'total-cost-input-for-woocommerce_options' ) ) {
            update_option( 'total-cost-input-for-woocommerce_options', $new_options );
        } else {
            $deprecated = '';
            $autoload = 'no';
            add_option(
                'total-cost-input-for-woocommerce_options',
                $new_options,
                $deprecated,
                $autoload
            );
        }
        
        ?>
        <div class="updated">
            <p><?php 
        _e( 'Settings saved.' );
        ?></p>
        </div>
    <?php 
    } else {
        
        if ( isset( $_POST['Reset'] ) ) {
            // Nonce verification
            check_admin_referer( 'total-cost-input-for-woocommerce-update-options' );
            delete_option( 'total-cost-input-for-woocommerce_options' );
        }
    
    }
    
    $existing_options = get_option( 'total-cost-input-for-woocommerce_options', array() );
    $options = stripslashes_deep( get_option( 'total-cost-input-for-woocommerce_options', array() ) );
    ?>

    <div class="wrap">

        <h1><?php 
    _e( 'Total Cost Input Settings', 'total-cost-input-for-woocommerce' );
    ?></h1>

        <?php 
    settings_errors();
    ?>

        <section>
            <h1><?php 
    _e( 'Install and Configure Guide', 'total-cost-input-for-woocommerce' );
    ?></h1>
            <p><?php 
    echo  sprintf( __( 'Use the official %1$sInstall and Configure%2$s step by step guide to configure this plugin.', 'total-cost-input-for-woocommerce' ), '<a href="https://ethereumico.io/knowledge-base/total-cost-input-for-woocommerce-plugin-install-and-configure/" target="_blank">', '</a>' ) ;
    ?></p>
        </section>

        <?php 
    
    if ( total_cost_input_for_woocommerce_freemius_init()->is_not_paying() ) {
        echo  '<section><h1>' . esc_html__( 'Awesome Premium Features', 'total-cost-input-for-woocommerce' ) . '</h1>' ;
        echo  esc_html__( 'Per product total cost input and more.', 'total-cost-input-for-woocommerce' ) ;
        echo  ' <a href="' . esc_attr( total_cost_input_for_woocommerce_freemius_init()->get_upgrade_url() ) . '">' . esc_html__( 'Upgrade Now!', 'total-cost-input-for-woocommerce' ) . '</a>' ;
        echo  '</section>' ;
    }
    
    ?>

        <h2 class="nav-tab-wrapper">
            <?php 
    if ( $possible_screens ) {
        foreach ( $possible_screens as $s => $sTitle ) {
            ?>
                <a href="<?php 
            echo  admin_url( 'options-general.php?page=total-cost-input-for-woocommerce&tab=' . esc_attr( $s ) ) ;
            ?>" class="nav-tab<?php 
            if ( $s == $current_screen ) {
                echo  ' nav-tab-active' ;
            }
            ?>"><?php 
            echo  esc_html( $sTitle ) ;
            ?></a>
            <?php 
        }
    }
    ?>
        </h2>

        <form id="total-cost-input-for-woocommerce_admin_form" method="post" action="">

            <?php 
    wp_nonce_field( 'total-cost-input-for-woocommerce-update-options' );
    ?>

            <table class="form-table">

                <?php 
    
    if ( 'default' == $current_screen ) {
        ?>
                    <tr valign="top">
                        <th scope="row"><?php 
        _e( "Show quantity input field?", 'total-cost-input-for-woocommerce' );
        ?></th>
                        <td>
                            <fieldset>
                                <label>
                                    <input <?php 
        if ( !total_cost_input_for_woocommerce_freemius_init()->is__premium_only() || !total_cost_input_for_woocommerce_freemius_init()->can_use_premium_code() ) {
            echo  'disabled' ;
        }
        ?> class="checkbox" name="TOTAL_COST_INPUT_FOR_WOOCOMMERCE_show_quantity_input_field" type="checkbox" <?php 
        echo  ( !empty($options['show_quantity_input_field']) ? 'checked' : '' ) ;
        ?>>
                                    <p><?php 
        _e( "If this setting is set, the normal WooCommerce Quantity field will also be shown on the product page.", 'total-cost-input-for-woocommerce' );
        ?></p>
                                    <?php 
        
        if ( total_cost_input_for_woocommerce_freemius_init()->is_not_paying() ) {
            ?>
                                        <p><?php 
            echo  sprintf( __( '%1$sUpgrade Now!%2$s to enable this feature.', 'total-cost-input-for-woocommerce' ), '<a href="' . esc_attr( total_cost_input_for_woocommerce_freemius_init()->get_upgrade_url() ) . '" target="_blank">', '</a>' ) ;
            ?></p>
                                    <?php 
        }
        
        ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php 
        _e( "Product Quantity Step", 'total-cost-input-for-woocommerce' );
        ?></th>
                        <td>
                            <fieldset>
                                <label>
                                    <input <?php 
        if ( !total_cost_input_for_woocommerce_freemius_init()->is__premium_only() || !total_cost_input_for_woocommerce_freemius_init()->can_use_premium_code() ) {
            echo  'disabled' ;
        }
        ?> class="checkbox" name="TOTAL_COST_INPUT_FOR_WOOCOMMERCE__product_quantity_step" type="number" step="0.001" value="<?php 
        echo  ( !empty($options['product_quantity_step']) ? esc_attr( $options['product_quantity_step'] ) : 1 ) ;
        ?>">
                                    <p><?php 
        _e( "Enter 0.1 to allow customer to buy 0.1, 0.2, 0.3, e.t.c. quantity of your products", 'total-cost-input-for-woocommerce' );
        ?></p>
                                    <?php 
        
        if ( total_cost_input_for_woocommerce_freemius_init()->is_not_paying() ) {
            ?>
                                        <p><?php 
            echo  sprintf( __( '%1$sUpgrade Now!%2$s to enable this feature.', 'total-cost-input-for-woocommerce' ), '<a href="' . esc_attr( total_cost_input_for_woocommerce_freemius_init()->get_upgrade_url() ) . '" target="_blank">', '</a>' ) ;
            ?></p>
                                    <?php 
        }
        
        ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>

                <?php 
    }
    
    ?>
                <?php 
    do_action( 'total_cost_input_for_woocommerce_print_options', $options, $current_screen );
    ?>

            </table>

            <h2><?php 
    _e( "Need help to configure this plugin?", 'total-cost-input-for-woocommerce' );
    ?></h2>
            <p><?php 
    echo  sprintf( __( 'Feel free to %1$shire me!%2$s', 'total-cost-input-for-woocommerce' ), '<a target="_blank" href="https://ethereumico.io/product/configure-wordpress-plugins/" rel="noreferrer noopener sponsored nofollow">', '</a>' ) ;
    ?></p>

            <?php 
    
    if ( total_cost_input_for_woocommerce_freemius_init()->is_not_paying() ) {
        ?>
                <h2><?php 
        _e( "Want more features?", 'total-cost-input-for-woocommerce' );
        ?></h2>
                <p><?php 
        echo  sprintf( __( 'Install the %1$sPRO plugin version%2$s!', 'total-cost-input-for-woocommerce' ), '<a target="_blank" href="' . esc_attr( total_cost_input_for_woocommerce_freemius_init()->get_upgrade_url() ) . '">', '</a>' ) ;
        ?></p>

            <?php 
    }
    
    ?>

            <p class="submit">
                <input class="button-primary" type="submit" name="Submit" value="<?php 
    _e( 'Save Changes', 'total-cost-input-for-woocommerce' );
    ?>" />
                <input id="TOTAL_COST_INPUT_FOR_WOOCOMMERCE_reset_options" type="submit" name="Reset" onclick="return confirm('<?php 
    _e( 'Are you sure you want to delete all Total Cost Input options?', 'total-cost-input-for-woocommerce' );
    ?>')" value="<?php 
    _e( 'Reset', 'total-cost-input-for-woocommerce' );
    ?>" />
            </p>

        </form>

        <p class="alignleft"><?php 
    echo  sprintf( __( 'If you like <strong>Total Cost Input for WooCommerce</strong> please leave us a %1$s rating. A huge thanks in advance!', 'total-cost-input-for-woocommerce' ), '<a href="https://wordpress.org/support/plugin/total-cost-input-for-woocommerce/reviews?rate=5#new-post" target="_blank">★★★★★</a>' ) ;
    ?></p>


    </div>

<?php 
}
