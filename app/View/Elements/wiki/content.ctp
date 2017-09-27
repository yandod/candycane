<div class="wiki">
  <?php 
  	if($content)
  		echo $this->Candy->textilizable($content['WikiContent']['text']); //textilizable content, :text, :attachments => content.page.attachments 
  ?>
</div>
