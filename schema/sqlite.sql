BEGIN TRANSACTION;
create table ball(
	id INTEGER PRIMARY KEY,
	num INTEGER,
	color CHAR(100)
);

CREATE table bruise(
	id integer primary key,
	game integer,
	round integer,
	player integer,
	success integer
);

CREATE TABLE game(
	id INTEGER PRIMARY KEY,
	loser INTEGER,
	winner INTEGER,
	coinflip_winner INTEGER,
	loser_points INTEGER,
	winner_points INTEGER,
	winner_ball1 CHAR(100),
	winner_ball2 CHAR(100),
	loser_ball1 CHAR(100),
	loser_ball2 CHAR(100),
	dts INTEGER
);

CREATE TABLE league (
	id integer PRIMARY KEY,
	name varchar(100),
	manager integer,
	dts integer
);

CREATE TABLE league_player (
	id integer PRIMARY KEY,
	league integer,
	player integer,
	dts integer
);

CREATE TABLE league_player_join_request (
	id integer PRIMARY KEY,
	league integer,
	manager_requested integer,
	player_exists integer,
	player_id integer,
	email VARCHAR(100),
	random_key VARCHAR(100),
	dts integer
);

CREATE TABLE player(
	id INTEGER PRIMARY KEY,
	nickname VARCHAR(100),
	email VARCHAR(100),
	pass CHAR(40),
	dts integer
);

CREATE TABLE point(
	id INTEGER PRIMARY KEY,
	game INTEGER,
	round INTEGER,
	scorer INTEGER,
	pallino_tosser INTEGER,
	amount INTEGER
);
CREATE TABLE season (
	id integer PRIMARY KEY,
	league integer,
	dts_start integer,
	dts_end integer,
	num_games integer
);

CREATE TABLE season_player (
	id integer PRIMARY KEY,
	season integer,
	player integer
);
COMMIT;
