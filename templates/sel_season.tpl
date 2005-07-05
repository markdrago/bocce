{include file="header.tpl" title=$title}

<br />

{section name=s loop=$seasons}
<a href="flipping.php?season={$seasons[s].season}">{$seasons[s].name}</a><br/>
{/section}

{include file="footer.tpl}
