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

DROP TABLE player;
CREATE TABLE player (
	id int NOT NULL default nextval('idseq') PRIMARY KEY,
	username varchar(100),
	firstname varchar(100),
	lastname varchar(100),
	email varchar(100),
	password char(32)
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

