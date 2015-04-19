<?php
/*
Plugin Name: marathon events
Description: 
Version: 1.1
Revision: $Rev: 1 $
Author: Konstantin Stoyanov <kosyo>
Text Domain: us
License: Free
*/
add_filter('show_admin_bar', '__return_false');
add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php 
        echo admin_url('admin-ajax.php?lang=' . qtrans_getLanguage() );
?>';
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
        if(current_user_can('administrator') && !isset($attr['unjoin']))
        {
            $output .= '<div id="payment_confirm"><form action="" method="post" id="payment_form">Payment ID: <input type="text" name="payment_id"><input type="submit" id="payed" value="' . __('Payed', 'us') . '">
</form></div>';
  
        }

        $lang = qtrans_getLanguage();
		$pluginUrl = plugins_url();

		wp_enqueue_script('loadjs', $pluginUrl.'/trunk/registration.js');
        wp_enqueue_script('loadvalidationjs', $pluginUrl.'/trunk/jquery.validate.min.js');
        wp_enqueue_style('loadcss', $pluginUrl.'/trunk/registration.css');

		global $wpdb;
        if (is_user_logged_in())
        {
            $user_id = get_current_user_id();
        }
        else
        {
            $user_id = -1;
        }
		$rows = $wpdb->get_results($wpdb->prepare("SELECT ME.*, MEU.event_distance_id, MEU.id AS marathon_events_users_id  FROM marathon_events ME
                                    LEFT JOIN marathon_events_users MEU ON MEU.event_id = ME.id AND MEU.user_id = $user_id
                                    where ME.start_timestamp < now() 
                                    and ME.end_timestamp > now()
                                    AND ME.organization_id = %d
                         ORDER BY ME.ordering, ME.id", $attr['organization_id']));
		foreach($rows as $row)
        {
            if(!isset($row->event_distance_id))
            {
                $distances = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM marathon_events_distances WHERE event_id = %s ORDER BY ordering, id", $row->unique_id));

                $events .= '<div class="event"><b>'. $row->{name_ . $lang} . '</b><div>';
                $has_distances = false;
                foreach($distances as $distance)
                {
                    $has_distances = true;
                    $events .= '<input type="radio" name="' . $row->id . '" value="' . $distance->id. '"> ' . $distance->{name_ . $lang}. ' ' ;
                }
                if(!$has_distances)
                {
                    $rows = $wpdb->insert("marathon_events_distances", array( event_id => $row->unique_id ));
                    $events .= '<input type="radio" name="' . $row->id . '" value="' . $wpdb->insert_id . '">';
                }
                $events .= '</div></div>';
            }   
            else
            {
                $events_unjoin .= '<div class="unjoin_event_row">' . $row->{name_ . $lang} . '<button class="unjoin_event" data-id="' . $row->marathon_events_users_id . '">' . __('unjoin') . '</button></div>';
            }
        }
        if(!isset($events_unjoin) && isset($attr['unjoin']))
        {
            $unjoin_title = '<div class="title">'.__("There are no events.",'us') .'</div>';
        }
        else
        {
            $unjoin_title = '';
        }

        if(!isset($events) && !isset($attr['unjoin']))
        {
            $new_events_title = '<div class="title">'.__("There are no events",'us') .'</div>';
        }
        else
        {
            if(!isset($attr['unjoin']))
            {
                $events = '<form action="" method="post" id="registration_form">' . $events; 
            }    
            $new_events_title = '';
        }

        if(isset($attr['unjoin']))
        {
            $events = '';
        }
        else
        {
            $events_unjoin = '';
        }

        $output .= '<div id="content"> <div id="error_box"></div>' . $unjoin_title . $events_unjoin . $new_events_title . $events;
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
if(isset($events) && $events != '')
{
    $output .= '<input type="submit" id="register" value="' . __('Register', 'us') . '"></form>';
}

$output .= '</div>';
		return $output;
	}

	if ( ! function_exists('us_sendContact'))
	{		

		function tb_us_sendContact($attr)
		{
            $lang = qtrans_getLanguage();
   //         error_reporting(E_ERROR | E_PARSE);
		    global $wpdb;
            $wpdb->query( "START TRANSACTION;" );
 
			if (!is_user_logged_in())
			{
				$pass = rand(1000,9999);//wp_generate_password( $length=6, $include_standard_special_chars=false );
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

                $mail = $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_messages WHERE code = 'register_account_email' AND lang = %s", $lang));
 
                if(isset($mail[0]->data))
                {
                    $mail[0]->data = str_replace('{EMAIL}', $_POST['email'], $mail[0]->data);
                    $mail[0]->data = str_replace('{PASS}', $pass, $mail[0]->data);
                    $mail[0]->data = str_replace('{PASS_RESET_LINK}', password_reset_link($_POST['email']), $mail[0]->data);
                    
//                    mail("kosyokk@gmail.com",$mail[0]->title, $mail[0]->data);
                }
			}
			else
			{
				$user_id = get_current_user_id();
			}
		
			$rows = $wpdb->get_results("SELECT * FROM marathon_events 
					     where start_timestamp < now() 
					     and end_timestamp > now()"
                         );

            $have_event = false;
                
            $events_count = 0;
            $events_arr = array();
			foreach($rows as $row)
			{
				if(isset($_POST[$row->id]))
				{
                    if($have_event == false)
                    {       
                        $wpdb->insert("marathon_events_users_group_seq", array(t => 1));
                        $group_id = $wpdb->insert_id;
                    }
                    $have_event = true;
					$sth = $wpdb->insert("marathon_events_users", array (event_distance_id => $_POST[$row->id], user_id => $user_id, event_id => $row->id, group_id => $group_id));
                    array_push($events_arr, $wpdb->insert_id);
				    if(!$sth)
                    {
                        $events_rows = $wpdb->get_results($wpdb->prepare("SELECT ME.* FROM marathon_events_users MEU 
                                                                          JOIN marathon_events_distances MED ON MED.id = MEU.event_distance_id 
                                                                          JOIN marathon_events ME ON MED.event_id = ME.unique_id 
                                                                          WHERE MEU.user_id = %d 
                                                                            AND start_timestamp < now() 
                                                                            AND ME.end_timestamp > now() ORDER BY MED.ordering", $user_id));
                        $events_name = '';
                        foreach($events_rows as $event)
                        {
                            $events_name .= $event->{name_ . $lang} . ", ";
                        }

                        $response = array ('success' => 0, 'msg' => sprintf(__("There is a problem with your registration. You are already registered for %s events. Please uncheck these events and try again."), substr($events_name, 0, -2)), "us");
                        echo json_encode($response);
                        die();
                    }

                    $distance = $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_events_distances WHERE id = %d", $_POST[$row->id]));
                    $event_names .= $row->{name_ . $lang} . '  ' . $distance[0]->{name_ . $lang} .  ', '; 
                    $event_names_en .= $row->{name_en} . '  ' . $distance[0]->{name_en} .  ', ';
                    $events_count++;
                }
            }

            if(!$have_event)
            {
                $response = array ('success' => 0, 'msg' => __("Please select at least one event.", "us"));
                echo json_encode($response);
                die();
            }
            
            $mail = $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_messages WHERE code = 'registration_email_confirmation' AND lang = %s", $lang));
            $event_names = substr($event_names, 0, -2);
            $event_names_en = substr($event_names_en, 0, -2);

            if(isset($mail[0]->data))
            {
//               mail("kosyokk@gmail.com", $mail[0]->title, str_replace('{EVENTNAMES}', $event_names, $mail[0]->data));
            }

            if($events_count > 0)
            {
                $price_row = $wpdb->get_results("SELECT * FROM marathon_events_prices WHERE count = $events_count");

                $price = sprintf('%.2f', $price_row[0]->price);
            }
            $epay = '<style>

A.epay-button             { border: solid  1px #FFF; background-color: #168; padding: 6px; color: #FFF; background-image: none; font-weight: normal; padding-left: 20px; padding-right: 20px; }
A.epay-button:hover       { border: solid  1px #ABC; background-color: #179; padding: 6px; color: #FFF; background-image: none; font-weight: normal; padding-left: 20px; padding-right: 20px; }

A.epay                    { text-decoration: none; border-bottom: dotted 1px #168; color: #168; font-weight: bold; }
A.epay:hover              { text-decoration: none; border-bottom: solid  1px #179; color: #179; font-weight: bold; }

TABLE.epay-view    { white-space: nowrap; background-color: #CCC; }

/********** VIEWES **********************************************************/

TD.epay-view            { width: 100%; text-align: center; background-color: #DDD; }
TD.epay-view-header     {                                  background-color: #168; color: #FFF; height: 30px; }
TD.epay-view-name       { width:  25%; text-align: right;  background-color: #E9E9F9; border-bottom: none;  height: 30px; }
TD.epay-view-value      { width:  75%; text-align: left;   background-color: #E9E9F9; border-bottom: none; white-space: normal; }

INPUT.epay-button         { border: solid  1px #FFF; background-color: #168; padding: 4px; color: #FFF; background-image: none; padding-left: 20px; padding-right: 20px; }
INPUT.epay-button:hover   { border: solid  1px #ABC; background-color: #179; padding: 4px; color: #FFF; background-image: none; padding-left: 20px; padding-right: 20px; }

</style>
</br>
</br>
<form action="https://www.epay.bg/" method=post>
<table class=epay-view cellspacing=1 cellpadding=4 width=350>
<tr>
<td class=epay-view-header align=center>Описание</td>
<td class=epay-view-header align=center>Сума</td>
</tr>
<tr>
<td class=epay-view-value><b>' . $event_names . '</b></td>
<td class=epay-view-name>'. $price . ' BGN</td>
</tr>
<tr>
<td colspan=2 class=epay-view-name>
<input class=epay-button type=submit name=BUTTON:EPAYNOW value="' . __('Pay on-line', 'us') . '">
</td>
</tr>
<tr>
<td colspan=2 class=epay-view-name style="white-space: normal; font-size: 10">
' . __('Payment is processed by') . ' <a class=epay href="https://www.epay.bg/">ePay.bg</a>
</td>
</tr>
</table>
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=MIN value="1682609015">
<input type=hidden name=INVOICE value="">
<input type=hidden name=TOTAL value="' . $price . '">
<input type=hidden name=DESCR value="(' . $group_id . ') ' . $event_names_en . '">
<input type=hidden name=URL_OK value=" '.  get_site_url() . '/' . $lang . '/payment_confirmation?events=' . json_encode($events_arr) . '>
<input type=hidden name=URL_CANCEL value="https://www.epay.bg/?p=cancel">
</form>
                    ';
            $msg_row = $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_messages WHERE code = 'registration_confirmation' AND lang = %s", $lang));

            $msg = str_replace('{EVENTNAMES}', substr($event_names, 0, -2), $msg_row[0]->data);
            $msg = str_replace('{EPAY}', $epay , $msg);
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
			$response = array ('success' => 1, msg => $msg);
            $wpdb->query( "COMMIT;" );
    		
            echo json_encode($response);
			die();
            
		}

	}

	function start_list($attr)
	{
        $lang = qtrans_getLanguage();
        $pluginUrl = plugins_url();
        wp_enqueue_script('loadjs', $pluginUrl.'/trunk/registration.js');
        wp_enqueue_script('loadvalidationjs', $pluginUrl.'/trunk/jquery.validate.min.js');
        wp_enqueue_style('loadcss', $pluginUrl.'/trunk/registration.css');

        global $wpdb;

        $event =  $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_events WHERE unique_id = %s", $attr['event_id']));

        $rows = $wpdb->get_results($wpdb->prepare("SELECT MED.* FROM marathon_events_distances MED JOIN marathon_events ME ON ME.unique_id = MED.event_id WHERE ME.unique_id = %s order by MED.ordering, MED.id", $attr['event_id']));
        if($rows == NULL)
        {
            $output .= __('No users are registered for this event yet. '.$attr['event_id'], 'us');
        }
        else
        {
            if(current_user_can('administrator'))
            {
                $admin_cells_header = '<td><b>' . __('Email', 'us') . '</b></td><td><b>' . __('Phone', 'us') . '</b></td><td><b>' . __('Payment ID', 'us') . '</b></td><td><b>' . __('Price', 'us') . '</b></td><td>' . __("Delete",'us') . '</td>';
            }
            else
            {   
                $admin_cells_header = '';
            }   

            foreach($rows as $row)
            {
                $output .=  $event[0]->{name_ . $lang} . '  ' . $row->{name_ . $lang} . '<br><table class="start_list_table"><tr><td><b>' . __('Name', 'us') . '</b></td><td><b>' . __('Surname') . '</b></td><td><b>' . __('YOB') . '</b></td><td><b>' .__('Club') . '</b></td><td><b>' . __('Gender') . '</b></td>' . $admin_cells_header . ' <tr>';
                $user_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_events_users MEU WHERE MEU.event_distance_id = %d order by id", $row->id));
                if($user_rows == NULL)
                {   
                    $output .= '</table>' . __('No users are registered for this event yet!', 'us') . '</br></br>';
                    next;
                }  
                foreach($user_rows as $user_row)
                {
                    $events_count = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM marathon_events_users MEU WHERE MEU.group_id = %d AND MEU.payment = false", $user_row->group_id));
                    $payment = $wpdb->get_results($wpdb->prepare("SELECT * FROM marathon_events_prices WHERE count = %d", $events_count));

                    $user = get_userdata($user_row->user_id);
                    if($user->gender == 'male')
                    {
                        $gender = __('Male', 'us');
                    }
                    else if (($user->gender == 'female'))
                    {
                        $gender = __('Female', 'us');
                    }
                    if(current_user_can('administrator'))
                    {   
                        $admin_cells = '<td>' . $user->user_email . '</td><td>' . $user->phone . '</td><td>' . $user_row->group_id . '</td><td>' . sprintf("%.2f", $payment[0]->price) .  '</td><td><button class="unjoin_event" data-id="' . $user_row->id . '">Delete</button></td>';
                    }
                    else
                    {
                        $admin_cells = '';
                    }
                    $output .=  '<tr><td>' . $user->first_name . '</td><td>' . $user->last_name . '</td><td>' . $user->year_of_birth . '</td><td>' . $user->club . '</td><td>' . $gender . '</td>' . $admin_cells .'</tr>';
                }
                $output .= '</table>';        
            }
        }


        return $output;
	}
/*
function payment_confirmation($atts)
{
    
    global $wpdb;
    $wpdb->query( "START TRANSACTION;" );

    $events = json_decode($_GET['events']);
    foreach ($events as $event)
    {
        $wpdb->query($wpdb->prepare("UPDATE marathon_events_users set online_payment = true WHERE id = %d", $event));
    }

    $wpdb->query( "COMMIT;" );

    return '';
}
*/

function pay()
{
    global $wpdb;
    $rows = $wpdb->get_results($wpdb->prepare("UPDATE marathon_events_users  
                                    SET payment = 1
                                    where group_id = %d", $_POST['payment_id']));

    $response = array ('success' => 1, msg => 'Successful pay for user ');
    echo json_encode($response);
    die();
}

function unjoin_event() 
{
    global $wpdb;
    $wpdb->query($wpdb->prepare("DELETE FROM marathon_events_users WHERE id = %d AND (user_id = %d OR %b)", $_POST['id'], get_current_user_id(), current_user_can('administrator') ));
    $response = array ('success' => 1, msg => $msg);
    echo json_encode($response);
    die();
}

function password_reset_link($login) {
    global $wpdb, $wp_hasher;

    $errors = new WP_Error();

        $user_data = get_user_by('login', $login);

    /**
     * Fires before errors are returned from a password reset request.
     *
     * @since 2.1.0
     */
    do_action( 'lostpassword_post' );

    if ( $errors->get_error_code() )
        return $errors;

    if ( !$user_data ) {
        $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    /**
     * Fires before a new password is retrieved.
     *
     * @since 1.5.0
     * @deprecated 1.5.1 Misspelled. Use 'retrieve_password' hook instead.
     *
     * @param string $user_login The user login name.
     */
    do_action( 'retreive_password', $user_login );

    /**
     * Fires before a new password is retrieved.
     *
     * @since 1.5.1
     *
     * @param string $user_login The user login name.
     */
    do_action( 'retrieve_password', $user_login );

    /**
     * Filter whether to allow a password to be reset.
     *
     * @since 2.7.0
     *
     * @param bool true           Whether to allow the password to be reset. Default true.
     * @param int  $user_data->ID The ID of the user attempting to reset a password.
     */
    $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

    if ( ! $allow ) {
        return new WP_Error( 'no_password_reset', __('Password reset is not allowed for this user') );
    } elseif ( is_wp_error( $allow ) ) {
        return $allow;
    }

    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );

    /**
     * Fires when a password reset key is generated.
     *
     * @since 2.5.0
     *
     * @param string $user_login The username for the user.
     * @param string $key        The generated password reset key.
     */
    do_action( 'retrieve_password_key', $user_login, $key );

    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = $wp_hasher->HashPassword( $key );
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );

    return network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');

}

// Deregister editor-expand as it breaks CKEditor integration
function custom_deregister_editor_expand() {
wp_deregister_script('editor-expand');
}
    add_action( 'admin_init', 'custom_deregister_editor_expand' );


    add_action( 'wp_ajax_nopriv_sendContact', 'tb_us_sendContact' );
    add_action( 'wp_ajax_sendContact', 'tb_us_sendContact' );

    add_action( 'wp_ajax_nopriv_unjoin_event', 'unjoin_event' );
    add_action( 'wp_ajax_unjoin_event', 'unjoin_event' );

    add_action( 'wp_ajax_nopriv_pay', 'pay' );
    add_action( 'wp_ajax_pay', 'pay' );

	add_shortcode('registration', 'tb_contact_form');

	add_shortcode('start_list', 'start_list');
    add_shortcode('payment_list', 'payment_list');

//    add_shortcode('payment_confirmation', 'payment_confirmation');
    add_filter( 'locale', 'wpse_52419_change_language' );
    function wpse_52419_change_language( $locale )
    {
        return 'bg_BG';
    }

?>
