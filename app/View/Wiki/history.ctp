<h2><?php echo h($this->Wiki->pretty_title($page['WikiPage']['title'])); ?></h2>

<h3><?php echo __('History'); ?></h3>

<?php /*form_tag({:action => "diff"}, :method => :get) do*/ ?>
<?php echo $this->Form->create(null, array('type' => 'get',
                               'url' =>
                               array('action' => 'diff',
                                     'project_id' => $main_project['Project']['identifier'],
                                     'wikipage' => $page['WikiPage']['title']))); ?>
<table class="list">
   <thead><tr>
   <th>#</th>
   <th></th>
   <th></th>
   <th><?php echo __('Updated'); ?></th>
   <th><?php echo __('Author'); ?></th>
   <th><?php echo __('Comments'); ?></th>
   <th></th>
   </tr></thead>
<tbody>
<?php $show_diff = (sizeof($versions) > 1); ?>
<?php $line_num = 1; ?>
<?php
/* prepare neighbour version number for JavaScript */
$prev_version = $next_version = array();
$first_version_value = $second_version_value = null;
foreach ($versions as $ver) {
   $current_value = $ver['WikiContentVersion']['version'];
   if (!isset($prev_value)) {
     $first_version_value = $current_value;
   } else {
     $prev_version[$current_value] = $prev_value;
     $next_version[$prev_value] = $current_value;
   }
   $prev_value = $current_value;
}
if (isset($next_version[$first_version_value])) {
  $second_version_value = $next_version[$first_version_value];
}
?>
<?php foreach ($versions as $ver) : /*@versions.each do |ver|*/ ?>
<tr class="<?php echo $this->Candy->cycle("odd", "even"); ?>">
   <td class="id">
   <?php echo($this->Html->link($ver['WikiContentVersion']['version'],
                       array('action' => 'index',
                             'project_id' => $main_project['Project']['identifier'],
                             'wikipage'   => $page['WikiPage']['title'],
                             '?version='. $ver['WikiContentVersion']['version']))); ?></td>
<td class="checkbox"><?php
   if ($show_diff && ($line_num < sizeof($versions))) {
     echo($this->Form->input('version',
                    array('type'=>'radio',
                          'options' => array($ver['WikiContentVersion']['version'] => null),
                          'value' => $first_version_value,
                          'id' => "Cb-",
                          'onclick' => sprintf('$(\'Cbto-%d\').checked=true;',
                                               $next_version[$ver['WikiContentVersion']['version']]),
                          'div' => false,
                          'label' => false)));
   } ?></td>
<td class="checkbox"><?php
   if ($show_diff && ($line_num > 1)) {
     echo($this->Form->input('version_from',
                    array('type'=>'radio',
                          'options' => array($ver['WikiContentVersion']['version'] => null),
                          'value' => $second_version_value,
                          'id' => "Cbto-",
                          'onclick' => sprintf('if ($(\'Cb-%d\').checked==true || $(\'version_from\').value > %d) {$(\'Cb-%d\').checked=true;}',
                                               $ver['WikiContentVersion']['version'], $ver['WikiContentVersion']['version'],
                                               $prev_version[$ver['WikiContentVersion']['version']]),
                          'div' => false,
                          'label' => false)));
   } ?></td>
    <td align="center"><?php echo $this->Candy->format_time($ver['WikiContentVersion']['updated_on']); ?></td>
    <td><em><?php echo isset($ver['Author']) ? $this->Candy->format_username($ver['Author']) : "anonyme"; ?></em></td>
    <td><?php echo h($ver['WikiContentVersion']['comments']) ?></td>
    <td align="center"><?php echo $this->Html->link(__('Annotate',
                                           array('action'     => 'annotate',
                                                 'project_id' => $main_project['Project']['identifier'],
                                                 'wikipage'   => $page['WikiPage']['title'],
                                                 '?version='. $ver['WikiContentVersion']['version']))); ?></td>
</tr>
<?php $line_num += 1 ?>
<?php endforeach; ?>
</tbody>
</table>
<?php
if ($show_diff) {
  echo $this->Form->submit(__('View differences'), array('class' => 'small', 'div' => false));
} ?>

<span class="pagination"><?php
  $this->Paginator->options(array('url' =>
                         array('action' => 'history',
                               'project_id' => $main_project['Project']['identifier'],
                               'wikipage' => $page['WikiPage']['title'])));
  echo $this->Paginator->prev('<< '.__('Previous'), array(), null, array('style'=>'display:none;'));
echo ' ';
echo $this->Paginator->numbers();
echo ' ';
echo $this->Paginator->next(__('Next').' >>', array(), null, array('style'=>'display:none;'));
echo $this->Paginator->counter(array('format' => '(%start%-%end%/%count%)'));
?></span>
<?php /* pagination_links_full @version_pages, @version_count, :page_param => :p */ ?>
<?php $this->Form->end() ?>
