<?php
/**
 * Footer content for Neatline 'Thin' theme
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package   omeka
 * @author    "Scholars Lab"
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   Release: 0.0.1-pre
 * @link      http://www.scholarslab.org
 *
 * PHP version 5
 *
 */
?>

</article> <!-- end #main -->

<div class="clearfix"> </div>


<footer id="footer">
    <div id="footer-container">
        <section class="footer-col-two-thirds">
         <h3>About <?php echo settings('site_title'); ?></h3>

         <?php echo settings('description'); ?>
        </section>
       
         
        <section class="footer-col footer-misc">
            <h3>About Us</h3>
            
            <p>
                <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" rel="license" id="cc-icon">
                    <img src="<?php echo img('cc.png'); ?>" alt="Creative Commons" />
                </a>
		<a href="http://neatline.org">Neatline</a> is a project of the <a href="http://lib.virginia.edu">University of Virginia Library's</a> <a href="http://lib.virginia.edu/scholarslab">Scholars' Lab</a> and is proudly powered by <a href="http://omeka.org">Omeka</a>.
            </p>
            <p>
                
                <a href="http://www.virginia.edu/copyright.html">&copy;</a> Rectors and Visitors of the <a href="http://www.virginia.edu">University of Virginia</a>
            </p>
            <p class="contact-info">
                <a href="http://www.scholarslab.org/" id="slab-logo">
                    <img src="<?php echo img('slab-logo.png'); ?>" />
                </a>
		<a class="contact web-contact" href="http://lib.virginia.edu/scholarslab/">Visit us</a>
		<a class="contact email-contact" href="http://lib.virginia.edu/scholarslab/help/">Write to us</a>
            </p>
        </section>
    </div>
</footer>

</div> <!-- end #container -->

<!-- Javascript at the bottom for fast page loading -->

<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
<!-- 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.4.js"%3E%3C/script%3E'))</script>
-->

<!-- scripts concatenated and minified via ant build script-->
<?php echo javascript_include_tag('mylibs/fonts, script'); ?>
<!-- end concatenated and minified scripts-->


<!--[if lt IE 7 ]>
<?php echo javascript_include_tag('libs/dd_belatedpng'); ?>
<script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
<![endif]-->

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-12081576-2']);
_gaq.push(['_setDomainName', '.scholarslab.org']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>

</body>
</html>