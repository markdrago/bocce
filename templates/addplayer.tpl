{include file="header.tpl" title=$title}

{if $notice}
<div id="notice">{$notice}</div>
{/if}

<form action="" method="post">
  <div id="labels">
    <div class="label">
      <div>First Name:</div>
      <input type="text" maxlength="100" size="25" name="fname" value="{$fname}" />
    </div>
    <div class="label">
      <div>Last Name:</div>
      <input type="text" maxlength="100" size="25" name="lname" value="{$lname}" />
    </div>
    <div class="label">
      <div>Nickname:</div>
      <input type="text" maxlength="100" size="25" name="nick" value="{$nick}" />
    </div>
    <div class="label">
      <div>Email Address:</div>
      <input type="text" maxlength="100" size="25" name="email" value="{$email}" />
    </div>
    <br />
    <div class="label">
      <div>User Name:</div>
      <input type="text" maxlength="100" size="25" name="uname" value="{$uname}" />
    </div>
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
