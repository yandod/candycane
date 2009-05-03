<h2><?php $candy->html_title(__('Settings',true)) ?></h2>

<div class="tabs">
<ul>
<?php foreach($tabs as $tab): ?>
<?php $selected = ($selected_tab == $tab['name']) ? "selected" : ""; ?>
    <li><?php echo $html->link($tab['label'],aa('tab',$tab['name']),
                                     aa('id', "tab-".$tab['name'],
                                     'class',$selected,
                                     'onclick', "showTab('{$tab['name']}'); this.blur(); return false;",
                                     'escape', false
                                     )) ?></li>
<?php endforeach; ?>
</ul>
</div>

<% tabs.each do |tab| -%>
<%= content_tag('div', render(:partial => tab[:partial]), 
                       :id => "tab-content-#{tab[:name]}",
                       :style => (tab[:name] != selected_tab ? 'display:none' : nil),
                       :class => 'tab-content') %>
<% end -%>

