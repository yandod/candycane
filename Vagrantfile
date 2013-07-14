# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "candycane"
  # config.vm.box_url = "http://domain.com/path/to/above.box"
  src_dir = './'
  doc_root = '/vagrant_data/app/webroot'
  config.vm.synced_folder src_dir, "/vagrant_data", :create => true, :owner=> 'vagrant', :group=>'www-data', :extra => 'dmode=775,fmode=775'
end
