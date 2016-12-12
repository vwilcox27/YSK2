<?php
/*
Plugin Name: WordPress Social Share Buttons
Plugin URI: https://www.usefulblogging.com/wordpress-social-share-buttons-plugin/
Description: WordPress Social Share Buttons - Facebook Share, Tweet, Google+, Buffer, LinkedIn, StumbleUpon, Pin it Buttons after posts.
Version: 1.2
Author: Ataul Ghani
Author URI: https://www.usefulblogging.com/
*/
/**
 * This file is part of wordpress-social-share-buttons.
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with wordpress-social-share-buttons.  If not, see <http://www.gnu.org/licenses/>.
 */
        /*
        Contents:
        1.Head js: Pinterest,Google + 
        2.Footer js: Twitter,Linkedin
        3.Get the Image for Pinterest
        4.Display the Social Share Buttons
        */

##### 1. Head js: Pinterest,Google +#####################

function FTGSB_head() {
if (is_single()) { ?> 
<script type="text/javascript">
(function() {
    window.PinIt = window.PinIt || { loaded:false };
    if (window.PinIt.loaded) return;
    window.PinIt.loaded = true;
    function async_load(){
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.async = true;
        if (window.location.protocol == "https:")
            s.src = "https://assets.pinterest.com/js/pinit.js";
        else
            s.src = "http://assets.pinterest.com/js/pinit.js";
        var x = document.getElementsByTagName("script")[0];
        x.parentNode.insertBefore(s, x);
    }
    if (window.attachEvent)
        window.attachEvent("onload", async_load);
    else
        window.addEventListener("load", async_load, false);
})();
</script>
<script type="text/javascript">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/plusone.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
<?php }
 }
add_action('wp_head', 'FTGSB_head',20);

############2.Footer js: Twitter,linkedin Share#################
function FTGSB_footer() {
    if (is_single()) { ?> 
        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
        <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
<script type="text/javascript">
  (function() {
    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
  })();
</script>
    <?php }
 }
add_action('wp_footer', 'FTGSB_footer');


###############3.Get the Image for Pinterest#####################

function pinterest_image() {
  global $post, $posts;
  //if you set p-img custom field, then set it as pinterest_image
  $pinterest_image = get_post_meta($post->ID, 'p-img', true);  
  
  //if not,get the first image in the post as pinterest_image
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  //return pinterest_image URL
  if(empty($pinterest_image)){    
    $pinterest_image = $first_img;
  }
  return $pinterest_image;
}


###############4.Display the Social Share Buttons################

add_filter ('the_content', 'FTGSB');
function FTGSB($content) {
if(is_single()) {
global $post, $posts;
$content.= '
<style>#ssbnav li{list-style:none;overflow:hidden;margin:1 auto;background:none;overflow:hidden;width:96px; height:52px; line-height:20px; margin-right:0px; padding: 16px 0px 0px 0px; float:left; text-align:center;}</style>
<ul id="ssbnav">
<li><!-- Facebook Share--><div class="fb-share-button" data-layout="button_count" data-mobile-iframe="true"></div></li>
<li><!-- Google plus one--><div class="g-plus" data-action="share" data-annotation="bubble"></div></li>
<li><!-- Twitter--><a name="twitter_share" data-count="horizontal" href="http://twitter.com/share" class="twieet-button" ><img src="https://www.usefulblogging.com/wp-content/uploads/2016/10/tweet-button.png" /></a></li>
<li><!-- Buffer--><a href="http://bufferapp.com/add" class="buffer-add-button" data-count="horizontal" >Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script></li>
<li><!-- linkedin--><div><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/Share" data-counter="right"></script></div></li>
<li><!-- stumbleupon--><div><script src="http://www.stumbleupon.com/hostedbadge.php?s=1"></script></div></li>
<li><!-- Pinterest like--> <a href="http://pinterest.com/pin/create/button/?url='.urlencode(get_permalink($post->ID)).
'&media='.pinterest_image().'" class="pin-it-button" count-layout="horizontal">Pin It</a></li>
</ul>';
}
return $content;
}