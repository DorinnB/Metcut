<?php
//*********************************************************
// The php calendar component 
// written by TJ @triconsole
//
// version 2.3 (31 Jan 2009)


//bug fixed: Incorrect next month display show on 'February 2008'
//	- thanks Neeraj Jain for bug report
//
//bug fixed: Incorrect month comparable on calendar_form.php line 113
// - thanks Djenan Ganic, Ian Parsons, Jesse Davis for bug report
//
//add on: date on calendar form change upon textbox in datepicker mode
//add on: validate date enter from dropdown and textbox
//
//bug fixed: Calendar path not valid when select date from dropdown
// - thanks yamba for bug report
//
//adjust: add new function setWidth and deprecate getDayNum function
//
//bug fixed: year combo box display not correct when extend its value
//	- thanks Luiz Augusto for bug report
//
//fixed on date and month value return that is not leading by '0'
//
//adjust: change php short open tag (<?=) to normal tag (<?php)
//  - thanks Michael Lynch
//
//add on: getMonthNames() function to make custom month names on each language
//  - thanks Jean-Francois Harrington
//
//add on: button close on datepicker on the top-right corner of calendar
//  - thanks denis
//
//bug fixed: hide javascript alert when default date not defined
//	- thanks jon-b
//
//bug fixed: incorrect layout when select part of date
//	- thanks simonzebu (I just got what you said  :) )
//
//bug fixed: not support date('N') for php version lower 5.0.1 so change to date('w') instead
//  - thanks simonzebu, Kamil, greensilver for bug report
//  - thanks Paul for the solution
//
//add on: setHeight() function to set the height of iframe container of calendar
//	- thanks Nolochemcial
//
//add on: startMonday() function to set calendar display first day of week on Monday
//
//bug fixed: don't display year when not in year interval
//********************************************************

class tc_calendar{
	var $icon;
	var $objname;
	var $txt;
	var $date_format;
	var $year_display_from_current = 30;
	
	var $date_picker;
	var $path = '';
	
	var $day = 00;
	var $month = 00;
	var $year = 0000;	
	
	var $width = 150;
	var $height = 205;
	
	var $year_start;
	var $year_end;
	
	var $startMonday = false;
	
	//calendar constructor
	function tc_calendar($objname, $date_picker = false){
		$this->objname = $objname;
		$this->txt = "Select";
		$this->date_format = "Y-m-d";
		//$this->year_display_from_current = 50;
		$this->date_picker = $date_picker;
		
		//set default year display from current year
		$thisyear = date('Y');
		$this->year_start = $thisyear-$this->year_display_from_current;
		$this->year_end = $thisyear+$this->year_display_from_current;
	}
	
	//check for leapyear
	function is_leapyear($year){
    	return ($year % 4 == 0) ?
    		!($year % 100 == 0 && $year % 400 <> 0)	: false;
    }
	
	//get the total day of each month in year
    function total_days($month,$year){
    	$days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    	return ($month == 2 && $this->is_leapYear($year)) ? 29 : $days[$month-1];
    }
	
	//Deprecate since v1.6
	function getDayNum($day){
		$headers = $this->getDayHeaders();
		return isset($headers[$day]) ? $headers[$day] : 0;
	}
	
	//get the day headers start from sunday till saturday
	function getDayHeaders(){
		if($this->startMonday)
			return array("1"=>"Mo", "2"=>"Tu", "3"=>"We", "4"=>"Th", "5"=>"Fr", "6"=>"Sa","7"=>"Su");
		else
			return array("7"=>"Su", "1"=>"Mo", "2"=>"Tu", "3"=>"We", "4"=>"Th", "5"=>"Fr", "6"=>"Sa");
	}
	
	function setIcon($icon){
		$this->icon = $icon;
	}
	
	function setText($txt){
		$this->txt = $txt;
	}
	
	//not currently in-used
	function setDateFormat($format){
		$this->date_format = $format;
	}

	//set default selected date
	function setDate($day, $month, $year){
		$this->day = $day;
		$this->month = $month;
		$this->year = $year;
	}
	
	//specified location of the calendar_form.php 
	function setPath($path){
		$last_char = substr($path, strlen($path)-1, strlen($path));
		if($last_char != "/") $path .= "/";
		$this->path = $path;
	}
	
	function writeScript(){
		$this->writeHidden();

		//check whether it is a date picker
		if($this->date_picker){
			$this->writeDay();
			$this->writeMonth();
			$this->writeYear();
			echo(" <a href=\"javascript:toggleCalendar('div_".$this->objname."');\">");
			if(is_file($this->icon)){
				echo("<img src=\"".$this->icon."\" id=\"tcbtn_".$this->objname."\" name=\"tcbtn_".$this->objname."\" border=\"0\" align=\"absmiddle\" />");
			}else echo($this->txt);				
			echo("</a>");			
			
			$div_display = "none";
			$iframe_position = "absolute";
			$dp=1;
		}else{
			$div_display = "block";
			$iframe_position = "relative";
			$dp=0;
		}
		
		//write the calendar container
		echo("<div id=\"div_".$this->objname."\" style=\"position: relative;display:".$div_display.";z-index:10000;\"><IFRAME id=\"".$this->objname."_frame\" style=\"DISPLAY:block; LEFT:0px; POSITION:".$iframe_position."; TOP:0px;\" src=\"".$this->path."calendar_form.php?objname=$this->objname&selected_day=".$this->day."&selected_month=".$this->month."&selected_year=".$this->year."&year_start=".$this->year_start."&year_end=".$this->year_end."&dp=$dp&mon=".$this->startMonday."\" frameBorder=\"0\" scrolling=\"no\" height=\"$this->height\" width=\"$this->width\"></IFRAME></div>");
		
	}
	
	//write the select box of days
	function writeDay(){
		echo("<select name=\"".$this->objname."_day\" id=\"".$this->objname."_day\" onChange=\"javascript:tc_setDay('".$this->objname."', this[this.selectedIndex].value, '$this->path');\" class=\"tcday\">");
		echo("<option value=\"\">Day</option>");
		for($i=1; $i<=31; $i++){
			$selected = ((int)$this->day == $i) ? " selected" : "";
			echo("<option value=\"".str_pad($i, 2 , "0", STR_PAD_LEFT)."\"$selected>$i</option>");
		}
		echo("</select> ");
	}
	
	//write the select box of months
	function writeMonth(){
		echo("<select name=\"".$this->objname."_month\" id=\"".$this->objname."_month\" onChange=\"javascript:tc_setMonth('".$this->objname."', this[this.selectedIndex].value, '$this->path');\" class=\"tcmonth\">");
		echo("<option value=\"\">Month</option>");
		
		$monthnames = $this->getMonthNames();		
		for($i=1; $i<=sizeof($monthnames); $i++){
			$selected = ((int)$this->month == $i) ? " selected" : "";
			echo("<option value=\"".str_pad($i, 2, "0", STR_PAD_LEFT)."\"$selected>".$monthnames[$i-1]."</option>");
		}
		echo("</select> ");
	}
	
	//write the year textbox
	function writeYear(){
		//echo("<input type=\"textbox\" name=\"".$this->objname."_year\" id=\"".$this->objname."_year\" value=\"$this->year\" maxlength=4 size=5 onBlur=\"javascript:tc_setYear('".$this->objname."', this.value, '$this->path');\" onKeyPress=\"javascript:if(yearEnter(event)){ tc_setYear('".$this->objname."', this.value, '$this->path'); return false; }\"> ");
		echo("<select name=\"".$this->objname."_year\" id=\"".$this->objname."_year\" onChange=\"javascript:tc_setYear('".$this->objname."', this[this.selectedIndex].value, '$this->path');\" class=\"tcyear\">");
		echo("<option value=\"\">Year</option>");
		for($i=$this->year_start; $i<=$this->year_end; $i++){
			$selected = ((int)$this->year == $i) ? " selected" : "";
			echo("<option value=\"$i\"$selected>$i</option>");
		}
		echo("</select> ");
	}
	
	//write hidden components
	function writeHidden(){
		$svalue = str_pad($this->year, 4, "0", STR_PAD_LEFT)."-".str_pad($this->month, 2, "0", STR_PAD_LEFT)."-".str_pad($this->day, 2, "0", STR_PAD_LEFT);
		echo("<input type=\"hidden\" name=\"".$this->objname."\" id=\"".$this->objname."\" value=\"$svalue\">");
		echo("<input type=\"hidden\" name=\"".$this->objname."_dp\" id=\"".$this->objname."_dp\" value=\"".$this->date_picker."\">");
		echo("<input type=\"hidden\" name=\"".$this->objname."_year_start\" id=\"".$this->objname."_year_start\" value=\"".$this->year_start."\">");
		echo("<input type=\"hidden\" name=\"".$this->objname."_year_end\" id=\"".$this->objname."_year_end\" value=\"".$this->year_end."\">");
	}
	
	//set width of calendar
	function setWidth($width){
		if($width) $this->width = $width;
	}
	
	//set height of calendar
	function setHeight($height){
		if($height) $this->height = $height;
	}
	
	function setYearInterval($start, $end){
		$this->year_start = $start;
		$this->year_end = $end;
	}
	
	function getMonthNames(){
		return array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");		
	}
	
	function startMonday($flag){
		$this->startMonday = $flag;
	}
}
?>