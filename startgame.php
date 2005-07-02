<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
 *           (C) 2004 Tom Melendez
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

require('start.php');

$uname = "";
$titlehint = "";
$notice = "";

if (isset($_POST["submit"]) and ($_POST["submit"] == "Cancel")) {
  unset($_SESSION);
  session_destroy();
  $uname = "";
}
elseif (isset($_POST["submit"]) and ($_POST["submit"] == "Login")) {
  db_open();

  //try and login player
  $player = "player1";
  if (isset($_SESSION["player1"])) {
    $player = "player2";
  }

  $uname = $_POST["uname"];
  $pass = $_POST["pass"];

  //check that username and password were entered
  if ($notice == "") {
    $requiredarray = array("You must enter a user name."=>$uname,
			   "You must enter a password."=>$pass);

    $notice = checkrequired($requiredarray);
  }

  //check that username and password are of an acceptable length
  if ($notice == "") {
    $lengtharray = array("Your user name must be between " .
			 "3 and 16 characters in length."=>array($uname,3,16),
			 "Your password must be between " .
			 "6 and 25 characters in length."=>array($pass,6,25));

    $notice = checklength($lengtharray);
  }

  if (isset($_SESSION["player1"]) and ($notice == "")) {
    $firstID = $_SESSION["player1"];

    $query = "select username from player where id=$firstID";
    $result = db_query($query);
    $row = db_fetch_array($result);

    //make sure the user names are different
    $notice = checkdifferent($row["username"], $uname, "This user '$uname' has already logged in.");
    if ($notice != "") {
      $uname = "";
    }
  }

  if ($notice == "") {
    $sha1pass = sha1($pass);
    $query = "select id from player where username='$uname' and password='$sha1pass'";
    $result = db_query($query);

    if ($row = db_fetch_array($result)) {
      $_SESSION[$player] = $row['id'];
      $titlehint = "($uname's opponent)";

      //clear out username so it doesn't show up in login box
      $uname = "";
    } else {
      $notice = "The user name and password that you entered are not correct.<br />Either the user name doesn't exist or you mistyped the password.";
    }
  }

  db_close();

  if (isset($_SESSION["player1"]) and isset($_SESSION["player2"])) {
    redirect("flipping.php");
    return 0;
  }
}
else {
  unset($_SESSION);
  session_destroy();
  session_start();
}

$playernum = "First";
if (isset($_SESSION["player1"])) {
  $playernum = "Second";
}

$page->assign('notice', $notice);
$page->assign('subtitle', "Login $playernum Player");
$page->assign('subtitlehint', $titlehint);
$page->assign('uname', $uname);

$page->display('startgame.tpl');

?>
