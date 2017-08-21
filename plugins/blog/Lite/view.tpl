<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<minds-banner-fat>
  <div class="minds-banner">
    <img class="minds-banner-img" src="/fs/v1/banners/<?= $vars['blog']->guid ?>/0/1501848604">
  </div>
</minds-banner-fat>

<div class="m-blog-lite mdl-card mdl-shadow--4dp">

  <?php if ($vars['blog']->monetized) { ?>
  <google-ad style="display:block; width:calc(100% + 32px); margin:-16px -16px 16px">
    <ins
      class="adsbygoogle"
      style="display:block; width:100%;"
      data-ad-client="ca-pub-9303771378013875"
      data-ad-slot="7588308825"
      data-ad-format="auto"
      ></ins>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
  </google-ad>
  <?php } ?>

  <div class="m-blog-lite--header">
    <h1><?= $vars['blog']->title ?></h1>

  </div>

  <div class="minds-blog-ownerblock">

    <div class="minds-avatar" [hovercard]="blog.ownerObj.guid">
      <a href="<?= $vars['blog']->getOwnerEntity()->username ?>">
        <img src="/icon/<?= $vars['blog']->getOwnerEntity()->guid?>/small}}" class="mdl-shadow--2dp"/>
      </a>
    </div>
    <div class="minds-body">
      <a href="<?= $vars['blog']->getOwnerEntity()->username ?>" class="mdl-color-text--blue-grey-500"><?= $vars['blog']->getOwnerEntity()->name ?></a>
      <span><?= date('M d Y', $vars['blog']->getOwnerEntity()->time_created) ?></span>
    </div>
  </div>

  <div class="m-blog-lite--body">
     <?php if ($vars['blog']->monetized) { ?>
     <google-ad class="m-ad-block m-ad-block-google square m-ad-block-default m-ad-block-square">
      <ins
        class="adsbygoogle"
        style="display:block; width:100%;"
        data-ad-client="ca-pub-9303771378013875"
        data-ad-slot="7588308825"
        data-ad-format="auto"
      ></ins>
      <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </google-ad>
    <?php } ?> 
    <div class="minds-blog-body">
      <?= $vars['blog']->description ?>
    </div>
    <?php if ($vars['blog']->monetized) { ?>
      <google-ad style="display:block; width:calc(100% + 32px); margin:-16px -16px 16px" class="m-ad-block-mobile">
        <ins
          class="adsbygoogle"
          style="display:block; width:100%;"
          data-ad-client="ca-pub-9303771378013875"
          data-ad-slot="7588308825"
          data-ad-format="auto"
          ></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </google-ad>
      <div id="rcjsload_7c87b6"></div>
        <script type="text/javascript">
        (function() {
        var rcel = document.createElement("script");
        rcel.id = 'rc_' + Math.floor(Math.random() * 1000);
        rcel.type = 'text/javascript';
        rcel.src = "http://trends.revcontent.com/serve.js.php?w=11364&t="+rcel.id+"&c="+(new Date()).getTime()+"&width="+(window.outerWidth || document.documentElement.clientWidth);
        rcel.async = true;
        var rcds = document.getElementById("rcjsload_7c87b6"); rcds.appendChild(rcel);
        })();
        </script>
    <?php } ?>

  </div>

  <div class="m-blog-lite--full-link mdl-color--blue-grey-200" style="margin: 16px 0">
    <a href="<?= $vars['blog']->getUrl() ?>?lite=false" class="mdl-color-text--white">
      You are viewing the Lite verion of Minds Blogs. Click here to see the full view.  
    </a>
  </div>

</div>
