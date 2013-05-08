# CandyCane #

CandyCane is a issue tracking system. The original implementation on which it is based, is [Redmine](http://www.redmine.org)

You can view a live demo of CandyCane, as well as the bug tracking for CandyCane here: [http://my.candycane.jp/](http://my.candycane.jp/)

* Continuous Integration is running on here: [https://travis-ci.org/yandod/candycane](https://travis-ci.org/yandod/candycane)
[![Build Status](https://travis-ci.org/yandod/candycane.png?branch=master)](https://travis-ci.org/yandod/candycane)
* Coverage report is genrated at Coverall [![Coverage Status](https://coveralls.io/repos/yandod/candycane/badge.png?branch=master)](https://coveralls.io/r/yandod/candycane)


## Installation ##

1. Extract all files, and place into a directory that is accessible to the web server, and able to run PHP.
2. Setup correct permissions on files and folders:
	* `chmod -R 777 app/Config`
	* `chmod -R 777 app/files`
	* `chmod -R 777 app/tmp`
	* `chmod -R 777 app/Plugin`
3. Access the site via your web server. If you installed into a subdirectory, then ensure that directory is in your URL: http://mysite.com/candycane
4. The step-by-step installer will appear.
5. Just use it!

## Updating to latest version ##

You need to copy these file and directories into extracted latest codes.
Currently we don't make database schema change.

- app/Config/database.php
- app/files
- app/Plugin


## Notes ##

Currently some features which are present in Redmine are not supported by CandyCane. These are:

- Repository viewer
- Forum
- Documents

CandyCane is using CakePHP v2.3.


## Contributors

- yandod
- halt
- Ignacio Albors
- k-kishida
- [Graham Weldon](http://grahamweldon.com) (predominant)
- akiyan
- Takuya Sato
- Yoshio HANAWA
- kaz29
- Dima
- Norio Suzuki
- hamaco
- kiang
- okonomi
- shin1x1
- Steve Grosbois
- Spenser Jones
- tomo
- hiromi2424
- Mindiell
- mzdakr
- Òscar Casajuana
- elboletaire
- Michito Suzuki
- Shogo Kawahara
- Sebastien pencreach
- Sardorbek Pulatov
- Hisateru Tanaka
- [Jose Gonzalez](http://josediazgonzalez.com) (savant)

We will appreciate any pull requests.

I try to merge as much as possible. Please fork the repository if you find something you want to fix, and submit a pull request.
