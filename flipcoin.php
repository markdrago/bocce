<?
/*
 * Copyright (C) 2005 Mark Drago
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
  db_open();

if (!isset($_POST["submit"])) {
  coinFlipResults();

} elseif ($_POST["submit"] == "Go First") {
  chooseGoFirst();

} elseif ($_POST["submit"] == "Choose Balls") {
  chooseBallColor();
}

function chooseGoFirst() {
  require("header.php");
  require("side.php");

  //if the winner of the coin toss selected to 'go first'
  $_SESSION["pallino_toss"] = array();
  $_SESSION["pallino_toss"][0] = $_SESSION["acting_player"];
  $chose_first_name = player_name($_SESSION["acting_player"]);

  $_SESSION["acting_player"] = nonActingPlayer();
  $acting_player_name = player_name($_SESSION["acting_player"]);

  ?>
  <div class="body">
  <form action="" method="post">
    <h1 class="title"><?=$acting_player_name?>, Choose Your Balls</h1>
    <div style="padding-top: 1em; width: 12em; text-align: right;">
      <?=$chose_first_name?> chose to go first.
    </div>
    <form action="" method="post">
    <div class="labels">
      <? select_balls("Choose your balls:"); ?>
    </div>
  </form>
  </div>
  <?

  db_close();
}

function chooseBallColor() {
  require("header.php");
  require("side.php");

  if ($_SESSION["acting_player"] == $_SESSION["player1"]) {
    $player = "player1";
  } else {
    $player = "player2";
  }

  $_SESSION[$player."_ball1"]=$_POST['ball1'];
  $_SESSION[$player."_ball2"]=$_POST['ball2'];

  if (!isset($_SESSION["pallino_toss"][0])) {
    $_SESSION["pallino_toss"][0] = nonActingPlayer();
  }

  //if we're done
  if (isset($_SESSION["player1_ball1"]) &&
      isset($_SESSION["player1_ball2"]) &&
      isset($_SESSION["player2_ball1"]) &&
      isset($_SESSION["player2_ball2"]) &&
      isset($_SESSION["pallino_toss"][0])) {

    unset($_SESSION["acting_player"]);

    redirect("addscore.php");
    db_close();
    return 0;
  }

  $chose_balls_name = player_name($_SESSION["acting_player"]);

  $_SESSION["acting_player"] = nonActingPlayer();
  $acting_player_name = player_name($_SESSION["acting_player"]);

  $big_ball_name = ball_type_name($GLOBALS["BRUISER_TYPE"]);
  $small_ball_name = ball_type_name($GLOBALS["LITTLE_BALL_TYPE"]);

  $big_ball_color = ball_color($_POST['ball1']);
  $small_ball_color = ball_color($_POST['ball2']);

  ?>
  <div class="body">
  <form action="" method="post">
    <h1 class="title"><?=$acting_player_name?>, Choose Your Balls</h1>
    <div style="padding-top: 1em; padding-left: 1em;">
      <?=$chose_balls_name?> chose to use the following balls:
    </div>
    <div style="padding-left: 6em;">
      <?=$big_ball_name?>: <?=$big_ball_color?><br />
      <?=$small_ball_name?>: <?=$small_ball_color?>
    </div>
    <form action="" method="post">
    <div class="labels">
      <? select_balls("Choose your balls:"); ?>
    </div>
  </form>
  </div>
  <?

  db_close();
}

function coinFlipResults() {
  require("header.php");
  require("side.php");

  $coinflip_winner = (flip_coin() + 1);

  //the acting_player is the player that is making the current decision
  $coinflip_winner = "player" . $coinflip_winner;
  $_SESSION["acting_player"] = $_SESSION[$coinflip_winner];
  $_SESSION["coinflip_winner"] = $_SESSION[$coinflip_winner];
  $coinflip_winner_name = player_name($_SESSION["coinflip_winner"]);
  
  ?>
  <div class="body">
  <form action="" method="post">
  <h1 class="title"><?=$coinflip_winner_name?> Won the Coin Toss!</h1>
  
  <form action="" method="post">
    <div class="labels">
      <div class="label" style="padding-bottom: 10px;">
        <div style="width: 12em;">I want to go first:</div>
        <input name="submit" type="submit" value="Go First" />
      </div>
     <? select_balls("I want to choose my balls:"); ?>
    </div>
  </form>
  </div>
  <?

  db_close();
}
  
function select_balls($message) {
  $bruiserselect = get_ball_select($GLOBALS['BRUISER_TYPE']);
  $littleballselect = get_ball_select($GLOBALS['LITTLE_BALL_TYPE']);

?>
  <div class="label">
    <div style="width: 12em;"><?=$message?></div>
    <?=$bruiserselect?>
    <?=$littleballselect?>
    <br />
    <div style="text-align: right; width: 25.7em; padding-top: 0px;">
      <input name="submit" type="submit" value="Choose Balls" />
    </div>
  </div>
<?
}

function flip_coin() {
  //this function just returns a 0 or a 1
  return (mt_rand() & 1);
}

function nonActingPlayer() {
  //return the player that is not currently making a decision
  if ($_SESSION["acting_player"] == $_SESSION["player1"]) {
    return $_SESSION["player2"];
  } else {
    return $_SESSION["player1"];
  }
}

function get_ball_select($type) {
  $query = "select id, color from ball where num=$type order by color";
  $result = db_query($query);

  $balls = array();

  while($row = db_fetch_array($result)) {
    $balls[$row['id']] = $row['color'];
  }

  $ball_type_name = ball_type_name($type);

  $exclude = "";
  for ($i = 1; $i <= 2; $i++) {
    if (isset($_SESSION["player$i" . "_ball$type"])) {
      $exclude = $_SESSION["player$i" . "_ball$type"];
    }
  }

  $ballselect = "<select name=\"ball$type\">\n";
  $ballselect .= "<option value=\"-----\">$ball_type_name</option>\n";
  foreach ($balls as $id => $ball) {
    if ($id != $exclude) {
      $ballselect .= "<option value=\"$id\">$ball</option>\n";
    }
  }
  $ballselect .= "</select>\n";
  
  return $ballselect;
}

?>

</body>
</html>