<?php 
// Include the shared header file
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
   
    <link rel="stylesheet" href="assets/css/watchingM.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Movie or Series</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Impact&display=swap">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .related-movie-title {
    font-size: 1rem;
    margin-top: 10px;
    color: #fff; /* White color */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Subtle shadow */
    font-family: 'Impact', sans-serif;
    text-align: center;
}

.related-movie-title:hover {
    color: #ff5733; /* Hover effect for the title */
    transform: scale(1.05); /* Slight zoom on hover */
}

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            text-align: center;
            position: relative;
        }

        /* Video Player Styling */
        #movie-video {
            width: 100%;
            max-width: 1000px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
            margin-bottom: 20px;
        }

        /* Play Icon Styling */
        #play-icon {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.8);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;
            display: none;
            z-index: 10;
        }

        /* Heading Styling */
        #dynamic-title {
            font-size: 2rem;
            margin: 20px 0;
            color: #e5e5e5;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        /* Related Movies Section Styling */
        #related-movies-section {
            margin: 30px auto;
            text-align: center;
        }

        #related-movies-section h4 {
    font-size: 1.8rem;
    color: #ff0000;
    margin-bottom: 20px;
    font-family: 'Impact', sans-serif; /* Set Impact font */
    transition: color 0.3s ease, transform 0.3s ease; /* Smooth transition for hover effect */
}

/* Hover effect for h4 */
#related-movies-section h4:hover {
    color: #e60000; /* Slightly darker red on hover */
    transform: scale(1.1); /* Enlarge the text on hover */
}


        /* Grid Layout for Related Movies */
        .grid-layout {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px;
        }

        .grid-layout img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }

        .grid-layout img:hover {
            transform: scale(1.05);
        }

        /* Mobile Responsive Styling */
        @media (max-width: 768px) {
            #dynamic-title {
                font-size: 1.5rem;
            }

            #play-icon {
                font-size: 3rem;
            }

            /* Adjust grid layout for mobile (2 items per row) */
            .grid-layout {
                grid-template-columns: repeat(2, 1fr); /* Show two cards per row */
            }
        }
        .related-movie-container {
    text-align: center;
    max-width: 200px;
    margin: 10px;
}

.related-movie-container img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
    cursor: pointer;
}

.related-movie-title {
    font-size: 1rem;
    margin-top: 10px;
    color: #fff;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
}

    </style>
</head>

<body>


    <div class="container">
        <!-- Play Icon -->
        <i id="play-icon" class="fas fa-play-circle"></i>

        <!-- Video Player -->
        <video 
            id="movie-video" 
            controls 
            controlsList="nodownload noremoteplayback" 
            poster="https://www.themoviedb.org/t/p/w1920_and_h800_multi_faces/your-backdrop-path.jpg">
            <source src="https://cdn.jsdelivr.net/gh/iDevMore/tvs-vd1/nfx.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>

        <!-- Dynamic Title -->
        <h2 id="dynamic-title">Enjoy Watching!</h2>
    </div>

    <!-- Related Movies Section -->
    <section id="related-movies-section">
        <h4>Related Movies</h4>
        <div id="related-movies" class="grid-layout">
            <!-- Related movies will load here dynamically -->
        </div>
    </section>

    <script>
        const videoElement = document.getElementById("movie-video");
        const playIcon = document.getElementById("play-icon");
        const titleElement = document.getElementById("dynamic-title");
        const relatedMoviesContainer = document.getElementById("related-movies");

        const API_KEY = "b7cd3340a794e5a2f35e3abb820b497f";
        const BASE_URL = "https://api.themoviedb.org/3";
        const urlParams = new URLSearchParams(window.location.search);
        const contentId = urlParams.get("id"); // Get Movie ID from URL

        // Show play icon when the video is paused
        videoElement.addEventListener("pause", () => {
            playIcon.style.display = "block";
        });

        // Hide play icon when the video plays
        videoElement.addEventListener("play", () => {
            playIcon.style.display = "none";

            // Call _iH() after 5 seconds of video play
            setTimeout(() => {
                if (typeof _iH === "function") {
                    _iH(); // Trigger custom logic
                }
            }, 5000);
        });

        // Handle play icon click
        playIcon.addEventListener("click", () => {
            videoElement.play();
        });

        // Define the custom _iH function
        function _iH() {
            console.log("_iH() function triggered after 5 seconds of video play.");
            // Add custom logic or external integration
        }

        // Fetch movie details dynamically
        if (contentId) {
            fetch(`${BASE_URL}/movie/${contentId}?api_key=${API_KEY}`)
                .then(response => response.json())
                .then(data => {
                    const backdropPath = data.backdrop_path 
                        ? `https://www.themoviedb.org/t/p/w1920_and_h800_multi_faces${data.backdrop_path}` 
                        : '';
                    const title = data.title || "Unknown Title";

                    // Update video poster and title
                    if (backdropPath) {
                        videoElement.setAttribute("poster", backdropPath);
                    }
                    if (title) {
                        titleElement.textContent = `Enjoy Watching: ${title}`;
                    }

// Fetch related movies
fetch(`${BASE_URL}/movie/${contentId}/similar?api_key=${API_KEY}`)
    .then(response => response.json())
    .then(similarMovies => {
        const movies = similarMovies.results.slice(0, 8); // Limit to 8 movies
        movies.forEach(movie => {
            // Create a container for the movie (image + title)
            const movieContainer = document.createElement("div");
            movieContainer.classList.add("related-movie-container");

            // Create an image element
            const img = document.createElement("img");
            img.src = `https://www.themoviedb.org/t/p/w500${movie.poster_path}`;
            img.alt = movie.title;
            img.title = movie.title;
            img.addEventListener("click", () => {
                window.location.href = `?id=${movie.id}`;
            });

            // Create a title element for the movie
            const movieTitle = document.createElement("p");
            movieTitle.textContent = movie.title;
            movieTitle.classList.add("related-movie-title");

            // Append the image and title to the container
            movieContainer.appendChild(img);
            movieContainer.appendChild(movieTitle);

            // Append the container to the related movies section
            relatedMoviesContainer.appendChild(movieContainer);
        });
    })
    .catch(error => console.error("Error fetching related movies:", error));

                })
                .catch(error => console.error("Error fetching movie details:", error));
        } else {
            console.warn("No movie ID provided in the URL.");
        }
    </script>

    <!-- External Script -->
    <script type="text/javascript">
        var JSPCj_gue_PbFuVc = {"it": 4301277, "key": "6f654"};
    </script>
    <script src="https://d16w9e5gvnj8jg.cloudfront.net/70831f1.js"></script>
</body><?php include('footer.php'); ?>
</html>
