<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
$user_id = get_current_user_id();
?>
<script type="text/javascript">

jQuery(document).ready(function(){

jQuery("#gender_select").val('<?php echo get_user_meta( $user_id, 'gender', true);?>');
jQuery("#year_of_birth_select").val('<?php echo get_user_meta( $user_id, 'year_of_birth', true); ?>');
function validate_form(form){
        var isvalid = document.getElementById("your-profile").valid();
        if(!isvalid)
        {
            this.preventDefault();
        }
}
});
</script>
<script src="<?php echo plugins_url(); ?>/trunk/jquery.validate.min.js"></script>
<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'profile' ); ?>
	<?php $template->the_errors(); ?>
	<form id="your-profile" action="<?php $template->the_action_url( 'profile' ); ?>" method="post">
		<?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
		<p>
			<input type="hidden" name="from" value="profile" />
			<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
		</p>

		<?php if ( has_action( 'personal_options' ) ) : ?>

		<h3><?php _e( 'Personal Options' ); ?></h3>

		<table class="form-table">
		<?php do_action( 'personal_options', $profileuser ); ?>
		</table>

		<?php endif; ?>

		<?php do_action( 'profile_personal_options', $profileuser ); ?>

		<h3><?php _e( 'Profile' ); ?></h3>

		<table class="form-table">

		<tr>
			<th><label for="first_name"><?php _e( 'First Name' ); ?></label></th>
			<td>
<input type="text" data-msg-required="This field is required." data-rule-required="true" aria-required="true" class="error" aria-invalid="true" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ); ?>" class="regular-text" /></td>
		</tr>

		<tr>
			<th><label for="last_name"><?php _e( 'Last Name' ); ?></label></th>
			<td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ); ?>" class="regular-text" /></td>
		</tr>
<tr>
        <th><label><?php _e('[:en]Gender[:bg]Пол') ?></label></th>
	<td>	
        <select id="gender_select" name="gender" data-rule-required="true" data-msg-required="' . $required_msg . '">
            <option value="">-</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
	</td>
</tr>
		<tr>
			<th><label for="email"><?php _e( 'Email' ); ?> </label></th>
			<td><input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ); ?>" class="regular-text" /></td>
		</tr>
<tr>
<th>
<label><?php _e('[:en]Year of birth[:bg]Година на раждане', 'us')?></label>
</th>
<td>
       <select name="year_of_birth" id="year_of_birth_select" data-rule-required="true" data-msg-required="' . $required_msg . '">
 <option value="">-</option>
<?php 
        for($i = 2000; $i > 1939; $i--)
        {
            echo '<option value="' . $i . '">' . $i . '</option>';
        }
?>
        </select>
</td>
</tr>
<tr>
        <th><label><?php _e('[:en]Phone number[:bg]Телефонен номер', 'us')?></label></th>  
        <td><input type="text" name="phone" value="<?php echo get_user_meta( $user_id, 'phone', true);?>" data-rule-required="true" data-msg-required="' . $required_msg . '"></td>
</tr>
<tr>
        <th><label><?php _e('[:en]Club[:bg]Клуб')?></label></th>
        <td><input type="text" name="club" value="<?php echo get_user_meta( $user_id, 'club', true);?>"></td>   
</tr>
</tr>        


		<?php
			foreach ( _wp_get_user_contactmethods() as $name => $desc ) {
		?>
		<tr>
			<th><label for="<?php echo $name; ?>"><?php echo apply_filters( 'user_'.$name.'_label', $desc ); ?></label></th>
			<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr( $profileuser->$name ); ?>" class="regular-text" /></td>
		</tr>
		<?php
			}
		$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
		if ( $show_password_fields ) :
		?>
		<tr id="password">
			<th><label for="pass1"><?php _e( 'New Password' ); ?></label></th>
			<td><input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /> <span class="description"><?php _e( 'If you would like to change the password type a new one. Otherwise leave this blank.' ); ?></span><br />
				<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" /> <span class="description"><?php _e( 'Type your new password again.' ); ?></span><br />
			</td>
		</tr>
		<?php endif; ?>
		</table>

		<?php do_action( 'show_user_profile', $profileuser ); ?>

		<p class="submit">
			<input type="hidden" name="action" value="profile" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Profile' ); ?>" name="submit" />
		</p>
	</form>
</div>
