<?php echo $this->Form->create() ?>

<table class="list issues">
    <thead>
        <tr>
            <th>
                <?php echo $this->Html->link(
                    $this->Html->image('toggle_check.png'),
                    array(),
                    array(
                        'onclick' => "toggleIssuesSelection(Element.up(this, 'table')); return false;",
                        'title' => __('Check all') . '/' . __('Uncheck all'),
                        'escape' => false
                    )
                ) ?>

                <!--<%= link_to image_tag('toggle_check.png'), {}, :onclick => 'toggleIssuesSelection(Element.strtoupper(this, "form")); return false;',
                :title => "#{l(:button_check_all)}/#{l(:button_uncheck_all)}" %>
                -->
            </th>
            <?php
                $url_param = $this->Paginator->params['url_param'];
                unset($url_param[0]);

                $sort_mark = '';
                if ($this->Paginator->sortKey() == 'id' || $this->Paginator->sortKey() == 'Issue.id') {
                    $sort_mark = '&nbsp;'.$this->Html->image('sort_'.$this->Paginator->sortDir().'.png', array('alt' => "Sort_desc"));
                }
                echo $this->Html->tag('th', $this->Paginator->sort('Issue.id', '#', array('url' => $url_param)).$sort_mark);
                foreach ($this->Queries->columns($query) as $column):
                    $sort_mark = '';
                    if ($this->Paginator->sortKey() == $this->QueryColumn->sortable($column)) {
                        $sort_mark = '&nbsp;'.$this->Html->image('sort_'.$this->Paginator->sortDir().'.png', array('alt' => "Sort_desc"));
                    }
                    echo $this->Html->tag('th', strlen($this->QueryColumn->sortable($column)) ?
                        $this->Paginator->sort($this->QueryColumn->sortable($column), __($column),  array(
                            'direction' => $this->QueryColumn->default_order($column),
                            'update' => 'content',
                            'url' => $url_param
                        )).$sort_mark : h(__($column))
                    );
                endforeach;
            ?>
            <!--
            <%= sort_header_tag('id', :caption => '#', :default_order => 'desc') %>
            <% query.columns.each do |column| %>
            <%= column_header(column) %>
            <% end %>
            -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($issue_list as $issue): ?>
        <tr id="issue-<?php echo h($issue['Issue']['id']) ?>" class="hascontextmenu <?php echo $this->Candy->cycle('odd', 'even') ?> <?php echo $this->Issues->css_issue_classes($issue) ?>">
            <td class="checkbox"><input type="checkbox" name="ids[]" value="<?php echo h($issue['Issue']['id']) ?>" /></td>
            <td><?php echo $this->Html->link($issue['Issue']['id'], array('controller' => 'issues', 'action' => 'show', $issue['Issue']['id'])) ?></td>
            <?php foreach ($this->Queries->columns($query) as $column): ?>
                <?php echo $this->Html->tag('td', $this->Queries->column_content($column, $issue), array('class' => $column)) ?>
            <?php endforeach ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php echo $this->Form->end() ?>
