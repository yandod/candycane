<?php $reply_links = $candy->authorize_for(array('controller'=>'issues', 'action'=>'edit')); ?>
<?php $indice = 1; ?>
<?php foreach($journals as $journal): ?>
  <div id="change-<?php e(h($journal['Journal']['id'])) ?>" class="journal">
    <h4><div style="float:right;"><?php echo $html->link("#$indice", array('id'=>$issue['Issue']['id'], '#'=>"note-$indice")); ?></div>
    <?php echo $html->tag('a', '', array('name'=>"note-$indice"));?>
    <?php echo $candy->authoring($journal['Journal']['created_on'], $journal['User'], array('label'=>__('Updated by %s %s ago',true))); ?></h4>
    <?php echo $candy->avatar($journal['User'], array('size' => "32")); ?>
    <ul>
    <?php foreach($journal['JournalDetail'] as $detail): ?>
       <li><%= show_detail(detail) %></li>
    <?php endforeach; ?>
    </ul>
    <%= render_notes(journal, :reply_links => reply_links) unless journal.notes.blank? %>
  </div>
  <%= call_hook(:view_issues_history_journal_bottom, { :journal => journal }) %>
  <?php $indice++; ?>
<?php endforeach; ?>
