{include file="header.tpl" title=$title}

{if $notice}
<div id="notice">{$notice}</div>
{/if}

<form action="" method="post">
  <div id="labels">
    <div class="label">
      <div>Email Address:</div>
      <input type="text" maxlength="100" size="25" name="email" value="{$email}" />
    </div>
    <div class="label">
      <div>Nickname:</div>
      <input type="text" maxlength="100" size="25" name="nick" value="{$nick}" />
    </div>
    <br />
    <div class="label">
      <div>Password:</div>
      <input type="password" maxlength="100" size="25" name="pass" />
    </div>
    <div class="label">
      <div>Verify Password:</div>
      <input type="password" maxlength="100" size="25" name="vpass" />
    </div>
    <div class="submit">
      <input class="submit" name="submit" type="submit" value="Submit" />
    </div>
  </div>
</form>

{include file="footer.tpl"}
