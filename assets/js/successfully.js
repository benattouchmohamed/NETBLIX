const API_KEY = "b7cd3340a794e5a2f35e3abb820b497f";
const BASE_URL = "https://api.themoviedb.org/3";
const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/w500";

// Helper function to format dates safely
function formatDate(dateString) {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.getFullYear();
}

// Helper function to render movie/series cards
function renderCards(items, type) {
    return items
        .filter((item) => item.poster_path) // Only show items with a poster
        .map(
            (item) => `
            <div class="movie-card" data-movie-id="${item.id}" onclick="goToPlayer('${type}', ${item.id})">
                <img src="${IMAGE_BASE_URL + item.poster_path}" alt="${item.title || item.name}">
                <h3>${item.title || item.name}</h3>
                <p>${type === "movie" ? "Release Date" : "First Air Date"}: ${formatDate(item.release_date || item.first_air_date)}</p>
                <p>Rating: ${item.vote_average || "N/A"}/10 <span class="star">★</span></p>
            </div>
        `
        )
        .join("");
}

// Function to fetch and display recently added movies
function fetchRecentlyAddedMovies() {
    const url = `${BASE_URL}/movie/now_playing?api_key=${API_KEY}&language=en-US&page=1`;

    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            const moviesHTML = renderCards(data.results, "movie");
            document.getElementById("recent-movies").innerHTML = moviesHTML || "<p>No movies found.</p>";
        })
        .catch((error) => {
            console.error("Error fetching movies:", error);
            document.getElementById("recent-movies").innerHTML = "<p>Error fetching movies.</p>";
        });
}

// Function to fetch and display recently added series
function fetchRecentlyAddedSeries() {
    const url = `${BASE_URL}/tv/on_the_air?api_key=${API_KEY}&language=en-US&page=1`;

    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            const seriesHTML = renderCards(data.results, "series");
            document.getElementById("recent-series").innerHTML = seriesHTML || "<p>No series found.</p>";
        })
        .catch((error) => {
            console.error("Error fetching series:", error);
            document.getElementById("recent-series").innerHTML = "<p>Error fetching series.</p>";
        });
}

// Function to handle search
function search() {
    const query = document.getElementById("search-input").value.trim();

    if (!query) {
        alert("Please enter a search term.");
        return;
    }

    // Redirect to successfullycherch.php with the search query as a parameter
    window.location.href = `successfullycherch.php?query=${encodeURIComponent(query)}`;
}

// Function to navigate to the appropriate player page
function goToPlayer(type, id) {
    // Check the type and redirect to the correct page (playermovie.php for movies, playerseries.php for series)
    if (type === "movie") {
        window.location.href = `playermovie.php?type=${type}&id=${id}`;
    } else if (type === "series") {
        window.location.href = `playerseries.php?type=${type}&id=${id}`;
    }
}

// Fetch recently added movies and series on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchRecentlyAddedMovies();
    fetchRecentlyAddedSeries();
});
