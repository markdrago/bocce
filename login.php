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

side_panel($page, "NOT_LOGGED_IN");

$email = "";
$pass = "";

if (isset($_POST["submit"])) {
  $email = $_POST["email"];
  $pass  = $_POST["pass"];

  if ($notice == "") {
    $requiredarray = array("You must enter your email address."=>$email,
			   "You must enter your password."=>$pass);

    $notice = checkrequired($requiredarray);
  }

  if ($notice == "") {
    db_open();
    $sha1pass = sha1($pass);
    $query = "select id from player where email='$email' and pass='$sha1pass'";
    $result = db_query($query);
    
    if ($row = db_fetch_array($result)) {
      $_SESSION['player_id'] = $row['id'];
      $email = "";
      $pass = "";

      #redirect to the player's home page
      redirect("player_home.php");

    } else {
      $notice = "The user name and password that you entered are incorrect." .
	"<br />Either the user name doesn't exist " .
	"or you mistyped the password.";
      $pass = "";

      $page->assign('notice', $notice);
      $page->assign('subtitle', "Login");
      
      $page->display('login.tpl');

    }

    db_close();
  }
} else {
  
  $page->assign('notice', $notice);
  $page->assign('subtitle', "Login");

  $page->display('login.tpl');
}

?>
