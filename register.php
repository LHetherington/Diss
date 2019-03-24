<?php

include "inc/init.php";

if($user->islg()) { // if it's already logged in redirect to the main page
    header("Location: $set->url");
    exit;
}


$page->title = "Register to ". $set->site_name;

$name = $_POST['name'];
$display_name = $_POST['displayname'];
$email = $_POST['email'];
$password = $_POST['password'];


if($_POST && !isset($name[3]) || isset($name[30]))
    $page->error = "Username too short or too long !";

if($_POST && !$options->validUsername($name))
    $page->error = "Invalid username !";

if($_POST && !isset($display_name[3]) || isset($display_name[50]))
    $page->error = "Display name too short or too long !";

if($_POST && !isset($password[3]) || isset($password[30]))
    $page->error = "Password too short or too long !";

if($_POST && !$options->isValidMail($email))
    $page->error = "Email address is not valid.";

if($_POST && $db->getRow("SELECT `userid` FROM `".MLS_PREFIX."users` WHERE `username` = '$name'"))
    $page->error = "Username already in use !";

if($_POST && $db->getRow("SELECT `userid` FROM `".MLS_PREFIX."users` WHERE `email` = $email"))
    $page->error = "Email already in use !";

if(!isset($page->error)) {

    if ($_POST) {

        $username = $_POST["name"];
        $pw = sha1($password);
        $disname = $_POST["displayname"];
        $email = $_POST["email"];
        $time = time();

        $data = $db->getRow("SELECT * FROM `" . MLS_PREFIX . "users` LIMIT 1");
        $columns = get_object_vars($data);

        $sql = "INSERT INTO `" . MLS_PREFIX . "users` (`userid`, `username`, `display_name`, `password`, `email`, `key`, `validated`, `groupid`, `lastactive`, `showavt`, `banned`, `regtime`) VALUES (NULL, '$username', '$disname', '$pw', '$email', '0', 'Yes', '2', '$time', '1', '0', '$time')";
        $sql2 = "INSERT INTO `" . MLS_PREFIX . "privacy` (`userid`, `email`) VALUES (NULL, '0')";

        if ($db->query($sql) && $db->query($sql2)) {

        }else {
            $page->error = "An Error Occurred!";

        }


    }
}

include 'header.php';


if(!$set->register) // we check if the registration is enabled
    $options->fError("We are sorry registration is blocked momentarily please try again later !");


$_SESSION['token'] = sha1(rand()); // random token


$extra_content = ''; // holds success or error message

if(isset($page->error))
    $extra_content = $options->error($page->error);
else if(isset($page->success))
    $options->success($page->success);

if(isset($page->success)) {
    $user = new User($db);
    echo "<div class='container'>
    <div class='span3 hidden-phone'></div>
    <div class='span6 well'>
    <h1>Congratulations !</h1>";
    $options->success("<p><strong>Your account was successfully registered !</strong></p>");
    echo " <a class='btn btn-primary' href='/index.php'>Login!</a>
    </div>
  </div>";


} else {

    echo "
  <div class='container'>
    <div class='span3 hidden-phone'></div>
      <div class='span6'>

      ".$extra_content."

      <form action='#' id='contact-form' class='form-horizontal well' method='post'>
        <fieldset>
          <legend>Register Form </legend>

          <div class='control-group'>
            <label class='control-label' for='name'>Username</label>
            <div class='controls'>
              <input type='text' class='input-xlarge' name='name' id='name'>
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='displayname'>Display name</label>
            <div class='controls'>
              <input type='text' class='input-xlarge' name='displayname' id='displayname'>
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='email'>Email Address</label>
            <div class='controls'>
              <input type='text' class='input-xlarge' name='email' id='email'>
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='password'>Password</label>
            <div class='controls'>
              <input type='password' class='input-xlarge' name='password' id='password'>
            </div>
          </div>
          <div class='form-actions'>
          <button type='submit' class='btn btn-primary btn-large'>Register</button>
            <button type='reset' class='btn'>Reset</button>
          </div>
        </fieldset>
      </form>
    </div>


  </div>";
}





include "footer.php";

