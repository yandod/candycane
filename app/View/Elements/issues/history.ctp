<?php $reply_links = $this->Candy->authorize_for(array('controller'=>'issues', 'action'=>'edit')); ?>
<?php
  $user_pref = $currentuser['UserPreference']['pref'];
  if (isset($user_pref['comments_sorting']) && $user_pref['comments_sorting'] === 'desc') {
    $indice = count($journalList);
  } else {
    $indice = 1;
  }
?>
<?php foreach($journalList as $journal): ?>
  <div id="change-<?php echo h($journal['Journal']['id']) ?>" class="journal">
    <h4><div style="float:right;"><?php echo $this->Html->link("#$indice", array('issue_id'=>$issue['Issue']['id'], '#'=>"note-$indice")); ?></div>
    <?php echo $this->Html->tag('a', '', array('name'=>"note-$indice"));?>
    <?php echo $this->Candy->authoring(date('Y-m-d H:i:s',strtotime($journal['Journal']['created_on'])), $journal['User'], array('label'=>__('Updated by %s %s ago'))); ?></h4>
    <?php echo $this->Candy->avatar($journal['User'], array('size' => "32")); ?>
    <ul>
    <?php foreach($journal['JournalDetail'] as $detail): ?>
       <li><?php echo $this->Issues->show_detail($detail); ?></li>
    <?php endforeach; ?>
    </ul>
    <?php if($journal['Journal']['notes'] != '') echo $this->Journals->render_notes($journal, $currentuser, array('reply_links'=>$reply_links)); ?>
  </div>
  <!-- TODO : For Plugin, call_hook -->
  <!-- <%= call_hook(:view_issues_history_journal_bottom, { :journal => journal }) %> -->
  <?php
    if (isset($user_pref['comments_sorting']) && $user_pref['comments_sorting'] === 'desc') {
      $indice--;
    } else {
      $indice++;
    }
  ?>
<?php endforeach; ?>
