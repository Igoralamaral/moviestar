<?php
require_once("templates/header.php");
require_once("dao/MovieDAO.php");

// DAO dos filmes
$movieDao = new MovieDAO($conn, $BASE_URL);

$latestMovies = $movieDao->getLatestMovies();
$actionMovies = $movieDao->getMoviesByCategory("Ação");
$comedyMovies = $movieDao->getMoviesByCategory("Comédia");
$dramaticMovies = $movieDao->getMoviesByCategory("Drama");
$fantasyMovies = $movieDao->getMoviesByCategory("Fantasia/Ficção");
$romanceMovies = $movieDao->getMoviesByCategory("Romance");
?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title">Filmes novos</h2>
    <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar</p>
    <div class="movies-container">
        <?php foreach ($latestMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($latestMovies) === 0) : ?>
            <p class="empty-list">ainda não há filmes cadastrados!</p>
        <?php endif; ?>
    </div>
    <h2 class="section-title">Ação</h2>
    <p class="section-description">Veja os melhores filmes de ação</p>
    <div class="movies-container">
        <?php foreach ($actionMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($actionMovies) === 0) : ?>
            <p class="empty-list">Não há filmes de ação cadastrados!</p>
        <?php endif; ?>
    </div>
    <h2 class="section-title">Comédia</h2>
    <p class="section-description">Veja os melhores filmes de comédia</p>
    <div class="movies-container">
        <?php foreach ($comedyMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($comedyMovies) === 0) : ?>
            <p class="empty-list">Não há filmes de comédia cadastrados!</p>
        <?php endif; ?>
    </div>
    <h2 class="section-title">Drama</h2>
    <p class="section-description">Veja os melhores filmes de comédia</p>
    <div class="movies-container">
        <?php foreach ($dramaticMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($dramaticMovies) === 0) : ?>
            <p class="empty-list">Não há filmes de comédia cadastrados!</p>
        <?php endif; ?>
    </div>
    <h2 class="section-title">Fantasia e Ficção</h2>
    <p class="section-description">Veja os melhores filmes de comédia</p>
    <div class="movies-container">
        <?php foreach ($fantasyMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($fantasyMovies) === 0) : ?>
            <p class="empty-list">Não há filmes de comédia cadastrados!</p>
        <?php endif; ?>
    </div>
    <h2 class="section-title">Romance</h2>
    <p class="section-description">Veja os melhores filmes de comédia</p>
    <div class="movies-container">
        <?php foreach ($romanceMovies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($romanceMovies) === 0) : ?>
            <p class="empty-list">Não há filmes de comédia cadastrados!</p>
        <?php endif; ?>
    </div>
</div>

<?php
require_once("templates/footer.php");
?>