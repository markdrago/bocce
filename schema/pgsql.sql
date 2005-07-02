DROP SEQUENCE idseq;
CREATE SEQUENCE idseq INCREMENT BY 1 START WITH 1;

DROP TABLE ball;
CREATE TABLE ball (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	num int,
	color varchar(100)
);

DROP TABLE bruise;
CREATE TABLE bruise (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	game int,
	round int,
	player int,
	success int
);

DROP TABLE game;
CREATE TABLE game (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	loser int,
	winner int,
	coinflip_winner int,
	loser_points int,
	winner_points int,
	winner_ball1 varchar(100),
	winner_ball2 varchar(100),
	loser_ball1 varchar(100),
	loser_ball2 varchar(100),
	dts int default 0
);

DROP TABLE league;
CREATE TABLE league (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	name int,
	manager int,
	dts int
);

DROP TABLE league_player;
CREATE TABLE league_player (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	league int,
	player int
);

DROP TABLE player;
CREATE TABLE player (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	username varchar(100),
	nickname varchar(100),
	firstname varchar(100),
	lastname varchar(100),
	email varchar(100),
	pass char(40)
);

DROP TABLE point;
CREATE TABLE point (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	game int,
	round int,
	scorer int,
	pallino_tosser int,
	amount int
);

DROP TABLE season;
CREATE TABLE season (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	league int,
	dts_start int,
	dts_end int,
	num_games int
);

DROP TABLE season_player;
CREATE TABLE season_player (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	season int,
	player int
);
