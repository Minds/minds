<?php
elgg_load_js('wavesurfer');
?>

<div id="wave">
</div>

<div class="controls audio">
    <div class="play entypo" onclick="wavesurfer.playPause()">
        &#9654;
    </div>
    <div class="pause entypo" onclick="wavesurfer.playPause()">
            &#9097;
                </div>
</div>
<script>
var wavesurfer = Object.create(WaveSurfer);

wavesurfer.init({
        container: document.querySelector('#wave'),
            waveColor: 'yellow',
                progressColor: 'orange'
});

wavesurfer.on('ready', function () {
        wavesurfer.play();
        $('.pause').show();
});

wavesurfer.on('pause', function () {
    $('.pause').hide();
     $('.play').show();
});

wavesurfer.on('play', function () {
        $('.pause').show();
        $('.play').hide();
});

wavesurfer.load('<?=key($vars['sources'])?>');
</script>

