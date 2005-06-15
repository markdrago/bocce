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

require('start.php');

db_open();

if (!isset($_POST["submit"])) {
	coinFlipResults();
	$template = 'results';
} elseif ($_POST["submit"] == "Go First") {
	chooseGoFirst();
	$template = 'gofirst';
} elseif ($_POST["submit"] == "Choose Balls") {
	chooseBallColor();
	$template = 'ballcolor';
}

$page->display("flipcoin-$template.tpl");
exit(0);

function chooseGoFirst() {
	global $page;

	//if the winner of the coin toss selected to 'go first'
	$_SESSION["pallino_toss"] = array();
	$_SESSION["pallino_toss"][0] = $_SESSION["acting_player"];
	$chose_first_name = player_name($_SESSION["acting_player"]);

	$_SESSION["acting_player"] = nonActingPlayer();
	$acting_player_name = player_name($_SESSION["acting_player"]);

	$page->assign('subtitle', "$acting_player_name, Choose Your Balls");

	$page->assign('chose_first_name', $chose_first_name);
        select_balls("Choose your balls:");
	
	db_close();
}

function chooseBallColor() {
	global $page;

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
	$page->assign('chose_balls_name', $chose_balls_name);

	$_SESSION["acting_player"] = nonActingPlayer();
	$acting_player_name = player_name($_SESSION["acting_player"]);

	$page->assign('subtitle', "$acting_player_name, Choose Your Balls");

	$prev_balls = Array(
		Array(
			'name' => ball_type_name($GLOBALS["BRUISER_TYPE"]),
			'color' => ball_color($_POST['ball1'])
		),
		Array(
			'name' => ball_type_name($GLOBALS["LITTLE_BALL_TYPE"]),
			'color' => ball_color($_POST['ball2'])));
	$page->assign('prev_balls', $prev_balls);

	select_balls("Choose your balls:");

	db_close();
}

function coinFlipResults() {
	global $page;
	
	$coinflip_winner = (flip_coin() + 1);

	//the acting_player is the player that is making the current decision
	$coinflip_winner = "player" . $coinflip_winner;
	$_SESSION["acting_player"] = $_SESSION[$coinflip_winner];
	$_SESSION["coinflip_winner"] = $_SESSION[$coinflip_winner];

	$page->assign('subtitle', player_name($_SESSION["coinflip_winner"]) . " Won the Coin Toss!");
  
	select_balls("I want to choose my balls:");
  
	db_close();
}
  
function select_balls($message) {
	global $page;

	$bruisers = get_balls($GLOBALS['BRUISER_TYPE']);
	$littleballs = get_balls($GLOBALS['LITTLE_BALL_TYPE']);

	$page->assign('balls_message', $message);
	$page->assign('balls', Array($bruisers, $littleballs));
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

function get_balls($type) {
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

	$balldata = Array();
	foreach ($balls as $id => $ball) {
		if ($id != $exclude) {
			$balldata[] = Array('id' => $id, 'name' => $ball);
		}
	}
  
	return Array('type' => $type, 'type_name' => $ball_type_name, 'balls' => $balldata);
}


?>
