  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">
  
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.0', {'packages':['corechart']});
    
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
    
    // Callback that creates and populates a data table, 
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

    // Create the data table.
    var data = new google.visualization.DataTable();
        data.addColumn('string', 'date');
        data.addColumn('number', 'Intcero');
        <?php
					$table = 'ce_camino_solar_'.$_REQUEST['tid'].'t';
					extract(mysql_fetch_array(mysql_query("SELECT COUNT(*) AS cuantos FROM ".$table."")));					
				?>
        data.addRows(<?php echo $cuantos;?>);
				<?php
					$row = mysql_query("SELECT tiempo AS fecha, intcero FROM ".$table."");			
					$i=0;
					while($linea = mysql_fetch_array($row)){
						$date = explode(" ", $linea['fecha']);
						echo "data.setValue(".$i.", 0, '".$date[0]."');"."\r";
        		echo "data.setValue(".$i.", 1, ".$linea['intcero'].");"."\r";
						$i++;
					}
				?>
				
				
    // Set chart options
    var options = {
									 'title':'Camino Solar',
                   'width':940,
                   'height':500,
									 'backgroundColor':'#FFF',
									 'pointSize': 2
									};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.LineChart(document.getElementById('grafica'));
    chart.draw(data, options);
  }
  </script>
  <div><a href="index.php?mod=4">Regresar</a></div>
  <div id="grafica"></div>