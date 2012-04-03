<!--<h2><%=l(:label_report_plural)%></h2>-->
<h2><?php echo h(__('Reports')); ?></h2>

<!--<h3><%=@report_title%></h3>-->
<h3><?php echo h(__($report_title)); ?></h3>
<!--<%= render :partial => 'details', :locals => { :data => @data, :field_name => @field, :rows => @rows } %>-->
<?php echo $this->element('reports/_details', array('data' => $data, 'field_name' => $field, 'rows' => $rows)); ?>
<br />
<!--<%= link_to l(:button_back), :action => 'issue_report' %>-->
<?php echo $this->Html->link(__('Back'), array('action' => 'issue_report', $project['identifier'])); ?>
