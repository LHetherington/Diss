<?php



include "inc/init.php";



$page->title = "News". $set->site_name;


$presets->setActive("News");
$uid = ($user->islg() ? $user->filter->userid : "");


if(isset($_POST['newsSubmit'])) {


    $name = $_POST['display_name'];
    $userid = $_POST['userid'];
    $comment = $_POST['message'];
    $time = time();
    $comment_length = strlen($comment);
    if ($comment_length > 500){
        header("Location: News.php?error=1");
    } else {
        $sql1 = "INSERT INTO `" . MLS_PREFIX . "news` (`id`, `userid`, `name`, `comment`, `time`) VALUES (NULL, '$userid', '$name', '$comment', '$time')";
            if($db->query($sql1)){
                $page->success = "News submitted !";
            }else {
                $page->error = "An error came up !";

            }
    }

}
if (isset($_POST['btnEdit'])){
    $time2 = time();
    $id2 = $_POST['postid1'];
    $comment2 = $_POST['comment1'];
    $sql2 = "UPDATE `" . MLS_PREFIX . "news` SET `comment`='$comment2',`time`='$time2' WHERE id = '$id2'";

    if($db->query($sql2)){
        $page->success = "Edited Successfully !";
    }else {
        $page->error = "An Edit error came up !";

    }
}

if (isset($_POST['btnDelete'])){
    $postId = $_POST['postId'];
    $sql3 = "DELETE FROM `" . MLS_PREFIX . "news` WHERE `" . MLS_PREFIX . "news`. `id` = $postId";
    if($db->query($sql3)){
        $page->success = "Deleted Post !";
    }else {
        $page->error = "A Deletion Error Came Up !";

    }
}

include 'header.php';

$_SESSION['token'] = sha1(rand()); // random token

echo "
<script language='javascript'>
function showhide(id) {
    var e = document.getElementById(id);
    e.style.display = (e.style.display == 'block') ? 'none' : 'block';
}
</script>
<div class='container'>
    <div class='span3 hidden-phone'></div>
	<div class='span6'>	";


if(isset($page->error))
  $options->error($page->error);
else if(isset($page->success))
  $options->success($page->success);

    if ($user->group->canban) {
        echo " 
            <form class='form-horizontal well' action='' method='POST'>
		        <fieldset>
		            <div class='control-group'>
		            <legend>Post News:</legend>
		            <div hidden>
		                <input type='text' name='display_name' class='input-large' value='" . ($user->islg() ? $user->filter->display_name : "") . "'>
		                <input type='text' name='userid' class='input-large' value='" . ($user->islg() ? $user->filter->userid : "") . "'> 
		            </div>
                    </div>
		            <div class='control-group'>
		              <div class='control-label'>
		                <label>News</label>
		              </div>
		              <div class='controls'>
		                <textarea name='message' rows='3' class='input-large'></textarea>
		              </div>
		            </div>

           			<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>

		            <div class='control-group'>
		              <div class='controls'>
		              <button type='submit' name='newsSubmit' class='btn btn-primary'>Post News</button>
		              </div>
		            </div>
		          </fieldset>
		    </form>
		    
   
		    </div>";
            }
echo "</div><!-- /container -->";

echo "

                      
    <br>
    <div class='container' style='margin-left: 10%'>
    <div class='span10 well' style='...'>
    <legend>The News!</legend>";

        if (isset($uid)){
            $sql = "SELECT * FROM `" . MLS_PREFIX . "news` ORDER BY `" . MLS_PREFIX . "news`.`time` DESC";
            $result = $db->getAll($sql);
            $i = 0;
            while($columns = get_object_vars($result[$i])){
                echo "
                
                <div class='row' style='margin-left: 1px'>
                  <div class='span9 well commentBox'>
                    <b><u>".$columns['name']."<u></b>
                    <div class='row'>
                    <br>
                      <div class='span7'>".nl2br($columns['comment'])."
                      <br><br>
                      </div>
                      </div>
                      <div style='margin-left: 85%'>
                      <b><u>".$options->tsince($columns['time'])."<u></b>
                      </div>";

                  if ($uid == $columns['userid'] ){
                   echo  "
                      


                      <div class='btn-group editButton'>
                      <a class='btn btn-info' style='width: 70%' href='javascript:showhide(".$columns['id'].");'>
                      <i class='icon-edit'></i>
                        Edit
                      </a>
                      
                      <br/><br/>
                      <div style='display: none;'>
                      <form class='' method='post' action='#'>
                        <input name='postId' value='".$columns['id']."'>
                      </div>
                      <button type='submit' class='btn btn-danger' name='btnDelete' style='width: 100%'>
                      <i class='icon-trash'></i>
                        Delete
                      </button>
                      </form>
                      </div>
                      ";
                  }
                echo  " 
                  </div>
                        <div  style='display:none;margin-left:35%;' id='".$columns['id']."'>
                        <form class='' method='post' action='#'>
                        <input type='hidden' name='postid1' value='".$columns['id']."'>
                        <input type='hidden' name='userid1' value='".$columns['userid']."'>
                        <h3>Edit Post:</h3>
                        <textarea name='comment1' rows='3' class='input-large'>".$columns['comment']."</textarea>
                        <button type='submit' class='btn btn-danger' name='btnEdit'>Submit</button>
                        </form>
                      </div>
                      
                </div>
                
                ";
                $i++;
            }

        } else {
            $page->error = "Error Collecting News";

        }

echo "</div></div>";

include 'footer.php';