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

//this file contains general functions that are used throughout officebocce

require_once("consts.php");
require_once("db.php");

$GLOBALS['BRUISER_TYPE'] = "1";
$GLOBALS['LITTLE_BALL_TYPE'] = "2";

/* checkrequired($checkarray) takes an array of the form:
 * array("error string 1"=>$field1, "error string 2"=>$field2, ... )
 * and returns the error string that corresponds to the first
 * empty field.  If none of the fields are empty, an empty
 * string is returned */
function checkrequired($checkarray) {
  foreach($checkarray as $message => $checkfield) {
    if ($checkfield == "") {
      return $message;
    }
  }

  return "";
}

function dumpvar($variable) {
  print "<pre>";
  var_dump($variable);
  print "</pre>";
}

function checkmatching($var1, $var2, $message) {
  if ($var1 != $var2) {
    return $message;
  }

  return "";
}

function checkdifferent($var1, $var2, $message) {
  if ($var1 == $var2) {
    return $message;
  }

  return "";
}

function check_is_defined($args=null) {
  $numargs = count($args);
  $i = 0;
  while ($i < $numargs) {
    if ((!isset($args[$i])) or ($args[$i] == "")) {
      $args[$i] = 0;
    }
    $i++;
  }
}

function clean_value($value) {
  if ((!isset($value)) or ($value == "")) {
    return 0;
  } else {
    return $value;
  }
}

/* checklength($checkarray) takes an array of the form:
 * array("error string 1"=>array($field1,min,max),
 * "error string 2"=>array($field2,min,max), ... )
 * and returns the error string that corresponds to the first
 * field that is of an offending length, if none of the fields are
 * outside of their bounds an empty string is returned */
function checklength($checkarray) {
  foreach($checkarray as $message => $item) {
    $length = strlen($item[0]);

    if ($length < $item[1]) {
      return $message;
    }

    if ($length > $item[2]) {
      return $message;
    }
  }

  return "";
}

function redirect($page) {
  print "<script type=\"text/javascript\">";
  print "window.location=\"$page\"";
  print "</script>";
}

function getNames($id1, $id2) {
  db_open();

  $query = "select nickname,email from player where id='$id1'";
  $result = db_query($query);
  $row = db_fetch_array($result);
  $name1 = $row["nickname"];
  $email1 = $row['email'];
  
  $query = "select nickname,email from player where id='$id2'";
  $result = db_query($query);
  $row = db_fetch_array($result);
  $name2 = $row["nickname"];
  $email2 = $row['email'];

  if ( $name1 == $name2 ) {
	// replace with email address
	$name1 = $email1;
	$name2 = $email2;
  }
  
  db_close();
  
  return array($name1, $name2);
}

#get player's name
function player_name($id) {
  $result = db_query("select nickname from player where id='$id'");
  $row = db_fetch_array($result);
  return clean_value($row["nickname"]);
}

#return the name for the ball type given its integer representation
function ball_type_name($type) {
  switch($type) {
  case 1:
    return "Bruiser";
    break;
  case 2:
    return "Little Ball";
    break;
  default:
    return "Ball";
    break;
  }
}

#get the string representation of the ball color
function ball_color($ball) {
  $result = db_query("select color from ball where id='$ball'");
  $row = db_fetch_array($result);
  return clean_value($row[0]);
}

#generate a random string
function generate_random_string() {
  $length = 40;

  $charset  = "abcdefghijklmnopqrstuvwxyz";
  $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $charset .= "0123456789";

  $rand_string = "";

  for ($i = 0; $i < $length; $i++) {
    $index = rand(0, strlen($charset) - 1);
    
    $rand_string .= substr($charset, $index, 1);
  }

  return $rand_string;
}

#setup the contents of the sidepanel given the type we want to display
function side_panel($page, $type) {
  $panel_links = array();

  switch($type) {

  case "PLAYING_GAME":
    $panel_links["Cancel Game"] = "index.php";
    break;

  case "LOGGED_IN":
    $panel_links["Home"] = "player_home.php";
    $panel_links["Start a Game"] = "startgame.php";
    $panel_links["Manage Leagues"] = "manage_leagues.php";
    $panel_links["Preferences"] = "userprefs.php";
    $panel_links["Logout"] = "logout.php";
    break;

  default:
  case "NOT_LOGGED_IN":
    $panel_links["Login"] = "login.php";
    $panel_links["Create Account"] = "createaccount.php";
    $panel_links["Start a Game"] = "startgame.php";
    $panel_links["League Statistics"] = "league_stats.php";
    break;
  }

  $page->assign('side_panel_links', $panel_links);
}

?>
