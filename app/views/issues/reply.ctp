<?php
$content = sprintf(__('%s wrote:',true), $candy->format_username($user));
$text = preg_replace('/<pre>((.|\s)*?)<\/pre>/m', '[...]', trim($text));
$text = preg_replace('/\"/', '/\\\"/', $text);
$text = preg_replace('/(\r?\n|\r\n?)/', "\\n> ", $text);
$text .= "\\n\\n"; 
$content .= $text;

echo '$(\'notes\').value = "'.$content.'";'."\n";
echo 'Element.show("update");'."\n";
echo "Form.Element.focus('notes');"."\n";
echo "Element.scrollTo('update');"."\n";
echo "$('notes').scrollTop = $('notes').scrollHeight - $('notes').clientHeight;"."\n";
?>