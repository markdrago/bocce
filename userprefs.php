<?
/*
 * Copyright (C) 2006 Mark Drago
 *
 *This program is free software; you can redistribute it and/or modify
 *it under the terms of the GNU General Public License as published by
 *the Free Software Foundation; either version 2 of the License, or
 *(at your option) any later version.
 *
 *This program is distributed in the hope that it will be useful,
 *but WITHOUT ANY WARRANTY; without even the implied warranty of
 *MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *GNU General Public License for more details.
 *
 *You should have received a copy of the GNU General Public License
 *along with this program; if not, write to the Free Software
 *Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require ('start.php');

$notice = "";

side_panel($page, "LOGGED_IN");

$player_id = $_SESSION["player_id"];

$email = "";
$nick = "";
$oldpw = "";
$pass = "";
$vpass = "";

if (isset($_POST["submit"])) {
  $email = $_POST["email"];
  $oldpw = $_POST["oldpass"];
  $pass  = $_POST["pass"];
  $vpass = $_POST["vpass"];
  $nick  = $_POST["nick"];

  if ($notice == "") {
    $requiredarray = array("You must have an email address."=>$email,
			   "You must have a nickname."=>$nick);

    $notice = checkrequired($requiredarray);
  }

  if (($notice == "") and ($pass != "")) {
    $requiredarray = array("You must enter your current password " .
			   "to change your password."=>$oldpw,
			   "You must enter a verified password " .
			   "to change your password."=>$vpass);

    $notice = checkrequired($requiredarray);
  }
  
  if (($notice == "") and ($pass != "")) {
    $notice = checkmatching($pass, $vpass, "Your password and verify password must match.");
  }
  
  if (($notice == "") and ($pass != "")) {
    $lengtharray = array("Your password must be between 6 and " .
			 "100 characters in length."=>array($pass,6,100));

    $notice = checklength($lengtharray);
  }

  if ($notice == "") {
    $lengtharray = array("Your nickname must be between 1 and " .
			 "100 characters in length."=>array($nick,1,100));

    $notice = checklength($lengtharray);
  }

  if ($notice == "") {
    db_open();
    
    $query = "select count(*) from player where email='$email' and " .
      "id != $player_id";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $count = $row[0];
    if ($count > 0) {
      $notice = "An account with this email address already exists";
      $uname = "";
    }
    db_close();

  }

  #if we got here, update this user's preferences
  if ($notice == "") {
    db_open();

    $query = "update player set email='$email', nickname='$nick'";

    if ($pass != "") {
      #if the password was updated, change it
      $sha1pass = sha1($pass);
      $query .= ", pass='$sha1pass'";
    }

    db_query($query);
    db_close();

    $notice = "Your account has been updated";
  }
} else {
  db_open();
  $query = "select email, nickname from player where id=$player_id";
  $result = db_query($query);
  $row = db_fetch_array($result);
  $email = $row[0];
  $nick = $row[1];
  db_close();
}

$page->assign('email', $email);
$page->assign('nick', $nick);
$page->assign('notice', $notice);
$page->assign('subtitle', "User Preferences");

$page->display('userprefs.tpl');

?>
