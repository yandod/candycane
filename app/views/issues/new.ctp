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
<?php echo $form->create('Issue', array('action'=>'add', 'class'=>"tabular", 'enctype'=>"multipart/form-data")); ?>
  <div class="box">
    <p>
      <?php echo $form->label('tracker_id', __('Tracker', true).'<span class="required"> *</span>'); ?>
      <?php echo $form->input('tracker_id', array('div'=>false, 'label'=>false)); ?></p>
    </p>
    <script type="text/javascript">
    //<![CDATA[
    new Form.Element.EventObserver('IssueTrackerId', function(element, value) {new Ajax.Updater('content', '/projects/test/issues/new', {asynchronous:true, evalScripts:true, parameters:Form.serialize('IssueAddForm')})})
    //]]>
    </script>
    <hr />
    <div id="issue_descr_fields" >
      <p>
        <?php echo $form->label('subject', __('Subject', true).'<span class="required"> *</span>'); ?>
        <?php echo $form->input('subject', array('div'=>false, 'label'=>false)); ?></p>
      </p>
      <p>
        <?php echo $form->label('description', __('Description', true)); ?>
        <?php echo $form->input('description', array('type'=>'text', 'cols'=>"60", 'div'=>false, 'label'=>false, 'class'=>"wiki-edit")); ?></p>
      </p>
    </div>
    <div class="splitcontentleft">
      <p>
        <?php echo $form->label('status_id', __('Status', true).'<span class="required"> *</span>'); ?>
        <?php echo $form->input('status_id', array('div'=>false, 'label'=>false)); ?></p>
      </p>
      <p>
        <?php echo $form->label('priority_id', __('Priority', true).'<span class="required"> *</span>'); ?>
        <?php echo $form->input('priority_id', array('div'=>false, 'label'=>false)); ?>
      </p>
      <p>
        <?php echo $form->label('assigned_to_id', __('Assigned to', true)); ?>
        <?php echo $form->input('assigned_to_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$assignableUsers)); ?>
      </p>
      <p>
        <?php echo $form->label('issue_category_id', __('Category', true)); ?>
        <?php echo $form->input('issue_category_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$issueCategories)); ?>
        <a href="/projects/test/issues/new" class="small" onclick="promptToRemote('新しいカテゴリ', 'category[name]', '/projects/add_issue_category/test'); return false;" tabindex="199">新しいカテゴリ</a></p>
      </p>
      <p>
        <?php echo $form->label('fixed_version_id', __('Target version', true)); ?>
        <?php echo $form->input('fixed_version_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$fixedVersions)); ?>
      </p>
    </div>
    <div class="splitcontentright">
      <p>
        <?php echo $form->label('start_date', __('start_date', true)); ?>
        <?php echo $form->input('start_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
      </p>
      <p>
        <?php echo $form->label('due_date', __('due_date', true)); ?>
        <?php echo $form->input('due_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
      </p>
      <p>
        <?php echo $form->label('estimated_hours', __('estimated_hours', true)); ?>
        <?php echo $form->input('estimated_hours', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text'));__('Hours');?>
      </p>
      <p>
        <?php echo $form->label('done_ratio', __('done_ratio', true).'%'); ?>
        <?php echo $form->input('done_ratio', array('type'=>'select', 'div'=>false, 'label'=>false, 'options'=>array(0=>'0 %', 10=>'10 %', 20=>'20 %', 30=>'30 %', 40=>'40 %', 50=>'50 %', 60=>'60 %', 70=>'70 %', 80=>'80 %', 90=>'90 %', 100=>'100 %'))); ?>
      </p>
    </div>

<div style="clear:both;"> </div>

<div class="splitcontentleft">



    <p><label for="issue_custom_field_values_1">カスタム入力 <span class="required">*</span></label><input id="issue_custom_field_values_1" name="data[Issue][custom_field_values][1]" type="text" value="" /></p>

    <p><label for="issue_custom_field_values_3">カスタム３</label><select id="issue_custom_field_values_3" name="data[Issue][custom_field_values][3]"><option></option><option value="高い">高い</option>
<option value="普通">普通</option>
<option value="安い">安い</option></select></p>

</div><div class="splitcontentright">
</div>
<div style="clear:both;"> </div>



<p><label>ファイル</label><span id="attachments_fields">
<input name="attachments[1][file]" size="30" type="file" /><input name="attachments[1][description]" size="60" type="text" value="" />
<em>任意のコメント</em>
</span>
<br />
<small><a href="#" onclick="addFileField(); return false;">別のファイルを追加</a>
(最大サイズ: 5 MB)

</small>
</p>


<p><label>Watchers</label>
<label class="floating"><input id="issue[watcher_user_ids][]" name="data[Issue][watcher_user_ids][]" type="checkbox" value="4" /> ichiro suzuki</label>
<label class="floating"><input id="issue[watcher_user_ids][]" name="data[Issue][watcher_user_ids][]" type="checkbox" value="1" /> Redmine Admin</label>
<label class="floating"><input id="issue[watcher_user_ids][]" name="data[Issue][watcher_user_ids][]" type="checkbox" value="3" /> 健一郎 岸田</label>
</p>



<script src="/js/jstoolbar/jstoolbar.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/textile.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/lang/jstoolbar-ja.js?1236399204" type="text/javascript"></script><script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('IssueDescription')); toolbar.setHelpLink('テキストの書式: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;">ヘルプ</a>'); toolbar.draw();
//]]>
</script>

    </div>
  <?php echo $form->submit(__('Create', true)); ?>
    <input name="continue" type="submit" value="Create and continue" />
    <a accesskey="r" href="#" onclick="new Ajax.Updater('preview', '/projects/test/issues/preview', {asynchronous:true, evalScripts:true, method:'post', onComplete:function(request){Element.scrollTo('preview')}, parameters:Form.serialize('issue-form')}); return false;">プレビュー</a>


        <script type="text/javascript">
//<![CDATA[
Form.Element.focus('IssueSubject');
//]]>
</script>
<?php echo $form->end(); ?>

<div id="preview" class="wiki"></div>




    </div>
</div>
