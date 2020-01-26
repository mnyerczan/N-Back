<!-- Chart Start -->
<?php

if(isset($result_user[0]['id'])){
	$uid = $result_user[0]['id'];
}
else{
	$uid = $user->id;
}



if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][CHART] ***START***\n", $log_param_1);
$result = Sql_query($sql =  '
	SELECT * FROM (SELECT
			((sum(level) / count(level)) ) AS level,
			CASE
			WHEN sum(correctHit) = 0 or (sum(correctHit) + sum(wrongHit)) = 0
			then 0
			else sum(correctHit) / (sum(correctHit) + sum(wrongHit)) END AS stat,
			substr(timestamp, 1, 10) as date,
			sec_to_time(ceil(SUM(sessionLength) / 1000)) as sessionLength			
		from nbackSessions
		where gameMode ="Position"
			and userID =  '.$uid.'
		group by date
		ORDER BY date DESC
		Limit 20
	) as T order by T.date ASC;');



#
#	Méret változók
#
$chart_size_unit = 4.2;

# Képarányok:
# 4:3
# 3:2
# 16:9
# 1.85:1
# 2.39:1
$chart_width = 185 * $chart_size_unit;
$chart_height = 100 * $chart_size_unit;

$column_font_size = $chart_width / 50;
$skala_font_size = $column_font_size * 0.8;
?>

<style>
	#chart_parent_container{
		width: <?php echo $chart_width * 1.10; ?>px;
		height: <?php echo $chart_height * 1.10; ?>px;
	}
	#chart_child_container{
		width: <?php echo $chart_width; ?>px;
		height: <?php echo $chart_height; ?>px;
	}
	#chart_left_skala_container{
		height: <?php echo $chart_height; ?>px !important;
	}
	#chart_times_container{
		width:<?php echo $chart_width + 2; ?>px;
	}
	.skala_conteiner div{
		right: <?php echo $chart_width / 20 ?>px;
		width: <?php echo $chart_width / 30; ?>px;
		font-size: <?php echo $skala_font_size; ?>px;
	}
	#y_axis_for_chart{
		bottom: <?php echo $chart_height + $chart_height / 3.75; ?>px;
		left: <?php echo $chart_width / 120; ?>px;
	}
	#chart_parent_container div{
		font-size: <?php echo $skala_font_size; ?>px;
	}
	#x_axis_for_chart{
		left: <?php echo $chart_width + $chart_width / 16; ?>px;
	}
</style>

<div id="chart_parent_container">

	<div id="chart_left_skala_container">
		<?php
			if(0 < count($result))
			{
				$i = 0;
				$min = $max = $result[0]['level'] + $result[0]['stat'];

				#az alulról legkisebb és felülről legnagyobb érték között skáláz.

				#min-max kiválasztása
				while($i < count($result))
				{
					if($result[$i]['level'] + $result[$i]['stat'] < $min)
					{
						$min = $result[$i]['level'] + $result[$i]['stat'];
					}
					if($result[$i]['level'] + $result[$i]['stat'] > $max)
					{
						$max = $result[$i]['level'] + $result[$i]['stat'];
					}
					$i++;
				}
				$differences  =  ceil($max) - floor($min) +3; // A legalsó skála alatt 1-el kezdődik a grafikon.


				$skala_unit = $chart_height / $differences;//count($result);

				for($i = ceil($max) + 1; $i >= floor($min) ; $i--){

					if($i == ceil($max) + 1){

						#legelső skála
						echo '<div id="first_skala_conteiner" style="height:',$skala_unit - 1,'px; "></div>';
					}
					else{
						echo '<div class="skala_conteiner" style="height:',$skala_unit - 1,'px; "><div>',$i,'</div></div>';
					}
				}
			}
		?>
	</div>



	<div id="chart_child_container">
		<?php

		if(0 < count($result)){

		#
		# Oszlopdiagramhoz az alábbi width és margin-left használandó
		#	width: '.(($column_container_width / count($result) - ($column_container_width / count($result) / 4)) - 2).'px !important;
		#	margin-left: '.($time_containe_width / count($result) / 4).'px  !important;
		#

		echo
			'<style>
				.column{
					width: ',($chart_width / 25 ) -  $column_font_size / 2,'px;
					margin-left: ', $chart_width / count($result) - $chart_width / 27 ,'px;
					font-size: '.$column_font_size.'px;
					padding-left: ',($chart_width / 25  ) / 2 - $column_font_size / 2,'px;
				}
			</style>';

			for($i=0; $i< count($result); $i++){
			echo '
				<div id="column_'.$i.'" style="height:', (round($result[$i]['level'] + $result[$i]['stat'], 2) - floor($min) + 1) * $skala_unit + $skala_unit - 8,'px;

				margin-top:',$chart_height - ((round($result[$i]['level'] + $result[$i]['stat'], 2) - floor($min) + 1) * $skala_unit + $skala_unit) ,'px" class="column"

				onmouseout="Delet_mark_on_x_axis(time_column_'.$i.', '.round($result[$i]['level'] + $result[$i]['stat'], 2).')"

				onmouseover="Mark_on_x_axis(time_column_'.$i.', '.round($result[$i]['level'] + $result[$i]['stat'], 2).')">          <span class="chart_pointer">&#9670</span>

				<span class="chart_column_text" style="',substr($result[$i]['sessionLength'], 0, 2) >= 20 || strlen($result[$i]['sessionLength']) > 5 ?

				"font-weight: bold" : ($main_theme == "white" ? "color: #bbb" : "color: #555"),'">',
				
				
				/** 	(^,^)
				 * Ha a kapott dátum éve azonos az aktuális évvel, ha a kapott dátum nem régebbi 7 naposnál, írja ki a nap teljes nevét ('l'),
				 * különben írja ki a hótapot és a napot számmal. Ha a kapott dátum nem az aktuális évhez tartozik,
				 * írja ki az egész dátumot.
				 */
				date('Y', strtotime($result[$i]['date'])) == date("Y")	?
						(( $result[$i]['date']) > date('Y-m-d', strtotime('-7 days'))  ?
							date('l', strtotime($result[$i]['date'])) :  date('M d.', strtotime($result[$i]['date'])))
					: date('Y M d.', strtotime($result[$i]['date']))
				,'</span>
				</div>';
			}
		}
		else{
			echo '<p style="padding: 50px;">Statistics appear after first game.</p>';
		}
		
		?>
	</div>


	
	<div style="clear: both" ></div>


	<div id="chart_times_container">
		<?php

		#common.css > .time_column{ width: 400px;}
		$time_containe_width = 400;

		if(0 < count($result)){

			#
			# Oszlopdiagramhoz az alábbi width és margin-left használandó
			#	width: '.(($column_container_width / count($result) - ($column_container_width / count($result) / 4)) - 2).'px !important;
			#	margin-left: '.($time_containe_width / count($result) / 4).'px  !important;
			#

			echo
				'<style>
					.time_column{
						width: '.($chart_width / 25).'px;
						margin: 0;
						margin-left: ', $chart_width / count($result) - $chart_width / 27 ,'px;
						padding: 1px;
						font-size: '.$skala_font_size.'px;
					}
				</style>';

				for($i=0; $i< count($result); $i++)
				{
					
					 $game_time_for_chart = 
					 	(int)substr($result[$i]['sessionLength'], 0, 2) > 0 ?
						(int)substr($result[$i]['sessionLength'], 3, 2) + 60 : 
						(int)substr($result[$i]['sessionLength'], 3, 2);

					$game_time_for_chart +=  (int)substr($result[$i]['sessionLength'], 6, 2) >= 30 ? 1 : 0;

					echo '<div class="time_column" id="time_column_'.$i.'">'.$game_time_for_chart.'</div>';

				}
		}
		?>
	</div>
	<div id="x_axis_for_chart">t(m)</div>
	<div id="y_axis_for_chart">P(<code id="y_axis_value">l</code>)</div>
	<p>
		The chart works by averaging the level of strides for the current day and adding it to the averages of the percentages achieved.
	</p>
</div>



<script>
	function Delet_mark_on_x_axis(e, avg){
		document.getElementById("y_axis_value").innerHTML = "l";
	}
	function Mark_on_x_axis(e, avg){
		document.getElementById("y_axis_value").innerHTML = avg;
	}
</script>

<?php
	if($error_level >0) file_put_contents($logfile, "[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][CHART] ***END***\n", $log_param_1);
?>

<!-- Chart End -->
