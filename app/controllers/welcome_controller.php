<?php
/**
 * welcome_controller.php
 *
 */

/**
 * WelcomeController
 *
 */
class WelcomeController extends AppController
{
    var $uses = array('News','Project');

    /**
     * index
     *
     */
    function index()
    {
        $user = array(
            'id' => 3,
            'name' => 'yando',
            'logged' => true,
        );

        $this->set('news',$this->News->latest($user));
        $this->set('projects',$this->Project->latest($user));
    }
}
