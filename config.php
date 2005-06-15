<?
$jpgraph_dir = "../jpgraph-1.17beta2";

# Location of smarty class
define(SMARTY_DIR,'/usr/share/php/smarty/libs/');

# Smarty directories
$template_dir	= 'templates';
$compile_dir	= 'templates_c';
$config_dir	= 'configs';
$cache_dir	= 'cache';

# Database engine to use
#    Choices:
#	sqlite
$dbengine = "sqlite";

# Database file for sqlite (used only if $dbengine is set to sqlite)
$database_file = "bocce.db";

?>
