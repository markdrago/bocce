<?
#Location of jpgraph
$jpgraph_dir = "/usr/share/jpgraph/";

# Location of smarty class
$smarty_dir = '/usr/share/php/smarty/libs/';

# Smarty directories
$template_dir	= 'templates';
$compile_dir	= 'templates_c';
$config_dir	= 'configs';
$cache_dir	= 'cache';

# Database engine to use
#    Choices:
#	mysql
#	pgsql
#	sqlite
$dbengine = "mysql";

# Database file for sqlite (used only if $dbengine is set to sqlite)
$database_file = "bocce.db";

# Database hostname, username and password (used only if $dbengine is set
# to mysql or pgsql)
$database_host = "localhost";
$database_db   = "bocce";
$database_user = "root";
$database_pass = "";

?>
