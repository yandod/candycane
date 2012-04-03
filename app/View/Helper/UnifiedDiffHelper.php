<?php
class UnifiedDiffHelper extends AppHelper
{
  function getUnifiedDiff($diff, $options=array()) {
    $defaults = array('max_lines' =>$this->Settings->diff_max_lines_displayed);
    $united_diff = new UnifiedDiff($diff, array_merge($defaults, $options));

    return $united_diff;
  }
}

# Class used to parse unified diffs
class UnifiedDiff extends Object
{
  var $truncated = false;
  var $data = array();

  function __construct($diff, $options=array()) {
    $options = array_merge(array('type'=>false, 'max_lines'=>false), array_intersect_key($options, array('type'=>false, 'max_lines'=>false)));
    $diff_type = $options['type'];
    if (empty($diff_type)) {
      $diff_type = 'inline';
    }

    $lines = 0;
    $this->truncated = false;
    $diff_table = new DiffTable($diff_type);
    foreach ($diff as $line) {
      if (preg_match('/^(---|\+\+\+) (.*)$/', $line)) {
        if ($diff_table->length() > 1) {
          $this->request->data[] = $diff_table;
        }
        $diff_table = new DiffTable($diff_type);
      }
      $diff_table->add_line($line);
      $lines += 1;
      if ($options['max_lines'] && $lines > $options['max_lines']) {
        $this->truncated = true;
        break;
      }
    }
    if (!empty($diff_table)) {
      $this->request->data[] = $diff_table;
    }
  }

  function is_truncated() {
    return $this->truncated;
  }
}

# Class that represents a file diff
class DiffTable extends Object
{
  var $data = array();

  var $file_name;
  var $line_num_l;
  var $line_num_r;

  var $_parsing = false;
  var $_nb_line = 1;
  var $_start = false;
  var $_before = 'same';
  var $_second = true;
  var $_type = null;

  # Initialize with a Diff file and the type of Diff View
  # The type view must be inline or sbs (side_by_side)
  function __construct($type="inline") {
    $this->_type = $type;
  }

  # Function for add a line of this Diff
  function add_line($line) {
    if (!$this->_parsing) {
      if (preg_match('/^(---|\+\+\+) (.*)$/', $line, $matches)) {
        $this->file_name = $matches[2];
        return false;
      } elseif (preg_match('/^@@ (\+|\-)(\d+)(,\d+)? (\+|\-)(\d+)(,\d+)? @@/', $line, $matches)) {
        $this->line_num_l = $matches[2];
        $this->line_num_r = $matches[5];
        $this->_parsing = true;
      }
    } else {
      if (preg_match('/^[^\+\-\s@\\\]/', $line)) {
        $this->_parsing = false;
        return false;
      } elseif (preg_match('/^@@ (\+|\-)(\d+)(,\d+)? (\+|\-)(\d+)(,\d+)? @@/', $line, $matches)) {
        $this->line_num_l = $matches[2];
        $this->line_num_r = $matches[5];
      } elseif ($this->_parse_line($line, $this->_type)) {
        $this->_nb_line += 1;
      }
    }
    return true;
  }

  function inspect() {
    $this->log('### DIFF TABLE ###');
    $this->log("file : {$this->file_name}");
    foreach ($this->request->data as $d) {
      $d->inspect();
    }
  }

  # Test if is a Side By Side type
  function _sbs($type, $func) {
    if ($this->_start && $this->_type == "sbs") {
      if ($this->_before == $func && $this->_second) {
        $tmp_nb_line = $this->_nb_line;
        $this->request->data[$tmp_nb_line] = new Diff();
      } else {
        $this->_second = false;
        $tmp_nb_line = $this->_start;
        $this->_start += 1;
        $this->_nb_line -= 1;
      }
    } else {
      $tmp_nb_line = $this->_nb_line;
      $this->_start = $this->_nb_line;
      $this->request->data[$tmp_nb_line] = new Diff();
      $this->_second = true;
    }
    if (empty($this->request->data[$tmp_nb_line])) {
      $this->_nb_line += 1;
      $this->request->data[$tmp_nb_line] = new Diff();
    }
    return $this->request->data[$tmp_nb_line];
  }

  function _parse_line(&$line, $type="inline") {
    if (substr($line, 0, 1) == "+") {
      $diff = $this->_sbs($type, 'add');
      $this->_before = 'add';
      $diff->line_right = h(substr($line,1,-1));
      $diff->nb_line_right = $this->line_num_r;
      $diff->type_diff_right = 'diff_in';
      $this->line_num_r += 1;
      return true;
    } elseif (substr($line,0,1) == "-") {
      $diff = $this->_sbs($type, 'remove');
      $this->_before = 'remove';
      $diff->line_left = h(substr($line, 1, -1));
      $diff->nb_line_left = $this->line_num_l;
      $diff->type_diff_left = 'diff_out';
      $this->line_num_l += 1;
      return true;
    } elseif (preg_match('/\s/', substr($line, 0, 1))) {
      $this->_before = 'same';
      $this->_start = false;
      $diff = new Diff();
      $diff->line_right = h(substr($line, 1, -1));
      $diff->nb_line_right = $this->line_num_r;
      $diff->line_left = h(substr($line, 1, -1));
      $diff->nb_line_left = $this->line_num_l;
      $this->request->data[$this->_nb_line] = $diff;
      $this->line_num_l += 1;
      $this->line_num_r += 1;
      return true;
    } else {
      $strs = str_split($line, 1);
      $strs[0] = "\\";
      $line = implode("", $strs);
      return true;
    }
  }
  function length() {
    return count($this->request->data);
  }
}

# A line of diff
class Diff extends Object
{
  var $nb_line_left = '';
  var $line_left = '';
  var $nb_line_right = '';
  var $line_right = '';
  var $type_diff_right = '';
  var $type_diff_left = '';

  function inspect() {
    $this->log('### Start Line Diff ###');
    $this->log($this->nb_line_left);
    $this->log($this->line_left);
    $this->log($this->nb_line_right);
    $this->log($this->line_right);
  }
}
