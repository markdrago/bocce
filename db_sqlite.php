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

if (!@extension_loaded('sqlite'))
	@dl('sqlite.so');

$db = null;
$open_count = 0;

function db_open()
{
	global $database_file;
	global $db;
	global $open_count;
	
	$open_count++;
	if ($open_count!=1)
		return;
	
	$db = sqlite_open($database_file);
}

function db_close()
{
	global $db;
	global $open_count;
	
	$open_count--;
	if ($open_count>0)
		return;

	sqlite_close($db);
	$db = null;
}

function db_query($sql)
{
	global $db;
	global $open_count;
	
	$result = sqlite_query($db,$sql);
	if ($result===false) {
		echo "Error, could not execute query:\n";
		echo "\t$sql\n";
		echo "Open Count = $open_count\n";
	}
	
	return $result;
}

function db_fetch_array()
{
	global $db;
	
	$result = func_get_arg(0);
	$type = SQLITE_BOTH;
	if (func_num_args()==2)
		$type = func_get_arg(1);

	return sqlite_fetch_array($result,$type);
}

function db_num_rows($result)
{
	return sqlite_num_rows($result);
}

function db_last_insert_rowid()
{
	global $db;

	return sqlite_last_insert_rowid($db);
}

?>