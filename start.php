<?
/*
 * Copyright (C) 2005 Josef "Jeff" Sipek <jeffpc@josefsipek.net>
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

require_once("config.php");

/*
 * Start up the session
 */
session_start();

/*
 * Set up Smarty
 */
require_once($smarty_dir . "Smarty.class.php");

$page = new Smarty;
$page->template_dir	= $template_dir;
$page->compile_dir	= $compile_dir;
$page->config_dir	= $config_dir;
$page->cache_dir	= $cache_dir;

/*
 * Set up db
 */
require_once("db.php");

/*
 * Start up boccelib
 */
require("boccelib.php");

?>
