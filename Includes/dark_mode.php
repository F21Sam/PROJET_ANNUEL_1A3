<script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('toggle-dark-mode');
            const toggleLogo = document.getElementById('toggle-logo');

            // Function to apply dark mode based on localStorage
            const applyDarkMode = () => {
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                    toggleLogo.src = 'IMAGES/sun.png'; // Change to dark mode logo
                } else {
                    document.body.classList.remove('dark-mode');
                    toggleLogo.src = 'IMAGES/moon.png'; // Change to light mode logo
                }
            };

            // Toggle dark mode on button click
            toggleButton.addEventListener('click', () => {
                if (localStorage.getItem('darkMode') !== 'enabled') {
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                }
                applyDarkMode();
            });

            // Apply dark mode on page load based on localStorage
            applyDarkMode();
        });
    </script>