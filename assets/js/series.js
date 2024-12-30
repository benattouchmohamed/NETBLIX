const API_KEY = "b7cd3340a794e5a2f35e3abb820b497f";
const BASE_URL = "https://api.themoviedb.org/3";
const IMAGE_BASE_URL = "https://image.tmdb.org/t/p/w500";

// Extract the series ID from the URL
const urlParams = new URLSearchParams(window.location.search);
const seriesId = urlParams.get("id");

// Fetch series details
async function fetchSeriesDetails(id) {
    try {
        const response = await fetch(`${BASE_URL}/tv/${id}?api_key=${API_KEY}`);
        const data = await response.json();

        if (!data) {
            console.error("No series data found.");
            return;
        }

        // Populate series details
        document.getElementById("series-title").textContent = data.name || "Unknown Title";
        document.getElementById("series-overview").textContent = data.overview || "No overview available.";
        document.getElementById("series-poster").src = data.poster_path
            ? IMAGE_BASE_URL + data.poster_path
            : "";

        // Set series background image
        const backgroundUrl = data.backdrop_path
            ? IMAGE_BASE_URL + data.backdrop_path
            : "";
        document.getElementById("series-background").style.backgroundImage = `url(${backgroundUrl})`;

        // Update action buttons
        document.getElementById("watch-series").href = `#`; // You can add a series watch link here if needed
        document.getElementById("watch-trailer").href = `https://www.youtube.com/results?search_query=${encodeURIComponent(data.name)}+trailer`;
    } catch (error) {
        console.error("Error fetching series details:", error);
    }
}

// Fetch related series
async function fetchRelatedSeries(id) {
    try {
        const response = await fetch(`${BASE_URL}/tv/${id}/similar?api_key=${API_KEY}`);
        const data = await response.json();

        if (!data.results.length) {
            document.getElementById("related-series").innerHTML = `<p>No related series found.</p>`;
            return;
        }

        const relatedSeriesHTML = data.results
            .filter((series) => series.poster_path) // Only show series with a poster
            .map(
                (series) => `
                <div class="series-card" data-series-id="${series.id}">
                    <img src="${IMAGE_BASE_URL + series.poster_path}" alt="${series.name}">
                    <h3>${series.name}</h3>
                    <p>First Air Date: ${new Date(series.first_air_date).getFullYear()}</p>
                    <p>Rating: ${series.vote_average}/10 <span class="star">★</span></p>
                </div>
            `
            )
            .join("");

        const relatedContainer = document.getElementById("related-series");
        relatedContainer.innerHTML = relatedSeriesHTML;

        // Add click listeners to related series cards
        document.querySelectorAll(".series-card").forEach((seriesCard) => {
            seriesCard.addEventListener("click", () => {
                const newSeriesId = seriesCard.getAttribute("data-series-id");
                fetchSeriesDetails(newSeriesId);
                fetchRelatedSeries(newSeriesId);
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        });
    } catch (error) {
        console.error("Error fetching related series:", error);
    }
}

// Show trailer in modal
async function showTrailer() {
    try {
        const response = await fetch(`${BASE_URL}/tv/${seriesId}/videos?api_key=${API_KEY}`);
        if (!response.ok) throw new Error("Failed to fetch trailer data");

        const data = await response.json();
        const trailers = data.results;

        const trailer = trailers.find((t) => t.type === "Trailer" && t.site === "YouTube");
        if (!trailer) throw new Error("No trailer found for this series");

        const youtubeUrl = `https://www.youtube.com/embed/${trailer.key}`;

        // Show modal and set iframe source
        const modal = document.getElementById("trailer-modal");
        const iframe = document.getElementById("trailer-iframe");
        iframe.src = youtubeUrl;
        modal.style.display = "flex";

        // Close modal
        document.getElementById("close-modal").addEventListener("click", () => {
            modal.style.display = "none";
            iframe.src = ""; // Clear iframe when modal is closed
        });
    } catch (error) {
        console.error("Error showing trailer:", error);
    }
}

// Initialize the page
document.addEventListener("DOMContentLoaded", () => {
    if (!seriesId) {
        console.error("No series ID found in the URL.");
        return;
    }

    fetchSeriesDetails(seriesId);
    fetchRelatedSeries(seriesId);

    // Trailer Button Click Handler
    document.getElementById("watch-trailer").addEventListener("click", (event) => {
        event.preventDefault(); // Prevent default link action
        showTrailer();
    });
});
