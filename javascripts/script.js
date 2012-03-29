/*
 * jQuery methods for the Neatline Thin theme
 *
 * @author Scholars' Lab
 *
 */

Omeka.NeatlineTheme = Omeka.NeatlineTheme || {};

Omeka.NeatlineTheme.handleNav = function(event) {
    event.preventDefault(); // prevent default action

    var $element = $(event.target);
    var $nav = $element.parents('#main-nav');
    var $parent = $element.parent();

    Omeka.NeatlineTheme.hideSubNav($nav);

    $element.toggleClass('nav-on');

    if($element.hasClass('nav-on')){
        $nav.siblings('.sub-nav').slideDown();
    }

};

Omeka.NeatlineTheme.hideSubNav = function($nav) {
    $nav.find('.nav-on').removeClass('nav-on');
    $nav.siblings('.sub-nav').slideUp();
};

jQuery(document).ready(function($){
    // set up navigation
    jQuery('a.exhibits-nav-anchor').bind('click', function(event) {
        event.preventDefault();

       // NEATLINE.handleNav(event);
        
        $('a.exhibits-nav-anchor').toggleClass('exhibits-on');
        if(jQuery('.exhibits-nav a').hasClass('exhibits-on')) {
            jQuery('#exhibits-sub-nav').slideDown();
        } else {
            jQuery('#exhibits-sub-nav').slideUp();
        }
     });

    jQuery("a.collections-nav-anchor").bind('click', function(event) {
        event.preventDefault();

        jQuery('.collections-nav-anchor').toggleClass('collections-on');


        if(jQuery('.collections-nav-anchor').hasClass('collections-on')) {
            jQuery('#collections-sub-nav').slideDown();
        } else {
            jQuery('#collections-sub-nav').slideUp();
        }
    });
});
