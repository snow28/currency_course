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
					<input type="radio" name="formDoor[]" value="USD-UAH" />
					<label for="currency">UAH-EUR</label> 
					<input type="radio"  name="formDoor[]" value="EUR-UAH" />
					
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
					<input type="submit" value="Submit" class='w3-btn w3-white w3-border w3-border-red w3-round-large'>
				</div>
			</form>		
		</nav>


		<section id="rates" class="col-xs-offset-2 col-xs-8"  >
		<?php
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

				function display_content($coursesArray, $datesArray , $coursesArrayEUR){
					$trigger=false;
					for($currentPosition = 0; $currentPosition < sizeof($coursesArray) ; $currentPosition++){
						if($datesArray[$currentPosition] == $_GET['date_2']){
							$trigger = true;
						}else if($datesArray[$currentPosition] == $_GET['date_1']){
							$trigger = false;
						}
						if($trigger == true){
							echo '<tr>';
							echo "<td style='font-size : 20px;' >" . $datesArray[$currentPosition] . "</td><td style='font-size : 20px;'>" . $coursesArray[$currentPosition] . "</td><td style='font-size:20px;' >" . $coursesArrayEUR[$currentPosition] . '</td>';
							echo '</tr>';
						}
					}							
				}

					//				1 EUR -> USD
					$contentEUR=file_get_contents('http://www.exchangerates.org.uk/EUR-USD-exchange-rate-history-full.html');
					#начало забираемого контента: 
					$pos=strpos($contentEUR,'<table'); 
					#Отрезаем все, что идет до нужной нам позиции: 
					$contentEUR=substr($contentEUR,$pos); 
					#Таким же образом находим позицию конечной строки: 
					$pos=strpos($contentEUR, '</table>'); 
					#Отрезаем ненужное: 
					$contentEUR=substr($contentEUR,0,$pos); 
					$contentEUR=substr($contentEUR,295);
					$sizeOfContent = sizeof($contentEUR);
					$contentEUR = substr($contentEUR,28);
					$coursesArrayEUR = array();
					$datesArrayEUR = array();


					for($var=0 ; $var < strlen($contentEUR) ; $var++){
						//checking if we are at appropriate position 
						if( check_position($contentEUR , $var , 'U' , 'R' , 'U') ){
							for($x=0; $x<11;$x++){
								if($contentEUR[$var+98+$x] == '/' && $x >= 10){
									break;
								}
								if($contentEUR[$var+98+$x] != '<'  ){
									$tmp1 .= $contentEUR[$var+98+$x];
								}
							}
							for($z=0; $z<6;$z++){
								//do not display spaces
								if($contentUSD[$var+5+$z] != ' '){
									$tmp2 .= $contentEUR[$var+5+$z];
								}
							}
							$numberOfCourses++;
							array_push($coursesArrayEUR, $tmp2);
							array_push($datesArrayEUR , $tmp1);
						}
						unset($tmp1);
						unset($tmp2);
					}

										//				1 USD -> EUR
					$contentUSD=file_get_contents('http://www.exchangerates.org.uk/USD-EUR-exchange-rate-history-full.html');
					#начало забираемого контента: 
					$pos=strpos($contentUSD,'<table'); 
					#Отрезаем все, что идет до нужной нам позиции: 
					$contentUSD=substr($contentUSD,$pos); 
					#Таким же образом находим позицию конечной строки: 
					$pos=strpos($contentUSD, '</table>'); 
					#Отрезаем ненужное: 
					$contentUSD=substr($contentUSD,0,$pos); 
					$contentUSD=substr($contentUSD,295);
					$sizeOfContent = sizeof($contentUSD);
					$contentUSD = substr($contentUSD,28);
					$coursesArray = array();
					$datesArray = array();
					//count current number of courses available
					$numberOfCourses = 0;

					for($var=0 ; $var < strlen($contentUSD) ; $var++){
						//checking if we are at appropriate position 
						if(check_position($contentUSD , $var , 'S' , 'D' , 'E') ){
							//tmp1 and tmp2 variables will be used to store date and course of current date
							if($contentUSD[$var+98-1] == '1'){
								$tmp1 .='1';
							}else if($contentUSD[$var+98-1] == '2'){
								$tmp1 .='2';
							}else if($contentUSD[$var+98-1] == '3'){
								$tmp1 .='3';
							}
							for($x=0; $x<11;$x++){
								if($contentUSD[$var+98+$x] == '/' && $x >= 10){
									break;
								}
								if($contentUSD[$var+98+$x] != '<'  ){
									$tmp1 .= $contentUSD[$var+98+$x];
								}
							}
							for($z=0; $z<6;$z++){
								//do not display spaces
								if($contentUSD[$var+5+$z] != ' '){
									$tmp2 .= $contentUSD[$var+5+$z];
								}
							}
							$numberOfCourses++;
							array_push($coursesArray, $tmp2);
							array_push($datesArray , $tmp1);
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
					echo "<table class='col-xs-offset-3 col-xs-6'>";
					echo "<caption style='text-align : center;'>EUR-USD currency course table</caption>";
					echo "<tr><th>Date</th><th>1 USD-EUR</th><th>1 EUR-USD</th></tr>";
					//var_dump((int)$date_2,(int)$month_2,(int)$year_2);

					if((int)$year_1 < (int)$year_2 ){
						
						display_content($coursesArray, $datesArray , $coursesArrayEUR);

					}else if((int)$year_1 == (int)$year_2 && (int)$month_1 < (int)$month_2){

						display_content($coursesArray, $datesArray , $coursesArrayEUR);

					}else if((int)$year_1 == (int)$year_2 && (int)$month_1 == (int)$month_2 && (int)$date_1 < (int)$date_2 ){

						display_content($coursesArray, $datesArray , $coursesArrayEUR);

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



