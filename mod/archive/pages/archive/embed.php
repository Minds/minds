
	<?php echo elgg_view('page/elements/head');?>
	<script>
			$(document).on('click', 'img', function(e){
				e.preventDefault();
				
				//
				top.tinymce.activeEditor.windowManager.getParams().onInsert($(this).data('href'));
				
				//close and go back to inset panel
				top.tinymce.activeEditor.windowManager.close();
			});
			
			$(document).on('submit', '#upload', function(e){
				e.preventDefault();
				
				$.ajax({
		           type: "POST",
		           url: $(this).attr('action'),
		           data: new FormData(this), // serializes the form's elements.
		           mimeType:"multipart/form-data",
					contentType: false,
					cache: false,
					processData:false,
		           success: function(data){
		             location.reload();
		           }
		         });
			});
	</script>
	<style>
		#upload input[type=file]{
			width:80%;
			
		}
	</style>

	<body>
		<form id="upload" action="<?php echo elgg_add_action_tokens_to_url('/action/archive/upload'); ?>" method="POST" enctype="multipart/form-data">
			<input type="hidden" value="Upload"/>
			<input type="file" name="fileData"/>
			<button type="submit" class="elgg-button elgg-button-action">Upload</button>
		</form>
		<?php 
			
		
			$subtype = get_input('subtype');
			switch($subtype){
				case 'media':
					$subtype = 'kaltura_video';
					break;	
				case 'link':
					$subtype = 'file';
					break;
			}
			
			$items =  elgg_get_entities(array('subtype'=>$subtype, 'offset'=>''));
			foreach($items as $item){
				$block = $item->getIconURL('small');
				
				switch($item->subtype){
					case 'kaltura_video':
						$src = $item->getVideoUrl();
						break;
					case 'image':
					default:
						$src = $item->getIconURL('large');
				}
				echo "<img data-href=\"$src\"\ src=\"$block\" style=\"float:left; width:182px;margin:8px;\">";
			}
		?>
	</body>
</html>
