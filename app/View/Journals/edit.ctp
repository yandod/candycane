<?php 
$text = $this->element('journals/notes_form', compact('journal'));
//$text = preg_replace('/(\r?\n|\r\n?)/', "\\n", $text);
//$text = preg_replace('/(\")/', "\\\"", $text);
//header("Content-type: text/javascript; charset=utf-8");
//echo 'Element.hide("journal-'.$journal['Journal']['id'].'-notes");'."\n";
//echo 'Element.insert("journal-'.$journal['Journal']['id'].'-notes", { after: "'.$text.'" });';
echo $text;
?>
