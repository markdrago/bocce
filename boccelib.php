<?
/*
 * Copyright (C) 2004, 2005 Mark Drago
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

require("vardefs.php");

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
  global $database_file;
  $db = sqlite_open($database_file);
  
  $query = "select firstname, lastname from player where id=$id1";
  $result = sqlite_query($db,$query);
  $row = sqlite_fetch_array($result,SQLITE_ASSOC);
  $firstname1 = $row["firstname"];
  $lastname1 = $row["lastname"];
  
  $query = "select firstname, lastname from player where id=$id2";
  $result = sqlite_query($db,$query);
  $row = sqlite_fetch_array($result,SQLITE_ASSOC);
  $firstname2 = $row["firstname"];
  $lastname2 = $row["lastname"];

  if ($firstname1 != $firstname2) {
    $name1 = $firstname1;
    $name2 = $firstname2;
  } else {
    $initial1 = substr($lastname1,0,1);
    $initial2 = substr($lastname2,0,1);
    if ($initial1 != $initial2) {
      $name1 = $firstname1 . " " . $initial1 . ".";
      $name2 = $firstname1 . " " . $initial2 . ".";
    } else {
      $name1 = $firstname1 . " " . $lastname1;
      $name2 = $firstname2 . " " . $lastname2;
    }
  }

  sqlite_close($db);

  return array($name1, $name2);
}

function checkForDB() {
  global $database_file;
  global $schema_file;
  if (!file_exists($database_file)) {
    system("sqlite $database_file < $schema_file");
  }
}
?>