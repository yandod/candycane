<?php
/**
 * welcome_controller.php
 *
 */
class WelcomeController extends AppController
{
    var $uses = array('User', 'News', 'Project');

    /**
     * index
     *
     */
    function index()
    {
      /*
        $user = $this->User->findById(3);

        if ($user == false) {
            $user = array(
                'id' => 3,
                'name' => 'yando',
                'logged' => true,
            );
        }
       */

        $this->set('news',$this->News->latest($this->current_user));
        $this->set('projects',$this->Project->latest($this->current_user));
    }
}
