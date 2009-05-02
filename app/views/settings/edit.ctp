<h2><?php echo $candy->html_title(__('Settings',true)) ?></h2>

<!-- <% selected_tab = params[:tab] ? params[:tab].to_s : administration_settings_tabs.first[:name] %> -->
<?php //$selected_tab = 'general' ?>
<div class="tabs">
<?php //pr($settings_tabs) ?>
<ul>
<?php foreach($settings_tabs as $tab): ?>
<?php $selected = ($selected_tab == $tab['name']) ? "selected" : ""; ?>
<!-- <% administration_settings_tabs.each do |tab| -%> -->
    <li><?php echo $html->link($tab['label'],aa('tab',$tab['name']),
                                     aa('id', "tab-".$tab['name'],
                                     'class',$selected,
                                     'onclick', "showTab('{$tab['name']}'); this.blur(); return false;",
                                     'escape', false
                                     )) ?></li>
<!--    <li><%= link_to l(tab[:label]), { :tab => tab[:name] },
                                    :id => "tab-#{tab[:name]}",
                                    :class => (tab[:name] != selected_tab ? nil : 'selected'),
                                    :onclick => "showTab('#{tab[:name]}'); this.blur(); return false;" %></li> -->
<!-- <% end -%> -->
<?php endforeach; ?>
</ul>
</div>

<?php foreach($settings_tabs as $tab): ?>
<?php $disp = ($selected_tab !== $tab['name']) ? 'display:none' : ''; ?>
<?php echo $html->tag('div',$this->renderElement($tab['partial']),
						aa('id','tab-content-'.$tab['name'],
						   'style', $disp,
						   'class', 'tab-content'
						)
) ?>
<!-- <% administration_settings_tabs.each do |tab| -%> -->
<!--  <%= content_tag('div', render(:partial => tab[:partial]), 
                       :id => "tab-content-#{tab[:name]}",
                       :style => (tab[:name] != selected_tab ? 'display:none' : nil),
                       :class => 'tab-content') %>-->
<!-- <% end -%> -->
<?php endforeach; ?>

