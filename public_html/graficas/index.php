<html>
<head>
<style>
	body{background:#FFF;}
</style>
  <!--Load the AJAX API-->
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
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Cumulativo de CFE');
        data.addColumn('number', 'Cumulativo para simular');
        data.addRows(13);
				data.setValue(0, 0, 'Dic');
        data.setValue(0, 1, 0);
        data.setValue(0, 2, 806.50);
        data.setValue(1, 0, 'Ene');
        data.setValue(1, 1, 1613.00);
        data.setValue(1, 2, 1613.00);
        data.setValue(2, 0, 'Feb');
        data.setValue(2, 1, 1613.00);
        data.setValue(2, 2, 2400.25);
        data.setValue(3, 0, 'Mar');
        data.setValue(3, 1, 3187.50);
        data.setValue(3, 2, 3187.50);
        data.setValue(4, 0, 'Abr');
        data.setValue(4, 1, 3187.50);
        data.setValue(4, 2, 4134.75);
   		  data.setValue(5, 0, 'May');
        data.setValue(5, 1, 5082.00);
        data.setValue(5, 2, 5082.00);
 			  data.setValue(6, 0, 'Jun');
        data.setValue(6, 1, 5082.00);
        data.setValue(6, 2, 6083.00);
				data.setValue(7, 0, 'Jul');
        data.setValue(7, 1, 7084.00);
        data.setValue(7, 2, 7084.00);
				data.setValue(8, 0, 'Ago');
        data.setValue(8, 1, 7084.00);
        data.setValue(8, 2, 8191.00);
				data.setValue(9, 0, 'Sep');
        data.setValue(9, 1, 9298.00);
        data.setValue(9, 2, 9298.00);
				data.setValue(10, 0, 'Oct');
        data.setValue(10, 1, 9298.00);
        data.setValue(10, 2, 10275.25);
				data.setValue(11, 0, 'Nov');
        data.setValue(11, 1, 11253.00);
        data.setValue(11, 2, 11253.00);
				data.setValue(12, 0, 'Dic');
        data.setValue(12, 1, 11253.00);
        data.setValue(12, 2, 12059.50);			


    // Set chart options
    var options = {'title':'Consumo Cumulativo',
                   'width':820,
                   'height':500,
									 'backgroundColor':'#FFF',
									 'pointSize': 7
									};

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.LineChart(document.getElementById('grafica'));
    chart.draw(data, options);
  }
  </script>
</head>

<body>
  <div id="grafica"></div>
</body>
</html>