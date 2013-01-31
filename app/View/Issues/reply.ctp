<?php
$content = sprintf(__("%s wrote:\\n>"), $this->Candy->format_username($user));
//$text = preg_replace('/<pre>((.|\s)*?)<\/pre>/m', '[...]', trim($text));
//$text = preg_replace('/\"/', '/\\\"/', $text);
$text = preg_replace('/(\r?\n|\r\n?)/', "\\n> ", $text);
//$text .= "\\n\\n"; 
$content .= $text;

//header("Content-type: text/javascript; charset=utf-8");
echo '<script>';
echo '$(\'notes\').value = "'.$content.'";'."\n";
echo 'Element.show("update");'."\n";
echo "Form.Element.focus('notes');"."\n";
echo "Element.scrollTo('update');"."\n";
echo "$('notes').scrollTop = $('notes').scrollHeight - $('notes').clientHeight;"."\n";
echo '</script>';
