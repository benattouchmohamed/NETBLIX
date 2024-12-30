<?php
// Include the header file
include 'header.php';

// Define constants for API
define("API_KEY", "b7cd3340a794e5a2f35e3abb820b497f");
define("BASE_URL", "https://api.themoviedb.org/3");
define("IMAGE_BASE_URL", "https://image.tmdb.org/t/p/w500");

// Helper function to fetch movie details
function fetchMovieDetails($movieId) {
    $url = BASE_URL . "/movie/$movieId?api_key=" . API_KEY . "&append_to_response=videos";
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return null; // Return null if the API request fails
    }

    return json_decode($response, true);
}

// Get movie ID from the URL
$movieId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$movieId) {
    die("Invalid movie ID.");
}

// Fetch movie details
$movie = fetchMovieDetails($movieId);

if (!$movie) {
    die("Unable to fetch movie details.");
}
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
    <title><?php echo htmlspecialchars($movie['title'] ?? 'Movie Details'); ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
       /* Server List (Initially Hidden) */
.server-list {
    margin-top: 20px;
    display: none; /* Hidden by default */
    transform: scale(0.8); /* Start slightly smaller */
    opacity: 0; /* Fully transparent */
    transition: transform 0.4s ease, opacity 0.4s ease; /* Smooth transitions */
}

/* Active State (When Shown) */
.server-list.active {
    display: block; /* Make it visible */
    transform: scale(1); /* Scale to normal size */
    opacity: 1; /* Fully visible */
}

/* Server List */
.server-list {
    margin-top: 20px;
    display: none;
    transform: scale(0);
    opacity: 0;
    transition: transform 0.3s ease, opacity 0.3s ease;
    text-align: center;
}

.server-list.active {
    display: block;
    transform: scale(1);
    opacity: 1;
}

.server-list button {
    margin: 5px;
    padding: 10px 15px;
    cursor: pointer;
    border: none;
    background: #007bff;
    color: white;
    border-radius: 5px;
    transition: background 0.3s ease, transform 0.2s ease;
}

.server-list button:hover {
    background: #0056b3;
    transform: scale(1.1);
}

/* Iframe Container */
.iframe-container {
    margin-top: 20px;
    display: none;
    animation: zoomIn 0.5s ease;
    text-align: center;
}

.iframe-container iframe {
    width: 100%;
    max-width: 800px;
    height: 500px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Loading Indicator */
#loading-indicator {
    display: none;
    margin: 20px 0;
    color: #007bff;
    font-size: 16px;
    font-weight: bold;
    animation: fadeInOut 1.5s ease-in-out infinite;
}

/* Trailer Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    max-width: 800px;
    width: 90%;
    position: relative;
    text-align: center;
}

.modal iframe {
    width: 100%;
    height: 450px;
    border-radius: 10px;
}

.modal-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff0000;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    font-size: 18px;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: #cc0000;
}

/* Animations */
@keyframes zoomIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeInOut {
    0%, 100% {
        opacity: 0.3;
    }
    50% {
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .movie-poster img {
        width: 100%;
    }

    .movie-info {
        text-align: center;
    }

    .iframe-container iframe {
        height: 300px;
    }

    .modal iframe {
        height: 300px;
    }
}
    </style>
</head>
<body>
<section id="movie-details">
    <div class="movie-poster">
        <img id="movie-poster" src="<?php echo IMAGE_BASE_URL . $movie['poster_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
    </div>
    <div class="movie-info">
        <h2 id="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h2>
        <div class="rating">
            <span>★★★★★</span>
            <span class="status">Rating: <?php echo htmlspecialchars($movie['vote_average']); ?> / 10</span>
        </div>
        <p id="movie-overview"><?php echo htmlspecialchars($movie['overview']); ?></p>
        <div class="action-buttons">
            <button id="watch-movie" class="btn-primary">WATCH MOVIE ONLINE</button>
            <?php
            if (isset($movie['videos']['results']) && count($movie['videos']['results']) > 0) {
                $trailer = current(array_filter($movie['videos']['results'], function ($video) {
                    return $video['type'] === 'Trailer';
                }));
                if ($trailer) {
                    echo "<button id='watch-trailer' class='btn-secondary' onclick=\"showTrailer('https://www.youtube.com/embed/{$trailer['key']}')\">WATCH MOVIE TRAILER</button>";
                }
            }
            ?>
        </div>
    </div>
</section>

<div id="server-list" class="server-list">
    <h3>Select a Server</h3>
    <?php
  $iframeUrls = [
    'vidsrc.me' => 'https://vidsrc.me/embed/movie/',
    'vidsrc.in' => 'https://vidsrc.in/embed/movie/',
    'vidsrc.pm' => 'https://vidsrc.pm/embed/movie/',
    'vidsrc.net' => 'https://vidsrc.net/embed/movie/',
    'vidsrc.xyz' => 'https://vidsrc.xyz/embed/movie/',
    'vidsrc.io' => 'https://vidsrc.io/embed/movie/',
    'vidsrc.vc' => 'https://vidsrc.vc/embed/movie/',
    'vidsrc.dev' => 'https://vidsrc.dev/embed/movie/',
    '2embed.org' => 'https://2embed.org/embed/movie/',
    'vidlink.pro' => 'https://vidlink.pro/movie/',
    'embed.su' => 'https://embed.su/embed/movie/',
];
    foreach ($iframeUrls as $name => $url) {
        echo "<button onclick=\"loadIframe('$url$movieId')\">$name</button>";
    }
    ?>
</div>

<div id="iframe-container" class="iframe-container">
    <div id="loading-indicator">
        <p>Loading, please wait...</p>
    </div>
    <iframe id="movie-iframe" src="" allowfullscreen></iframe>
</div>

<!-- Modal for the trailer -->
<div id="trailer-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">×</button>
        <iframe id="trailer-iframe" src="" allowfullscreen></iframe>
    </div>
</div>

<script>
    document.getElementById('watch-movie').addEventListener('click', function() {
        loadIframe('https://embed.su/embed/movie/<?php echo $movieId; ?>');
        const serverList = document.getElementById('server-list');
        serverList.classList.add('active');
    });
    document.getElementById('watch-movie').addEventListener('click', function () {
    const serverList = document.getElementById('server-list');
    serverList.classList.add('active'); // Add the active class to show with animation
});

    function loadIframe(url) {
        const iframeContainer = document.getElementById('iframe-container');
        const iframe = document.getElementById('movie-iframe');
        const loadingIndicator = document.getElementById('loading-indicator');

        loadingIndicator.style.display = 'block';
        iframe.style.display = 'none';

        iframe.src = url;
        iframe.onload = function() {
            loadingIndicator.style.display = 'none';
            iframe.style.display = 'block';
        };

        iframeContainer.style.display = 'block';
    }

    function showTrailer(url) {
        const modal = document.getElementById('trailer-modal');
        const iframe = document.getElementById('trailer-iframe');
        iframe.src = url;
        modal.style.display = 'flex';
    }

    function closeModal() {
        const modal = document.getElementById('trailer-modal');
        const iframe = document.getElementById('trailer-iframe');
        iframe.src = '';
        modal.style.display = 'none';
    }
</script>
<?php include('footer.php'); ?>
</body>
</html>
