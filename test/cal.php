
<script language="javascript" src="calendar.js"></script>


<form action="somewhere.php" method="post">
<?php
//get class into the page
require_once('classes/tc_calendar.php');

//instantiate class and set properties
$myCalendar = new tc_calendar("date1", true);
$myCalendar->setIcon("images/iconCalendar.gif");
$myCalendar->setDate(24, 3, 2009);

//output the calendar
$myCalendar->writeScript();	  
?>
</form>
