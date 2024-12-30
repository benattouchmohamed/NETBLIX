<?php
// Include the header file

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Movie & Series Hub">
    <meta name="description" content="Discover the latest movies and TV shows. Explore recently added movies and series, search for your favorites, and dive into the world of entertainment!">
    <meta name="keywords" content="movies, TV shows, series, entertainment, discover movies, watch series">
    <meta name="author" content="Your Website Name">
    <meta name="robots" content="index, follow">
    <link rel="icon" href="assets/images/logo.png ">
    <title>Movie & Series Hub</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<section class="search-section">
    <h2 class="search-title">Discover Movies & TV Shows</h2>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for Movies & TV Shows">
        <button id="search-button" onclick="search()">Search</button>
    </div>
</section>

<section>
    <h2>Recently Added Movies</h2>
    <div id="recent-movies" class="grid-layout container-fluid">
        <!-- Recently added movies will load here dynamically -->
    </div>
</section>

<section>
    <h2>Recently Added Series</h2>
    <div id="recent-series" class="grid-layout container-fluid">
        <!-- Recently added series will load here dynamically -->
    </div>
</section>

<div id="loading-spinner" class="loading-spinner" style="display: none;">
    <div class="spinner"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/successfully.js"></script><?php include('footer.php'); ?>
</body>
</html>
