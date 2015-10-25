<?php

$page = elgg_extract('page', $vars);
$context = elgg_extract('context', $vars, $page->context ?: 'footer');
?>

<h4>Title</h4>
<p><input type="text" name="title" placeholder="eg. About us" value="<?= $page ? $page->title : '' ?>" required/></p>

<h4>External forwarding url?</h4>
<p><input type="text" name="forwarding" placeholder="Enter the full url of the url you wish this page to forward to, or leave blank" value="<?= $page ? $page->forwarding : '' ?>"/></p>

<h4>Description</h4>
<?= elgg_view('input/longtext', array('name'=>'body', 'placeholder'=>'eg. html content etc', 'value'=> $page ? $page->body : '' )) ?>

<br/>
<h4>Banner Image</h4>
<p><input type="file" name="banner"/></p>
<input type="hidden" name="banner_position" value="<?= $page->banner_position ?>"/>

<h4>Path URI</h4>
<p><input type="text" name="uri" placeholder="eg. about" value="<?= $page ? $page->uri : '' ?>" required/></p>


<input type="hidden" name="context" value="<?= $context ?>"/>
<input type="submit" value="Save!" class="elgg-button elgg-button-action"/>
