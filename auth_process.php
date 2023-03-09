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

// Verificação do tipo do formulário
if ($type === "register") {

  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  // Verificação de dados mínimos
  if ($name && $lastname && $email && $password) {
    // Verificar se as senhas são iguais
    if ($password === $confirmpassword) {

      // Verificar se o e-mail está cadastrado no sistema
      if ($userDao->findByEmail($email) === false) {

        $user = new User();

        // Criação de token e senha
        $userToken = $user->generateToken();
        $finalPassword = $user->generatePassword($password);

        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = $finalPassword;
        $user->token = $userToken;

        $auth = true;

        $userDao->create($user, $auth);
      } else {
        // Enviar mensagem caso as senhas não sejam iguais
        $message->setMessage("Usuário já cadastrado, tente outro e-mail", "error", "back");
      }
    } else {
      // Enviar mensagem caso as senhas não sejam iguais
      $message->setMessage("As senhas não são iguais.", "error", "back");
    }
  } else {
    // Enviar mensagem de erro para dados faltantes
    $message->setMessage("Por favor preencha todos os campos", "error", "back");
  }
} else if ($type === "login") {

  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");

  // Autenticação de usuário
  if ($userDao->authenticateUser($email, $password)) {

    // Enviar mensagem de sucesso de autenticação
    $message->setMessage("Seja bem vindo!", "sucess", "editprofile.php");

    // Redirecionar usuário caso não esteja autenticado
  } else {

    // Enviar mensagem de erro para dados incorretos
    $message->setMessage("Usuário e/ou senha incorretos", "error", "back");
  }
} else {

  // Dados não registrados
  $message->setMessage("Informações inválidas", "error", "index.php");
}
