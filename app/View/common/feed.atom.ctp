<feed xmlns="http://www.w3.org/2005/Atom">
<?php
  echo $xml->elem('title',array(), $this->Candy->truncate_single_line($atomTitle, 100));
  echo $xml->elem('link', array("rel" => "self", "href" => Router::url(array('format'=>null), true)));
  echo $xml->elem('link', array("rel" => "alternate", "href" => Router::url('/',true)));
  echo $xml->elem('id'  , array(), Router::url(array('controller' => 'welcome'),true));
  echo $xml->elem('updated', array(), (!empty($items[0])) ? $this->Time->toRSS($EventModel->event_datetime($items[0])) : date('r'));
  echo $xml->elem('author', array(), null, false).'>';
    echo $xml->elem('name', array(), $Settings->app_title);
  echo $xml->closeElem();
  echo $xml->elem('generator', array('uri'=>'http://www.candycane.com'/*$Settings->url*/), null, $Settings->app_title /*$Settings->app_name*/);
  foreach($items as $item) {
    echo $xml->elem('entry', array(), null, false).'>';
      echo $xml->elem('title',array(), $this->Candy->truncate_single_line($EventModel->event_title($item), 100));
      echo $xml->elem('link', array("rel" => "alternate", "href" => Router::url($EventModel->event_url($item), true)));
      echo $xml->elem('id', array(), Router::url($EventModel->event_url($item), true));
      echo $xml->elem('updated', array(), $this->Time->toRSS($EventModel->event_datetime($item)));
      $author = $EventModel->event_author($item);
      if(!empty($author)) {
        echo $xml->elem('author', array(), null, false).'>';
          echo $xml->elem('name', array(), $this->Candy->format_username($author));
          echo $xml->elem('email', array(), $author['mail']);
        echo $xml->closeElem();
      }
      echo $xml->elem('content', array("type" => "html"), h($this->Candy->textilizable($EventModel->event_description($item))));
    echo $xml->closeElem();
  }
?>
</feed>