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

$email = "";
$titlehint = "";
$notice = "";

side_panel($page, "PLAYING_GAME");

if (isset($_POST["submit"]) and ($_POST["submit"] == "Cancel")) {
	unset($_SESSION);
	session_destroy();
	$email = "";
} elseif (isset($_POST["submit"]) and ($_POST["submit"] == "Login")) {
	db_open();

	//try and login player
	$player = "player1";
	if (isset($_SESSION["player1"])) {
		$player = "player2";
	}

	$email = $_POST["email"];
	$pass = $_POST["pass"];

	//check that username/email and password were entered
	if ($notice == "") {
		$requiredarray = array("You must enter an email address."=>$email,
				       "You must enter a password."=>$pass);

		$notice = checkrequired($requiredarray);
	}

	//check that password is an acceptable length
	// TODO: check email for valid syntax?
	if ($notice == "") {
		$lengtharray = array("Your password must be between 6 and 100 characters in length."=>array($pass,6,100));

		$notice = checklength($lengtharray);
	}

	if (isset($_SESSION["player1"]) and ($notice == "")) {
		$firstID = $_SESSION["player1"];

		$query = "select email from player where id=$firstID";
		$result = db_query($query);
		$row = db_fetch_array($result);

		//make sure the user names are different
		$notice = checkdifferent($row["email"], $email, "The user '$email' has already logged in.");
		if ($notice != "") {
			$email = "";
		}
	}

	if ($notice == "") {
		$sha1pass = sha1($pass);
		$query = "select id from player where email='$email' and pass='$sha1pass'";
		$result = db_query($query);

		if ($row = db_fetch_array($result)) {
			$_SESSION[$player] = $row['id'];
			$titlehint = "($email's opponent)";

			//clear out username so it doesn't show up in login box
			$email = "";
		} else {
			$notice = "The email address and password that you entered are not correct.<br />Either that account doesn't exist or you mistyped the password.";
		}
	}

	db_close();

	if (isset($_SESSION["player1"]) and isset($_SESSION["player2"])) {
		redirect("sel_season.php");
		return 0;
	}
} else {
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
$page->assign('email', $email);

$page->display('startgame.tpl');

?>
