<h1><%=h @message.board.project.name %> - <%=h @message.board.name %>: <%= link_to @message.subject, @message_url %></h1>
<em><%= @message.author %></em>

<%= textilizable(@message, :content, :only_path => false) %>
