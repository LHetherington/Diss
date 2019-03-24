<?php

include 'inc/init.php';


if (!isset($_GET["u"]) || !($u = $db->getRow("SELECT * FROM `" . MLS_PREFIX . "users` WHERE `userid`= ?i", $_GET["u"]))) {
    $page->error = "User doesn't exists or it was deleted !";
    $u = new stdClass();
    $u->username = 'Guest';
}

$page->title = "Profile of " . $options->html($u->username);


include 'header.php';


if (isset($page->error))
    $options->fError($page->error);


$show_actions = ''; // Holds actions.


if ($user->group->canban && $user->hasPrivilege($u->userid) && ($user->data->userid != $u->userid))
    $show_actions .= "<li><a href='$set->url/mod.php?act=ban&id=$u->userid'><i class='icon-ban-circle'></i> " . ($u->banned ? "Un" : "") . "Ban " . $options->html($u->username) . "</a></li>";


if ($user->group->canhideavt && $user->hasPrivilege($u->userid))
    $show_actions .= "<li><a href='$set->url/mod.php?act=avt&id=$u->userid'><i class='icon-eye-close'></i> " . ($u->showavt ? "Hide" : "Show") . " avatar</a></li>";


if (($user->data->userid == $u->userid) || ($user->group->canedit && $user->hasPrivilege($u->userid)))
    $show_actions .= "<li><a href='$set->url/user.php?id=$u->userid'><i class='icon-pencil'></i> Edit profile</a></li>";


if ($user->isAdmin() && $user->data->userid != $u->userid)
    $show_actions .= "<li><a href='$set->url/mod.php?act=del&id=$u->userid'><i class='icon-trash'></i> Delete " . $options->html($u->username) . "</li>";


$tooltip = ''; // holds the tooltip data

if ($user->data->userid == $u->userid) {
    $tooltip = " rel='tooltip' title='change avatar'";
}


// show data based on privacy
$extra_details = '';


$privacy = $db->getRow("SELECT * FROM `" . MLS_PREFIX . "privacy` WHERE `userid` = ?i", $u->userid);
$group = $db->getRow("SELECT * FROM `" . MLS_PREFIX . "groups` WHERE `groupid` = ?i", $u->groupid);

if ($privacy->email == 1 || $user->isAdmin())
    $extra_details .= "<b>Email:</b> " . $options->html($u->email) . "<br/>";

if (isset($_POST['deleteButton'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql2 = "SELECT * FROM `mls_files` WHERE userid = $id";
    $result = $db->getAll($sql2);
    $fileDestination = 'uploads/' . $name;
    if (unlink($fileDestination)) {
        $delsql = "DELETE FROM `" . MLS_PREFIX . "files` WHERE `" . MLS_PREFIX . "files`. `id` = $id";
        if ($db->query($delsql)) {
            $page->success = "Successfully Deleted File!";
        } else {
            $page->error = "An Error Has Occurred, Please Try Again!";
        }
    } else {
        $page->error = "An Error Has Occurred, Please Try Again!";

    }
}

if (isset($_POST['shareButton'])) {
    $shareids = $_POST['shareids'];
    $id = $_POST['id'];
    $sql3 = "UPDATE `" . MLS_PREFIX . "files` SET `shareids`='$shareids' WHERE id = '$id'";
    if ($db->query($sql3)) {
        $page->success = "Successfully Shared " . $id . " With Users " . $shareids;
    } else {
        $page->error = "An Error Has Occurred, Please Try Again!";
    }
}

if (isset($_POST['uploadButton'])) {
    $file = $_FILES['file'];
    $uid = $user->data->userid;
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));


    $allowed = array('JPG', 'jpg', 'JPEG', 'jpeg', 'PNG', 'png', 'PDF', 'pdf', 'pptx', 'PPTX', 'DOCX', 'docx', 'xlsx', 'XLSX');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 50000000) {
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = 'uploads/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                $userid = $options->html($u->userid);
                $sql1 = "INSERT INTO `" . MLS_PREFIX . "files` (`id`, `userid`, `name`, `originalname`, `type`, `size`, `shareids`) VALUES (NULL, '$userid', '$fileNameNew','$fileName', '$fileType', '$fileSize', '')";
                if ($db->query($sql1)) {
                    $page->success = "File Uploaded";
                } else {
                    $page->error = "Database Error" . "<br>$fileNameNew<br>$fileType<br>$userid";
                }
            } else {
                $page->error = "Your file is too big!";
            }
        } else {
            $page->error = "There was an error uploading, please try again!";
        }
    } else {
        $page->error = "You cannot upload files of this type.";
    }

}

if (isset($page->error))
    $options->error($page->error);
else if (isset($page->success))
    $options->success($page->success);

echo "<div class='container'>
	<h3 class='pull-left'>Profile of " . $options->html($u->username) . "</h3>";

if ($show_actions != '')
    echo "<div class='btn-group pull-right'>
  		<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
    		Actions
    		<span class='caret'></span>
  		</a>
  		<ul class='dropdown-menu'>

  			$show_actions
  		
  		</ul>
  	</div>";

echo "  	
  	<div class='clearfix'></div>
	<hr>
	<div class='row'>	

		<div class='span3'>
			<a href='http://gravatar.com'$tooltip><small>Change Profile Picture</small><br/>
				<img src='" . $user->getAvatar($u->userid, 240) . "' width='240' class='img-polaroid' alt='" . $options->html($u->username) . "'>
			</a>
			<div style='text-align:center;'><b>" . $user->showName($u->userid) . " (" . $options->html($u->username) . ") </b></div>
		</div>
		<div class='span6 well' style='margin:10px;'> 
			<b>Rank:</b> " . $options->html($group->name) . "<br/>
			<b>Last seen:</b> " . $options->tsince($u->lastactive) . "<br/>
                $extra_details
                <br/>
            </div>
           <div class='visible-desktop visible-phone'>
                <div class='float-right span well'>
                    <ul class='nav nav-list' >
                      <li class='nav-header'>Profile Options</li>
                      <li><a href='profile.php?u=" . $user->data->userid . "'><i class='icon-user'></i> My Profile</a></li>
                      <li><a href='user.php'><i class='icon-cog'></i>Account Settings</a></li>
                      <li><a href='news.php'><i class='icon-comment'></i>News</a></li>
                      <li><a href='messages.php'><i class='icon-inbox'></i>Messages</a></li>
                      <li><a href='privacy.php'><i class='icon-lock'></i>Privacy Settings</a></li>
                      <li><a href='logout.php'><i class='icon-off'></i>Log Out</a></li>
                    </ul>
                </div><br/>
             </div>";

if ($options->html($u->userid) == $user->data->userid || $user->isAdmin()) {
    echo "<div class='span4 well' style='margin-left:10%; padding: 20px'> 
			<h4><u><b>Upload Files:</b></u></h4>
			<div style='border: dashed; border-color: #515151' >
			 <form  class='offset1'enctype='multipart/form-data' action='' method='POST'>
			 <br/> 
                <span>Click to upload file:</span>
                <br/><br/>
                <span class='control-fileupload'>
                <input type='file' name='file' id='fileInput'>
                <br/><br/>
                <button class='btn btn-success' type='submit' name='uploadButton'><span class='icon-white icon-upload'></span>&nbsp;Upload</button>
              </span>
              </form></div></div><br/>";


    $user_id = $options->html($u->userid);
    $sql2 = "SELECT * FROM `mls_files` WHERE userid = $user_id";
    $result = $db->getAll($sql2);
    $i = 0;
    $count = 1;
    echo "  
            <div style='position: relative;'>
            <div class='span10 well' style='margin-left: 10%;'>
            <legend>Stored Files:</legend>

          ";
    while ($columns = get_object_vars($result[$i])) {
        $fileDestination = 'uploads/' . $columns['name'];
        $options->
        $array_from_db = $columns['shareids'];
        echo "
              <p><b>ID: </b><span class='badge badge-inverse'>" . $columns['id'] . "</span></p><br/>
              <p><b>Unique File Name: </b>" . $columns['name'] . "</p><br/>
              <p><b>File Name: </b>" . $columns['originalname'] . "</p><br/>
              <p><b>Size: </b>" . $options->formatSizeUnits($columns['size']) . "</p><br/>
              <p><b>Type: </b>" . $columns['type'] . "</p><br/>
              <div class='visible-desktop' style='position: absolute; right: 20%; margin-top: -21%;'>
              <p><b>Actions: </b></p>
              <a class='btn btn-success' target='_blank' href='$fileDestination'>View</a><br/><br/>
                  <form class='' method='post' action='#'>
                        <textarea name='id' rows='1' cols='1' class='input-large' style='display: none'>" . $columns['id'] . "</textarea>
                        <textarea name='name' rows='1' cols='1' class='input-large' style='display: none'>" . $columns['name'] . "</textarea>
                        <button name='deleteButton' type='submit' class='btn btn-danger'>Delete</button><br/>
                      </form>
              
              <a class='btn btn-primary' href='javascript:showhide(" . $columns['id'] . ");'>Share</a>
                  
                      <form id='" . $columns['id'] . "' style='display: none;' method='post' action='#'>
                      <br/><br/>
                        <p>Enter the Username of the user you would like to add.<br/> Separate each user with a comma ','</p>
                        <input name='id' value='" . $columns['originalname'] . "'>
                        <textarea name='shareids' rows='1' cols='1'>" . $columns['shareids'] . "</textarea>
                        <button type='submit' class='btn btn-mini btn-danger' name='shareButton'>Submit</button>
                      </form><br/><br/><br/><br/><br/><br/></div>
              <div class='hidden-desktop'>
              <p><b>Actions: </b></p>
              <a class='btn btn-success' target='_blank' href='$fileDestination'>View</a><br/><br/>
                  <form class='' method='post' action='#'>
                        <textarea name='id' rows='1' cols='1' class='input-large' style='display: none'>" . $columns['id'] . "</textarea>
                        <textarea name='name' rows='1' cols='1' class='input-large' style='display: none'>" . $columns['name'] . "</textarea>
                        <button name='deleteButton' type='submit' class='btn btn-danger'>Delete</button><br/>
                      </form>
              
              <a class='btn btn-primary' href='javascript:showhide(" . $columns['id'] . ");'>Share</a>
                  
                      <form id='" . $columns['id'] . "' style='display: none;' method='post' action='#'>
                      <br/><br/>
                        <p>Enter the Username of the user you would like to add.<br/> Separate each user with a comma ','</p>
                        <input name='id' value='" . $columns['originalname'] . "'>
                        <textarea name='shareids' rows='1' cols='1'>" . $columns['shareids'] . "</textarea>
                        <button type='submit' class='btn btn-mini btn-danger' name='shareButton'>Submit</button>
                      </form></div>
                      <hr>
            ";
        $i++;
        $count++;


    }
    echo "</div></div><br/>";

    $user_id = $options->html($u->userid);
    $username = $options->html($u->username);
    if ($user->isAdmin()) {
        $sql3 = "SELECT * FROM `mls_files`";
    } else {
        $sql3 = "SELECT * FROM `mls_files` WHERE shareids LIKE '%$username%'";
    }
    $result = $db->getAll($sql3);
    $i = 0;
    $count = 1;
    echo "  
            <div style='position: relative;'>
            <div class='span10 well' style='margin-left: 10%;'>
            <legend>Stored Files:</legend>

          ";
    while ($columns = get_object_vars($result[$i])) {
        $fileDestination = 'uploads/' . $columns['name'];
        $array_from_db = $columns['shareids'];
        echo "
              <p><b>ID: </b><span class='badge badge-inverse'>" . $columns['id'] . "</span></p><br/>
              <p><b>Unique File Name: </b>" . $columns['name'] . "</p><br/>
              <p><b>File Name: </b>" . $columns['originalname'] . "</p><br/>
              <p><b>Size: </b>" . $options->formatSizeUnits($columns['size']) . "</p><br/>
              <p><b>Type: </b>" . $columns['type'] . "</p><br/>
              <div class='visible-desktop' style='position: absolute; right: 20%; margin-top: -21%;'>
              <p><b>Actions: </b></p>
              <a class='btn btn-success' target='_blank' href='$fileDestination'>View</a><br/><br/>
               </div>
              <div class='hidden-desktop'>
              <p><b>Actions: </b></p>
              <a class='btn btn-success' target='_blank' href='$fileDestination'>View</a><br/><br/>
               </div><hr>";
        $i++;
        $count++;


    }


}
echo "</div></div>";

include 'footer.php';
