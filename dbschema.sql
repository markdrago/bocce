create table ball(
id INTEGER PRIMARY KEY,
num INTEGER,
color CHAR(100));
CREATE table bruise(
id integer primary key,
game integer,
round integer,
player integer,
success integer);
CREATE TABLE game(
id INTEGER PRIMARY KEY,
loser INTEGER,
winner INTEGER,
loser_points INTEGER,
winner_points INTEGER,
winner_ball1 CHAR(100),
winner_ball2 CHAR(100),
loser_ball1 CHAR(100),
loser_ball2 CHAR(100),
date DATETIME);
CREATE TABLE player(
id INTEGER PRIMARY KEY,
username VARCHAR(100),
firstname VARCHAR(100),
lastname VARCHAR(100),
email VARCHAR(100),
password CHAR(32));
CREATE TABLE point(
id INTEGER PRIMARY KEY,
game INTEGER,
round INTEGER,
scorer INTEGER,
amount INTEGER);
