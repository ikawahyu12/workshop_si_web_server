<?php
$nama = "IKA WAHYUNINGTYAS";
$nim = "E41251028";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Server</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 700px;
            max-width: 100%;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #eeeeee;
            width: 35%;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Informasi Server</h1>

    <table>
        <tr>
            <th>Nama</th>
            <td><?php echo $nama; ?></td>
        </tr>

        <tr>
            <th>NIM</th>
            <td><?php echo $nim; ?></td>
        </tr>

        <tr>
            <th>Waktu Server</th>
            <td><?php echo date("Y-m-d H:i:s"); ?></td>
        </tr>

        <tr>
            <th>Versi PHP</th>
            <td><?php echo phpversion(); ?></td>
        </tr>

        <tr>
            <th>Sistem Operasi Server</th>
            <td><?php echo PHP_OS; ?></td>
        </tr>
    </table>

</div>

</body>
</html>