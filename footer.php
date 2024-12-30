<?php
// Include shared header file or any other setup (if needed)
?>
<link rel="stylesheet" href="assets/css/header.css">
<header id="header-general">
    <!-- Logo Section -->
  

  
</header>


<!-- Footer Section -->
<footer id="footer-general">
    <!-- Footer Logo Section -->
    <div id="footer-logo">
        <a href="index.php">
            <img id="logo-image-footer" src="assets/images/logo.png" alt="Footer Logo">
        </a>
    </div>

    <!-- Footer Copyright -->
    <div id="footer-copyright">
        <p>&copy; <?php echo date("Y"); ?> FREE NETBLIX. All Rights Reserved.</p>
    </div>
</footer>

<style>
    /* Footer styles */
    #footer-general {
    background: linear-gradient(to right, #25bba5, #2a8df7); /* Apply gradient background */
    color: #fff; /* White text color */
    padding: 40px 20px; /* Padding */
    text-align: center; /* Center-align text */
}


    #footer-logo img {
        width: 150px;
        margin-bottom: 20px;
    }

    #footer-copyright p {
        margin-top: 20px;
        font-size: 14px;
    }

    /* Add specific styles for footer logo */
    #logo-image-footer {
        width: 100px;
    }
</style>
