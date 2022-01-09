<?php
    echo "Testando conexao com o BANCO DE DADOS <br /> <br />";
    $servername = "192.168.1.22";
    $username = "phpuser";
    $password = "pass";

    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error){
        die("Falha na conexao: " . $conn->connect_error);
    }
    echo "Conectado com sucesso";
?>