<?php
/**
 * Presets class
 * This Script Generates certain parts of the website.
 */
 
class presets {
  
  var $active = '';


  function GenerateNavbar() {
      global $set, $user;
      $var = array();
      $var[] = array("item" ,
                      array("href" => $set->url,
                            "name" => "Home",
                            "class" => $this->isActive("home")),
                      "id" => "home");

      if($user->group->type != 0)
          $var[] = array("item",
                      array("href" => $set->url."/users_list.php",
                            "name" => "User List",
                            "class" => $this->isActive("userslist")),
                      "id" => "userslist");

      $var[] = array("item",
                      array("href" => $set->url."/contact.php",
                            "name" => "Contact",
                            "class" => $this->isActive("contact")),
                      "id" => "contact");

      if($user->group->type == 3) // Admin only
      $var[] = array("item",
                      array("href" => $set->url."/admin",
                            "name" => "Admin Panel",
                            "class" => $this->isActive("adminpanel")),
                      "id" => "adminpanel");



      // Keeps Profile Last Item In Bar
      $var[] = array("dropdown",
                      array(  array("href" => $set->url."/profile.php?u=".$user->data->userid,
                                       "name" => "<i class='icon-user'></i> My Profile",
                                       "class" => 0),
                              array("href" => $set->url."/messages.php",
                                  "name" => "<i class='icon-inbox'></i> Messages",
                                  "class" => 0),
                              array("href" => $set->url."/news.php",
                                  "name" => "<i class='icon-comment'></i> My News",
                                  "class" => 0),
                              array("href" => $set->url."/privacy.php",
                                  "name" => "<i class='icon-lock'></i> Privacy settings",
                                  "class" => 0),
                              array("href" => $set->url."/user.php",
                                  "name" => "<i class='icon-cog'></i> Account settings",
                                  "class" => 0),
                              array("href" => $set->url."/logout.php",
                                  "name" => "<i class='icon-off'></i> LogOut",
                                  "class" => 0),
                          ),
                      "class" => 0,
                      "style" => 0,
                      "name" => "<i class='icon-white icon-user'></i> My Profile",
                      "id" => "user");



          

      return $var;
  }

  function setActive($id) {
    $this->active = $id;
  }

  function isActive($id) {
    if($id == $this->active)
      return "active";
    return 0;
  }

}
