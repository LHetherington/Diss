<?php

// Navbar components generated
if($page->navbar == array())
    $page->navbar = $presets->GenerateNavbar();

if(!$user->islg()) // Hide login if user is logged in
    unset($page->navbar[count($page->navbar)-1]);


?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $page->title; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="<?php echo $set->url; ?>/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <link rel="stylesheet" href="<?php echo $set->url; ?>/css/main.css">
        <link rel="stylesheet" href="<?php echo $set->url; ?>/css/bootstrap-responsive.min.css">




        <link rel="icon" href="favicons/favicon-32x32.png" type="image/png" sizes="32x32">
        <link rel="icon" href="favicons/favicon-96x96.png" type="image/png" sizes="96x96">

        <link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png">
        <script src="<?php echo $set->url; ?>/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <script src='<?php echo $set->url; ?>/js/main.js'></script>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo $set->url; ?>"><?php echo $set->site_name; ?></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-left">
<?php
// Generate a simple menu.
foreach ($page->navbar as $key => $v) {

    if ($v[0] == 'item') {
    
        echo "<li".($v[1]['class'] ? " class='".$v[1]['class']."'" : "").">
            <a href='".$v[1]['href']."'>".$v[1]['name']."</a></li>";
    
    } else if($v[0] == 'dropdown') {

        echo "<li class='dropdown".
            // extra classes 
            ($v['class'] ? " ".$v['class'] : "")."'".
            // extra style
            ($v['style'] ? " style='".$v['style']."'" : "").">
            
            <a href='#' class='dropdown-toggle' data-toggle='dropdown'>".$v['name']." <b class='caret'></b></a>
            <ul class='dropdown-menu'>";
        foreach ($v[1] as $k => $v) 
            echo "<li".
                
                ($v['class'] ? " class='".$v['class']."'" : "").">

                <a href='".$v['href']."'>".$v['name']."</a></li>";                                
        echo "</ul></li>";

    }
    
}

echo "</ul>";

if(!$user->islg()) { 

echo "<span class='pull-right'>
        <a href='$set->url/register.php' class='btn btn-primary btn-small'>Sign Up</a>
        <a href='$set->url/login.php' class='btn btn-small'>Login</a>
    </span>
    ";
}

echo "

            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>";




if($user->data->banned) {
  
    // Delete expired bans
    $_unban = $db->getAll("SELECT `userid` FROM `".MLS_PREFIX."banned` WHERE `until` < ".time());
    if($_unban) 
        foreach ($_unban as $_usr) {
            $db->query("DELETE FROM `".MLS_PREFIX."banned` WHERE `userid` = ?i", $_usr->userid);
            $db->query("UPDATE `".MLS_PREFIX."users` SET `banned` = '0' WHERE `userid` = ?i", $_usr->userid);             
        }


    $_banned = $user->getBan();
    if($_banned)
    $options->error("You were banned by <a href='$set->url/profile.php?u=$_banned->by'>".$user->showName($_banned->by)."</a> for `<i>".$options->html($_banned->reason)."</i>`.
        Your ban will expire in ".$options->tsince($_banned->until, "from now.")."
        ");
    exit();


    


}



if($user->islg() && $set->email_validation && ($user->data->validated != 1)) {
    $options->fError("Your account is not yet acctivated ! Please check your email !");
}

if(file_exists('install.php')) {
    $options->fError("You have to delete the install.php file before you start using this app.");
}




if(isset($_SESSION['success'])){
    $options->success($_SESSION['success']);
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])){
    $options->error($_SESSION['error']);
    unset($_SESSION['error']);

}
flush();
