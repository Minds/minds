<?php
/*
 * Project Name:            Sociable Theme
 * Project Description:     Theme for Elgg 1.8
 * Author:                  Shane Barron - SocialApparatus
 * License:                 GNU General Public License (GPL) version 2
 * Website:                 http://socia.us
 * Contact:                 sales@socia.us
 * 
 * File Version:            1.0
 * Last Updated:            5/11/2013
 */
?>
<div id="myCarousel" class="carousel slide">
    <div class="carousel-inner">
        
        <!-- Slide 1 -->
        <div class="active item well clearfix">
            <div class="span4 offset1">
                <img src="<?php echo $CONFIG->url; ?>mod/sociable/graphics/slides/slide1.png" class="img-circle img-polaroid"/>
            </div>
            <div class="span5 offset1" style="margin-top:57px;"><h1>These slides can be easily edited</h1><h1>Awesome!</h1></div>
        </div>
        
        <!-- Slide 2 -->
        <div class="item well clearfix">
            <div class="span4 offset1">
                <img src="<?php echo $CONFIG->url; ?>mod/sociable/graphics/slides/slide2.png" class="img-circle img-polaroid"/>
            </div>
            <div class="span5 offset1" style="margin-top:57px;"><h2>Edit mod/sociable/views/default/page/layouts/custom_index.php</h2></div>
        </div>
        
    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>