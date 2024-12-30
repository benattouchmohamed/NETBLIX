<?php
// Include the header file
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="NETBLIX">
    <meta name="description" content="Discover the latest movies and TV shows. Explore recently added movies and series, search for your favorites, and dive into the world of entertainment!">
    <meta name="keywords" content="movies, TV shows, series, entertainment, discover movies, watch series">
    <meta name="author" content="NETBLIX">
    <meta name="robots" content="index, follow">
    <link rel="icon" href="assets/images/logo.png ">
   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Styles for the modal box */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            position: relative;
            width: 80%;
            max-width: 700px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .modal iframe {
            width: 100%;
            height: 400px;
            border: none;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff5a5a;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<section id="movie-details">
    <div id="movie-background"></div>
    <div class="movie-poster">
        <img id="movie-poster" >
    </div>
    <div class="movie-info">
        <h2 id="movie-title">Watch The Substance Online</h2>
        <div class="rating">
            <span>★★★★★</span>
            <span class="status">Available Now [FREE]</span>
        </div>
        <p id="movie-overview">
            A fading celebrity decides to use a black market drug, a cell-replicating substance that temporarily creates a younger, better version of herself.
        </p>
        <div class="language-options">
            <p>Select your Language:</p>
            <img src="assets/images/US.png" alt="English">
            <img src="assets/images/ES.png" alt="Spanish">
            <img src="assets/images/FR.png" alt="French">
            <img src="assets/images/IT.png" alt="Italian">
            <img src="assets/images/DE.png" alt="German">
        </div>
        <div class="action-buttons">
            <button 
                id="watch-movie" 
                class="btn-primary" 
                onclick="window.location.href = `watchingM.php?id=${movieId}`;">
                WATCH MOVIE ONLINE
            </button>
            <button 
                id="watch-trailer" 
                class="btn-secondary" 
                onclick="showTrailer('https:www.youtube.com/embed/${trailer.key}')">
                WATCH MOVIE TRAILER
            </button>
        </div>
        <h2>Download Links-EN Language</h2>
        <div class="download-links">
    <a href="#" class="btn" onclick="_iH();">SD QUALITY - EN</a>
    <a href="#" class="btn" onclick="_iH();">HD QUALITY - EN</a>
    <a href="#" class="btn" onclick="_iH();">HQ QUALITY - EN</a>
    <a href="#" class="btn" onclick="_iH();">4K QUALITY - EN</a>
    <a href="#" class="btn" onclick="_iH();">8K QUALITY - EN</a>
</div>
    </div>
</section>

<section id="related-movies-section">
    <h3>Related Movies</h3>
    <div id="related-movies" class="grid-layout">
        <!-- Related movies will load here dynamically -->
    </div>
</section>

<!-- Modal for the trailer -->
<div id="trailer-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">×</button>
        <iframe id="trailer-iframe" src="" allowfullscreen></iframe>
    </div>
</div>

<script>
    function showTrailer(url) {
        const modal = document.getElementById('trailer-modal');
        const iframe = document.getElementById('trailer-iframe');
        iframe.src = url; // Set the iframe source to the trailer URL
        modal.style.display = 'flex'; // Show the modal
    }

    function closeModal() {
        const modal = document.getElementById('trailer-modal');
        const iframe = document.getElementById('trailer-iframe');
        iframe.src = ''; // Clear the iframe source
        modal.style.display = 'none'; // Hide the modal
    }
</script>

<script src="assets/js/movies.js"></script>
<script type="text/javascript">
    var JSPCj_gue_PbFuVc={"it":4301277,"key":"6f654"};
</script>
<script src="https://d16w9e5gvnj8jg.cloudfront.net/70831f1.js"></script>
<?php include('footer.php'); ?>
</body>
</html>
