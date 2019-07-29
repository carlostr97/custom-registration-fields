<?php
/*
Plugin Name: Inserimento campi Partita IVA e Ragione Sociale
Description: Un semplice plugin per aggiungere il campo della Ragione Sociale e della Partita IVA nel form di registrazione utente di WooCommerce. Entrambi sono obbligatori.
Author: Carlo Stringaro
Version: 1.2.1.4
Author URI: http://www.carlostringaro.it
*/


/**
 * Aggiungo i campi personalizzati nella scheda utente di WordPress
 */
 
add_action ( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action ( 'edit_user_profile', 'my_show_extra_profile_fields' );


function my_show_extra_profile_fields ( $user )
{
?>
	<h3>Dati di fatturazione</h3>
	<table class="form-table">
		<tr>
			<th><label for="ragionesociale">Ragione Sociale</label></th>
			<td>
				<input type="text" name="ragionesociale" id="ragionesociale" value="<?php echo esc_attr( get_the_author_meta( 'ragionesociale', $user->ID ) ); ?>" class="regular-text required" /><br />
				<span class="description">Inserisci la Ragione Sociale.</span>
			</td>
		</tr>
		<tr>
			<th><label for="piva">Partita IVA</label></th>
			<td>
				<input type="text" name="piva" id="piva" value="<?php echo esc_attr( get_the_author_meta( 'piva', $user->ID ) ); ?>" class="regular-text required" /><br />
				<span class="description">Inserisci la Partita IVA.</span>
			</td>
		</tr>
		
	</table>
<?php
}


add_action ( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action ( 'edit_user_profile_update', 'my_save_extra_profile_fields' );


/**
 * Aggiungo i campi personalizzati nella dettaglio account di WooCommerce
 */
 
function my_show_extra_myaccount_fields ()
{
	$user = wp_get_current_user();
?>
<form class="woocommerce-EditAccountForm edit-account" action="" method="post" >
	<h3>Dati di fatturazione</h3>
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_ragione_sociale"><?php esc_html_e( 'Ragione Sociale', 'woocommerce' ); ?></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_ragione_sociale" id="account_ragione_sociale" autocomplete="given-name" value="<?php echo esc_attr( get_the_author_meta( 'ragionesociale', $user->ID )); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_piva"><?php esc_html_e( 'Partita IVA', 'woocommerce' ); ?></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_piva" id="account_piva" autocomplete="family-name" value="<?php echo esc_attr(get_the_author_meta( 'piva', $user->ID )); ?>" />
	</p>
	</form>
<?php
}

add_action ( 'woocommerce_edit_account_form_start', 'my_show_extra_myaccount_fields' );

/**
 * Aggiungo i campi personalizzati nel checkout di WooCommerce
 */

function my_show_extra_checkout_fields ()
{
	$user = wp_get_current_user();
	unset($fields['billing']['billing_company']);
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_ragione_sociale"><?php esc_html_e( 'Ragione Sociale', 'woocommerce' ); ?></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_ragione_sociale" id="account_ragione_sociale" autocomplete="given-name" value="<?php echo esc_attr( get_the_author_meta( 'ragionesociale', $user->ID )); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_piva"><?php esc_html_e( 'Partita IVA', 'woocommerce' ); ?></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_piva" id="account_piva" autocomplete="family-name" value="<?php echo esc_attr(get_the_author_meta( 'piva', $user->ID )); ?>" />
	</p>
	</form>
<?php
}
add_action ('woocommerce_checkout_before_customer_details', 'my_show_extra_checkout_fields');



/**
 * Elimino il campo "Nome della società (opzionale)" da checkout
 */

 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  
function custom_override_checkout_fields( $fields ) {
 unset($fields['billing']['billing_company']);
    return $fields;
}

/**
 * Aggiorno i campi personalizzati nel database
 */
 
function my_save_extra_profile_fields( $user_id )
{
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	
	update_usermeta( $user_id, 'piva', $_POST['piva'] );
	update_usermeta( $user_id, 'ragionesociale', $_POST['ragionesociale'] );
	
}

/**
 * Aggiungo i campi personalizzati nel form di registrazione WooCommerce
 */

function wooc_extra_register_fields() {?>
       <p class="form-row form-row-first">
       <label for="reg_billing_ragione_sociale"><?php _e( 'Ragione sociale', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_ragione_sociale" id="reg_billing_ragione_sociale" value="<?php if ( ! empty( $_POST['billing_ragione_sociale'] ) ) esc_attr_e( $_POST['billing_ragione_sociale'] ); ?>" />
       </p>
       <p class="form-row form-row-last">
       <label for="reg_billing_partita_iva"><?php _e( 'Partita IVA', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_partita_iva" id="reg_billing_partita_iva" value="<?php if ( ! empty( $_POST['billing_partita_iva'] ) ) esc_attr_e( $_POST['billing_partita_iva'] ); ?>" />
       </p>
       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
 
 
 
 
/**
 * Convalido i campi personalizzati nel form di registrazione WooCommerce
 */
 
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
      if ( isset( $_POST['billing_ragione_sociale'] ) && empty( $_POST['billing_ragione_sociale'] ) ) {
             $validation_errors->add( 'billing_ragione_sociale_error', __( '<strong>Error</strong>: Campo obbligatorio!', 'woocommerce' ) );
      }
      if ( isset( $_POST['billing_partita_iva'] ) && empty( $_POST['billing_partita_iva'] ) ) {
             $validation_errors->add( 'billing_partita_iva_error', __( '<strong>Error</strong>: Campo obbligatorio!.', 'woocommerce' ) );
      }
         return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );


/**
 * Aggiungo o Aggiorno i campi personalizzati nel database
 */
 
 
function wooc_save_extra_register_fields( $customer_id ) {
      if ( isset( $_POST['billing_ragione_sociale'] ) ) {
             
             update_user_meta( $customer_id, 'ragionesociale', sanitize_text_field( $_POST['billing_ragione_sociale'] ) );
      
      }
      if ( isset( $_POST['billing_partita_iva'] ) ) {
             
             update_user_meta( $customer_id, 'piva', sanitize_text_field( $_POST['billing_partita_iva'] ) );
             
      }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

/**
 * Aggiungo i campi personalizzati nel dettaglio ordine checkout
 */
 function display_extra_order_data( $order_id )
 {
	$user = wp_get_current_user();
?>
    <h2><?php _e( 'Dati di fatturazione' ); ?></h2>
    <table class="shop_table shop_table_responsive additional_info">
        <tbody>
            <tr>
                <th><?php _e( 'Ragione Sociale:' ); ?></th>
                <td><?php echo esc_attr(get_the_author_meta( 'ragionesociale', $user->ID )); ?></td>
            </tr>
            <tr>
                <th><?php _e( 'Partita IVA:' ); ?></th>
                <td><?php echo esc_attr(get_the_author_meta( 'piva', $user->ID )); ?></td>
            </tr>
        </tbody>
    </table>
<?php }
add_action( 'woocommerce_thankyou', 'display_extra_order_data', 20 );
add_action( 'woocommerce_view_order', 'display_extra_order_data', 20 );


/**
 * Aggiungo i campi personalizzati nel dettaglio ordine WooCommerce Admin
 */

function display_extra_order_data_in_admin( $order ){ 
$order = new WC_Order( $order_id ); ?>

    <div class="order_data_column">
        <h4><?php _e( 'Dati di fatturazione', 'woocommerce' ); ?><a href="#" class="edit_address"><?php _e( 'Edit', 'woocommerce' ); ?></a></h4>
        <div class="address">
        <?php
            echo '<p><strong>' . __( 'Ragione sociale' ) . ':</strong>' . get_the_author_meta( 'ragionesociale', $order->ID) . '</p>';
            echo '<p><strong>' . __( 'Partita IVA' ) . ':</strong>' . get_the_author_meta( 'piva', $order->ID) . '</p>'; ?>
        </div>
        <div class="edit_address">
            <?php woocommerce_wp_text_input( array( 'id' => 'ragionesociale', 'label' => __( 'Ragione sociale' ), 'wrapper_class' => 'billing_ragione_sociale' ) ); ?>
            <?php woocommerce_wp_text_input( array( 'id' => 'piva', 'label' => __( 'Partita IVA' ), 'wrapper_class' => 'billing_partita_iva' ) ); ?>
        </div>
    </div>
<?php }
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'display_extra_order_data_in_admin' );
?>