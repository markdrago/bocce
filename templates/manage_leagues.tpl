{include file="header.tpl" title=$title}

{if $notice}
<div id="notice">{$notice}</div>
{/if}

<a href="create_league.php">Create a New League</a>

{if $leagues_managed}
  <div id="league_label">Leagues That You Manage:</div>
  <div id="leagues">
    {foreach item=league from=$leagues_managed}
      <a href="{$league.id}">{$league.name}</a><br />
    {/foreach}
  </div>
{/if}

{if $leagues}
  <div id="league_label">Leagues That You Are A Member Of:</div>
  <div id="leagues">
    {foreach item=league from=$leagues}
      <a href="{$league.id}">{$league.name}</a><br />
    {/foreach}
  </div>
{/if}

{include file="footer.tpl"}
