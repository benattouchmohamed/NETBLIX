<?php
// Include shared header file or any other setup (if needed)
?>
<link rel="stylesheet" href="assets/css/header.css">
<header id="header-general">
    <!-- Logo Section -->
    <div id="logo-general">
        <a id="logo-link" href="index.php">
            <img id="logo-image" src="assets/images/logo.png" alt="Logo">
            <span id="logo-span" style="color: #2196F3;">FREE </span>
            <span id="logo-span2" style="color: #4CAF50;">NETBLIX</span>
        </a>
    </div>

    <!-- Icons Section -->
    <div id="icons-general">
        <img id="dark-mode-toggle" src="assets/images/light.svg" alt="Light Mode Icon" class="dark-mode">
    </div>
</header>

<script>
    // Get dark mode toggle button
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const header = document.querySelector('#header-general');
    const body = document.body;

    // Check dark mode in localStorage
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled';

    // Apply mode on load
    if (isDarkMode) {
        body.classList.add('dark-mode-enabled');
        header.classList.add('dark-mode-enabled');
        darkModeToggle.src = 'assets/images/dark-mode.svg';
    } else {
        darkModeToggle.src = 'assets/images/light.svg';
    }

    // Toggle dark mode
    darkModeToggle.addEventListener('click', () => {
        const isNowDarkMode = body.classList.toggle('dark-mode-enabled');
        header.classList.toggle('dark-mode-enabled');
        darkModeToggle.src = isNowDarkMode ? 'assets/images/dark-mode.svg' : 'assets/images/light.svg';
        localStorage.setItem('darkMode', isNowDarkMode ? 'enabled' : 'disabled');
    });
</script>
