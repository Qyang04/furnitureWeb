function toggleMenu(subMenuId, event) {
    event.preventDefault(); // Prevent default anchor behavior

    // Toggle the clicked submenu
    $('#' + subMenuId).slideToggle(300);

    // Close other open submenus
    $('.dropdown ul ul').not('#' + subMenuId).slideUp(300);
}

// Optional: Close all submenus if clicking outside
$(document).click(function (event) {
    if (!$(event.target).closest('.dropdown').length) {
        $('.dropdown ul ul').slideUp(300);
    }
});