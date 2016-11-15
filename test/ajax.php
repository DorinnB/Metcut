
<?php
  $co=mysql_connect("localhost","root","");
  $dbnom="METCUT";
  $db=mysql_select_db($dbnom,$co);
  //==========================================
  //== on affiche dans un select la TABLE 1 ==
  //==========================================
  $res=mysql_query("SELECT * FROM tabl1",$co);
  $max=@mysql_num_rows($res);
?>
   <script type="text/javascript">
   function xmlhttp()
   {  var x;
      try         {  x = new ActiveXObject("Microsoft.XMLHTTP");   }
      catch (e)   {  try         {   x = new ActiveXObject("Msxml2.XMLHTTP");   }
                     catch (e)   {   try         {   x = new XMLHttpRequest();   }
                                     catch (e)   {   x=false;   }
                                 }
                  }
      return x;
   }
   function appel()
   {   var xml = xmlhttp();
      if(!xml)
             {   alert("XmlHttpRequest non supporté");   }
      else   {   xml.onreadystatechange = function()
                 {   if(xml.readyState==4)
                     {   var opt=xml.responseText.split("\t");
                         tb2.length=0;
                         for ( var n=1;n<opt.length;n++ )
                         {   tb2.length++;
                             tb2.options[tb2.length-1].text=opt[n];
                         }
                     }
                  }
                  xml.open("GET", "Ajax2.php?tbl2="+tb1.options[tb1.selectedIndex].text, true);
                  xml.send(null);
             }
   }      
  </script>
  <select   name="tb1" id="tb1"
            onchange='appel();'><?php
   for ($nb=0;$nb<$max;$nb++)
   {  $i=mysql_result($res,$nb,"t1ind");
      echo '<option>'.$i.'</option>';   
	}
   ?>  
      </select>
      <select    name="tb2" id="tb2">  
      </select>
<?php
  mysql_close($co);
?>
