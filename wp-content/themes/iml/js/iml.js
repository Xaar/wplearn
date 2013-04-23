/*search field - clear on focus*/

$(document).ready(function(){
    var Input = $('input[name=navsearch]');
    var default_value = Input.val();

    Input.focus(function() {
        if(Input.val() == default_value) Input.val("");
    }).blur(function(){
        if(Input.val().length == 0) Input.val(default_value);
    });
});

/* Main nav CSS animation */

/*$(".navigation-main>a").hover(function() {
    $(this).toggleClass("main_nav_hover_class");
   
});*/

/*var toggle = function() {
    $(this).stop(true, true).toggleClass( "main_nav_hover_class", 200);
};

$("#logo li.menu-item>a").hover(toggle, toggle);*/