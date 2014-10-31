<?php

$page = elgg_extract('page', $vars);

?>

<h4>Title</h4>
<p><input type="text" name="title" placeholder="eg. About us" value="<?= $page ? $page->title : '' ?>"/></p>

<h4>Description</h4>
<?= elgg_view('input/longtext', array('name'=>'body', 'placeholder'=>'eg. html content etc', 'value'=> $page ? $page->body : '' )) ?>

<h4>Uri</h4>
<p><input type="text" name="uri" placeholder="eg. about" value="<?= $page ? $page->uri : '' ?>"/></p>

<input type="submit" value="Save!" class="elgg-button elgg-button-action"/>
