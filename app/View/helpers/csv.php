<?php
if (!PHP5) {
  App::import('vendor', 'csv_php4/csv_helper');
} else {
  App::import('vendor', 'csv/csv_helper');
}
?>