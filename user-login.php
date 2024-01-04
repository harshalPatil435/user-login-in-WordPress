<?php
/** Template Name: User Login */

$diwali = strtotime("2022-07-09 12:00:00");
$current= strtotime('now');
echo $diffference =($diwali-    $current);


$subject = '';
$html = '';

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


if(isset($_POST['user_email']) && isset($_POST['user_password'])) {

    $creds = $err = array();
    $user = get_user_by( 'email', $_POST['user_email'] );
                
    $user_verify = (isset($user) && !empty($user)) ? get_user_meta($user->data->ID,'user_verify', true) : '';
    $creds['user_login'] = $_POST['user_email'];
    $creds['user_password'] = $_POST['user_password'];
    $creds['remember'] = true;
    
    if(!empty($user_verify) && $user_verify == 'active') {
        $login_user = wp_signon( $creds, false );
    }else{
        ?>
            <script>
                alert('Please verify your account');
            </script>
        <?php
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