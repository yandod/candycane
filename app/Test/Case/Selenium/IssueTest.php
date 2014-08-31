<?php
/**
 * Created by PhpStorm.
 * User: yandod
 * Date: 2014/08/22
 * Time: 11:01
 */

class IssueTest extends PHPUnit_Extensions_Selenium2TestCase {

    protected function setUp()
    {
        $this->setHost('127.0.0.1');
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://127.0.0.1/');
        //$this->setPort(80);
    }

    public function testDestroyRelation()
    {

        //login
        $this->url('http://127.0.0.1/account/login');
        $input = $this->byName('data[User][username]');
        $input->clear();
        $input->value('admin');
        $input = $this->byName('data[User][password]');
        $input->clear();
        $input->value('admin');
        $button = $this->byName('login');
        $this->moveto($button);
        $this->byId('UserLoginForm')->submit();

        //navigate
        $this->url('http://127.0.0.1/projects/sampleproject');
        $this->assertEquals('Overview - Sample Project - CandyCane', $this->title());
        $link = $this->byLinkText('New issue');
        $this->moveto($link);
        $this->click();
        $this->timeouts()->implicitWait(300);

        //new issue
        $input = $this->byId('IssueSubject');
        $input->value('We are in Spain');
        $input = $this->byId('description');
        $input->value('Hola.');
        $this->byId('IssueAddForm')->submit();

        //add more issue
        $link = $this->byLinkText('New issue');
        $this->moveto($link);
        $this->click();
        $input = $this->byId('IssueSubject');
        $input->value('We are in Madrid');
        $input = $this->byId('description');
        $input->value('Madrid is center of Spain.');
        $this->byId('IssueAddForm')->submit();

        //link issue
        $link = $this->byLinkText('Add');
        $this->moveto($link);
        $this->click();
        $input = $this->byId('IssueRelationIssueToId');
        $pieces = explode('/',$this->url());
        $current_id = array_pop($pieces) - 1;
        $input->value($current_id);
        $this->byId('new-relation-form')->submit();
        $this->timeouts()->implicitWait(800);

        //delete relation
        $link = $this->byXPath("//a[@title='Delete relation']");
        $this->moveto($link);
        $this->click();
        $this->timeouts()->implicitWait(300);

        file_put_contents('app/tmp/logs/screenshot.png',$this->currentScreenshot());
    }

    public function onNotSuccessfulTest(Exception $e)
    {
        file_put_contents('app/tmp/logs/screenshot.png',$this->currentScreenshot());
        throw $e;
    }
}
