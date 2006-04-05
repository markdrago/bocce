DROP SEQUENCE ball_seq;
CREATE SEQUENCE ball_seq INCREMENT BY 1 START WITH 1;

DROP TABLE ball;
CREATE TABLE ball (
	id int NOT NULL default nextval('ball_seq') PRIMARY KEY,
	num int,
	color varchar(100)
);

DROP SEQUENCE bruise_seq;
CREATE SEQUENCE bruise_seq INCREMENT BY 1 START WITH 1;

DROP TABLE bruise;
CREATE TABLE bruise (
	id int NOT NULL default nextval('bruise_seq') PRIMARY KEY,
	game int,
	round int,
	player int,
	success int
);

DROP SEQUENCE game_seq;
CREATE SEQUENCE game_seq INCREMENT BY 1 START WITH 1;

DROP TABLE game;
CREATE TABLE game (
	id int NOT NULL default nextval('game_seq') PRIMARY KEY,
	loser int,
	winner int,
	coinflip_winner int,
	loser_points int,
	winner_points int,
	winner_ball1 varchar(100),
	winner_ball2 varchar(100),
	loser_ball1 varchar(100),
	loser_ball2 varchar(100),
	dts int default 0,
	season int
);

DROP SEQUENCE league_seq;
CREATE SEQUENCE league_seq INCREMENT BY 1 START WITH 1;

DROP TABLE league;
CREATE TABLE league (
	id int NOT NULL default nextval('league_seq') PRIMARY KEY,
	name varchar(100),
	manager int,
	dts int
);

DROP SEQUENCE league_player_seq;
CREATE SEQUENCE league_player_seq INCREMENT BY 1 START WITH 1;

DROP TABLE league_player;
CREATE TABLE league_player (
	id int NOT NULL default nextval('league_player_seq') PRIMARY KEY,
	league int,
	player int,
	dts int default 0
);

DROP SEQUENCE player_seq;
CREATE SEQUENCE player_seq INCREMENT BY 1 START WITH 1;

DROP TABLE player;
CREATE TABLE player (
	id int NOT NULL default nextval('player_seq') PRIMARY KEY,
	nickname varchar(100),
	email varchar(100),
	pass char(40),
	dts int default 0
);

DROP SEQUENCE point_seq;
CREATE SEQUENCE point_seq INCREMENT BY 1 START WITH 1;

DROP TABLE point;
CREATE TABLE point (
	id int NOT NULL default nextval('point_seq') PRIMARY KEY,
	game int,
	round int,
	scorer int,
	pallino_tosser int,
	amount int
);

DROP SEQUENCE season_seq;
CREATE SEQUENCE season_seq INCREMENT BY 1 START WITH 1;

DROP TABLE season;
CREATE TABLE season (
	id int NOT NULL default nextval('season_seq') PRIMARY KEY,
	league int,
	dts_start int,
	dts_end int,
	num_games int
);

DROP SEQUENCE season_player_seq;
CREATE SEQUENCE season_player_seq INCREMENT BY 1 START WITH 1;

DROP TABLE season_player;
CREATE TABLE season_player (
	id int NOT NULL default nextval('season_player_seq') PRIMARY KEY,
	season int,
	player int
);

