<?php
/*
Plugin Name: marathon events
Description: 
Version: 1.1
Revision: $Rev: 1 $
Author: Konstantin Stoyanov <kosyo>
License: Free
*/
add_filter('show_admin_bar', '__return_false');
add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}
function pw_global_js_vars() {
	echo '<script type="text/javascript">
	/* <![CDATA[ */
	var ajaxurl = ' . admin_url('admin-ajax.php') . ';
	/* ]]> */
	</script>';
}
add_action( 'wp_head', 'pw_global_js_vars' );

function tb_contact_form($attr)
	{
		$pluginUrl = plugins_url();

		wp_enqueue_script('loadjs', $pluginUrl.'/registration.js');

		global $wpdb;
		$rows = $wpdb->get_results("SELECT * FROM marathon_events 
					     where start_timestamp < now() 
					     and end_timestamp > now()");
		foreach($rows as $row)
		{
			$distances = $wpdb->get_results( 
			$wpdb->prepare("SELECT * FROM marathon_events_distances WHERE event_id = %d", $row->id));

			$events .= '<div>'. $row->name . ' ';
			$has_distances = false;
			foreach($distances as $distance)
			{
				$has_distances = true;
				$events .= '<input type="radio" name="' . $row->id . '" value="' . $distace->id . '"> ' . $distance->name ;
			}
			if(!$has_distances)
			{
				$events .= '<input type="checkbox" name="' . $row->id . '" value="' . $row->id . '">';
			}
			$events .= '</div>';
		}
$output .= '<div id="content"><form action="" method="post" id="registration_form">
		<div>Event</div>
		' . $events;
if (!is_user_logged_in())
{
 	$output .= ' 
		<div><label>'.__('First name', 'us').'</label></div>	
  		<div><input type="text" name="first_name" data-rule-required="true"></div>
		
		<div><label>'.__('Last name', 'us').'</label></div>	
  		<div><input type="text" name="last_name" data-required="1"></div>
		<div><label>'.__('Gender', 'us').'</label></div>
		<div>
		<select name="gender">
  			<option value="male">Male</option>
			<option value="female">Female</option>
		</select>
	 	<div><label>'.__('Email', 'us').'</label></div>	
  		<div><input type="email" name="email" data-required="1"></div>
		
 		<div><label>'.__('Year of birth', 'us').'</label></div>	
  		<div><input type="text" name="year_of_birth" data-required="1"></div>
		
		<div><label>'.__('Phone number', 'us').'</label></div>	
		<div><input type="text" name="phone" data-required="1"></div>

		<div><label>'.__('Club', 'us').'</label></div>
		<div><input type="text" name="club"></div>												
		
		
';
}

$output .= '	
<input type="submit" id="message_send" value="' . __('Send Message', 'us') . '">
</form>
		</div>
';

		return $output;
	}

	if ( ! function_exists('us_sendContact'))
	{		

		function tb_us_sendContact()
		{
		global $wpdb;

			if (!is_user_logged_in())
			{
				$pass = wp_generate_password( $length=6, $include_standard_special_chars=false );
			
				$userdata = array(
					'first_name' => $_POST['first_name'],
  		        		'user_login'  =>  $_POST['email'],	
		     	     		'user_email' => $_POST['email'	],
		        		'user_pass'   =>  $pass
				);

				$user_id = wp_insert_user( $userdata ) ;
	
				add_user_meta( $user_id, 'club', $_POST['club'], NULL );
			}
			else
			{
				$user_id = get_current_user_id();
			}
		
			$rows = $wpdb->get_results("SELECT * FROM marathon_events 
					     where start_timestamp < now() 
					     and end_timestamp > now()");
			foreach($rows as $row)
			{
				if(isset($_POST[$row->id]))
				{
					$wpdb->query($wpdb->prepare("INSERT INTO marathon_events_users (event_distance_id, user_id) VALUES (%s, %s)", $row->id, $user_id));
				}
			}
//mail("kosyokk@gmail.com","My subject",$_POST['first_name'] . $pass);
			@session_start();

			global $smof_data;
			$errors = 0;

			$errorJson = array();

			if (empty($_POST['email']))
			{
//				$errors++;
//				$errorJson['email'] = "Invalid email";
			}

			if (empty($_POST['phone']))
			{
//				$errors++;
//				$errorJson['phone'] = "Invalid Phone";
			}

			if($errors > 0){
				echo json_encode($errorJson);
				die();
			}

			$emailTo = (@$smof_data['contact_form_email'] != '') ? $smof_data['contact_form_email'] : get_option('admin_email');

			$body = '';

			if (in_array(@$smof_data['contact_form_name_field'], array('Shown, required', 'Shown, not required')))
			{
				$body .= __('Name', 'us').": ".addslashes($_POST['name'])."\n";
			}

			if (in_array(@$smof_data['contact_form_email_field'], array('Shown, required', 'Shown, not required')))
			{
				$body .= __('Email', 'us').": ".addslashes($_POST['email'])."\n";
			}

			if (in_array(@$smof_data['contact_form_phone_field'], array('Shown, required', 'Shown, not required')))
			{
				$body .= __('Phone', 'us').": ".addslashes($_POST['phone'])."\n";
			}

			$body .= "\n".__('Message', 'us').":\n".addslashes($_POST['message']);
			$headers = '';

			wp_mail($emailTo, __('Contact request from', 'us')." http://".$_SERVER['HTTP_HOST'].'/', $body, $headers);

			$response = array ('success' => 1);
		echo json_encode($response);

			die();

		}

	}

	function start_list($attr)
	{
        global $wpdb;
        
        $rows = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM marathon_events_users MEU JOIN marathon_events ME ON ME.id = MEU.event_id WHERE ME.event_id = %s ", $attr['event_id']));
        foreach($rows as $row)
        {
            $output .= $row->user_id;
        }
        return $output;
	}




add_action( 'wp_ajax_nopriv_sendContact', 'tb_us_sendContact' );
		add_action( 'wp_ajax_sendContact', 'tb_us_sendContact' );
	add_shortcode('tb_contact', 'tb_contact_form');

	add_shortcode('start_list', 'start_list');

?>
