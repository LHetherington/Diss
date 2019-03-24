<?php

include "inc/init.php";



$page->title = "Welcome to ". $set->site_name;

$presets->setActive("home");


include 'header.php';


    echo "
<div class='container'>

<div class='hero-unit'>
<div class=''>
    <img class='pull-right' src='img/logo.png' class='img-rounded' style=' margin-top:-50px; height:200px; width:200px;''>
    </div>
    <h1>Welcome " . ucfirst($user->filter->username) . " </h1>
    <br/><br/>
    <p>Welcome to Luke's Cloud Document Repository, Once Registered/Signed in you can view User lists, Personal profile, News ,Upload and View Files.</p>
    <br/>
    <p>Files are all securely stored to the data server and database! </p>
    <br/>
";
    if (!$user->islg()) {
        echo "<br><p>
        <a class='btn btn-primary btn-large' href='$set->url/register.php'>Sign Up</a>
        <a class='btn btn-large' href='$set->url/login.php'>Login</a>
    </p>";

    }

    echo "</div></div> <!-- /container -->";

    if ($user->islg()) {
        $useriden = $user->filter->userid;
        echo "<div class='container marketing' style='margin-left: 10%;'>
      <!-- Three columns of text below the carousel -->
        <div class='row'>
        <div class='span4'>
          <img class='img-circle' src='img/passport.png' height='200px' width='200px'>
          <h2>Upload Identification</h2>
          <p>Upload Identification so you are always protected</p>
          <p><a class='btn btn-primary' href='profile.php?u=$useriden'> Go There!  &raquo;</a></p>
        </div><!-- /.span4 -->
        <div class='span4'>
          <img class='img-circle' src='img/document.png' height='200px' width='200px'>
          <h2>Upload Documents</h2>
          <p>You can upload any office document or PDF</p>
          <p><a class='btn btn-primary' href='profile.php?u=$useriden'>Go There!  &raquo;</a></p>
        </div><!-- /.span4 -->
        <div class='span4'>
          <img class='img-circle' src='img/image.png' height='200px' width='200px'>
          <h2>Upload Images</h2>
          <p>You can upload images of any type!</p>
          <p><a class='btn btn-primary' href='profile.php?u=$useriden'>Go There!  &raquo;</a></p>
        </div><!-- /.span4 -->
      </div><!-- /.row -->
      <div class='row' style='margin-left: 5%;'>
        <div class='span5'>
          <img class='img-circle' src='img/web.png' height='150px' width='150px'>
          <h2>Upload Web Documents</h2>
          <p>Upload any type of web document!</p>
          <p><a class='btn btn-primary' href='profile.php?u=$useriden'> Go There!  &raquo;</a></p>
        </div><!-- /.span4 -->
        <div class='span5'>
          <img class='img-circle' src='img/sourcecode.png' height='150px' width='150px'>
          <h2>Upload Source Code</h2>
          <p>Easily share source code with others!</p>
          <p><a class='btn btn-primary' href='profile.php?u=$useriden'>Go There!  &raquo;</a></p>
        </div><!-- /.span4 -->
      </div><!-- /.row --></div>";
    }
    include 'footer.php';
