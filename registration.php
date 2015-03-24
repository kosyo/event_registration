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

		wp_enqueue_script('loadjs', $pluginUrl.'/trunk/registration.js');
        wp_enqueue_script('loadvalidationjs', $pluginUrl.'/trunk/jquery.validate.min.js');
        wp_enqueue_style('loadcss', $pluginUrl.'/trunk/registration.css');

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
        $output .= '<div id="content"><div id="error_box"></div><form action="" method="post" id="registration_form">
		<div>Event</div>
		' . $events;
if (!is_user_logged_in())
{
    $required_msg = __('This field is required.', 'us');
 	$output .= ' 
		<div><label>'.__('First name', 'us').'</label></div>	
  		<div><input type="text" name="first_name" data-rule-required="true" data-msg-required="' . $required_msg . '"></div>
		
		<div><label>'.__('Last name', 'us').'</label></div>	
  		<div><input type="text" name="last_name" data-rule-required="true" data-msg-required="' . $required_msg . '"></div>
		<div><label>'.__('Gender', 'us').'</label></div>
		<div>
		<select name="gender" data-rule-required="true" data-msg-required="' . $required_msg . '">
  			<option value="male">-</option>
            <option value="male">Male</option>
			<option value="female">Female</option>
		</select>
	 	<div><label>'.__('Email', 'us').'</label></div>	
  		<div><input type="email" name="email" data-rule-required="true" data-msg-required="' . $required_msg . '"></div>
		
 		<div><label>'.__('Year of birth', 'us').'</label></div>	
        <div>
       <select name="year_of_birth" id="year_of_birth" data-rule-required="true" data-msg-required="' . $required_msg . '">
 <option value="">-</option>
 <option value="1999">1999</option>
 <option value="1998">1998</option>
 <option value="1997">1997</option>
 <option value="1996">1996</option>
 <option value="1995">1995</option>
 <option value="1994">1994</option>
 <option value="1993">1993</option>
 <option value="1992">1992</option>
 <option value="1991">1991</option>
 <option value="1990">1990</option>
 <option value="1989">1989</option>
 <option value="1988">1988</option>
 <option value="1987">1987</option>
 <option value="1986">1986</option>
 <option value="1985">1985</option>
 <option value="1984">1984</option>
 <option value="1983">1983</option>
 <option value="1982">1982</option>
 <option value="1981">1981</option>
 <option value="1980">1980</option>
 <option value="1979">1979</option>
 <option value="1978">1978</option>
 <option value="1977">1977</option>
 <option value="1976">1976</option>
 <option value="1975">1975</option>
 <option value="1974">1974</option>
 <option value="1973">1973</option>
 <option value="1972">1972</option>
 <option value="1971">1971</option>
 <option value="1970">1970</option>
 <option value="1969">1969</option>
 <option value="1968">1968</option>
 <option value="1967">1967</option>
 <option value="1966">1966</option>
 <option value="1965">1965</option>
 <option value="1964">1964</option>
 <option value="1963">1963</option>
 <option value="1962">1962</option>
 <option value="1961">1961</option>
 <option value="1960">1960</option>
 <option value="1959">1959</option>
 <option value="1958">1958</option>
 <option value="1957">1957</option>
 <option value="1956">1956</option>
 <option value="1955">1955</option>
 <option value="1954">1954</option>
 <option value="1953">1953</option>
 <option value="1952">1952</option>
 <option value="1951">1951</option>
 <option value="1950">1950</option>
 <option value="1949">1949</option>
 <option value="1948">1948</option>
 <option value="1947">1947</option>
 <option value="1946">1946</option>
 <option value="1945">1945</option>
 <option value="1944">1944</option>
 <option value="1943">1943</option>
 <option value="1942">1942</option>
 <option value="1941">1941</option>
 <option value="1940">1940</option>
 <option value="1939">1939</option>
</select> 
        </div>
		
		<div><label>'.__('Phone number', 'us').'</label></div>	
		<div><input type="text" name="phone" data-rule-required="true" data-msg-required="' . $required_msg . '"></div>

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
   //         error_reporting(E_ERROR | E_PARSE);
		    global $wpdb;

			if (!is_user_logged_in())
			{
				$pass = wp_generate_password( $length=6, $include_standard_special_chars=false );
echo is_object($_POST['first_name']);
echo is_object($_POST['last_name']);
echo is_object($_POST['email']);
echo is_object($_POST['club']);

				$userdata = array(
			    		'first_name' => $_POST['first_name'],
                        'last_name' => $_POST['last_name'],
  		        		'user_login'  =>  $_POST['email'],	
		     	     	'user_email' => $_POST['email'	],
		        		'user_pass'   =>  $pass
				);

				$user_id = wp_insert_user( $userdata ) ;
	
				add_user_meta( $user_id, 'club', $_POST['club'], NULL );
                add_user_meta( $user_id, 'phone', $_POST['phone'], NULL );
                add_user_meta( $user_id, 'gender', $_POST['gender'], NULL );
                add_user_meta( $user_id, 'year_of_birth', $_POST['year_of_birth'], NULL );

                if(is_wp_error($user_id))
                {
                    $response = array ('success' => 0, 'msg' => $user_id->get_error_message());
                    echo json_encode($response);
                    die();
                    
                }

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

add_action( 'wp_ajax_nopriv_sendContact', 'tb_us_sendContact' );
		add_action( 'wp_ajax_sendContact', 'tb_us_sendContact' );
	add_shortcode('tb_contact', 'tb_contact_form');
?>
