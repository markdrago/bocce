{include file="header.tpl" title=$title}

{if $notice}
<div id="notice">{$notice}</div>
{/if}

<form action="" method="post">
  <div id="labels">
    <div class="label">
      <div>League Name:</div>
      <input type="text" maxlength="100" size="25" name="league_name" value="{$league_name}" />
    </div>
    <div class="submit">
      <input class="submit" name="submit" type="submit" value="Submit" />
    </div>
  </div>
</form>

{include file="footer.tpl"}
