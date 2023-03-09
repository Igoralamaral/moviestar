<?php
require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Atualizar usuário
if ($type === "update") {
  // Resgata dados do usuários
  $userData = $userDao->verifyToken();

  // Receber dados do post
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");

  // Criar um novo objeto usuário
  $user = new User();

  // Preencher dados do usuário
  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;

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
      __DIR__ . "/img/users/" . $imageName
    );

    if (!isset($image)) {
      $message->setMessage("Nenhum arquivo de imagem enviado!", "error", "back");
    }

    $userData->image = $imageName;
  }

  $userDao->update($userData);

  // Atualizar senha do usuário
} else if ($type === "changepassword") {

  // Receber dados do input
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  // Resgata dados do usuário
  $userData = $userDao->verifyToken();

  $id = $userData->id;

  if ($password === $confirmpassword) {

    $user = new User();

    $finalPassword = $user->generatePassword($password);

    $user->password = $finalPassword;
    $user->id = $id;

    $userDao->changePassword($user);
  } else {
    $message->setMessage("As senhas não são iguais!", "error", "back");
  }
} else {
  $message->setMessage("Informações Inválidas!", "error", "index.php");
}
