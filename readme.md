# CandyCane #

CandyCane is a issue tracking system. The original implementation on which it is based, is [Redmine](http://www.redmine.org)

You can view a live demo of CandyCane, as well as the bug tracking for CandyCane here: [http://my.candycane.jp/](http://my.candycane.jp/)

Continuous Integration is running on here: [http://ci.candycane.jp:8080/](http://ci.candycane.jp:8080/)


## Installation ##

1. Extract all files, and place into a directory that is accessible to the web server, and able to run PHP.
2. Setup correct permissions on files and folders:
	* chmod -R 777 app/config
	* chmod -R 777 app/files
	* chmod -R 777 app/tmp
	* chmod -R 777 app/plugins 
3. Access the site via your web server. If you installed into a subdirectory, then ensure that directory is in your URL: http://mysite.com/candycane
4. The step-by-step installer will appear.
5. Just use it!


## Notes ##

Currently some features which are present in Redmine are not supported by CandyCane. These are:

- Repository viewer
- Forum
- Documents

CandyCane is using CakePHP v1.2. But using some PHP5 syntax in codes.


## Contributors

- yandod
- halt
- k-kishida
- akiyan
- [Graham Weldon](http://grahamweldon.com) (predominant)
- Takuya Sato
- Yoshio HANAWA
- kaz29
- Norio Suzuki
- shin1x1
- Steve Grosbois
- Spenser Jones
- tomo
- hiromi2424

We will appreciate any pull requests.

I try to merge as much as possible. Please fork the repository if you find something you want to fix, and submit a pull request.
