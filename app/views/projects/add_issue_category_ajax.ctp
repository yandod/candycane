<?php
header("Content-type: text/javascript; charset=utf-8");
$text = $form->input('Issue.category_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$issueCategories));
$text = preg_replace('/(\r?\n|\r\n?)/', "\\n", $text);
$text = preg_replace('/(\")/', "\\\"", $text);
echo "Element.replace(\"IssueCategoryId\", \"$text\");";
?>