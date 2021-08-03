<?php
/*
 * Esse arquivo contém todas as rotinas necessárias para:
 * - Estabelecer uma conexão com o banco de dados;
 * - Atribuir uma id para a SESSION do usuário;
 */

// Esta função estabelece uma conexão com o bando de dados;
function bdcon() {

	// Create connection
	$conn = mysqli_connect('localhost', 'root', '', 'u216684008_mde');

	// Check connection
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

	mysqli_set_charset($conn, 'utf8');
	return $conn;
}
?>