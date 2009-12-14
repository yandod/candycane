<h2><%= l(@enumeration.option_name) %>: <%=l(:label_enumeration_new)%></h2>

<% form_tag({:action => 'create'}, :class => "tabular") do %>
  <?php echo $this->element('enumerations/_form') ?>
  <%= submit_tag l(:button_create) %>
<% end %>
