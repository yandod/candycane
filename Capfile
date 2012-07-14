load 'deploy' 

role :app, "candycane.sakura.ne.jp"
set :scm, :git
#set :repository, "git@thechaw.com:candycane.git"
set :repository, "git@github.com:yandod/candycane.git"
set :branch, 'master'
set :user, "candycane"

set :application, "candycane"
set :deploy_via, :checkout
set :deploy_to, "/home/candycane/apps/#{application}"
set :use_sudo, false
#set :git_enable_submodules, 1

namespace :deploy do
  # disable restarting
  task :restart do
  end

  #
  # place core.php and database.php in shared/config-master/
  # place tcpdf and tcpdf_php4 in shared/lib/
  #
  task :finalize_update do
    run <<-CMD
      cp #{shared_path}/config-master/database.php #{latest_release}/app/Config/ &&
      cp #{shared_path}/config-master/core.php #{latest_release}/app/Config/ &&
      ln -s #{shared_path}/lib/tcpdf #{latest_release}/app/vendors/tcpdf &&
      rm -Rf #{latest_release}/app/files &&
      ln -s #{shared_path}/files #{latest_release}/app/files &&
      rm -Rf #{latest_release}/app/Plugin &&
      ln -s #{shared_path}/Plugin #{latest_release}/app/Plugin
    CMD
  end
end
