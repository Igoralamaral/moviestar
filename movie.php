<?php
require_once("templates/header.php");

// Verifica se o usuário está autenticado
require_once("models/Movie.php");
require_once("dao/MovieDAO.php");

// Pegar id do filme
$id = filter_input(INPUT_GET, "id");

$movie;

$movieDao = new MovieDao($conn, $BASE_URL);

if (empty($id)) {
    $message->setMessage("O filme não foi encontrado", "error", "index.php");
} else {
    $movie = $movieDao->findById($id);

    // Verificar se o filme existe
    if (!$movie) {
        $message->setMessage("O filme não foi encontrado", "error", "index.php");
    }
}

// Checar se o filme é do próprio usuário
$userOwnsMovie = false;

if (!empty($userData)) {
    if ($userData->id === $movie->users_id) {
        $userOwnsMovie = true;
    }
}

// Resgatar as reviews do filme
