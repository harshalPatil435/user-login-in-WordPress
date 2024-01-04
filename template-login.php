<?php
/** Template Name: User Login */

if(isset($_COOKIE['user_tkn']) && !empty($_COOKIE['user_tkn'])) {
    
}else{
    ?>
    <script>
        alert('This link is expired');
    </script>
    <?php
}



$subject = '';
$html = '';

/*
if(isset($_GET['uID']) && isset($_GET['ustatus'])) {
    if(!empty($_COOKIE['user_verify'])) {
        update_user_meta( $_GET['uID'] , 'user_verify' , $_GET['ustatus'] );
    }else{
        setcookie('user_verify', 'active', time() + (60), "/");
        ?><script>alert('Your link has been expired... we have send new link please check your mail');</script><?php

        $subject = 'Please verify yor account';
        $html .= '<h1>Click for verify</h1>';
        $html .= '<a href="http://localhost/training/user_registration/user-login/?uID='.$_COOKIE['user_id'].'&ustatus=active">verify</a>';
  
        echo $mail = wp_mail( $_COOKIE['user_email'], $subject, $html );
        if(isset($mail)) {
            ?>
                <script>alert('Please check your mail');</script>
            <?php
        }
    }
}
*/


if(isset($_POST['user_email']) && isset($_POST['user_password'])) {

    $creds = $err = array();
    $user = get_user_by( 'email', $_POST['user_email'] );
                
    $user_verify = (isset($user) && !empty($user)) ? get_user_meta($user->data->ID,'user_verify', true) : '';
    $creds['user_login'] = $_POST['user_email'];
    $creds['user_password'] = $_POST['user_password'];
    $creds['remember'] = true;
    /*
    if(!empty($user_verify) && $user_verify == 'active') {
        $login_user = wp_signon( $creds, false );
    }else{
        ?>
            <script>
                alert('Please verify your account');
            </script>
        <?php
    }
    */

    // $user = wp_authenticate($_POST['user_email'], $_POST['user_password']);
    $username = $_POST['user_email'];
    $password = $_POST['user_password'];

    $username = sanitize_user($username);
    $password = trim($password);

    echo '<pre>'; print_r($password); echo '</pre>';
    $user = apply_filters('authenticate', null, $username, $password);
    
    if ( $user == null ) {
        $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
    } elseif ( get_user_meta( $user->ID, 'has_to_be_activated', true ) != false ) {
        $user = new WP_Error('activation_failed', __('<strong>ERROR</strong>: User is not activated.'));
    }

    $ignore_codes = array('empty_username', 'empty_password');

    if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
        do_action('wp_login_failed', $username);
    }
    
}

get_header();
if ( !is_user_logged_in() ) {
    ?>
        <form action="" method="POST">
            <label>User email</label>
            <input type="text" name="user_email">
            <br><br>
            <label>Password</label>
            <input type="password" name="user_password">
            <br><br>
            <input type="submit" name="login_form" value="Login">
            <a href="<?php echo wp_logout_url(get_permalink( get_page_by_path( 'user-register' ) )); ?>">Register</a>
        </form>
    <?php
}

get_footer();