<h2><?php e(h($wiki->pretty_title($page['WikiPage']['title']))); ?></h2>

<h3><?php __('History'); ?></h3>

<?php /*form_tag({:action => "diff"}, :method => :get) do*/ ?>
<?php e($form->create(null, aa('type', 'get',
                               'url',
                               array('action' => 'diff',
                                     'project_id' => $main_project['Project']['identifier'],
                                     'wikipage' => $page['WikiPage']['title'])))); ?>
<table class="list">
   <thead><tr>
   <th>#</th>
   <th></th>
   <th></th>
   <th><?php __('Updated'); ?></th>
   <th><?php __('Author'); ?></th>
   <th><?php __('Comments'); ?></th>
   <th></th>
   </tr></thead>
<tbody>
<?php $show_diff = (sizeof($versions) > 1); ?>
<?php $line_num = 1; ?>
<?php
/* prepare neighbour version number for JavaScript */
$prev_version = null;
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
$second_version_value = $next_version[$first_version_value];
?>
<?php foreach ($versions as $ver) : /*@versions.each do |ver|*/ ?>
<tr class="<?php e($candy->cycle("odd", "even")); ?>">
   <td class="id">
   <?php e($html->link($ver['WikiContentVersion']['version'],
                       array('action' => 'index',
                             'project_id' => $main_project['Project']['identifier'],
                             'wikipage'   => $page['WikiPage']['title'],
                             '?version='. $ver['WikiContentVersion']['version']))); ?></td>
<td class="checkbox"><?php
   if ($show_diff && ($line_num < sizeof($versions))) {
     e($form->input('version',
                    array('type'=>'radio',
                          'options' => aa($ver['WikiContentVersion']['version'], null),
                          'value' => $first_version_value,
                          'id' => "Cb-",
                          'onclick' => sprintf('$(\'Cbto-%d\').checked=true;',
                                               $next_version[$ver['WikiContentVersion']['version']]),
                          'div' => false,
                          'label' => false)));
   } ?></td>
<td class="checkbox"><?php
   if ($show_diff && ($line_num > 1)) {
     e($form->input('version_from',
                    array('type'=>'radio',
                          'options' => aa($ver['WikiContentVersion']['version'], null),
                          'value' => $second_version_value,
                          'id' => "Cbto-",
                          'onclick' => sprintf('if ($(\'Cb-%d\').checked==true || $(\'version_from\').value > %d) {$(\'Cb-%d\').checked=true;}',
                                               $ver['WikiContentVersion']['version'], $ver['WikiContentVersion']['version'],
                                               $prev_version[$ver['WikiContentVersion']['version']]),
                          'div' => false,
                          'label' => false)));
   } ?></td>
    <td align="center"><?php e($candy->format_time($ver['WikiContentVersion']['updated_on'])); ?></td>
    <td><em><?php e(isset($ver['Author']) ? $ver['Author']['firstname'].' '.$ver['Author']['lastname'] : "anonyme"); ?></em></td>
    <td><?php e(h($ver['WikiContentVersion']['comments'])) ?></td>
    <td align="center"><?php e($html->link(__('Annotate', true),
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
  e($form->submit(__('View differences', true), aa('class', 'small', 'div', false)));
} ?>

<span class="pagination"><?php
  $paginator->options(aa('url',
                         array('action' => 'history',
                               'project_id' => $main_project['Project']['identifier'],
                               'wikipage' => $page['WikiPage']['title'])));
  e($paginator->prev('<< '.__('Previous', true), array(), null, array('style'=>'display:none;')));
e(' ');
e($paginator->numbers());
e(' ');
e($paginator->next(__('Next', true).' >>', array(), null, array('style'=>'display:none;')));
e($paginator->counter(aa('format', '(%start%-%end%/%count%)')));
?></span>
<?php /* pagination_links_full @version_pages, @version_count, :page_param => :p */ ?>
<?php $form->end() ?>
