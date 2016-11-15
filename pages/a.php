<script language="JavaScript" type="text/javascript">
function appendColumn(){
	var tbl=document.getElementById('my-table');
	for(var i=0;i<tbl.rows.length;i++)
		createCell(tbl.rows[i].insertCell(tbl.rows[i].cells.length),i,'col');
	}

function createCell(cell,text,style){
	var input=document.createElement('input');
	var txt=document.createTextNode(text);
	input.type= "text";
	input.name = "member";
	cell.appendChild(input);
	}

function deleteColumns(){
	var tbl=document.getElementById('my-table');
	var lastCol=tbl.rows[0].cells.length-1;
	for(var i=0;i<tbl.rows.length;i++)
		for(var j=lastCol;j>0;j--)tbl.rows[i].deleteCell(j);
	}
</script>


<div id="my-container">
<center><br />

<input type="button" value="Add column" onclick="javascript:appendColumn()" class="append_column"/><br />

<input type="button" value="Delete columns" onclick="javascript:deleteColumns()" class="delete"/><br />
<a href="#" class="cache" onclick="document.getElementById('aaa').submit();" class="toolbar">Maj Consignes</a>
<form id="aaa" action="b.php" method="POST">
<input type="hidden" name="idjob" value="0">
<table id="my-table" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
<td>Small</td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td>HTML</td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td>table</td>
</tr>
</table>
</form>
<p></center>
</div>