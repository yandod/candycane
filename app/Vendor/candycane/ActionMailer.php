<?php

/**
 * Created: Sat Aug 05 02:19:40 EDT 2006
 * 
 * A base class for application wide E-Mail sending/logic inspired
 * by the Ruby on Rails ActionMailer.
 * 
 * The Actionmailer is a hybrid of a Model and Controller since it
 * renders it's own views to be send out via mail. The easiest way to
 * use it within CakePHP is to create a class extending it inside the
 * models folder.
 * 
 * PHP versions 4 and 5
 *
 * Copyright (c) Felix Geisendorfer <info@fg-webdesign.de>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version         0.7.5
 * @copyright		Copyright (c) 2006, Felix Geisendorfer.
 * @link			http://www.fg-webdesign.de
 * @link            http://wiki.rubyonrails.org/rails/pages/ActionMailer
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class ActionMailer extends Component
{
    var $name = 'ActionMailer';
    
    /**
     * Enables some checks to prevent your server from being turned into a spamming machine ; )
     *
     * @var unknown_type
     */
    var $securityMode = true;    
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $data = null;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $recipients = null;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $sender = null;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $subject = null;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $headers = null;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $layout = 'mail';
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $view = 'View';
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $_viewClass = null;
    
    /**
     * Used to set variables that will be available in the e-mail view
     *
     * @param unknown_type $one
     * @param unknown_type $two
     */
	function set($one, $two = null) 
	{
        if (!is_array($this->data))
	       $this->data = array();
	       
	    if (!is_array($one)) 
        {
            $data = array($one => $two);
        } else {
        	$data = $one;
        }
        
        $this->data = array_merge($this->data, $data);
        
        return $data;
	}
	
	/**
	 * Set's a header value for the e-mail to be send.
	 * 
	 * Example: ActionMailer::setHeader('from', 'info@fg-webdesign.de');
	 *
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	function setHeader($name, $value)
	{
        if (!is_array($this->headers))
	       $this->headers = array();
        
	    $this->headers[$name] = $value;
	}
	
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function __generateHeader()
	{
        if (!is_array($this->headers))
            $this->headers = array();
        
        $headers = array();
            
        foreach ($this->headers as $key => $val)
        {
            $headers[] = $key.': '.$val;
        }
        
        return join("\n", $headers);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $email
	 * @param unknown_type $name
	 * @return unknown
	 */
	function setRecipient($email, $name = null)
	{
	   $this->recipients = array();
	   return $this->addRecipient($email, $name);    
	}
	
	function setRecipients($emails)
	{
	   $this->recipients = $emails;
	}	
	/**
	 * Enter description here...
	 * 
	 * Note: I experienced problems when using the $name field on my local dev machine (win32)
	 *       in case you hit a problem, try to only specify the email adresses for your recipients
	 *       if possible
	 *
	 * @param unknown_type $email
	 * @param unknown_type $name
	 * @return unknown
	 */
	function addRecipient($email, $name = null)
	{
        if (!is_array($this->recipients))
	       $this->recipients = array();	    
	     
        if ($adress = $this->__makeRFC2822MailAdress($email, $name))
	       $this->recipients[] = $adress;
        else
            return false;

        return true;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $email
	 * @param unknown_type $name
	 * @return unknown
	 */
	function setSender($email, $name = null)
	{
        if ($adress = $this->__makeRFC2822MailAdress($email, $name))
	       $this->sender = $adress;
        else
            return false;

        return true;	    
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $subject
	 * @return unknown
	 */
	function setSubject($subject)
	{
        if ($this->securityMode==true)
        {
            // Make sure nobody can inject own headers into our email
            if (preg_match("/[\r\n]/", $subject))
                return false;
        }
        
        $this->subject = $subject;
	}
	
	/**
	 * Uses $email and $name to generate a RFC 2822 compatible e-mail
	 * adress. If securityMode is enabled some checks on the input are
	 * performed which may cause the function to return false instead of
	 * the generated adress.
	 *
	 * @link http://www.faqs.org/rfcs/rfc2822
	 * @param string $email
	 * @param string $name
	 * @return mixed
	 */
	function __makeRFC2822MailAdress($email, $name = null)
	{
        if ($this->securityMode==true)
        {
            // Make sure nobody can inject own headers into our email
            if (preg_grep("/[\r\n]/", array($email, $name)))
                return false;
            
            if (!Validation::email($email))
                return false;
        }

	    if (empty($name))
            $adress = $email;
        else 
            $adress = $name.' <'.$email.'>';

        return $adress;
	}

	/**
	 * Resets the class before preparing/sending a new email
	 *
	 */
	function reset()
	{
	    $this->headers = array();
	    $this->recipients = array();
	    $this->sender = null;
	    $this->data = array();
	}
	
	/**
	 * Alternative name for deliver()
	 *
	 * @return unknown
	 */
	function send($fct = null, $params = null)
	{
	    return $this->deliver($fct, $params);
	}
	
    /**
     * Tries to deliver an email. Returns true on sucess.
     *
     * @return boolean
     */
	function deliver($fct = null, $params = null)
	{
	    if (!empty($fct))
	    {
            if (method_exists($this, $fct))
            {
                $this->reset();
                call_user_func_array(array(&$this, $fct), $params);
            }
            else 
            {
                 return false;
            }
	    }
      if (empty($this->recipients)) {
        return;
      }
	    
	    $this->set('recipients', $this->recipients);
	    $this->set('sender', $this->sender);
	    $this->set('subject', $this->subject);

	    $mail_body = $this->render($fct);
	    $to = join(', ', $this->recipients);
	    $from = $this->sender;
	    $subject = $this->subject;


        $headers = $this->__generateHeader();
		if (Configure::read('debug')) {
			$debug = array(
				'subject' => $subject,
				'mail_body' => $mail_body,
				'headers' => $headers
			);
			CakeLog::write(LOG_DEBUG,'Recipients:' . $to);
			CakeLog::write(LOG_DEBUG,var_export($debug,true));
			return true;
		}
		if (function_exists("mb_send_mail")) {
			return mb_send_mail($to, $subject, $mail_body, $headers);
		} else {
			return mail($to, $subject, $mail_body, $headers);
		}
	}
	
	/**
	 * Enter description here...
	 *
	 */
	function beforeRender()
	{

	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 * @param unknown_type $layout
	 * @param unknown_type $file
	 * @return unknown
	 */
	function render($action = null, $layout = null, $file = null) 
	{
		$viewClass = $this->view;
		if ($this->view != 'View') 
		{
			$viewClass = $this->view . 'View';
			loadView($this->view);
		}
		
		$this->beforeRender();
		
		$ctrl =& $this->controller;	
		$ctrl->layout = $this->layout;
		$ctrl->viewPath = Inflector::underscore($this->name);
		$ctrl->set($this->data);
		$this->_viewClass =& new $viewClass($ctrl);
		return $this->_viewClass->render($action.'.text.plain', $layout);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $method
	 * @param unknown_type $params
	 * @param unknown_type $return
	 * @return unknown
	 */
    function __call($method, $params) 
    {
        if (preg_match('/^(.+)_(.+)$/U', $method, $match))
        {
            list($method, $action, $fct) = $match;
            if ($action=='send' || $action=='deliver')
            {
                $return = $this->deliver($fct, $params);
            }
        }
        
        return true;
    }
}


