<div class="m-blog">
  <minds-banner-fat>
    <div class="minds-banner">
      <img class="minds-banner-img" src="/fs/v1/banners/<?= $vars['blog']->guid ?>/0/1501848604">
    </div>
  </minds-banner-fat>

  <div class="m-blog-lite  m-blog-container blogs-grid">

    <div class="m-blog-lite--header">
      <h1 class="m-blog--title"><?= $vars['blog']->title ?></h1>

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
      
      <div class="minds-blog-body">
        <?= $vars['blog']->description ?>
      </div>

    </div>

    <div class="m-blog-lite--full-link mdl-color--blue-grey-200" style="margin: 16px 0">
      <a href="<?= $vars['blog']->getUrl() ?>?lite=false" class="mdl-color-text--white">
        You are viewing the Lite version of Minds Blogs. Click here to see the full view.
      </a>
    </div>

  </div>
</div>