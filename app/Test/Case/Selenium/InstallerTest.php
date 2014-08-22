<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yandod
 * Date: 2014/01/02
 * Time: 20:05
 * To change this template use File | Settings | File Templates.
 * mysql -u root -e "drop database if exists test_candycane;create database test_candycane;"; ./vendor/bin/phpunit app/Test/Case/Selenium/InstallerTest.php
 *
 */
class InstallerTest extends PHPUnit_Extensions_Selenium2TestCase
{
    //protected $captureScreenshotOnFailure = TRUE;
    //protected $screenshotPath = '/tmp/';
    //protected $screenshotUrl = 'http://localhost/screenshots';

    protected function setUp()
    {
        $this->setHost('127.0.0.1');
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://127.0.0.1/');
        //$this->setPort(80);
    }

    public function testInstallMySQL()
    {
        $this->url('http://127.0.0.1/cc_install/cc_install/');

        $this->waitUntil(function($testCase){
            return $testCase->title();
        });

        $this->assertEquals('Installation: Welcome - CandyCane', $this->title());

        $this->timeouts()->implicitWait(6000);
        $this->waitUntil(function($testCase){
            $str = $testCase->byId('next-link')->text();
            return !empty($str);
         },100000);


        $link = $this->byId('next-link');
        $this->assertEquals('Click here to begin installation', $link->text());
        $this->moveto($link);
        $this->click();

        $this->assertEquals('Step 1: Database - CandyCane', $this->title());

        $this->select($this->byId('InstallDatasource'))->selectOptionByValue("mysql");
        $input = $this->byId('InstallDatabase');
        $input->clear();
        $input->value('test_candycane');
        $form = $this->byId('InstallDatabaseForm');
        $form->submit();

        $this->waitUntil(function($testCase){
            return $testCase->title();
        });
        $this->assertEquals('Step 2: Run SQL - CandyCane', $this->title());

        $link = $this->byId('run-link');
        $this->moveto($link);
        $this->click();
        $this->waitUntil(function($testCase){
            return $testCase->title();
        });
        $this->assertEquals('Installation completed successfully - CandyCane', $this->title());

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
        $this->timeouts()->implicitWait(1000);

        $this->url('http://127.0.0.1/');
        $link = $this->byClassName('administration');
        $this->moveto($link);
        $this->click();

        $this->assertEquals('http://127.0.0.1/admin', $this->url());

        $link = $this->byLinkText('Settings');
        $this->moveto($link);
        $this->click();
        $this->assertEquals('http://127.0.0.1/settings', $this->url());

        $link = $this->byId('tab-notifications');
        $this->moveto($link);
        $this->click();
        $this->timeouts()->implicitWait(100);

        $input = $this->byId('SettingMailFrom');
        $this->assertEquals('candycane@example.com',$input->value());
        $input->clear();
        $input->value('candycane-autotest@example.com');
        $this->select($this->byId('SettingMailTransport'))->selectOptionByValue("Debug");
        $this->byXPath('//form[@action="/settings/edit?tab=notifications"]')->submit();
        $this->timeouts()->implicitWait(1000);
        $this->assertEquals('Successful update.',$this->byId('flashMessage')->text());
    }
}
