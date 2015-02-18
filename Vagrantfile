# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  src_dir = './'
  doc_root = '/vagrant_data/app/webroot'
  app_name = 'candycane'
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.synced_folder src_dir, "/vagrant_data", :create => true, :owner=> 'vagrant', :group=>'www-data', :mount_options => ['dmode=775,fmode=775']
  config.berkshelf.enabled = true
  config.omnibus.chef_version = :latest
  File.open('Berksfile', 'w').write <<-EOS
    source 'https://supermarket.chef.io'
    cookbook 'apt'
    cookbook 'php5_ppa', '~> 0.1.1'
    cookbook 'omusubi', git: "https://github.com/yandod/omusubi.git"
    cookbook 'java'
    cookbook 'selenium-grid', git: "https://github.com/yandod/selenium-grid.git"
  EOS
  config.vm.provision :chef_solo do |chef|
    chef.add_recipe "apt"
    chef.add_recipe "php5_ppa::from_ondrej"
    chef.add_recipe "omusubi"
    chef.add_recipe "java"
    chef.add_recipe "selenium-grid"
    versions = {};
    # if you would use php5.3 family with fast boot,  comment out lines bellow.
    versions['php5'] = '5.5.*'
    versions['php5-mysql'] = '5.5.*'
    versions['php5-pgsql'] = '5.5.*'
    versions['php5-curl'] = '5.5.*'
    versions['php5-mcrypt'] = '5.5.*'
    versions['php5-cli'] = '5.5.*'
    versions['php5-fpm'] = '5.5.*'
    versions['php-pear'] = '5.5.*'
    versions['php5-imagick'] = '3.*'
    versions['php5-intl'] = '5.5.*'
    versions['php5-sqlite'] = '5.5.*'
    chef.json = {doc_root: doc_root, 'versions' => versions}
  end

  config.vm.provision :shell, :inline => <<-EOS
    mysql -u root --execute  "create database if not exists #{app_name}"
    mysql -u root --execute  "create database if not exists test_#{app_name}"
    #cd /vagrant_data; composer update
    #cd /vagrant_data/app; yes | ./Console/cake schema update
  EOS

end
