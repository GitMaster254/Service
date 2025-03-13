document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const menuToggle = document.getElementById("menu-toggle");

    menuToggle.addEventListener("click", function () {
        sidebar.classList.toggle("open");
    });

    const profileBtn = document.getElementById("profile-btn");
    const profileDropdown = document.getElementById("profile-dropdown");

    // Toggle the dropdown on button click
    profileBtn.addEventListener("click", (event) => {
        event.stopPropagation(); // Prevents the click from bubbling up
        profileDropdown.style.display = (profileDropdown.style.display === "block" ) ?"none" : "block";
    });

    // Close the dropdown when clicking outside of it
    document.addEventListener("click", (event) => {
        if (!profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.style.display = "none";
        }
    });


});
