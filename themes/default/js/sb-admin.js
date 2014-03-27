jQuery(function() {

    jQuery('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
jQuery(function() {
    jQuery(window).bind("load resize", function() {
        console.log(jQuery(this).width())
        if (jQuery(this).width() < 768) {
            jQuery('div.sidebar-collapse').addClass('collapse')
        } else {
            jQuery('div.sidebar-collapse').removeClass('collapse')
        }
    })
})
