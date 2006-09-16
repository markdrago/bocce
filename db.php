<?
/*
 * Copyright (C) 2005, 2006 Josef "Jeff" Sipek <jeffpc@josefsipek.net>
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

require_once "consts.php";

$db_stats = array("open" => 0, "close" => 0,
		  "query" => 0,
		  "begin" => 0, "commit" => 0, "rollback" => 0,
		  "checkfordb" => 0);

require_once "db_$dbengine.php";

function db_dump_stats()
{
	global $db_stats;

	echo $db_stats;
}

?>
