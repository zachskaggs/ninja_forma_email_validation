<?php
/*
Plugin Name: Ninja Forms Email Validation Field
Plugin URI: http://wpninjas.net
Description: This plugin allows you to add an e-mail valiation field to your Ninja Forms
Version: 1.0
Author: WPN Zach
Author URI: http://zachskaggs.com
*/
function ninja_forms_register_field_email_validate(){
	$args = array(
		'name' => __( 'Validate E-mail', 'ninja-forms' ),
		'sidebar' => 'template_fields',
		'edit_function' => 'ninja_forms_email_validate_edit',
		'edit_options' => array(
			array(
				'type' => 'hidden',
				'name' => 'email',
				'default' => 1,
			),
			array(
				'type' => 'checkbox',
				'name' => 'send_email',
				'label' => __( 'Send a response email to this email address?', 'ninja-forms' ),
			),
			// array(
			// 	'type' => 'checkbox',
			// 	'name' => 'from_email',
			// 	'label' => __( 'Use this as the "From" email address for Administrative recipients of this form?', 'ninja-forms' ),
			// ),
			array(
				'type' => 'checkbox',
				'name' => 'replyto_email',
				'label' => __( 'Use this email address as the Reply-To address?', 'ninja-forms' ),
			),
			array(
				'type' => 'hidden',
				'name' => 'user_email',
			),
			array(
				'type' => 'hidden',
				'name' => 'user_info_field_group',
				'default' => 1,
			),
		),
		'display_function' => 'ninja_forms_field_email_validate_display',
		'save_function' => '',
		'group' => 'standard_fields',
		'edit_label' => true,
		'edit_label_pos' => false,
		'edit_req' => true,
		'edit_custom_class' => true,
		'edit_help' => true,
		'edit_desc' => true,
		'edit_meta' => false,
		'edit_conditional' => true,
		'conditional' => array(
			'value' => array(
				'type' => 'text',
			),
		),
		'pre_process' => 'ninja_forms_field_email_validate_pre_process',
	);

	ninja_forms_register_field( 'Email Validation', $args );
}

add_action( 'init', 'ninja_forms_register_field_email_validate' );

function ninja_forms_email_validate_edit( $field_id, $data ){
	$plugin_settings = nf_get_settings();

		// Default Value
	if( isset( $data['default_value'] ) ){
		$default_value = $data['default_value'];
	}else{
		$default_value = '';
	}
	if( $default_value == 'none' ){
		$default_value = '';
	}

	if( isset( $data['email_validate_error'] ) ){
		$email_validate_error = $data['email_validate_error'];
	}else{
		$email_validate_error = __( 'E-Mail Addresses do not match', 'ninja-forms' );
	}

	?>
	<div class="description description-thin">
		<span class="field-option">
		<label for="">
			<?php _e( 'Default Value' , 'ninja-forms'); ?>
		</label><br />
			<select id="default_value_<?php echo $field_id;?>" name="" class="widefat ninja-forms-_text-default-value">
				<option value="" <?php if( $default_value == ''){ echo 'selected'; $custom = 'no';}?>><?php _e('None', 'ninja-forms'); ?></option>
				<option value="_user_email" <?php if($default_value == '_user_email'){ echo 'selected'; $custom = 'no';}?>><?php _e('User Email (If logged in)', 'ninja-forms'); ?></option>
				<option value="_custom" <?php if($custom != 'no'){ echo 'selected';}?>><?php _e('Custom', 'ninja-forms'); ?> -></option>
			</select>
		</span>
	</div>

	<div class="description description-thin">

		<label for="" id="default_value_label_<?php echo $field_id;?>" style="<?php if($custom == 'no'){ echo 'display:none;';}?>">
			<span class="field-option">
			<?php _e( 'Default Value' , 'ninja-forms'); ?><br />
			<input type="text" class="widefat code" name="ninja_forms_field_<?php echo $field_id;?>[default_value]" id="ninja_forms_field_<?php echo $field_id;?>_default_value" value="<?php echo $default_value;?>" />
			</span>
		</label>

	</div>
	
	<div class="description description-wide">
		<span class="field-option">
		<label for="">
			<?php _e( 'Email Validation Error Message' , 'ninja-forms'); ?>
		</label><br />
			<input type="text" id="email_validate_error_<?php echo $field_id;?>" name="ninja_forms_field_<?php echo $field_id;?>[email_validate_error]" value="<?php echo $email_validate_error;?>" class="widefat" />
		</span>
	</div>

	<?php
}

function ninja_forms_field_email_validate_display( $field_id, $data ){
	global $current_user, $ninja_forms_processing;
	$field_class = ninja_forms_get_field_class( $field_id );
	if ( isset ( $ninja_forms_processing ) ) {
		$validate_email = $ninja_forms_processing->get_extra_value( '_validate_email' );
	} else {
		$validate_email = '';
	}
	

	if ( isset( $data['email'] ) ) {
		$field_class .= ' email';
	}

	if(isset($data['default_value'])){
		$default_value = $data['default_value'];
	}else{
		$default_value = '';
	}

	if(isset($data['label_pos'])){
		$label_pos = $data['label_pos'];
	}else{
		$label_pos = "left";
	}

	if(isset($data['label'])){
		$label = $data['label'];
	}else{
		$label = '';
	}

	?>
	<input id="ninja_forms_field_<?php echo $field_id;?>" data-mask="<?php echo $mask;?>" data-input-limit="<?php echo $input_limit;?>" data-input-limit-type="<?php echo $input_limit_type;?>" data-input-limit-msg="<?php echo $input_limit_msg;?>" name="ninja_forms_field_<?php echo $field_id;?>" type="text" class="<?php echo $field_class;?> <?php echo $mask_class;?>" placeholder="<?php _e( 'Enter E-mail', 'ninja-forms' );?>" value="<?php echo $default_value;?>" rel="<?php echo $field_id;?>" />
	<input id="ninja_forms_field_<?php echo $field_id;?>" data-mask="<?php echo $mask;?>" data-input-limit="<?php echo $input_limit;?>" data-input-limit-type="<?php echo $input_limit_type;?>" data-input-limit-msg="<?php echo $input_limit_msg;?>" name="_validate_email" type="text" class="<?php echo $field_class;?> <?php echo $mask_class;?>" placeholder="<?php _e( 'Re-Enter E-mail', 'ninja-forms' );?>" value="<?php echo $validate_email;?>" rel="<?php echo $field_id;?>" />
	<?php

}

function ninja_forms_field_email_validate_pre_process( $field_id, $user_value ){
	global $ninja_forms_processing;
	$plugin_settings = nf_get_settings();
	$validate_email = $ninja_forms_processing->get_extra_value( '_validate_email' );
	
	if( isset( $plugin_settings['invalid_email'] ) ){
		$invalid_email = __( $plugin_settings['invalid_email'], 'ninja-forms' );
	}else{
		$invalid_email = __( 'Please enter a valid email address.', 'ninja-forms' );
	}
	$field_row = $ninja_forms_processing->get_field_settings( $field_id );
	$data = $field_row['data'];
	if( isset( $data['email_validate_error'] ) ) {
		$email_validate_error = $data['email_validate_error'];
	} 
	if( isset( $data['email'] ) AND $data['email'] == 1 AND $user_value != '' ){
		if ( ! is_email( $user_value ) ) {
    		$ninja_forms_processing->add_error( 'email-'.$field_id, $invalid_email, $field_id );
		}
	}

	if( isset( $data['email'] ) AND $data['email'] == 1 AND $user_value != '' ){
		if ( $user_value != $validate_email ) {
    		$ninja_forms_processing->add_error( 'email-'.$field_id, $email_validate_error, $field_id );
		}
	}

	if( ( isset( $data['replyto_email'] ) AND $data['replyto_email'] == 1 ) OR ( isset( $data['from_email'] ) AND $data['from_email'] == 1 ) ) {
		$user_value = $ninja_forms_processing->get_field_value( $field_id );
		$ninja_forms_processing->update_form_setting( 'admin_email_replyto', $user_value );
	}

}