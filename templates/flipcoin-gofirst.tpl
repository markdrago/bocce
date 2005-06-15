{include file="header.tpl" title=$title}

  <form action="" method="post">
    <div style="padding-top: 1em; width: 12em; text-align: right;">
      {$chose_first_name} chose to go first.
    </div>
    <div id="labels">
    <div class="widelabel">
      {include file="choseballs.tpl"}
    </div>
    <div style="text-align: right; width: 25.7em; padding-top: 0px;">
    <input name="submit" type="submit" value="Choose Balls" />
    </div>
    </div>
  </form>


{include file="footer.tpl"}
