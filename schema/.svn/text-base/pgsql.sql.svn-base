DROP TABLE ball;
CREATE TABLE ball (
       id int,
       num int,
       color varchar(100),
       CONSTRAINT ball_pk PRIMARY KEY (id)
);

DROP TABLE bruise;
CREATE TABLE bruise (
       id int,
       game int,
       round int,
       player int,
       success int,
       CONSTRAINT bruise_pk PRIMARY KEY (id)
);

DROP TABLE game;
CREATE TABLE game (
       id int,
       loser int,
       winner int,
       coinflip_winner int,
       loser_points int,
       winner_points int,
       winner_ball1 varchar(100),
       winner_ball2 varchar(100),
       loser_ball1 varchar(100),
       loser_ball2 varchar(100),
       dts timestamp without time zone,
       CONSTRAINT game_pk PRIMARY KEY (id)
);

DROP TABLE player;
CREATE TABLE player (
       id int,
       username varchar(100),
       firstname varchar(100),
       lastname varchar(100),
       email varchar(100),
       password char(32),
       CONSTRAINT player_pk PRIMARY KEY (id)
);

DROP TABLE point;
CREATE TABLE point (
       id int,
       game int,
       round int,
       scorer int,
       pallino_tosser int,
       amount int,
       CONSTRAINT point_pk PRIMARY KEY (id)
);

