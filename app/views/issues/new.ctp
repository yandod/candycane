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


<p><label for="issue_priority_id">優先度<span class="required"> *</span></label><select id="issue_priority_id" name="data[Issue][priority_id]"><option value="3">低め</option>

<option value="4" selected="selected">通常</option>
<option value="5">高め</option>
<option value="6">急いで</option>
<option value="7">今すぐ</option></select></p>
<p><label for="issue_assigned_to_id">担当者</label><select id="issue_assigned_to_id" name="data[Issue][assigned_to_id]"><option value=""></option>
<option value="4">ichiro suzuki</option>
<option value="1">Redmine Admin</option>
<option value="3">健一郎 岸田</option></select></p>

<p><label for="issue_category_id">カテゴリ</label><select id="issue_category_id" name="data[Issue][category_id]"><option value=""></option>
<option value="1">カテゴリ１</option></select>
<a href="/projects/test/issues/new" class="small" onclick="promptToRemote('新しいカテゴリ', 'category[name]', '/projects/add_issue_category/test'); return false;" tabindex="199">新しいカテゴリ</a></p>

<p><label for="issue_fixed_version_id">Target version</label><select id="issue_fixed_version_id" name="data[Issue][fixed_version_id]"><option value=""></option>
<option value="1">バージョン１</option>
<option value="2">バージョン２</option></select></p>
</div>

<div class="splitcontentright">
<p><label for="issue_start_date">開始日</label><input id="issue_start_date" name="data[Issue][start_date]" size="10" type="text" value="2009-04-12" /></p>
<p><label for="issue_due_date">期限日</label><input id="issue_due_date" name="data[Issue][due_date]" size="10" type="text" /></p>
<p><label for="issue_estimated_hours">予定工数</label><input id="issue_estimated_hours" name="data[Issue][estimated_hours]" size="3" type="text" /> 時間</p>
<p><label for="issue_done_ratio">進捗 %</label><select id="issue_done_ratio" name="data[Issue][done_ratio]"><option value="0" selected="selected">0 %</option>
<option value="10">10 %</option>
<option value="20">20 %</option>
<option value="30">30 %</option>

<option value="40">40 %</option>
<option value="50">50 %</option>
<option value="60">60 %</option>
<option value="70">70 %</option>
<option value="80">80 %</option>
<option value="90">90 %</option>
<option value="100">100 %</option></select></p>
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
