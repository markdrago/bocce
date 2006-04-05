<div id="sidepanel">
<div id="sp-buttons">
<ul>

{foreach name=link key=key item=item from=$side_panel_links}
  {if $smarty.foreach.link.last}
    <li class="last"><a href="{$item}">{$key}</a></li>
  {else}
    <li><a href="{$item}">{$key}</a></li>
  {/if}
{/foreach}

</ul>
</div>
</div>
