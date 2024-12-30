const API_KEY = "b7cd3340a794e5a2f35e3abb820b497f";
const BASE_URL = "https://api.themoviedb.org/3";
const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/w500";

// Show the loading spinner
function showLoadingSpinner() {
    document.getElementById("loading-spinner").style.display = "flex";
}

// Hide the loading spinner
function hideLoadingSpinner() {
    document.getElementById("loading-spinner").style.display = "none";
}

// Fetch and display recently added movies
async function fetchRecentlyAddedMovies() {
    showLoadingSpinner();
    try {
        const response = await fetch(`${BASE_URL}/movie/now_playing?api_key=${API_KEY}`);
        const data = await response.json();

        const recentMoviesContainer = document.getElementById("recent-movies");
        recentMoviesContainer.innerHTML = data.results.map(movie => `
            <div class="movie-card">
                <a href="movie.php?id=${movie.id}">
                    <img src="${IMAGE_BASE_URL + movie.poster_path}" alt="${movie.title}">
                    <h3>${movie.title}</h3>
                    <p>Release Year: ${new Date(movie.release_date).getFullYear()}</p>
                    <p>Rating: ${movie.vote_average}/10 <span class="star">★</span></p>

                </a>
            </div>
        `).join("");
    } catch (error) {
        console.error("Error fetching movies:", error);
    } finally {
        hideLoadingSpinner();
    }
}

// Fetch and display recently added series
async function fetchRecentlyAddedSeries() {
    showLoadingSpinner();
    try {
        const response = await fetch(`${BASE_URL}/tv/on_the_air?api_key=${API_KEY}`);
        const data = await response.json();

        const recentSeriesContainer = document.getElementById("recent-series");
        recentSeriesContainer.innerHTML = data.results.map(series => `
            <div class="series-card">
                <a href="series.php?id=${series.id}">
                    <img src="${IMAGE_BASE_URL + series.poster_path}" alt="${series.name}">
                    <h3>${series.name}</h3>
                    <p>First Air Year: ${new Date(series.first_air_date).getFullYear()}</p>
                    <p>Rating: ${series.vote_average}/10 <span class="star">★</span></p>

                </a>
            </div>
        `).join("");
    } catch (error) {
        console.error("Error fetching series:", error);
    } finally {
        hideLoadingSpinner();
    }
}

// Search functionality
function search() {
    const query = document.getElementById("search-input").value;
    if (query) {
        window.location.href = `movies-and-series-results.php?query=${encodeURIComponent(query)}`;
    }
}

// Fetch content on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchRecentlyAddedMovies();
    fetchRecentlyAddedSeries();
});
