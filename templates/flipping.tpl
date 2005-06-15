{include file="header.tpl" title=$title}

{literal}
<script>
function gotoFlipCoin() {
  window.location="flipcoin.php";
}
setTimeout("gotoFlipCoin()", 2500);
</script>
{/literal}

{include file="footer.tpl}
