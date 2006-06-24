{include file="header.tpl" title=$title}

{if $notice}
  <div id="notice">{$notice}</div>
{/if}

{if $join_requests}
  <div id="request_label">League Join Requests:</div>
  <div id="request">
    {foreach item=request from=$join_requests}
      {$request.manager} has invited you to join the league '{$request.league}'
      <br />
      <a href="answer_request.php?id={$request.id}&answer=1">Accept Request</a>
      &nbsp;&nbsp;
      <a href="answer_request.php?id={$request.id}&answer=0">Deny Request</a>
    {/foreach}
  </div>
{/if}

{if $leagues}
  <div id="league_label">Leagues:</div>
  <div id="leagues">
    <table>
    <tr><th>League Name</th><th>Win Percentage</th></tr>
      {foreach item=league from=$leagues}
        <tr>
          <td><a href="{$league.id}">{$league.name}</a></td>
          <td>{$league.win_percentage}</td>
	</tr>
      {/foreach}
    </table>
  </div>
{/if}

<div id="member_since">
<span class="label">Member Since:</span>&nbsp;&nbsp;{$join_date}
</div>

{include file="footer.tpl"}
