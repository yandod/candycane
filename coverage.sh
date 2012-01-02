#!/bin/sh
root_dir=`pwd`
simpletest_coverage_dir="$root_dir/vendors/simpletest/extensions/coverage"

php_option="-d include_path=.:/usr/share/pear:$simpletest_coverage_dir -d auto_prepend_file=$simpletest_coverage_dir/autocoverage.php"

start() {
 php $php_option $simpletest_coverage_dir/bin/php-coverage-open.php --include=$root_dir/app/controllers/.*\.php$ --include=$root_dir/app/models/.*\.php$ --include=$root_dir/app/vendors/candycane/.*\.php$ v--exclude='$root_dir/.*/tests/.*' --maxdepth=1
}

close() {
 php $php_option $simpletest_coverage_dir/bin/php-coverage-close.php
}

test() {
 php $php_option $root_dir/cake/console/cake.php testsuite app all
}

report() {
 php $php_option $simpletest_coverage_dir/bin/php-coverage-report.php
}

case "$1" in
 start)
       start
       ;;
 close)
       close
       ;;
 report)
       report
       ;;
 test)
       test
       ;;
 all)  start; test; close; report;
       ;;
 *)
       echo "start|test|close|report|all"
esac
