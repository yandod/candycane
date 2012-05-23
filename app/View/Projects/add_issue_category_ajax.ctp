<?php
$text = $this->Form->input('Issue.category_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$issue_categories));
$text = preg_replace('/(\r?\n|\r\n?)/', "\\n", $text);
$text = preg_replace('/(\")/', "\\\"", $text);
echo "Element.replace(\"IssueCategoryId\", \"$text\");";
exit;
?>
