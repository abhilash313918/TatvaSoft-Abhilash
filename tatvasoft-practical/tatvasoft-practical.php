<?php
/*
Plugin Name: TatvaSoft Practical
Plugin URI: https://www.tatvasoft.com
Description: Practical - WordPress
Version: 1.0
Author: Abhilash Singh
Author URI: https://www.taritas.com/
*/
 
function tatvasoft_enqueue() 
{       
    // JS
    
    wp_register_script('tatvasoft_jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js');
    wp_enqueue_script('tatvasoft_jquery');

    wp_register_script('tatvasoft_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
    wp_enqueue_script('tatvasoft_bootstrap');

    // CSS
    wp_register_style('tatvasoft_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('tatvasoft_bootstrap');
}
add_action( 'wp_enqueue_scripts', 'tatvasoft_enqueue' );

function tatvasoft_login_form() { 
	if (is_user_logged_in()) {
        wp_redirect( home_url() ); exit; 
    }
	
	$LoginForm = wp_login_form( 
	 array(
			'echo'           => false,
			// Default 'redirect' value takes the user back to the request URI.
			'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
			'form_id'        => 'loginform',
			'label_username' => __( 'Email' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in'   => __( 'Log In' ),
			'id_username'    => 'user_email',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => '',
			// Set 'value_remember' to true to default the "Remember me" checkbox to checked.
			'value_remember' => false,
		)
	);
	return '<h3 class="text-center">Login form</h3>'. $LoginForm;
 } 
// login shortcode
add_shortcode('tatvasoftlogin', 'tatvasoft_login_form'); 

function tatvasoft_registration_form() {
	if (is_user_logged_in()) {
        wp_redirect( home_url() ); exit; 
    }
    $FirstName="";
    $MiddleName="";
    $LastName="";
    $UserEmail="";
    $Phonenumber="";
    $Address="";
    if (isset($_POST['signup'])) {
        //registration_validation($_POST['username'], $_POST['useremail']);
        global $reg_errors;
        $reg_errors = new WP_Error;
        $FirstName=$_POST['FirstName'];
        $MiddleName=$_POST['MiddleName'];
        $LastName=$_POST['LastName'];
        $UserEmail=$_POST['UserEmail'];
        $Phonenumber=$_POST['Phonenumber'];
        $Address=$_POST['Address'];
        $UserPassword=$_POST['UserPassword'];
        $ConfirmPassword=$_POST['ConfirmPassword'];
        
        
        if(empty( $FirstName ) || empty( $LastName ) || empty($UserEmail) || empty($UserPassword) || empty($ConfirmPassword))
        {
            $reg_errors->add('field', 'Required form field is missing!');
        }    
        
        if ( !is_email( $UserEmail ) )
        {
            $reg_errors->add( 'email_invalid', 'Email id is not valid!' );
        }
        
        if ( email_exists( $UserEmail ) )
        {
            $reg_errors->add( 'email', 'Email Already exist!' );
        }
        if ( 5 > strlen( $UserPassword ) ) {
            $reg_errors->add( 'password', 'Password length must be greater than 5!' );
        }
        if ( $UserPassword != $ConfirmPassword ) {
            $reg_errors->add( 'password', 'Password and Confirm Password do not matched!' );
        }
        
        if (is_wp_error( $reg_errors ))
        { 
            foreach ( $reg_errors->get_error_messages() as $error )
            {
                $signUpError='<p style="color:#FF0000; text-aling:left;"><strong>ERROR</strong>: '.$error . '<br /></p>';
            } 
        }
        
        
        if ( 1 > count( $reg_errors->get_error_messages() ) )
        {
            // sanitize user form input
            global $username, $useremail;
            $useremail  =   sanitize_email( $_POST['UserEmail'] );
            $password   =   esc_attr( $_POST['UserPassword'] );
            
            $userdata = array(
                'user_login'    =>   $useremail,
                'user_email'    =>   $useremail,
                'user_pass'     =>   $password,
                );
            $user_id = wp_insert_user( $userdata );
            update_user_meta( $user_id, 'Tatva_FirstName', $FirstName );
            update_user_meta( $user_id, 'Tatva_MiddleName', $MiddleName );
            update_user_meta( $user_id, 'Tatva_LastName', $LastName );
            update_user_meta( $user_id, 'Tatva_Phonenumber', $Phonenumber );
            update_user_meta( $user_id, 'Tatva_Address', $Address );

            wp_redirect( home_url() ); exit; 
        }

    }
    $RegForm = '<form class="form-horizontal" action="" method="post" name="user_registeration">
	
  <div class="form-group">
    <label for="FirstName" class="col-sm-3 control-label">First Name<span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required value="'.$FirstName.'">
    </div>
  </div>
  
  <div class="form-group">
    <label for="MiddleName" class="col-sm-3 control-label">Middle Name</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="MiddleName" name="MiddleName" placeholder="Middle Name" value="'.$MiddleName.'">
    </div>
  </div>
  
  <div class="form-group">
    <label for="LastName" class="col-sm-3 control-label">Last Name<span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last Name" required value="'.$LastName.'">
    </div>
  </div>
  
  <div class="form-group">
    <label for="UserEmail" class="col-sm-3 control-label">Email<span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="email" class="form-control" id="UserEmail" name="UserEmail" placeholder="Email" required value="'.$UserEmail.'">
    </div>
  </div>
  
  <div class="form-group">
    <label for="Phonenumber" class="col-sm-3 control-label">Phone number</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="Phonenumber" name="Phonenumber" placeholder="Phone number" value="'.$Phonenumber.'">
    </div>
  </div>
  
  <div class="form-group">
    <label for="Address" class="col-sm-3 control-label">Address</label>
    <div class="col-sm-9">
	  <textarea class="form-control" id="Address" name="Address" placeholder="Address" rows="3">'.$Address.'</textarea>
    </div>
  </div>
  
  <div class="form-group">
    <label for="UserPassword" class="col-sm-3 control-label">Password<span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="password" class="form-control" id="UserPassword" name="UserPassword" placeholder="Password" required>
    </div>
  </div>
    
  <div class="form-group">
    <label for="ConfirmPassword" class="col-sm-3 control-label">Confirm Password<span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="password" class="form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password" required>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" name="signup" class="btn btn-default" value="Signup">Signup</button>
    </div>
  </div>
</form>';

    $ReturnHtml = '<h3 class="text-center">Create your account</h3>';
    if(isset($signUpError)){$ReturnHtml .= '<div>'.$signUpError.'</div>'; }
    $ReturnHtml .= $RegForm;
    return $ReturnHtml
;
}
// registration shortcode
add_shortcode('tatvasoftregistration', 'tatvasoft_registration_form'); 

// Add page on Plugin Activation.

define( 'TATVASOFT_PLUGIN_FILE', __FILE__ );

register_activation_hook( TATVASOFT_PLUGIN_FILE, 'tatvasoft_plugin_activation' );

function tatvasoft_plugin_activation() {
  if ( ! current_user_can( 'activate_plugins' ) ) return;
  global $wpdb;
  if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'new-page-slug'", 'ARRAY_A' ) ) {
    $current_user = wp_get_current_user();

    // create post object
    $loginpage = array(
      'post_title'  => __( 'Login Page' ),
      'post_status' => 'publish',
      'post_author' => $current_user->ID,
      'post_type'   => 'page',
      'post_content' => '[tatvasoftlogin]'
    );
    
    $registrationpage = array(
      'post_title'  => __( 'Registration Page' ),
      'post_status' => 'publish',
      'post_author' => $current_user->ID,
      'post_type'   => 'page',
      'post_content' => '[tatvasoftregistration]'
    );
    // insert the post into the database
    wp_insert_post( $loginpage );
    wp_insert_post( $registrationpage );
  }
}