<!--
<h2><%=l(:label_issue_new)%></h2>

<% labelled_tabular_form_for :issue, @issue, 
                             :html => {:multipart => true, :id => 'issue-form'} do |f| %>
    <%= error_messages_for 'issue' %>
    <div class="box">
    <%= render :partial => 'issues/form', :locals => {:f => f} %>
    </div>
    <%= submit_tag l(:button_create) %>
    <%= submit_tag l(:button_create_and_continue), :name => 'continue' %>
    <%= link_to_remote l(:label_preview), 
                       { :url => { :controller => 'issues', :action => 'preview', :project_id => @project },
                         :method => 'post',
                         :update => 'preview',
                         :with => "Form.serialize('issue-form')",
                         :complete => "Element.scrollTo('preview')"
                       }, :accesskey => accesskey(:preview) %>
											 
		<%= javascript_tag "Form.Element.focus('issue_subject');" %>
<% end %>

<div id="preview" class="wiki"></div>

<% content_for :header_tags do %>
    <%= stylesheet_link_tag 'scm' %>
<% end %>
-->
<h2><?php $candy->html_title();__('New issue'); ?></h2>
<?php echo $form->create('Issue', array('url'=>'/projects/'.$mainProject['Project']['identifier'].'/issues/add', 'class'=>"tabular", 'enctype'=>"multipart/form-data", 'id'=>'IssueAddForm')); ?>
  <div class="box">
    <?php echo $this->renderElement('issues/form', compact(
      'trackers', 'statuses', 'priorities', 'assignable_users', 'issueCategories', 
      'fixedVersions', 'customFieldValues', 'members')); ?>
  </div>
  <?php echo $form->submit(__('Create', true), array('div'=>false)); ?>
  <?php echo $form->submit(__('Create and continue', true), array('div'=>false, 'name'=>'continue')); ?>
  <a accesskey="r" href="#" onclick="new Ajax.Updater('preview', '/projects/test/issues/preview', {asynchronous:true, evalScripts:true, method:'post', onComplete:function(request){Element.scrollTo('preview')}, parameters:Form.serialize('IssueAddForm')}); return false;"><?php __('Preview');?></a>


<script type="text/javascript">
//<![CDATA[
Form.Element.focus('IssueSubject');
//]]>
</script>
<?php echo $form->end(); ?>

<div id="preview" class="wiki"></div>




    </div>
</div>
