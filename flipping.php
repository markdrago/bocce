<?
/*
 * Copyright (C) 2005 Mark Drago
 *
 *This program is free software; you can redistribute it and/or modify
 *it under the terms of the GNU General Public License as published by
 *the Free Software Foundation; either version 2 of the License, or
 *(at your option) any later version.
 *
 *This program is distributed in the hope that it will be useful,
 *but WITHOUT ANY WARRANTY; without even the implied warranty of
 *MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *GNU General Public License for more details.
 *
 *You should have received a copy of the GNU General Public License
 *along with this program; if not, write to the Free Software
 *Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require("header.php");
require("side.php");
?>

<div class="body">
<h1>Flipping the Coin ...</h1>
</div>

<script>
function gotoFlipCoin() {
  window.location="flipcoin.php";
}
setTimeout("gotoFlipCoin()", 2500);
</script>
</div>
</body>
</html>