<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>OfficeBocce.com</title>
<link rel="stylesheet" type="text/css" href="main.css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="print.css" media="print" />
</head>
<body>
<div id="wrapper">
<div id="header">
<a href="index.php"><img height="66px" width="90px" src="images/bocceball.png" alt="Bocce Ball" /></a>
<span id="title">{$title|default:"Office Bocce Score Tracker"}</span>
</div> <!--header-->

{include file="sidepanel.tpl"}

<div id="content">
{if $subtitle != ""}
<div id="subtitle">
{strip}
<h1>{$subtitle}</h1>
{if $subtitlehint != ""}
<div id="subtitlehint">{$subtitlehint}</div>
{/if}
{/strip}
</div>
{/if}
<!-- content begins -->
