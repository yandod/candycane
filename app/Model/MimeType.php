<?php
# redMine - project management software
# Copyright (C) 2006-2007  Jean-Philippe Lang
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

/**
 * Mime Type Object
 *
 * @package candycane
 */
class MimeType {

/**
 * Mime types
 *
 * @var array
 */
	public $MIME_TYPES = array(
		'text/plain' => 'txt,tpl,properties,patch,diff,ini,readme,install,upgrade',
		'text/css' => 'css',
		'text/html' => 'html,htm,xhtml',
		'text/jsp' => 'jsp',
		'text/x-c' => 'c,cpp,cc,h,hh',
		'text/x-java' => 'java',
		'text/x-javascript' => 'js',
		'text/x-html-template' => 'rhtml',
		'text/x-perl' => 'pl,pm',
		'text/x-php' => 'php,php3,php4,php5',
		'text/x-python' => 'py',
		'text/x-ruby' => 'rb,rbw,ruby,rake',
		'text/x-csh' => 'csh',
		'text/x-sh' => 'sh',
		'text/xml' => 'xml,xsd,mxml',
		'text/yaml' => 'yml,yaml',
		'image/gif' => 'gif',
		'image/jpeg' => 'jpg,jpeg,jpe',
		'image/png' => 'png',
		'image/tiff' => 'tiff,tif',
		'image/x-ms-bmp' => 'bmp',
		'image/x-xpixmap' => 'xpm',
	);

/**
 * Extensions
 *
 * @var array
 */
 	public $EXTENSIONS = array();

/**
 * Constructor
 *
 */
	public function __construct() {
		foreach ($this->MIME_TYPES as $type => $exts) {
			foreach (explode(',', $exts) as $ext) {
				$this->EXTENSIONS[trim($ext)] = $type;
			}
		}
	}

/**
 * Returns mime type for name or nil if unknown
 *
 * @param string $name 
 * @return mixed
 */
	public function of($name) {
		if (empty($name)) {
			return null;
		}
		if (preg_match('/(^|\.)([^\.]+)$/', $name, $m)) {
			if (!empty($this->EXTENSIONS[strtolower($m[2])])) {
				return $this->EXTENSIONS[strtolower($m[2])];
			}
		}
		return null;
	}

/**
 * Main Mimetype of
 *
 * @param string $name 
 * @return mixed
 */
	public function main_mimetype_of($name) {
		$mimetype = $this->of($name);
		if ($mimetype) {
			return array_shift(explode('/', $mimetype));
		}
		return null;
	}


/**
 * Return true if mime-type for name is type/* otherwise false
 *
 * @param string $type 
 * @param string $name 
 * @return boolean Mime type is type/*
 */
	public static function is_type($type, $name) {
		$_this = new MimeType();
		$main_mimetype = $_this->main_mimetype_of($name);
		return $type == $main_mimetype;
	}
}
