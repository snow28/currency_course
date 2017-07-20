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
					<label for="currency">USD-EUR</label>
					<input type="radio" checked name="formDoor[]" value="USD-EUR" /> 
					<label for="currency">EUR-USD</label>
					<input type="radio" name="formDoor[]" value="EUR-USD" />
					<label for="currency">UAH-USD</label> 
					<input type="radio" name="formDoor[]" value="UAH-USD" />
					<label for="currency">UAH-EUR</label> 
					<input type="radio"  name="formDoor[]" value="UAH-EUR" />
				</div>
				<label for="date_1">From:</label> 
				<input type="text" id="datepicker1"  name="date_1" size="30">
				<label for="date_2" id='padding_left_1em' >To:</label>
				<input type="text" id="datepicker2" name="date_2" size="30">
				<div class='button'>
					<input type="submit" value="Submit" class='w3-btn w3-white w3-border w3-border-red w3-round-large'>
				</div>
			</form>		
		</nav>


		<section id="rates" class="col-md-offset-2 col-xs-8"  >
		<?php
				function checkIfUSD($array, $position){
						if($array[$position] == 'S' && $array[$position+1] == 'D' && $array[$position+3] != 'E'){
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


 
				if($_GET['formDoor'][0] == 'USD-EUR'){
					#откуда парсим
					$content=file_get_contents('http://www.exchangerates.org.uk/USD-EUR-exchange-rate-history-full.html');
					#начало забираемого контента: 
					$pos=strpos($content,'<table'); 
					#Отрезаем все, что идет до нужной нам позиции: 
					$content=substr($content,$pos); 
					#Таким же образом находим позицию конечной строки: 
					$pos=strpos($content, '</table>'); 
					#Отрезаем ненужное: 
					$content=substr($content,0,$pos); 
					$content=substr($content,295);
					$sizeOfContent = sizeof($content);
					$content = substr($content,28);
					$coursesArray = array();
					$datesArray = array();
					//count current number of courses available
					$numberOfCourses = 0;



					
					for($var=0 ; $var<521552 ; $var++){
						//checking if we are at appropriate position 
						if(checkIfUSD($content, $var) ){
							//tmp1 and tmp2 variables will be used to store date and course of current date
							if($content[$var+98-1] == '1'){
								$tmp1 .='1';
							}else if($content[$var+98-1] == '2'){
								$tmp1 .='2';
							}
							for($x=0; $x<11;$x++){
								if($content[$var+98+$x] == '/' && $x >= 10){
									break;
								}
								if($content[$var+98+$x] != '<'  ){
									//echo $content[$var+98+$x];
									$tmp1 .= $content[$var+98+$x];
								}
							}
							for($z=0; $z<6;$z++){
								//do not display spaces
								if($content[$var+5+$z] != ' '){
									$tmp2 .= $content[$var+5+$z];
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
					//var_dump($_GET);
					//2911 elements now!

					echo "<table class='col-xs-offset-3 col-xs-6'>";
					echo "<caption style='text-align : center;'>USD-EUR course table</caption>";
					echo "<tr><th>Date</th><th>Currency</th></tr>";

					$trigger=false;
					for($currentPosition = 0; $currentPosition < sizeof($coursesArray) ; $currentPosition++){
						if($datesArray[$currentPosition] == $_GET['date_2']){
							$trigger = true;
						}else if($datesArray[$currentPosition] == $_GET['date_1']){
							$trigger = false;
						}
						if($trigger == true){
							echo '<tr>';
							echo "<td style='font-size : 20px;' >" . $datesArray[$currentPosition] . "</td><td style='font-size : 20px;'>" . $coursesArray[$currentPosition] . '</td>';
							echo '</tr>';
						}
					}
					echo '</table>';
				}elseif ($_GET['formDoor'][0] == 'EUR-USD') {
					//if we want to add oportunity to change currency, here we have to change our SOURCE link and code a little bit
				}
		
		?>

		</section>
		
		</div>
	<footer >
		<p><strong>Kachailo Dmytro</strong> Copyright &copy; 2016-<?php $today = date("Y"); echo $today?>.</p>
	</footer>

</body>



