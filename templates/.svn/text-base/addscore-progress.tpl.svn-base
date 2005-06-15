{include file="header.tpl" title=$title}

<form action="" method="post">
<center>
<table class="score">
<tr>
<td><h1>{$score1}</h1></td>
<td colspan="2"><h1>-</h1></td>
<td><h1>{$score2}</h1></td>
</tr>
<tr>
<td><h2>{$name1}</h2></td>
<td colspan="2"></td>
<td><h2>{$name2}</h2></td>
</tr>
</table>
<br />
<table class="controls">
{if !$confirmPage}
<tr><td style="border: 0px;">
<table class="controlpad">
<tr>
<td>{$name1}</td>
<td><button type="submit" name="action" value="Player1Score1">Score 1</button></td>
<td><button type="submit" name="action" value="Player1Score2" >Deuce</button></td>
<td><button type="submit" name="action" value="Player1GoodBruise" >Successful Bruise</button></td>
<td><button type="submit" name="action" value="Player1BadBruise" >Missed Bruise</button></td>
</tr>
<tr>
<td>{$name2}</td>
<td><button type="submit" name="action" value="Player2Score1">Score 1</button></td>
<td><button type="submit" name="action" value="Player2Score2" >Deuce</button></td>
<td><button type="submit" name="action" value="Player2GoodBruise" >Successful Bruise</button></td>
<td><button type="submit" name="action" value="Player2BadBruise" >Missed Bruise</button></td>
</tr>
</table>
</td>
</tr>
{/if}

{if $undo}
<tr><td colspan="5" style="border: 0px"><button type="submit" name="action" value="undo">Undo</button></td></tr>
{/if}

{if $confirmPage}
<tr><td colspan="5" style="border: 0px;"><button type="submit" name="action" value="confirm">Confirm</button></td></tr>
{/if}
</table>

{if $showhistory}
<h3>Score History</h3>
<table class="scoreboard">

<tr>
<td>{$name1}</td>
{section name=hist loop=$hist1}
<td>{$hist1[hist]}</td>
{/section}
</tr>

<tr>
<td>{$name2}</td>
{section name=hist loop=$hist2}
<td>{$hist2[hist]}</td>
{/section}
</tr>
</table>
{/if}

{if $showbruises}
<h3>Bruises</h3>
<table class="bruises">
<tr>
<th>Name</th><th>Round</th><th>Success</th>
</tr>
{section name=br loop=$bruises}
{strip}
<tr>
<td>{$bruises[br].name}</td>
<td>{$bruises[br].round}</td>
<td>{$bruises[br].succ}</td>
</tr>
{/strip}
{/section}
</table>
{/if}

</center>
</form>

{include file="footer.tpl"}
