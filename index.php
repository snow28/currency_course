<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        				<!--TITLE-->
    <title>Exchange Rates Online</title>
            				<!--Stylesheets-->
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        				<!--Normilize CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css" >
    					<!--Bootstrap-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	    					<!--Fonts-->
	<link href="https://fonts.googleapis.com/css?family=Fresca" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Crete+Round" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    	    					<!--JQuery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    	    					<!--JQuery UI-->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	    					<!--Bootstrap-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="javascript.js"></script>

</head>

<body>
	<div class='container col-xs-12' style='padding : 0;'>
		<header class='col-xs-12'><h1>Exchange Rates Online</h1></header>

		<nav class='col-xs-offset-2 col-xs-8'>

			<form method="get" action="index.php" >
				<div id='checkboxes'>
					<label for="formDoor">USD-EUR</label>
					<input type="radio" checked name="formDoor[]" value="USD-EUR" /> 
					<label for="currency">UAH-USD</label> 
					<input id='secondCheckbox' type="radio" name="formDoor[]" value="USD-UAH" />					
				</div>
				
				<div>
					<div id='display_inline'>
						<label for="date_1">From:</label> 
						<input type="text" id="datepicker1"  name="date_1" size="30">
					</div>
					<div id='display_inline'>
						<label for="date_2" id='padding_left_1em' >To:</label>
						<input type="text" id="datepicker2" name="date_2" size="30">
					</div>
				</div>
				<div class='button'>
					<input style='border : 1px solid black;' type="submit" value="Submit" class='w3-btn w3-white w3-border  w3-round-large'>
				</div>
			</form>		
		</nav>


		<section id="rates" class="col-xs-offset-2 col-xs-8"  >
		<?php
				function display_table_header($th1,$th2, $TH){
					echo "<table class='col-xs-offset-3 col-xs-6'>";
					echo "<caption style='text-align : center;'>" . $TH . " currency course table</caption>";
					echo "<tr><th>Date</th><th>" . $th1 . "</th><th>" . $th2 . "</th></tr>";
				}
				function check_position($array, $position , $pre_last_letter, $last_letter , $first_of_next_currency){
					if($array[$position] == $pre_last_letter && $array[$position+1] == $last_letter && $array[$position+3] != $first_of_next_currency){
						return true;
					}else{
						return false;
					}	
				}
				function changeTypeOfDate( $date ){
					$stringToReturn .= $date[3];
					$stringToReturn .= $date[4];
					$stringToReturn .= $date[5];
					for($x=0; $x<3;$x++){
						$stringToReturn .= $date[$x];
					}
					for($x=6; $x<10;$x++){
						$stringToReturn .= $date[$x];
					}
					return $stringToReturn;
				}

				function display_content($coursesArraySecond, $datesArraySecond , $courseArrayFirst){
					$trigger=false;
					for($currentPosition = 0; $currentPosition < sizeof($coursesArraySecond) ; $currentPosition++){
						if($datesArraySecond[$currentPosition] == $_GET['date_2']){
							$trigger = true;
						}else if($datesArraySecond[$currentPosition] == $_GET['date_1']){
							$trigger = false;
						}
						if($trigger == true){
							echo '<tr>';
							echo "<td style='font-size : 20px;' >" . $datesArraySecond[$currentPosition] . "</td><td style='font-size : 20px;'>" . $coursesArraySecond[$currentPosition] . "</td><td style='font-size:20px;' >" . $courseArrayFirst[$currentPosition] . '</td>';
							echo '</tr>';
						}
					}							
				}

					//change setting depends on checkboxes
					if($_GET['formDoor'][0] == 'USD-EUR'){
						$lettersSecond=array('U','R','U' );
						$lettersFirst=array('S','D','E');
						$linkTwo='EUR-USD';
						$linkOne='USD-EUR';
						$th1='1 USD-EUR';
						$th2='1 EUR-USD';
					}else if($_GET['formDoor'][0] == 'USD-UAH'){
						$lettersSecond=array('S','D','U' );
						$lettersFirst=array('A','H','U');
						$linkOne='UAH-USD';
						$linkTwo='USD-UAH';
						$th1='1 UAH-USD';
						$th2='1 USD-UAH';
						echo "<script>$('#secondCheckbox').attr('checked', 'checked');</script>";
					}

					$firstContent=file_get_contents('http://www.exchangerates.org.uk/' . $linkTwo . '-exchange-rate-history-full.html');
					#начало забираемого контента: 
					$pos=strpos($firstContent,'<table'); 
					#Отрезаем все, что идет до нужной нам позиции: 
					$firstContent=substr($firstContent,$pos); 
					#Таким же образом находим позицию конечной строки: 
					$pos=strpos($firstContent, '</table>'); 
					#Отрезаем ненужное: 
					$firstContent=substr($firstContent,0,$pos); 
					$firstContent=substr($firstContent,295);
					$sizeOfContent = sizeof($firstContent);
					$firstContent = substr($firstContent,28);
					$courseArrayFirst = array();
					$datesArraySecondFirst = array();


					for($var=0 ; $var < strlen($firstContent) ; $var++){
						//checking if we are at appropriate position 
						if( check_position($firstContent , $var , $lettersSecond[0] , $lettersSecond[1] , $lettersSecond[2]) ){
							for($x=0; $x<11;$x++){
								if($firstContent[$var+98+$x] == '/' && $x >= 10){
									break;
								}
								if($firstContent[$var+98+$x] != '<'  ){
									$tmp1 .= $firstContent[$var+98+$x];
								}
							}
							for($z=0; $z<6;$z++){
								//do not display spaces
								if($secondContent[$var+5+$z] != ' '){
									$tmp2 .= $firstContent[$var+5+$z];
								}
							}
							$numberOfCourses++;
							array_push($courseArrayFirst, $tmp2);
							array_push($datesArraySecondFirst , $tmp1);
						}
						unset($tmp1);
						unset($tmp2);
					}




					$secondContent=file_get_contents('http://www.exchangerates.org.uk/' . $linkOne . '-exchange-rate-history-full.html');
					#начало забираемого контента: 
					$pos=strpos($secondContent,'<table'); 
					#Отрезаем все, что идет до нужной нам позиции: 
					$secondContent=substr($secondContent,$pos); 
					#Таким же образом находим позицию конечной строки: 
					$pos=strpos($secondContent, '</table>'); 
					#Отрезаем ненужное: 
					$secondContent=substr($secondContent,0,$pos); 
					$secondContent=substr($secondContent,295);
					$sizeOfContent = sizeof($secondContent);
					$secondContent = substr($secondContent,28);
					$coursesArraySecond = array();
					$datesArraySecond = array();
					//count current number of courses available
					$numberOfCourses = 0;

					for($var=0 ; $var < strlen($secondContent) ; $var++){
						//checking if we are at appropriate position 
						if(check_position($secondContent , $var , $lettersFirst[0] , $lettersFirst[1] , $lettersFirst[2]) ){
							//tmp1 and tmp2 variables will be used to store date and course of current date
							if($secondContent[$var+98-1] == '1'){
								$tmp1 .='1';
							}else if($secondContent[$var+98-1] == '2'){
								$tmp1 .='2';
							}else if($secondContent[$var+98-1] == '3'){
								$tmp1 .='3';
							}
							for($x=0; $x<11;$x++){
								if($secondContent[$var+98+$x] == '/' && $x >= 10){
									break;
								}
								if($secondContent[$var+98+$x] != '<'  ){
									$tmp1 .= $secondContent[$var+98+$x];
								}
							}
							for($z=0; $z<6;$z++){
								//do not display spaces
								if($secondContent[$var+5+$z] != ' '){
									$tmp2 .= $secondContent[$var+5+$z];
								}
							}
							$numberOfCourses++;
							array_push($coursesArraySecond, $tmp2);
							array_push($datesArraySecond , $tmp1);
						}
						unset($tmp1);
						unset($tmp2);
					}


					$_GET['date_1'] = changeTypeOfDate($_GET['date_1']);
					$_GET['date_2'] = changeTypeOfDate($_GET['date_2']);
					//echo var_dump($_GET) . '</br>';
					
					for($x = 0; $x<2; $x++){
						$date_1 .= $_GET['date_1'][$x];
						$date_2 .= $_GET['date_2'][$x];
					}
					for($x = 0; $x<2; $x++){
						$month_1 .= $_GET['date_1'][$x+3];
						$month_2 .= $_GET['date_2'][$x+3];
					}
					for($x = 0; $x<4; $x++){
						$year_1 .= $_GET['date_1'][$x+6];
						$year_2 .= $_GET['date_2'][$x+6];
					}


					$year_1 = (int)$year_1;
					$year_2 = (int)$year_2;
					$month_1 = (int)$month_1;
					$month_2 = (int)$month_2;
					$day_1 = (int)$day_1;
					$day_2 = (int)$day_2;

					if($year_1 < $year_2 ){
						display_table_header($th1, $th2 , $_GET['formDoor'][0]);
						display_content($coursesArraySecond, $datesArraySecond , $courseArrayFirst);

					}else if($year_1 == $year_2 && $month_1 < $month_2){
						display_table_header($th1, $th2 ,$_GET['formDoor'][0]);
						display_content($coursesArraySecond, $datesArraySecond , $courseArrayFirst);

					}else if($year_1 == $year_2 && $month_1 == $month_2 && $date_1 < $date_2 ){
						display_table_header($th1, $th2 ,$_GET['formDoor'][0]);
						display_content($coursesArraySecond, $datesArraySecond , $courseArrayFirst);

					}else{
						
						echo "<div id='error'><p>First date should be less than second!</p></div>";
						
					}
					echo '</table>';
		?>

		</section>
		
		</div>
	<footer >
		<p><strong>Kachailo Dmytro</strong> Copyright &copy; 2016-<?php $today = date("Y"); echo $today?>.</p>
	</footer>

</body>



