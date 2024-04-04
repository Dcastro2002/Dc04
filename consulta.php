<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ventas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener datos
$sql = "SELECT producto, SUM(cantidad) AS cantidad_total FROM ventas GROUP BY producto";
$result = $conn->query($sql);

// Crear un array para almacenar los datos
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        #chart_div {
            width: 100%;
            height: 600px;
            margin-top: 30px;
        }
    </style>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Producto');
            data.addColumn('number', 'Cantidad Total');
            data.addRows([
                <?php
                foreach ($data as $row) {
                    echo "['" . $row["producto"] . "', " . $row["cantidad_total"] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Cantidad Total de Ventas por Producto',
                width: '100%',
                height: 500,
                chartArea: {width: '80%', height: '70%'},
                legend: {position: 'bottom'},
                hAxis: {
                    title: 'Producto',
                    titleTextStyle: {color: '#333'},
                    textStyle: {fontSize: 12}
                },
                vAxis: {
                    title: 'Cantidad Total',
                    titleTextStyle: {color: '#333'},
                    minValue: 0,
                    textStyle: {fontSize: 12}
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Reporte de Ventas por Producto</h1>
        <div id="chart_div"></div>
    </div>
</body>
</html>
