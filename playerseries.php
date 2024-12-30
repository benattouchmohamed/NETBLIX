<?php
// Include the header file
include 'header.php';

// Define constants for API
define("API_KEY", "b7cd3340a794e5a2f35e3abb820b497f");
define("BASE_URL", "https://api.themoviedb.org/3");
define("IMAGE_BASE_URL", "https://image.tmdb.org/t/p/w500");

// Helper function to fetch series details
function fetchSeriesDetails($seriesId) {
    $url = BASE_URL . "/tv/$seriesId?api_key=" . API_KEY . "&append_to_response=videos";
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return null; // Return null if the API request fails
    }

    return json_decode($response, true);
}

// Helper function to fetch season details
function fetchSeasonDetails($seriesId) {
    $url = BASE_URL . "/tv/$seriesId?api_key=" . API_KEY;
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return null;
    }

    return json_decode($response, true);
}

// Get series ID from the URL
$seriesId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$seriesId) {
    die("Invalid series ID.");
}

// Fetch series details
$series = fetchSeriesDetails($seriesId);

if (!$series) {
    die("Unable to fetch series details.");
}

// Fetch season details
$seriesDetails = fetchSeasonDetails($seriesId);
$seasons = $seriesDetails['seasons'] ?? [];

// Iframe URLs for servers
$iframeUrls = [
    'vidsrc.me' => 'https://vidsrc.me/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.in' => 'https://vidsrc.in/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.pm' => 'https://vidsrc.pm/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.net' => 'https://vidsrc.net/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.xyz' => 'https://vidsrc.xyz/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.io' => 'https://vidsrc.io/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.vc' => 'https://vidsrc.vc/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidsrc.dev' => 'https://vidsrc.dev/embed/tv/{series_id}/{season_number}/{episode_number}',
    '2embed.org' => 'https://2embed.org/embed/tv/{series_id}/{season_number}/{episode_number}',
    'vidlink.pro' => 'https://vidlink.pro/tv/{series_id}/{season_number}/{episode_number}',
    'embed.su' => 'https://embed.su/embed/tv/{series_id}/{season_number}/{episode_number}',
];
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
    <title><?php echo htmlspecialchars($series['name'] ?? 'Series Details'); ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<style>/* General Reset */

/* General styles for select elements */
select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #f8f8f8;
    cursor: pointer;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease;
}

/* Styles for the select when focused */
select:focus {
    border-color: #0056b3;
    outline: none;
}

/* Add a custom arrow icon */
select::-ms-expand {
    display: none;
}

select:after {
    content: '▼';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

/* Label styling for better readability */
label {
    font-size: 16px;
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

/* Style the dropdown container to make it uniform */
#dynamic-controls {
    max-width: 600px;
    margin: 20px auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    select {
        width: 100%;
    }

    #dynamic-controls {
        padding: 10px;
    }
}






/* Dynamic controls (Season and Episode Selection) */
#dynamic-controls {
    margin-top: 30px;
    text-align: center;
}

#dynamic-controls label {
    font-size: 16px;
    margin-right: 10px;
}

#dynamic-controls select {
    padding: 8px;
    font-size: 16px;
    margin-right: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #fff;
}

#dynamic-controls button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#dynamic-controls button:hover {
    background-color: #218838;
}

/* Server List Container */
.server-list {
    margin-top: 20px;
    text-align: center;
    display: none; /* Hidden by default */
    transform: scale(0.9); /* Initial animation scale */
    opacity: 0; /* Initial transparency */
    transition: transform 0.4s ease, opacity 0.4s ease; /* Smooth animation */
}

.server-list.active {
    display: block; /* Make visible when active */
    transform: scale(1); /* Full size */
    opacity: 1; /* Fully visible */
}

/* Server Buttons */
.server-list button {
    margin: 10px;
    padding: 10px 20px;
    cursor: pointer;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.server-list button:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: scale(1.1); /* Slightly enlarge on hover */
}

/* Loading Indicator */
#loading-indicator {
    display: none; /* Hidden by default */
    margin: 20px auto;
    font-size: 16px;
    color: #007bff;
    font-weight: bold;
    animation: fadeInOut 1.5s ease-in-out infinite; /* Blinking animation */
}

/* Animation for loading indicator */
@keyframes fadeInOut {
    0%, 100% {
        opacity: 0.3;
    }
    50% {
        opacity: 1;
    }
}

/* Iframe Container */
.iframe-container {
    margin-top: 20px;
    text-align: center;
    display: none; /* Hidden by default */
    animation: zoomIn 0.5s ease; /* Zoom-in effect */
}

.iframe-container iframe {
    width: 100%;
    max-width: 900px;
    height: 500px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for a clean look */
}

/* Responsive Design */
@media (max-width: 768px) {
    #series-details {
        flex-direction: column;
        align-items: center;
    }

    .series-poster {
        margin-bottom: 20px;
    }

    .series-info {
        text-align: center;
    }

    .iframe-container iframe {
        height: 300px; /* Smaller height for mobile */
    }

    #dynamic-controls {
        display: block;
    }

    #dynamic-controls select, #dynamic-controls button {
        width: 100%;
        margin-top: 10px;
    }

    .server-list button {
        width: 100%;
        font-size: 14px;
    }
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


/* Server List Container */
.server-list {
    margin-top: 20px;
    text-align: center;
    display: none; /* Hidden by default */
    transform: scale(0.9); /* Initial animation scale */
    opacity: 0; /* Initial transparency */
    transition: transform 0.4s ease, opacity 0.4s ease; /* Smooth animation */
}

.server-list.active {
    display: block; /* Make visible when active */
    transform: scale(1); /* Full size */
    opacity: 1; /* Fully visible */
}

/* Server Buttons */
.server-list button {
    margin: 10px;
    padding: 10px 20px;
    cursor: pointer;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.server-list button:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: scale(1.1); /* Slightly enlarge on hover */
}

/* Loading Indicator */
#loading-indicator {
    display: none; /* Hidden by default */
    margin: 20px auto;
    font-size: 16px;
    color: #007bff;
    font-weight: bold;
    animation: fadeInOut 1.5s ease-in-out infinite; /* Blinking animation */
}

/* Animation for loading indicator */
@keyframes fadeInOut {
    0%, 100% {
        opacity: 0.3;
    }
    50% {
        opacity: 1;
    }
}

/* Iframe Container */
.iframe-container {
    margin-top: 20px;
    text-align: center;
    display: none; /* Hidden by default */
    animation: zoomIn 0.5s ease; /* Zoom-in effect */
}

.iframe-container iframe {
    width: 100%;
    max-width: 900px;
    height: 500px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for a clean look */
}

/* Responsive Design */
@media (max-width: 768px) {
    .server-list button {
        padding: 8px 15px;
        font-size: 14px;
    }

    .iframe-container iframe {
        height: 300px; /* Smaller height for mobile */
    }
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

/* Close Button Styling */

</style>
<body>
<!-- Modal to show trailer -->
<div id="trailer-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" id="close-modal">×</button>
        <iframe id="trailer-iframe" src="" frameborder="0"></iframe>
    </div>
</div>

<!-- Series Information Section -->
<section id="series-details">
    <div class="series-poster">
        <img src="<?php echo IMAGE_BASE_URL . $series['poster_path']; ?>" alt="<?php echo htmlspecialchars($series['name']); ?>">
    </div>
    <div class="series-info">
        <h2><?php echo htmlspecialchars($series['name']); ?></h2>
        <div class="rating">
            <span>★★★★★</span>
            <span class="status">Rating: <?php echo htmlspecialchars($series['vote_average']); ?> / 10</span>
        </div>
        <p><?php echo htmlspecialchars($series['overview']); ?></p>
        <div class="action-buttons">
            <button id="watch-series" class="btn-primary">WATCH EPISODE ONLINE</button>
        </div>

        <!-- Watch Trailer Button -->
        <?php
        if (isset($series['videos']['results']) && count($series['videos']['results']) > 0) {
            $trailer = current(array_filter($series['videos']['results'], function ($video) {
                return $video['type'] === 'Trailer';
            }));
            if ($trailer) {
                echo "<button id='watch-trailer' class='btn-secondary' onclick=\"showTrailer('https://www.youtube.com/embed/{$trailer['key']}')\">WATCH SERIES TRAILER</button>";
            }
        }
        ?>
    </div>
</section>

<!-- Dynamic Season and Episode Selection -->
<div id="dynamic-controls">
    <label for="season-select">Select Season:</label>
    <select id="season-select">
        <option value="" disabled selected>Select Season</option>
        <?php foreach ($seasons as $season) { ?>
            <option value="<?php echo $season['season_number']; ?>">
                <?php echo htmlspecialchars($season['name']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="episode-select">Select Episode:</label>
    <select id="episode-select">
        <option value="" disabled selected>Select Episode</option>
    </select>

    <!-- Select Server Dropdown -->
    <label for="server-select">Select Server:</label>
    <select id="server-select">
        <?php foreach ($iframeUrls as $server => $url) { ?>
            <option value="<?php echo $server; ?>"><?php echo $server; ?></option>
        <?php } ?>
    </select>
</div>

<!-- Iframe Container -->
<!-- Iframe Container -->
<div id="iframe-container" class="iframe-container">
    <iframe id="series-iframe" src="" frameborder="0" allowfullscreen></iframe>
</div>


<script>
// Event listener for season selection change
document.getElementById('season-select').addEventListener('change', function() {
    const seasonNumber = this.value;

    if (!seasonNumber) {
        // If no season is selected, disable episode select and reset its options
        document.getElementById('episode-select').innerHTML = '<option value="" disabled selected>Select Episode</option>';
        return;
    }

    const url = "<?php echo BASE_URL; ?>/tv/<?php echo $seriesId; ?>/season/" + seasonNumber + "?api_key=<?php echo API_KEY; ?>";

    // Fetch episodes for the selected season
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const episodeSelect = document.getElementById('episode-select');
            episodeSelect.innerHTML = "<option value='' disabled selected>Select Episode</option>"; // Reset the dropdown

            if (data && data.episodes) {
                // Populate episodes dropdown
                data.episodes.forEach(episode => {
                    const option = document.createElement('option');
                    option.value = episode.episode_number;
                    option.textContent = "Episode " + episode.episode_number + ": " + episode.name;
                    episodeSelect.appendChild(option);
                });

                // Automatically load the first episode after fetching the episodes
                loadEpisode(data.episodes[0].episode_number);
            } else {
                // Handle the case where there are no episodes for the season
                const option = document.createElement('option');
                option.textContent = "No episodes found";
                episodeSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error("Error fetching episodes:", error);
        });
});

// Event listener for episode selection change
document.getElementById('episode-select').addEventListener('change', function() {
    const episodeNumber = this.value;

    if (episodeNumber) {
        // Load iframe when an episode is selected
        loadEpisode(episodeNumber);
    }
});

// Event listener for server selection change
document.getElementById('server-select').addEventListener('change', function() {
    const server = this.value;
    loadEpisodeUsingServer(server);
});

// Function to load the iframe with the given episode number and selected server
function loadEpisode(episodeNumber) {
    const seasonNumber = document.getElementById('season-select').value;

    if (!seasonNumber || !episodeNumber) {
        return; // Don't load the iframe if no season or episode is selected
    }

    const server = document.getElementById('server-select').value || 'embed.su'; // Default to embed.su if no server selected

    loadEpisodeUsingServer(server, seasonNumber, episodeNumber);
}

// Function to load the iframe using the selected server
function loadEpisodeUsingServer(server, seasonNumber = null, episodeNumber = null) {
    const seriesId = "<?php echo $seriesId; ?>";

    if (!seasonNumber || !episodeNumber) return;

    const iframeUrls = <?php echo json_encode($iframeUrls); ?>;

    let iframeUrl = iframeUrls[server] || iframeUrls['embed.su']; // Fallback to 'embed.su' if no valid server is selected
    iframeUrl = iframeUrl.replace("{series_id}", seriesId)
                         .replace("{season_number}", seasonNumber)
                         .replace("{episode_number}", episodeNumber);

    document.getElementById('series-iframe').src = iframeUrl;
    document.getElementById('iframe-container').style.display = 'block'; // Show the iframe container
}
</script>

<script>
    // Watch Trailer Button Click Event
    document.getElementById('watch-trailer').addEventListener('click', function() {
        const trailerKey = "<?php echo $series['videos']['results'][0]['key'] ?? ''; ?>"; // Get trailer key from API

        if (trailerKey) {
            const iframe = document.getElementById('trailer-iframe');
            iframe.src = "https://www.youtube.com/embed/" + trailerKey;

            // Show the modal
            const modal = document.getElementById('trailer-modal');
            modal.style.display = 'flex';
        }
    });

    // Close Modal
    document.getElementById('close-modal').addEventListener('click', function() {
        const modal = document.getElementById('trailer-modal');
        modal.style.display = 'none';

        // Reset the iframe source to stop the video when the modal is closed
        document.getElementById('trailer-iframe').src = '';
    });
    <!-- Fullscreen Button -->
<button id="fullscreen-button" class="btn-primary">Go Fullscreen</button>

<script>
// Fullscreen Button Functionality
document.getElementById('fullscreen-button').addEventListener('click', function() {
    const iframe = document.getElementById('series-iframe');

    // Request fullscreen mode for the iframe
    if (iframe.requestFullscreen) {
        iframe.requestFullscreen();
    } else if (iframe.mozRequestFullScreen) { // Firefox
        iframe.mozRequestFullScreen();
    } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari and Opera
        iframe.webkitRequestFullscreen();
    } else if (iframe.msRequestFullscreen) { // IE/Edge
        iframe.msRequestFullscreen();
    }
});
</script>

</script><?php include('footer.php'); ?>
</body>
</html>
