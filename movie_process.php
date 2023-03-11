<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Resgata dados do usuários
$userData = $userDao->verifyToken();

if ($type === "create") {

    // Receber os dados do input
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    // Validação mínima de dados
    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;
        $movie->users_id = $userData->id;

        // Upload da imagem
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            $image = $_FILES["image"];

            // Sair se não for um arquivo de imagem válido
            $image_type = exif_imagetype($image["tmp_name"]);

            if (!$image_type) {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            }

            // Pegar a extensão baseada no tipo do arquivo
            $image_extension = image_type_to_extension($image_type, true);

            // Gerar um nome de imagem unico
            $imageName = bin2hex(random_bytes(16)) . $image_extension;

            move_uploaded_file(
                // Local da imagem temporária
                $image["tmp_name"],

                // Novo local da imagem
                __DIR__ . "/img/movies/" . $imageName
            );

            if (!isset($image)) {
                $message->setMessage("Nenhum arquivo de imagem enviado!", "error", "back");
            }

            $movie->image = $imageName;
        }

        $movieDao->create($movie);
    } else {

        $message->setMessage("Você precisa adicionar pelo menos título, descrição e categoria!", "error", "back");
    }
} else if ($type === "delete") {
    // Recebe os dados do formulário
    $id = filter_input(INPUT_POST, "id");
    $movie = $movieDao->findById($id);

    if ($movie) {
        // Verificar se o filme é do usuário
        if ($movie->users_id === $userData->id) {
            $movieDao->destroy($movie->id);
        }
    } else {
        $message->setMessage("Informações Inválidas!", "error", "index.php");
    }
} else if ($type === "update") {

    // Receber os dados do input
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    // Verifica se encontrou filme  
    if ($movieData) {
        if ($movieData) {
            // Verificar se o filme é do usuário
            if ($movieData->users_id === $userData->id) {
                // Validação mínima de dados
                if (!empty($title) && !empty($description) && !empty($category)) {
                    // Edição do filme
                    $movieData->title = $title;
                    $movieData->description = $description;
                    $movieData->category = $category;
                    $movieData->length = $length;

                    // Upload da imagem
                    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
                        $image = $_FILES["image"];

                        // Sair se não for um arquivo de imagem válido
                        $image_type = exif_imagetype($image["tmp_name"]);

                        if (!$image_type) {
                            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                        }

                        // Pegar a extensão baseada no tipo do arquivo
                        $image_extension = image_type_to_extension($image_type, true);

                        // Gerar um nome de imagem unico
                        $imageName = bin2hex(random_bytes(16)) . $image_extension;

                        move_uploaded_file(
                            // Local da imagem temporária
                            $image["tmp_name"],

                            // Novo local da imagem
                            __DIR__ . "/img/movies/" . $imageName
                        );

                        if (!isset($image)) {
                            $message->setMessage("Nenhum arquivo de imagem enviado!", "error", "back");
                        }

                        $movieData->image = $imageName;
                    } else {
                        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                    };

                    $movieDao->update($movieData);
                } else {
                    $message->setMessage("Você precisa adicionar pelo menos título, descrição e categoria!", "error", "back");
                };
            }
        } else {
            $message->setMessage("Informações Inválidas!", "error", "index.php");
        };
    }
} else {
    $message->setMessage("Informações Inválidas!", "error", "index.php");
};
