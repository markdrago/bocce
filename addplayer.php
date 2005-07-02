<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
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

$uname = "";
$fname = "";
$lname = "";
$email = "";
$pass = "";
$vpass = "";

if (isset($_POST["submit"])) {
  $uname = $_POST["uname"];
  $fname = $_POST["fname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];
  $pass  = $_POST["pass"];
  $vpass = $_POST["vpass"];

  if ($notice == "") {
    $requiredarray = array("You must enter a user name."=>$uname,
			   "You must enter a first name."=>$fname,
			   "You must enter a last name."=>$lname,
			   "You must enter an email address."=>$email,
			   "You must enter a password."=>$pass);

    $notice = checkrequired($requiredarray);
  }
  
  if ($notice == "") {
    $notice = checkmatching($pass, $vpass, "Your password and verify password must match.");
  }
  
  if ($notice == "") {
    $lengtharray = array("Your user name must be between " .
			 "3 and 16 characters in length."=>array($uname,3,16),
			 "Your first name must be between ".
			 "2 and 25 characters in length."=>array($fname,2,25),
			 "Your last name must be between " .
			 "2 and 25 characters in length."=>array($lname,2,25),
			 "Your password must be between " .
			 "6 and 25 characters in length."=>array($pass,6,25));

    $notice = checklength($lengtharray);
  }

  if ($notice == "") {
    db_open();
    
    $query = "select count(*) from player where username='$uname'";
    $result = db_query($query);
    $row = db_fetch_array($result);
    $count = $row[0];
    if ($count > 0) {
      $notice = "The username '$uname' is already taken.  " .
	"Please choose another username.";
      $uname = "";
    }
    db_close();

  }

  if ($notice == "") {
    db_open();
    $md5pass = md5($pass);
    $query = "insert into player " .
      "(username, firstname, lastname, email, password)" .
    " values " .
      "('$uname','$fname','$lname','$email','$md5pass')";
    db_query($query);
    db_close();

    $notice = "The user '$uname' has been created";
  }
}

$page->assign('notice', $notice);
$page->assign('subtitle', "Add a Bocce Player");

$page->display('addplayer.tpl');

?>
