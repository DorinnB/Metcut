<!DOCTYPE html>
<html>
<body>

<div id="demo">
<h1>The XMLHttpRequest Object</h1>
<button type="button" onclick="loadDoc()">Change Content</button>


<form action=""> 
	First name: <input type="text" id="txt1">
</form>


<script>
function loadDoc() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("demo").innerHTML =
      this.responseText;
    }
  };
  var aaa= document.getElementById("txt1").value;

  xhttp.open("GET", "profile.php&i=1", true);
  xhttp.send();
}
</script>

</body>
</html>
