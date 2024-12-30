<?php
include 'header.php';
// Define constants for API
define("API_KEY", "b7cd3340a794e5a2f35e3abb820b497f");
define("BASE_URL", "https://api.themoviedb.org/3");
define("IMAGE_BASE_URL", "https://image.tmdb.org/t/p/w500");

// Helper function to fetch search results from the API
function fetchSearchResults($query) {
    $url = BASE_URL . "/search/multi?api_key=" . API_KEY . "&query=" . urlencode($query);
    $response = @file_get_contents($url);
    
    if ($response === FALSE) {
        return null; // Return null if the API request fails
    }

    return json_decode($response, true);
}

// Helper function to generate the card HTML for movies or series
function createCard($item, $type) {
    $detailPage = $type === "movie" ? "movie.php" : "series.php";
    $posterPath = isset($item['poster_path']) ? IMAGE_BASE_URL . $item['poster_path'] : 'assets/images/placeholder.jpg';
    $title = isset($item['title']) ? $item['title'] : (isset($item['name']) ? $item['name'] : 'No title');
    $releaseDate = isset($item['release_date']) ? $item['release_date'] : (isset($item['first_air_date']) ? $item['first_air_date'] : 'Unknown release date');
    $id = $item['id'];
    $rating = isset($item['vote_average']) ? $item['vote_average'] : 'N/A';
    
    return "
       <div class='movie-card'>
            <a href='$detailPage?id=$id'>
                <img src='$posterPath' alt='$title'>
                <h3>$title</h3>
                <p>$releaseDate</p>
                <p><span class='star'>★</span> $rating</p>
            </a>
        </div>
    ";
}

// Check for the query parameter in the URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

if ($query) {
    // Fetch the search results for the query
    $data = fetchSearchResults($query);

    if ($data && isset($data['results'])) {
        // Separate results into movies and series
        $movies = array_filter($data['results'], function($item) {
            return $item['media_type'] === 'movie';
        });
        $series = array_filter($data['results'], function($item) {
            return $item['media_type'] === 'tv';
        });

        // Limit results to the first 8 items
        $movies = array_slice($movies, 0, 8);
        $series = array_slice($series, 0, 8);
    } else {
        $movies = [];
        $series = [];
    }
} else {
    $movies = [];
    $series = [];
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
    <title>Search Results</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    
</head>
<body>
    <header>
        <h2>Search Results</h2>
    </header>

    <section>
        <h3>Results for "<span id="query-term"><?php echo htmlspecialchars($query); ?></span>"</h3>

        <h3>Movies</h3>
        <div id="movie-results" class="grid-layout">
            <?php
            if (count($movies) > 0) {
                foreach ($movies as $movie) {
                    echo createCard($movie, "movie");
                }
            } else {
                echo "<p>No movie results found for \"". htmlspecialchars($query) . "\".</p>";
            }
            ?>
        </div>

        <h3>Series</h3>
        <div id="serie-results" class="grid-layout">
            <?php
            if (count($series) > 0) {
                foreach ($series as $serie) {
                    echo createCard($serie, "serie");
                }
            } else {
                echo "<p>No series results found for \"". htmlspecialchars($query) . "\".</p>";
            }
            ?>
        </div>
    </section>

    <script>
        // Redirect to the respective detailed page for movies or series
        function watchNow(page, id) {
            if (page && id) {
                window.location.href = page + "?id=" + id;
            } else {
                console.error("Invalid page or ID for watchNow:", { page, id });
            }
        }
    </script><?php include('footer.php'); ?>
</body>
</html>
