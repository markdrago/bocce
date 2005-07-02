DROP TABLE IF EXISTS ball;
CREATE TABLE ball (
	id int(11) NOT NULL auto_increment,
	num int(11) default NULL,
	color varchar(100) default NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS bruise;
CREATE TABLE bruise (
	id int(11) NOT NULL auto_increment,
	game int(11) default NULL,
	round int(11) default NULL,
	player int(11) default NULL,
	success int(11) default NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS game;
CREATE TABLE game (
	id int(11) NOT NULL auto_increment,
	loser int(11) default NULL,
	winner int(11) default NULL,
	coinflip_winner int(11) default NULL,
	loser_points int(11) default NULL,
	winner_points int(11) default NULL,
	winner_ball1 varchar(100) default NULL,
	winner_ball2 varchar(100) default NULL,
	loser_ball1 varchar(100) default NULL,
	loser_ball2 varchar(100) default NULL,
	dts int(11) NOT NULL default 0,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS player;
CREATE TABLE player (
	id int(11) NOT NULL auto_increment,
	username varchar(100) default NULL,
	firstname varchar(100) default NULL,
	lastname varchar(100) default NULL,
	email varchar(100) default NULL,
	`password` varchar(32) default NULL,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS point;
CREATE TABLE point (
	id int(11) NOT NULL auto_increment,
	game int(11) default NULL,
	round int(11) default NULL,
	scorer int(11) default NULL,
	pallino_tosser int(11) default NULL,
	amount int(11) default NULL,
	PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
