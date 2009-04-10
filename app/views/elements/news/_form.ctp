<%= error_messages_for 'news' %>
<div class="box tabular">
<!--
<p><%= f.text_field :title, :required => true, :size => 60 %></p>
<p><%= f.text_area :summary, :cols => 60, :rows => 2 %></p>
<p><%= f.text_area :description, :required => true, :cols => 60, :rows => 15, :class => 'wiki-edit' %></p>
-->
<p><?php echo $form->input( 'title', aa('div', false) ) ; ?></p>
<p><?php echo $form->input( 'summary', aa('div', false) ) ; ?></p>
<p><?php echo $form->input( 'description', aa('div', false) ) ; ?></p>
</div>

<%= wikitoolbar_for 'news_description' %>
