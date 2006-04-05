<?
/*
 * Copyright (C) 2004-2006 Mark Drago
 *           (C) 2005 Josef "Jeff" Sipek <jeffpc@optonline.net>
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

side_panel($page, "NOT_LOGGED_IN");

$email = "";
$pass = "";
$vpass = "";
$nick = "";

if (isset($_POST["submit"])) {
  $email = $_POST["email"];
  $pass  = $_POST["pass"];
  $vpass = $_POST["vpass"];
  $nick  = $_POST["nick"];

  if ($notice == "") {
    $requiredarray = array("You must enter an email address."=>$email,
			   "You must enter a password."=>$pass,
			   "You must enter a nickname."=>$nick);

    $notice = checkrequired($requiredarray);
  }
  
  if ($notice == "") {
    $notice = checkmatching($pass, $vpass, "Your password and verify password must match.");
  }
  
  if ($notice == "") {
    $lengtharray =array("Your password must be between " .
			"6 and 100 characters in length."=>array($pass,6,100),
			"Your nickname must be between " .
			"1 and 100 characters in length."=>array($pass,1,100));

    $notice = checklength($lengtharray);
  }

  if ($notice == "") {
    db_open();
    
    $query = "select count(*) from player where email='$email'";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $count = $row[0];
    if ($count > 0) {
      $notice = "An account with this email address already exists";
      $uname = "";
    }
    db_close();

  }

  if ($notice == "") {
    db_open();
    $sha1pass = sha1($pass);
    $query = "insert into player (email, pass, nickname) values " .
      "('$email','$sha1pass', '$nick')";
    db_query($query);

    #get player id of the player that we just created
    $query = "select id from player where email='$email'";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $player_id = $row[0];
    db_close();

    $_SESSION['player_id'] = $player_id;

    #redirect to userprefs for now
    redirect('userprefs.php');

  }
}

$page->assign('email', $email);
$page->assign('nick', $nick);

$page->assign('notice', $notice);
$page->assign('subtitle', "Create a Bocce Account");

$page->display('createaccount.tpl');

?>
