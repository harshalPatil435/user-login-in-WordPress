<?php
/** Template Name: User register */

$html = '';
if(isset($_POST['reg_form']) && !empty($_POST['reg_form'])) {
    // echo '<pre>'; print_r($_POST); echo '</pre>';
    // die;
    $error = array();
    $username = sanitize_user($_POST['user_name']);
    $email = sanitize_email($_POST['user_email']);
    $password = $_POST['user_password'];
    $confpassword = $_POST['confpassword'];

    $error=array();

    if(strpos($username,' ')!==FALSE){
        $error['username_space']="username has space";
    }

    if(empty($username)){
        $error['username_empty']="username needed";
    }

    if(username_exists($username)) {
        $error['username_exist']="username already exists";
    }

    if(!is_email($email)) {
        $error['email_valid']="enter valid email id";
    }

    if(email_exists($email)) {
      $error['email_existence']="email already exists";
    }

    if(strcmp($password, $confpassword)) {
        $error['password']="password doesnt match";
    }

    if(count($error)==0) {

        $user_id = wp_create_user($username,$password,$email);
                
        $code = sha1( $user_id . time() );
        $token = md5($user_id).rand(10,9999);
        $activation_link = add_query_arg( array( 'user_tkn' => $token, 'user' => $user_id ), get_permalink( '18' ));
        add_user_meta( $user_id, 'has_to_be_activated', $code, true );
        wp_mail( $email, 'ACTIVATION SUBJECT', 'CONGRATS BLA BLA BLA. HERE IS YOUR ACTIVATION LINK: ' . $activation_link );
        setcookie('user_tkn', $token , time() + (120), "/");


        // $subject = 'Thanks for registering';
        // $html .= '<h1>Click for verify</h1>';
        // $html .= '<a href="http://localhost/training/user_registration/user-login/?uID='.$user_id.'&ustatus=active">verify</a>';
        // $message = 'Thanks for registering. Please return to the site to log in: http://localhost/training/user_registration/wp-admin/';
  
        // wp_mail( $email, $subject, $html );
        // exit();

    }else{
        print_r($error);
    }
}

get_header();

if ( !is_user_logged_in() ) {
?>
    <form action="" method="POST">
        <label>User Name</label>
        <input type="text" name="user_name">
        <br><br>
        <label>Email Id</label>
        <input type="email" name="user_email">
        <br><br>
        <label>Password</label>
        <input type="password" name="user_password">
        <br><br>
        <label>Confirm password</label>
        <input type="password" name="confpassword">
        <br><br>
        <input type="submit" name="reg_form" value="Submit">
    </form>
<?php
}

get_footer();