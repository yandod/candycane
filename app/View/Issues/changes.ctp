<feed xmlns="http://www.w3.org/2005/Atom">
<?php
  echo $xml->elem('title',array(), $atomTitle);
  echo $xml->elem('link', array("rel" => "self", "href" => Router::url(array('action'=>'changes', 'id'=>$issue['Issue']['id'], 'format'=>'atom', 'key'=>$rssToken), true)));
  echo $xml->elem('link', array("rel" => "alternate", "href" => Router::url('/',true)));
  echo $xml->elem('id'  , array(), Router::url(array('controller' => 'welcome'),true));
  echo $xml->elem('updated', array(), (!empty($journals)) ? $this->Time->toRSS($journals[0]['Journal']['created_on']) : date('r'));
  echo $xml->elem('author', array(), null, false).'>';
    echo $xml->elem('name', array(), $Settings->app_title);
  echo $xml->closeElem();
  foreach($journals as $change) {
    echo $xml->elem('entry', array(), null, false).'>';
      echo $xml->elem('title',   array(), $issue['Project']['name'].' - '.$issue['Tracker']['name'].' #'.$issue['Issue']['id'].': '.$issue['Issue']['subject']);
      echo $xml->elem('link',    array("rel" => "alternate", "href" => Router::url(array('controller' => 'issues' , 'action' => 'show', 'id' => $issue['Issue']['id']), true)));
      echo $xml->elem('id', array(), Router::url(array('controller' => 'issues' , 'action' => 'show', 'id' => $issue['Issue']['id'], 'journal_id' => $change['Journal']['id']), true));
      echo $xml->elem('updated', array(), $this->Time->toRSS($change['Journal']['created_on']));
      echo $xml->elem('author', array(), null, false).'>';
        echo $xml->elem('name', array(), $this->Candy->format_username($change['User']));
        echo $xml->elem('email', array(), $change['User']['mail']);
      echo $xml->closeElem();
      $content = '<ul>';
      foreach($change['JournalDetail'] as $detail) {
        $content .= '<li>'.$this->Issues->show_detail($detail, false).'</li>';
      }
      $content .= '</ul>';
      if(!empty($change['Journal']['notes'])) $content .= $this->Candy->textilizable($change['Journal']['notes']);
      echo $xml->elem('content', array("type" => "html"), h($content));
    echo $xml->closeElem();
  }
?>
</feed>