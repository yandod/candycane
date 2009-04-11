<?php echo $html->meta('atom', array('action'=>'activity', 'id'=>$this->data['Project']['id'], 'key'=>isset($currentuser['User']) ? $currentuser['User']['rss_key'] : '', 'from'=>null, 'format'=>'atom')); ?>

