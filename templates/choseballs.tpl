<div id="balls-message">{$balls_message}</div>

{section name=balltype loop=$balls}
	<select name="ball{$balls[balltype].type}">
		<option value="-----">{$balls[balltype].type_name}</option>
		{section name=ball loop=$balls[balltype].balls}
			<option value="{$balls[balltype].balls[ball].id}">{$balls[balltype].balls[ball].name}</option>
		{/section}
	</select>
{/section}
