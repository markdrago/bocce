<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
 *           (C) 2004 Tom Melendez
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

session_start();
require('boccelib.php');

$uname = "";
$titlehint = "";
$notice = "";

if (isset($_POST["submit"]) and ($_POST["submit"] == "Cancel")) {
  unset($_SESSION);
  session_destroy();
  $uname = "";
}
elseif (isset($_POST["submit"]) and ($_POST["submit"] == "Login")) {
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

  if ($notice == "") {
    $db = sqlite_open($database_file);
    $md5pass = md5($pass);
    $query = "select id from player where username='$uname' and password='$md5pass'";
    $result = sqlite_query($db,$query);

    if ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
      $_SESSION[$player] = $row['id'];
      $_SESSION[$player."_ball1"]=$_POST['ball1'];
      $_SESSION[$player."_ball2"]=$_POST['ball2'];
      $titlehint = "($uname's opponent)";

      //clear out username so it doesn't show up in login box
      $uname = "";
    } else {
      $notice = "The user name and password that you entered are not correct.<br />Either the user name doesn't exist or you mistyped the password.";
    }
    sqlite_close($db);
  }

  if (isset($_SESSION["player1"]) and isset($_SESSION["player2"])) {
    redirect("addscore.php");
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

require("header.php");
require("side.php");

print "<div class=\"body\">";

if ($notice != "") {
  print "<div class=\"notice\">$notice</div>";
}

?>
<h1 class="title">Login <?=$playernum?> Player<span class="titlehint"><?=$titlehint?></span></h1>
<form action="" method="post">
  <div class="labels">
    <div class="label">
      <div>User Name:</div>
      <input type="text" maxlength="16" size="25" name="uname" value="<?=$uname?>" />
    </div>
    <div class="label">
      <div>Password:</div>
      <input type="password" maxlength="25" size="25" name="pass" />
    </div>
    <div class="label">
      <div>Ball Color:</div>
<?
$db = sqlite_open($database_file);

$query = "select id,num, color from ball order by color";
$result = sqlite_query($db,$query);

$balls1 = array();
$balls2 = array();

while ($row = sqlite_fetch_array($result,SQLITE_ASSOC)) {
  if ($row['num'] == 1) {
    $balls1[$row['id']] = $row['color'];
  } else {
    $balls2[$row['id']] = $row['color'];
  }
}

print "<select name=\"ball1\" />";
foreach ($balls1 as $id => $ball) {
  if ($id != $_SESSION['player1_ball1']) {
    print "<option value=\"$id\">$ball</option>\n";
  }
}
print "</select>";

print "<select name=\"ball2\" />";
foreach ($balls2 as $id => $ball) {
  if ($id != $_SESSION['player1_ball2']) {
    print "<option value=\"$id\">$ball</option>\n";
  }
}
print "</select>";
?>
    </div>	
    <div class="submit">
      <input name="submit" type="submit" value="Login" />
      <input name="submit" type="submit" value="Cancel" />
    </div>
  </div>
</form>
</div>
<script type="text/javascript">
function setfocus() {
  document.forms[0].uname.focus();
}
window.onload=setfocus;
</script>
</body>
</html>
