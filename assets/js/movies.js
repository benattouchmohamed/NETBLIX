const API_KEY = "b7cd3340a794e5a2f35e3abb820b497f";
const BASE_URL = "https://api.themoviedb.org/3";
const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/w500";

// Extract the movie ID from the URL
const urlParams = new URLSearchParams(window.location.search);
const movieId = urlParams.get("id");

// Fetch movie details
async function fetchMovieDetails(id) {
    try {
        const response = await fetch(`${BASE_URL}/movie/${id}?api_key=${API_KEY}`);
        const data = await response.json();

        if (!data) {
            console.error("No movie data found.");
            return;
        }

        // Populate movie details
        document.getElementById("movie-title").textContent = data.title || "Unknown Title";
        document.getElementById("movie-overview").textContent = data.overview || "No overview available.";
        document.getElementById("movie-poster").src = data.poster_path
            ? IMAGE_BASE_URL + data.poster_path
            : "";

        // Set movie background image
        const backgroundUrl = data.backdrop_path
            ? IMAGE_BASE_URL + data.backdrop_path
            : "";
        document.getElementById("movie-background").style.backgroundImage = `url(${backgroundUrl})`;

        // Update action buttons
        document.getElementById("watch-movie").href = `watching.php?id=${id}`;
        document.getElementById("watch-trailer").href = `https://www.youtube.com/results?search_query=${encodeURIComponent(data.title)}+trailer`;
    } catch (error) {
        console.error("Error fetching movie details:", error);
    }
}

// Fetch related movies
async function fetchRelatedMovies(id) {
    try {
        const response = await fetch(`${BASE_URL}/movie/${id}/similar?api_key=${API_KEY}`);
        const data = await response.json();

        if (!data.results || !data.results.length) {
            document.getElementById("related-movies").innerHTML = `<p>No related movies found.</p>`;
            return;
        }

        const relatedMoviesHTML = data.results
            .filter((movie) => movie.poster_path) // Only show movies with a poster
            .map(
                (movie) => `
                <div class="movie-card" data-movie-id="${movie.id}">
                    <img src="${IMAGE_BASE_URL + movie.poster_path}" alt="${movie.title}">
                    <h3>${movie.title}</h3>
                    <p>Release Date: ${new Date(movie.release_date).getFullYear()}</p>
                    <p>Rating: ${movie.vote_average}/10 <span class="star">★</span></p>
                </div>
            `
            )
            .join("");

        const relatedContainer = document.getElementById("related-movies");
        relatedContainer.innerHTML = relatedMoviesHTML;

        // Add click listeners to related movie cards
        document.querySelectorAll(".movie-card").forEach((movieCard) => {
            movieCard.addEventListener("click", () => {
                const newMovieId = movieCard.getAttribute("data-movie-id");
                handleMovieChange(newMovieId);
            });
        });
    } catch (error) {
        console.error("Error fetching related movies:", error);
        document.getElementById("related-movies").innerHTML = `<p>Error fetching related movies. Please try again later.</p>`;
    }
}

// Handle movie change with animation
function handleMovieChange(newMovieId) {
    // Add fade-out animation and show loader
    const container = document.body;
    container.classList.add("fade-out");

    // Wait for the animation to finish before refreshing
    setTimeout(() => {
        window.location.href = `?id=${newMovieId}`;
    }, 500); // Match the duration of the fade-out animation
}

// Initialize the page
document.addEventListener("DOMContentLoaded", () => {
    if (!movieId) {
        console.error("No movie ID found in the URL.");
        return;
    }

    fetchMovieDetails(movieId);
    fetchRelatedMovies(movieId);

    // Trailer Button Click Handler
    document.getElementById("watch-trailer").addEventListener("click", async (event) => {
        event.preventDefault(); // Prevent default link action
        try {
            // Fetch the trailer data from the API
            const response = await fetch(`${BASE_URL}/movie/${movieId}/videos?api_key=${API_KEY}`);
            if (!response.ok) throw new Error("Failed to fetch trailer data");

            const data = await response.json();
            const trailers = data.results;

            // Find the first trailer with type "Trailer" from the response
            const trailer = trailers.find((t) => t.type === "Trailer" && t.site === "YouTube");
            if (!trailer) throw new Error("No trailer found for this movie");

            // Construct the YouTube URL using the trailer key
            const youtubeUrl = `https://www.youtube.com/embed/${trailer.key}`;

            // Show the modal and set the iframe source
            const modal = document.getElementById("trailer-modal");
            const iframe = document.getElementById("trailer-iframe");
            iframe.src = youtubeUrl;
            modal.style.display = "flex";

            // Add close functionality
            document.getElementById("close-modal").addEventListener("click", () => {
                modal.style.display = "none";
                iframe.src = ""; // Clear iframe when modal is closed
            });
        } catch (error) {
            console.error("Error showing trailer:", error);
        }
    });
});
