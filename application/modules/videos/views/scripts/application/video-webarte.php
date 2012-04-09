<?php if (!empty($this->video->description)) { ?>
    <p><?php echo $this->specialEscape($this->escape($this->video->description)) ?></p>
<?php } ?>

<?php
    list($w, $h) = @split(':', $this->video->proportion);
    $proportion = $w / $h;
    global $PALETTE;
?>

<object class="playerpreview" type="application/x-shockwave-flash" data="<?php echo $this->CONFIG->wwwroot ?>media/videos/flvplayer.swf" width="600" height="<?php echo intval(600 / $proportion) ?>">
    <param name="movie" value="<?php echo $this->CONFIG->wwwroot ?>media/videos/flvplayer.swf" />
    <param name="allowFullScreen" value="true" />
    <param name="FlashVars" value="flv=<?php echo $this->CONFIG->wwwroot ?>media/videos/<?php echo $this->video->resource ?>&showstop=1&showvolume=1&showtime=1&showfullscreen=1&bgcolor1=<?php echo substr($PALETTE['background_headers2'], 1) ?>&bgcolor2=<?php echo substr($PALETTE['background_headers'], 1) ?>&playercolor=<?php echo substr($PALETTE['background_headers'], 1) ?>&buttoncolor=<?php echo substr($PALETTE['color_headers'], 1) ?>&buttonovercolor=<?php echo substr($PALETTE['background_headers2'], 1) ?>&slidercolor1=<?php echo substr($PALETTE['color_headers'], 1) ?>&slidercolor2=<?php echo substr($PALETTE['color_headers'], 1) ?>&sliderovercolor=<?php echo substr($PALETTE['background_headers2'], 1) ?>&videobgcolor=000000&buffermessage=..." />
</object>
