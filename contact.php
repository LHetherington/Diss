<?php



include "inc/init.php";



$page->title = "Contact to ". $set->site_name;


$presets->setActive("contact");


if($_POST && isset($_SESSION['token']) && ($_SESSION['token'] == $_POST['token'])) {

	  $to = "lh.football@hotmail.co.uk";
	  $message = $_POST['message']." From: ".$_POST['email'];
	  $message .= '<br/><br/>Sent From '.$_POST['email'].' Using Lukes Cloud Document Repo';
	  $subject = "Contact Form - $set->site_name !";

	  if($options->isValidMail($email))
	    $page->error = "Email address is not valid.";
	  else if(!isset($message[1]))
	    $page->error = "Message was too short !";
	  else {
          $header = "From:no-reply@LCDR.com \r\n";
          $header .= "MIME-Version: 1.0\r\n";
          $header .= "Content-type: text/html\r\n";

          $retval = mail($to,$subject,$message,$header);

          if( $retval == true ) {
              $page->success = "Message sent successfully...";
          }else {
              $page->error = "Message could not be sent...";
          }

	  }
} else if($_POST)
    $page->error = "Invalid request !";


include 'header.php';

$_SESSION['token'] = sha1(rand()); // random token

echo "<div class='container'>
    <div class='span3 hidden-phone'></div>
	<div class='span6'>	";


if(isset($page->error))
  $options->error($page->error);
else if(isset($page->success))
  $options->success($page->success);

	echo "<form class='form-horizontal well' action='#' method='post'>
		        <fieldset>
		            <legend>Contact Admin</legend>

		            <div class='control-group'>
		              <div class='control-label'>
		                <label>Your Email</label>
		              </div>
		              <div class='controls'>
		                <input type='text' name='email' class='input-large' value='".($user->islg() ? $user->filter->email : "")."'>
		              </div>
		            </div>

		            <div class='control-group'>
		              <div class='control-label'>
		                <label>Message</label>
		              </div>
		              <div class='controls'>
		                <textarea name='message' rows='5' class='input-large'></textarea>
		              </div>
		            </div>

           			<input type='hidden' name='token' value='".$_SESSION['token']."'>

		            <div class='control-group'>
		              <div class='controls'>
		              <button type='submit' id='submit' class='btn btn-primary'>Send</button>
		              </div>
		            </div>
		          </fieldset>
		    </form>
		    
		    </div>
	</div><!-- /container -->";

include 'footer.php';