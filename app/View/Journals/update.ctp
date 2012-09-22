<?php
header("Content-type: text/javascript; charset=utf-8");
$journal_id = $journal['Journal']['id'];
if($delete) {
  # journal was destroyed
  echo "Element.remove(\"change-$journal_id\");";
} else {
  $reply_links = $this->Candy->authorize_for(array('controller'=>'issues', 'action'=>'edit'));
  $text = $this->Journals->render_notes($journal, $currentuser, array('reply_links'=>$reply_links));
  $text = preg_replace('/(\r?\n|\r\n?)/', "\\n", $text);
  $text = preg_replace('/(\")/', "\\\"", $text);
  echo "Element.replace(\"journal-$journal_id-notes\", \"$text\");";
  echo "Element.show(\"journal-$journal_id-notes\");";
  echo "Element.remove(\"journal-$journal_id-form\");";
}
// TODO call_hook
// call_hook(:view_journals_update_rjs_bottom, { :page => page, :journal => @journal })
?>

