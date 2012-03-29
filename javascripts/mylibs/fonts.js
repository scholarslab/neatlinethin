/* 
 * Sets up the Google web fonts programmatically
 *
 * You can mix Google fonts with fonts from Typekit and Ascender
 */
WebFontConfig = {
    google: {families: ['OFL Sorts Mill Goudy TT', 'Molengo']}
//    ,typekit: {id: 'typekitid' }
//    ,ascender: {key: 'ascenderKey', families: ['AscenderSans:bold,bolditalic,italic,regular']}

//    ,fontloading: function() {
//        alert('fonts loading');
//
//    }
//    ,active: function() {
//        alert('fonts loaded');
//    }
};

(function() {
   var wf = document.createElement('script');
   wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
       '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
   wf.type = 'text/javascript'
   wf.async = true;
   
   var s = document.getElementsByTagName('script')[0];
   s.parentNode.insertBefore(wf, s);
})();




