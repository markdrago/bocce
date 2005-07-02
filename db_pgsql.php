<?
/*
 * Copyright (C) 2005 Josef "Jeff" Sipek <jeffpc@optonline.net>
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

if (!@extension_loaded('pgsql'))
	@dl('pgsql.so');

$open_count = 0;
$link = null;
$last_res = null;

define("DB_ASSOC",	MYSQL_ASSOC);
define("DB_NUM",	MYSQL_NUM);

function db_open()
{
	global $database_host, $database_user, $database_pass;
	global $database_db;
	global $link;
	global $open_count;
	
	$open_count++;
	if ($open_count!=1)
		return;
	
	$link = pg_Connect("host=$database_host dbname=$database_db user=$database_user password=$database_pass");
}

function db_close()
{
	global $link;
	global $open_count;
	
	$open_count--;
	if ($open_count>0)
		return;

	pg_Close($link);
	$link = null;
}

function db_query($sql)
{
	global $link;
	global $open_count;
	global $last_res;
	
	$result = pg_Query($link,$sql);
	if ($result===false) {
		echo "Error, could not execute query:\n";
		echo "\t$sql\n";
		echo "Open Count = $open_count\n";
		if (pg_last_error($link))
			echo "pg_last_error(): " . pg_last_error($link) . "\n";
	}

	$last_res = $result;
	
	return $result;
}

function db_fetch_array()
{
	global $link;
	
	$result = func_get_arg(0);
	return pg_fetch_array($result);
}

function db_num_rows($result)
{
	return pg_num_rows($result);
}

function db_last_insert_rowid()
{
	global $link;

	$result = pg_query("SELECT CURRVAL('idseq') AS seq;");
	$result = pg_fetch_array($result);
	return $result[0];
}

function checkForDB()
{
}

?>
