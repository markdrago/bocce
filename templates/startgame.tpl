<!-- {$subtitlehint} -->
{include file="header.tpl" title=$title}

{if $notice}
<div id="notice">{$notice}</div>
{/if}
<form action="" method="post">
  <div class="labels">
    <div class="label">
      <div>User Name:</div>
      <input type="text" maxlength="100" size="25" name="uname" value="{$uname}" />
    </div>
    <div class="label">
      <div>Password:</div>
      <input type="password" maxlength="100" size="25" name="pass" />
    </div>
{if $playernum == "First"}
    <div class="label">
      <div>Season #:</div>
      <input type="text" maxlength="25" size="25" name="season" />
    </div>
{/if}
    <div class="submit">
      <input name="submit" type="submit" value="Login" />
      <input name="submit" type="submit" value="Cancel" />
    </div>
  </div>
</form>
</div>

{literal}
<script type="text/javascript">
function setfocus() {
  document.forms[0].uname.focus();
}
window.onload=setfocus;
</script>
{/literal}

{include file="footer.tpl"}
