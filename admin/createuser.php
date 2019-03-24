<?php


include "../inc/init.php";

if(!$user->isAdmin()) {
    header("Location: $set->url");
    exit;
}


$page->title = "Admin Panel";

$presets->setActive("adminpanel");


if($_POST) {

    $username = $_POST["username"];
    $pw = $_POST["password"];
    $password = sha1($pw);
    $disname = $_POST["display_name"];
    $email = $_POST["email"];
    $group = $_POST["groupid"];
    $time = time();

  $data = $db->getRow("SELECT * FROM `".MLS_PREFIX."users` LIMIT 1");
  $columns = get_object_vars($data);
  
  $sql = "INSERT INTO `".MLS_PREFIX."users` (`userid`, `username`, `display_name`, `password`, `email`, `key`, `validated`, `groupid`, `lastactive`, `showavt`, `banned`, `regtime`) VALUES (NULL, '$username', '$disname', '$password', '$email', '', 'Yes', '$group', '$time', '1', '0', '$time')";
  $sql2 = "INSERT INTO `" . MLS_PREFIX . "privacy` (`userid`, `email`) VALUES (NULL, '0')";

  if($db->query($sql) && $db->query($sql2))
    $page->success = "Settings saved !";
  else
    $page->error = "Some error came up !";

}

// Grabs the settings and merges them into the variable $set
$set = (object)array_merge((array)$set,(array)$db->getRow("SELECT * FROM `".MLS_PREFIX."users` LIMIT 1"));


include "../header.php";

?>
<div class="container-fluid">
<div class="row-fluid">
 <div class="span3">
   <div class="well sidebar-nav sidebar-nav-fixed">
    <ul class="nav nav-list">
      <li class="nav-header">ADMIN OPTIONS</li>
      <li><a href='index.php'>General Settings</a></li>
      <li><a href='groups.php'>Groups Management</a></li>
      <li class='active'><a href='createuser.php'>Create User</a></li>
    </ul>
   </div><!--/.well -->
 </div><!--/span-->
 <div class="span9">
<?php


// make sure we get the latest data
$data = $db->getRow("SELECT * FROM `".MLS_PREFIX."users` LIMIT 1");

$columns = get_object_vars($data);



if(isset($page->error))
  $options->error($page->error);
else if(isset($page->success))
  $options->success($page->success);



echo "
  <form class='form-horizontal' action='#' method='post'>
      <fieldset>

      <legend>Create User</legend>";

$limit = 0;
foreach ($columns as $key => $value) {
    $safe_name = $options->html($key);
    $safe_val = $options->html($value);
    if($safe_name == "userid" ||$safe_name == "key" || $safe_name == "validated"){
        next();
    }else{


    if ($limit == 4) {
        echo "
          <div class='control-group'>
            <label class='control-label' for='$safe_name'>".$options->prettyPrint(str_ireplace("can", "can ", $safe_name)).":</label>
            <div class='controls'>
              <select id='$safe_name' name='$safe_name' class='input-xlarge'>
                <option value='2' ".($edit && ($group->$k == 2 ) ? "selected='1'" : "").">User</option>
                <option value='5' ".($edit && ($group->$k == 5) ? "selected='1'" : "").">Corporate</option>
                <option value='3' ".($edit && ($group->$k == 3) ? "selected='1'" : "").">Moderator</option>
                <option value='4' ".($edit && ($group->$k == 4) ? "selected='1'" : "").">Admin</option>
              </select>
            </div>
          </div>";
            break;
    } else {

        if (strpos($value, "\n") !== FALSE)
            echo "
          <div class='control-group'>
            <label class='control-label' for='$safe_name'>".$options->prettyPrint(str_ireplace("can", "can ", $safe_name))."</label>
          </div>";
        else
            echo "
      <div class='control-group'>
        <label class='control-label' for='$safe_name'>" . $options->prettyPrint($safe_name) . ":</label>
          <div class='controls'>
          <input id='$safe_name' name='$safe_name' type='text' placeholder='$safe_name' class='input-xlarge'>
        </div>
      </div>";

        $limit++;
    }
    }
}


echo "  <div class='control-group'>
        <div class='controls'>
          <button class='btn btn-primary'>Save</button>
        </div>
      </div>

      </fieldset>
  </form>";

?>


 </div><!--/span-->
</div><!--/row-->

</div><!--/.fluid-container-->



<?php
include '../footer.php';
?>
