<?php
/**
 * CandyHelper
 *
 */
class CandyHelper extends AppHelper
{
	var $helpers = array('Html','Users', 'Paginator', 'Ajax');
  var $row = 0;

	function link($user)
	{
		return $this->Html->link($user['name'],'/account/show/'.$user['id']);
	}
	function accesskey($key)
	{
		$map = array(
			'quick_search' => 'f',
			'search' => 4,
		);
		return $map[$key];
	}
  function lwr_e()
  {
    $argv = func_get_args();
    $ret = call_user_func_array(array($this, 'lwr_r'), $argv);
    echo $ret;
  }
  function lwr_r()
  {
    $argc = func_num_args();
    $argv = func_get_args();
    $return = false;
    if ($argc == 0) {
      return null;
    } else {
      $singular = $argv[0];
      if ($argc > 1) {
        array_shift($argv);
        $singular = vsprintf(__($singular, true), $argv);
      }
    }

    return $singular;
  }
  function lwr()
  {
    $argv = func_get_args();
    call_user_func_array(array($this, 'lwr_e'), $argv);
  }

  /**
   * html_title
   *
   */
  function html_title($str=false)
  {
    #  def html_title(*args)
    #    if args.empty?
    #      title = []
    #      title << @project.name if @project
    #      title += @html_title if @html_title
    #      title << Setting.app_title
    #      title.compact.join(' - ')
    #    else
    #      @html_title ||= []
    #      @html_title += args
    #    end
    #  end
    $view =& ClassRegistry::getObject('view');
    if (empty($str)) {
      $title = array();
      if (! empty($this->project)) {
        $title[0] = $this->project['name'];
      } else {
        $Settings =& ClassRegistry::getObject('Setting');
        $title[0] = $Settings->app_title;
      }
      if(!empty($view->pageTitle)) {
        $title[0] .= $view->pageTitle;
      }
      $title = join(' - ', $title);
      $str = $view->pageTitle = $title;
    }
    $view->pageTitle = $str;
    return $view->pageTitle;
  }

#require 'coderay'
#require 'coderay/helpers/file_type'
#require 'forwardable'
#require 'cgi'
#
#module ApplicationHelper
#  include Redmine::WikiFormatting::Macros::Definitions
#  include GravatarHelper::PublicMethods
#
#  extend Forwardable
#  def_delegators :wiki_helper, :wikitoolbar_for, :heads_for_wiki_formatter
#
#  def current_role
#    @current_role ||= User.current.role_for_project(@project)
#  end
#
  /**
   * Return true if user is authorized for controller/action, otherwise false
   * @param $aco : Array. array('controller'=> controller, 'action'=> action)
   *              : String. ':update_form'
   * @param $project : Target Project. if false, get from main_project of viewVars.
   */
  function authorize_for($aco, $project=false) {
    if(empty($project)) {
      $view =& ClassRegistry::getObject('view');
      $project = $view->viewVars['main_project'];
    }
    return $this->requestAction(array('controller'=>'users', 'action'=>'allowed_to'), compact('aco', 'project'));
  }
  /** 
   * Display a link if user is authorized
   * 
   */
  function link_to_if_authorized($aco, $name, $url, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
    $out = '';
    if($this->authorize_for($aco)) {
      $out = $this->Html->link($name, $url, $htmlAttributes, $confirmMessage = false, $escapeTitle);
    }
    return $out;
  }

#  def link_to_if_authorized(name, options = {}, html_options = nil, *parameters_for_method_reference)
#    link_to(name, options, html_options, *parameters_for_method_reference) if authorize_for(options[:controller] || params[:controller], options[:action])
#  end
#
#  # Display a link to remote if user is authorized
#  def link_to_remote_if_authorized(name, options = {}, html_options = nil)
#    url = options[:url] || {}
#    link_to_remote(name, options, html_options) if authorize_for(url[:controller] || params[:controller], url[:action])
#  end
#
#  # Display a link to user's account page
#  def link_to_user(user, options={})
#    (user && !user.anonymous?) ? link_to(user.name(options[:format]), :controller => 'account', :action => 'show', :id => user) : 'Anonymous'
#  end
  function format_username($user, $format=null)
  {
  	if (empty($format)) {
  	  $format = $this->Settings->user_format;	
  	}

    $USER_FORMATS = array(
      ':firstname_lastname' => "{$user['firstname']} {$user['lastname']}",
      ':firstname' => "{$user['firstname']}",
      ':lastname_firstname' => "{$user['lastname']} {$user['firstname']}",
      ':lastname_coma_firstname' => "{$user['lastname']}, {$user['firstname']}",
      ':username' => "{$user['login']}"
    );
    if (!isset($USER_FORMATS[$format])) $format = ':firstname_lastname'; 
    return $USER_FORMATS[$format];
  }

  function link_to_user($user, $options = array())
  {
    $format = '';
    if (isset($options['format'])) {
      $format = $options['format'];
      unset($options['format']);
    }
    if ($user) /* && !user.anonymous? */ {
      return $this->Html->link($this->format_username($user, $format), array('controller'=>'account', 'action'=>'show', 'id'=>$user['id']), $options);
    } else {
      return 'Anonymous';
    }
  }
#  def link_to_user(user, options={})
#    (user && !user.anonymous?) ? link_to(user.name(options[:format]), :controller => 'account', :action => 'show', :id => user) : 'Anonymous'
#  end
#
  function link_to_issue($issue, $options = array())
  {
    if (!isset($options['class'])) {
      $options['class'] = '';
    }
    $options['class'] .= ' issue';
    if (isset($issue['closed'])) {
      $options['class'] .= ' closed';
    }

    return $this->Html->link("{$issue['Tracker']['name']} #{$issue['Issue']['id']}", array('controller'=>'issues', 'action'=>'show', 'id'=>$issue['Issue']['id']), $options);
#    options[:class] ||= ''
#    options[:class] << ' issue'
#    options[:class] << ' closed' if issue.closed?
#    link_to "#{issue.tracker.name} ##{issue.id}", {:controller => "issues", :action => "show", :id => issue}, options

  }
#  def link_to_issue(issue, options={})
#    options[:class] ||= ''
#    options[:class] << ' issue'
#    options[:class] << ' closed' if issue.closed?
#    link_to "#{issue.tracker.name} ##{issue.id}", {:controller => "issues", :action => "show", :id => issue}, options
#  end
#
#  # Generates a link to an attachment.
#  # Options:
#  # * :text - Link text (default to attachment filename)
#  # * :download - Force download (default: false)
  function link_to_attachment($attachment, $options = array())
  {
    $text = $attachment['Attachment']['filename'];
    if (isset($options['text'])) {
      $text = $options['text'];
    }
    $action = 'show';
    if (isset($options['download'])) {
      $action = 'download';
    }

    return $this->Html->link($text, array('controller'=>'attachments', 'action'=>$action, 'id'=>$attachment['Attachment']['id'], 'filename'=>$attachment['Attachment']['filename']), $options);
  }
#  def link_to_attachment(attachment, options={})
#    text = options.delete(:text) || attachment.filename
#    action = options.delete(:download) ? 'download' : 'show'
#
#    link_to(h(text), {:controller => 'attachments', :action => action, :id => attachment, :filename => attachment.filename }, options)
#  end
#
  function link_to_version($version, $options = array()) {
    return $this->Html->link(h($version['name']), array('controller'=>'versions', 'action'=>'show', 'id'=>$version['id']), $options);
  }

  function toggle_link($name, $id, $options=array()) {
    $onclick = "Element.toggle('$id'); ";
    $onclick .= (!empty($options['focus']) ? "Form.Element.focus('".$options['focus']."'); " : "this.blur(); ");
    $onclick .= "return false;";
    return $this->Html->link($name, "#", compact('onclick'));
  }
#
#  def image_to_function(name, function, html_options = {})
#    html_options.symbolize_keys!
#    tag(:input, html_options.merge({
#        :type => "image", :src => image_path(name),
#        :onclick => (html_options[:onclick] ? "#{html_options[:onclick]}; " : "") + "#{function};"
#        }))
#  end
#
#  def prompt_to_remote(name, text, param, url, html_options = {})
#    html_options[:onclick] = "promptToRemote('#{text}', '#{param}', '#{url_for(url)}'); return false;"
#    link_to name, {}, html_options
#  end
#
  /**
   * format_date
   *
   * @todo implement Setting
   */
  function format_date($date) 
  {
    if (!$date) {
      return null;
    }
    
    $view =& ClassRegistry::getObject('view');
    $Settings = $view->viewVars['Settings'];

    // "Setting.date_format.size < 2" is a temporary fix (content of date_format setting changed)
    $date_format = (empty($Settings->date_format) || strlen($Settings->date_format) < 2) ? '%m/%d/%Y' : $Settings->date_format;
    if(is_string($date)) {
      $date = strtotime($date);
    }
    $date_format = __($date_format,true);
    // for hack windows.
    $date_format = mb_convert_encoding($date_format, "SJIS", "UTF-8");
    $date = strftime("{$date_format}",$date);
    $date = mb_convert_encoding($date, "UTF-8", "SJIS");
    return $date;
  }
#
  
  /**
   * format_time
   *
   * @todo time_zone
   */
  function format_time($time, $include_date = true)
  {
    if (empty($time)) {
      return null;
    }

    if (is_string($time) && !is_numeric($time)) {
      $time = strtotime($time);
    }

#    zone = User.current.time_zone
#    local = zone ? time.in_time_zone(zone) : (time.utc? ? time.localtime : time)

    if (empty($this->Settings->date_format) || (strlen($this->Settings->date_format) < 2)) {
      $date_format = __('general_fmt_date', true);
    } else {
      $date_format = $this->Settings->date_format;
    }

    if (empty($this->Settings->time_format)) {
      $time_format = __('general_fmt_time', true);
    } else {
      $time_format = $this->Settings->time_format;
    }

    if ($include_date) {
      return strftime("{$date_format} {$time_format}", $time);
      // return strftime("{$date_format} {$time_format}", $local);
    } else {
      return strftime("{$time_format}", $time);
      // return strftime("{$time_format}", $local);
    }
  }
#  
#  def format_activity_title(text)
#    h(truncate_single_line(text, 100))
#  end
#  
#  def format_activity_day(date)
#    date == Date.today ? l(:label_today).titleize : format_date(date)
#  end
#  
#  def format_activity_description(text)
#    h(truncate(text.to_s, 250).gsub(%r{<(pre|code)>.*$}m, '...'))
#  end
#
#  def distance_of_date_in_words(from_date, to_date = 0)
#    from_date = from_date.to_date if from_date.respond_to?(:to_date)
#    to_date = to_date.to_date if to_date.respond_to?(:to_date)
#    distance_in_days = (to_date - from_date).abs
#    lwr(:actionview_datehelper_time_in_words_day, distance_in_days)
#  end
  function distance_of_date_in_words($from_date, $to_date = 0)
  {
    $from_date = strtotime($from_date);
    $to_date = strtotime($to_date);
    $distance_in_days = abs($to_date - $from_date) / (60*60*24);

    return $this->lwr_r('', $distance_in_days);
  }
#
#  def due_date_distance_in_words(date)
#    if date
#      l((date < Date.today ? :label_roadmap_overdue : :label_roadmap_due_in), distance_of_date_in_words(Date.today, date))
#    end
#  end
  function due_date_distance_in_words($date)
  {
    $ret = null;

    if ($date) {
      $time = strtotime($date);
      $now = time();
      if ($date < $now) {
        $ret = '%s late';
      } else {
        $ret = 'Due in %s';
      }
    }

    return null;
  }
#
#  def render_page_hierarchy(pages, node=nil)
#    content = ''
#    if pages[node]
#      content << "<ul class=\"pages-hierarchy\">\n"
#      pages[node].each do |page|
#        content << "<li>"
#        content << link_to(h(page.pretty_title), {:controller => 'wiki', :action => 'index', :id => page.project, :page => page.title},
#                           :title => (page.respond_to?(:updated_on) ? l(:label_updated_time, distance_of_time_in_words(Time.now, page.updated_on)) : nil))
#        content << "\n" + render_page_hierarchy(pages, page.id) if pages[page.id]
#        content << "</li>\n"
#      end
#      content << "</ul>\n"
#    end
#    content
#  end
#  
#  # Renders flash messages
#  def render_flash_messages
#    s = ''
#    flash.each do |k,v|
#      s << content_tag('div', v, :class => "flash #{k}")
#    end
#    s
#  end
#
#  # Truncates and returns the string as a single line
#  def truncate_single_line(string, *args)
#    truncate(string, *args).gsub(%r{[\r\n]+}m, ' ')
#  end
#
#  def html_hours(text)
#    text.gsub(%r{(\d+)\.(\d+)}, '<span class="hours hours-int">\1</span><span class="hours hours-dec">.\2</span>')
#  end
  function html_hours($text)
  {
    return preg_replace('/(\d+)\.(\d+)/', '<span class="hours hours-int">$1</span><span class="hours hours-dec">.$2</span>', $text);
  }
#
	function authoring($created, $author, $options = array())
	{
		//TODO:port
    $view =& ClassRegistry::getObject('view');
    $project = isset($view->viewVars['main_project']) ? $view->viewVars['main_project'] : null;
    if(empty($project)) {
  	  $time_tag = $this->Html->tag('acronym',$this->distance_of_time_in_words(time(),$created),aa('title',$this->format_time($created)));
    } else {
      $time_tag = $this->Html->link($this->distance_of_time_in_words(time(),$created), 
          array('controller'=>'projects', 'action'=>'activity', 'id'=>$project['Project']['id'], 'from'=>$created),
          aa('title',$this->format_time($created)));
    }
#    time_tag = @project.nil? ? content_tag('acronym', distance_of_time_in_words(Time.now, created), :title => format_time(created)) :
#                               link_to(distance_of_time_in_words(Time.now, created), 
#                                       {:controller => 'projects', :action => 'activity', :id => @project, :from => created.to_date},
#                                       :title => format_time(created))
	  $author_tag = $this->Html->link($this->format_username($author),aa('controller','account','action','show','id',$author['id']));
#    author_tag = (author.is_a?(User) && !author.anonymous?) ? link_to(h(author), :controller => 'account', :action => 'show', :id => author) : h(author || 'Anonymous')
#    l(options[:label] || :label_added_time_by, author_tag, time_tag)
		return $this->lwr('Added by %s %s ago',$author_tag, $time_tag);
	}
#
#  def l_or_humanize(s, options={})
#    k = "#{options[:prefix]}#{s}".to_sym
#    l_has_string?(k) ? l(k) : s.to_s.humanize
#  end
#
#  def day_name(day)
#    l(:general_day_names).split(',')[day-1]
#  end
#
#  def month_name(month)
#    l(:actionview_datehelper_select_month_names).split(',')[month-1]
#  end
#
#  def syntax_highlight(name, content)
#    type = CodeRay::FileType[name]
#    type ? CodeRay.scan(content, type).html : h(content)
#  end
#
#  def to_path_param(path)
#    path.to_s.split(%r{[/\\]}).select {|p| !p.blank?}
#  end
#
  function pagination_links_full($options = array())
  {
    $params = $this->Paginator->params;
    $paging = $params['paging'][$this->Paginator->defaultModel()];
    $view =& ClassRegistry::getObject('view');
    if (isset($options['page_param'])) {
      unset($options['page_param']);
      $page_param = null;
    } else {
      $page_param = 'page';
    }
    $url_param = $params['url_param'];
#    url_param.clear if url_param.has_key?(:set_filter)
    $html = '';
    if ($paging['prevPage']) {
      $html .= $this->Ajax->link('&#171;' . __('Previous', true), $url = am($url_param, array($page_param => $paging['page'] - 1)), array(
        'update' => 'content',
        'url' => $url,
        'complete' => 'window.scrollTo(0, 0)',
      ), null, false) . ' ';
    }
    $html .= $this->Paginator->numbers(array('update' => 'content', 'complete' => 'window.scrollTo(0,0)', 'url' => $url_param));
    if ($paging['nextPage']) {
      $html .= ' ' . $this->Ajax->link(__('Next', true) . '&#187;', $url = am($url_param, array($page_param => $paging['page'] + 1)), array(
        'update' => 'content',
        'url' => $url,
        'complete' => 'window.scrollTo(0, 0)',
      ), null, false) ;
    }
    if ($paging['count'] !== null) {
      $per_page_links = $this->per_page_links($paging['options']['limit']);
      $html .= ' (' .( ($paging['page'] - 1) * $paging['options']['limit'] + 1) . '-' . $paging['current'] . '/' . $paging['count'] . ')' . (strlen($per_page_links) ? (' | ' . $per_page_links) : '');
    }
    return $html;
  }
#  def pagination_links_full(paginator, count=nil, options={})
#    page_param = options.delete(:page_param) || :page
#    url_param = params.dup
#    # don't reuse params if filters are present
#    url_param.clear if url_param.has_key?(:set_filter)
#
#    html = ''
#    html << link_to_remote(('&#171; ' + l(:label_previous)),
#                            {:update => 'content',
#                             :url => url_param.merge(page_param => paginator.current.previous),
#                             :complete => 'window.scrollTo(0,0)'},
#                            {:href => url_for(:params => url_param.merge(page_param => paginator.current.previous))}) + ' ' if paginator.current.previous
#
#    html << (pagination_links_each(paginator, options) do |n|
#      link_to_remote(n.to_s,
#                      {:url => {:params => url_param.merge(page_param => n)},
#                       :update => 'content',
#                       :complete => 'window.scrollTo(0,0)'},
#                      {:href => url_for(:params => url_param.merge(page_param => n))})
#    end || '')
#
#    html << ' ' + link_to_remote((l(:label_next) + ' &#187;'),
#                                 {:update => 'content',
#                                  :url => url_param.merge(page_param => paginator.current.next),
#                                  :complete => 'window.scrollTo(0,0)'},
#                                 {:href => url_for(:params => url_param.merge(page_param => paginator.current.next))}) if paginator.current.next
#
#    unless count.nil?
#      html << [" (#{paginator.current.first_item}-#{paginator.current.last_item}/#{count})", per_page_links(paginator.items_per_page)].compact.join(' | ')
#    end
#
#    html
#  end
#
  function per_page_links($selected = null)
  {
    $url_param = $this->Paginator->params['url_param'];
#    url_param.clear if url_param.has_key?(:set_filter)
    $links = a();
    foreach ($this->Settings->per_page_options as $v) {
      $links[] = $v == $selected ? $v : $this->Ajax->link($v, am($url_param, array('?' . http_build_query(array('per_page' => $v)))), array(
        'update' => 'content',
      ));
    }
    return count($links) > 1 ? __("'Per page", true) . join(', ', $links) : '';
  }
#  def per_page_links(selected=nil)
#    url_param = params.dup
#    url_param.clear if url_param.has_key?(:set_filter)
#
#    links = Setting.per_page_options_array.collect do |n|
#      n == selected ? n : link_to_remote(n, {:update => "content", :url => params.dup.merge(:per_page => n)},
#                                            {:href => url_for(url_param.merge(:per_page => n))})
#    end
#    links.size > 1 ? l(:label_display_per_page, links.join(', ')) : nil
#  end
#
function breadcrumb($args)
{
}
#  def breadcrumb(*args)
#    elements = args.flatten
#    elements.any? ? content_tag('p', args.join(' &#187; ') + ' &#187; ', :class => 'breadcrumb') : nil
#  end
#
#  def html_title(*args)
#    if args.empty?
#      title = []
#      title << @project.name if @project
#      title += @html_title if @html_title
#      title << Setting.app_title
#      title.compact.join(' - ')
#    else
#      @html_title ||= []
#      @html_title += args
#    end
#  end
#
#  def accesskey(s)
#    Redmine::AccessKeys.key_for s
#  end
#
#  # Formats text according to system settings.
#  # 2 ways to call this method:
#  # * with a String: textilizable(text, options)
#  # * with an object and one of its attribute: textilizable(issue, :description, options)
#  def textilizable(*args)
  function textilizable($text, $options=array())
  {
  	 App::import('Vendor','Textile',aa('file','textile-2.0.0/classTextile.php'));
  	 $Textile = new Textile();
  	 return $Textile->TextileThis($text);

#    options = args.last.is_a?(Hash) ? args.pop : {}
#    case args.size
#    when 1
#      obj = options[:object]
#      text = args.shift
#    when 2
#      obj = args.shift
#      text = obj.send(args.shift).to_s
#    else
#      raise ArgumentError, 'invalid arguments to textilizable'
#    end
#    return '' if text.blank?
#
#    only_path = options.delete(:only_path) == false ? false : true
#
#    # when using an image link, try to use an attachment, if possible
#    attachments = options[:attachments] || (obj && obj.respond_to?(:attachments) ? obj.attachments : nil)
#
#    if attachments
#      attachments = attachments.sort_by(&:created_on).reverse
#      text = text.gsub(/!((\<|\=|\>)?(\([^\)]+\))?(\[[^\]]+\])?(\{[^\}]+\})?)(\S+\.(bmp|gif|jpg|jpeg|png))!/i) do |m|
#        style = $1
#        filename = $6.downcase
#        # search for the picture in attachments
#        if found = attachments.detect { |att| att.filename.downcase == filename }
#          image_url = url_for :only_path => only_path, :controller => 'attachments', :action => 'download', :id => found
#          desc = found.description.to_s.gsub(/^([^\(\)]*).*$/, "\\1")
#          alt = desc.blank? ? nil : "(#{desc})"
#          "!#{style}#{image_url}#{alt}!"
#        else
#          m
#        end
#      end
#    end
#
#    text = Redmine::WikiFormatting.to_html(Setting.text_formatting, text) { |macro, args| exec_macro(macro, obj, args) }
#
#    # different methods for formatting wiki links
#    case options[:wiki_links]
#    when :local
#      # used for local links to html files
#      format_wiki_link = Proc.new {|project, title, anchor| "#{title}.html" }
#    when :anchor
#      # used for single-file wiki export
#      format_wiki_link = Proc.new {|project, title, anchor| "##{title}" }
#    else
#      format_wiki_link = Proc.new {|project, title, anchor| url_for(:only_path => only_path, :controller => 'wiki', :action => 'index', :id => project, :page => title, :anchor => anchor) }
#    end
#
#    project = options[:project] || @project || (obj && obj.respond_to?(:project) ? obj.project : nil)
#
#    # Wiki links
#    #
#    # Examples:
#    #   [[mypage]]
#    #   [[mypage|mytext]]
#    # wiki links can refer other project wikis, using project name or identifier:
#    #   [[project:]] -> wiki starting page
#    #   [[project:|mytext]]
#    #   [[project:mypage]]
#    #   [[project:mypage|mytext]]
#    text = text.gsub(/(!)?(\[\[([^\]\n\|]+)(\|([^\]\n\|]+))?\]\])/) do |m|
#      link_project = project
#      esc, all, page, title = $1, $2, $3, $5
#      if esc.nil?
#        if page =~ /^([^\:]+)\:(.*)$/
#          link_project = Project.find_by_name($1) || Project.find_by_identifier($1)
#          page = $2
#          title ||= $1 if page.blank?
#        end
#
#        if link_project && link_project.wiki
#          # extract anchor
#          anchor = nil
#          if page =~ /^(.+?)\#(.+)$/
#            page, anchor = $1, $2
#          end
#          # check if page exists
#          wiki_page = link_project.wiki.find_page(page)
#          link_to((title || page), format_wiki_link.call(link_project, Wiki.titleize(page), anchor),
#                                   :class => ('wiki-page' + (wiki_page ? '' : ' new')))
#        else
#          # project or wiki doesn't exist
#          title || page
#        end
#      else
#        all
#      end
#    end
#
#    # Redmine links
#    #
#    # Examples:
#    #   Issues:
#    #     #52 -> Link to issue #52
#    #   Changesets:
#    #     r52 -> Link to revision 52
#    #     commit:a85130f -> Link to scmid starting with a85130f
#    #   Documents:
#    #     document#17 -> Link to document with id 17
#    #     document:Greetings -> Link to the document with title "Greetings"
#    #     document:"Some document" -> Link to the document with title "Some document"
#    #   Versions:
#    #     version#3 -> Link to version with id 3
#    #     version:1.0.0 -> Link to version named "1.0.0"
#    #     version:"1.0 beta 2" -> Link to version named "1.0 beta 2"
#    #   Attachments:
#    #     attachment:file.zip -> Link to the attachment of the current object named file.zip
#    #   Source files:
#    #     source:some/file -> Link to the file located at /some/file in the project's repository
#    #     source:some/file@52 -> Link to the file's revision 52
#    #     source:some/file#L120 -> Link to line 120 of the file
#    #     source:some/file@52#L120 -> Link to line 120 of the file's revision 52
#    #     export:some/file -> Force the download of the file
#    #  Forum messages:
#    #     message#1218 -> Link to message with id 1218
#    text = text.gsub(%r{([\s\(,\-\>]|^)(!)?(attachment|document|version|commit|source|export|message)?((#|r)(\d+)|(:)([^"\s<>][^\s<>]*?|"[^"]+?"))(?=(?=[[:punct:]]\W)|\s|<|$)}) do |m|
#      leading, esc, prefix, sep, oid = $1, $2, $3, $5 || $7, $6 || $8
#      link = nil
#      if esc.nil?
#        if prefix.nil? && sep == 'r'
#          if project && (changeset = project.changesets.find_by_revision(oid))
#            link = link_to("r#{oid}", {:only_path => only_path, :controller => 'repositories', :action => 'revision', :id => project, :rev => oid},
#                                      :class => 'changeset',
#                                      :title => truncate_single_line(changeset.comments, 100))
#          end
#        elsif sep == '#'
#          oid = oid.to_i
#          case prefix
#          when nil
#            if issue = Issue.find_by_id(oid, :include => [:project, :status], :conditions => Project.visible_by(User.current))
#              link = link_to("##{oid}", {:only_path => only_path, :controller => 'issues', :action => 'show', :id => oid},
#                                        :class => (issue.closed? ? 'issue closed' : 'issue'),
#                                        :title => "#{truncate(issue.subject, 100)} (#{issue.status.name})")
#              link = content_tag('del', link) if issue.closed?
#            end
#          when 'document'
#            if document = Document.find_by_id(oid, :include => [:project], :conditions => Project.visible_by(User.current))
#              link = link_to h(document.title), {:only_path => only_path, :controller => 'documents', :action => 'show', :id => document},
#                                                :class => 'document'
#            end
#          when 'version'
#            if version = Version.find_by_id(oid, :include => [:project], :conditions => Project.visible_by(User.current))
#              link = link_to h(version.name), {:only_path => only_path, :controller => 'versions', :action => 'show', :id => version},
#                                              :class => 'version'
#            end
#          when 'message'
#            if message = Message.find_by_id(oid, :include => [:parent, {:board => :project}], :conditions => Project.visible_by(User.current))
#              link = link_to h(truncate(message.subject, 60)), {:only_path => only_path,
#                                                                :controller => 'messages',
#                                                                :action => 'show',
#                                                                :board_id => message.board,
#                                                                :id => message.root,
#                                                                :anchor => (message.parent ? "message-#{message.id}" : nil)},
#                                                 :class => 'message'
#            end
#          end
#        elsif sep == ':'
#          # removes the double quotes if any
#          name = oid.gsub(%r{^"(.*)"$}, "\\1")
#          case prefix
#          when 'document'
#            if project && document = project.documents.find_by_title(name)
#              link = link_to h(document.title), {:only_path => only_path, :controller => 'documents', :action => 'show', :id => document},
#                                                :class => 'document'
#            end
#          when 'version'
#            if project && version = project.versions.find_by_name(name)
#              link = link_to h(version.name), {:only_path => only_path, :controller => 'versions', :action => 'show', :id => version},
#                                              :class => 'version'
#            end
#          when 'commit'
#            if project && (changeset = project.changesets.find(:first, :conditions => ["scmid LIKE ?", "#{name}%"]))
#              link = link_to h("#{name}"), {:only_path => only_path, :controller => 'repositories', :action => 'revision', :id => project, :rev => changeset.revision},
#                                           :class => 'changeset',
#                                           :title => truncate_single_line(changeset.comments, 100)
#            end
#          when 'source', 'export'
#            if project && project.repository
#              name =~ %r{^[/\\]*(.*?)(@([0-9a-f]+))?(#(L\d+))?$}
#              path, rev, anchor = $1, $3, $5
#              link = link_to h("#{prefix}:#{name}"), {:controller => 'repositories', :action => 'entry', :id => project,
#                                                      :path => to_path_param(path),
#                                                      :rev => rev,
#                                                      :anchor => anchor,
#                                                      :format => (prefix == 'export' ? 'raw' : nil)},
#                                                     :class => (prefix == 'export' ? 'source download' : 'source')
#            end
#          when 'attachment'
#            if attachments && attachment = attachments.detect {|a| a.filename == name }
#              link = link_to h(attachment.filename), {:only_path => only_path, :controller => 'attachments', :action => 'download', :id => attachment},
#                                                     :class => 'attachment'
#            end
#          end
#        end
#      end
#      leading + (link || "#{prefix}#{sep}#{oid}")
#    end
#
#    text
#  end
  }
#
#  # Same as Rails' simple_format helper without using paragraphs
#  def simple_format_without_paragraph(text)
#    text.to_s.
#      gsub(/\r\n?/, "\n").                    # \r\n and \r -> \n
#      gsub(/\n\n+/, "<br /><br />").          # 2+ newline  -> 2 br
#      gsub(/([^\n]\n)(?=[^\n])/, '\1<br />')  # 1 newline   -> br
#  end
#
#  def error_messages_for(object_name, options = {})
#    options = options.symbolize_keys
#    object = instance_variable_get("@#{object_name}")
#    if object && !object.errors.empty?
#      # build full_messages here with controller current language
#      full_messages = []
#      object.errors.each do |attr, msg|
#        next if msg.nil?
#        msg = msg.first if msg.is_a? Array
#        if attr == "base"
#          full_messages << l(msg)
#        else
#          full_messages << "&#171; " + (l_has_string?("field_" + attr) ? l("field_" + attr) : object.class.human_attribute_name(attr)) + " &#187; " + l(msg) unless attr == "custom_values"
#        end
#      end
#      # retrieve custom values error messages
#      if object.errors[:custom_values]
#        object.custom_values.each do |v|
#          v.errors.each do |attr, msg|
#            next if msg.nil?
#            msg = msg.first if msg.is_a? Array
#            full_messages << "&#171; " + v.custom_field.name + " &#187; " + l(msg)
#          end
#        end
#      end
#      content_tag("div",
#        content_tag(
#          options[:header_tag] || "span", lwr(:gui_validation_error, full_messages.length) + ":"
#        ) +
#        content_tag("ul", full_messages.collect { |msg| content_tag("li", msg) }),
#        "id" => options[:id] || "errorExplanation", "class" => options[:class] || "errorExplanation"
#      )
#    else
#      ""
#    end
#  end
#
  /**
   * lang_options_for_select
   *
   */
  function lang_options_for_select($blank = true)
  {
    $list = array();
    foreach (glob(APP . 'locale/*') as $dir) {
      $path = explode('/', $dir);
      $lang = end($path);
      $list[$lang] = $lang;
    }

    return $list;
  }
#
#  def label_tag_for(name, option_tags = nil, options = {})
#    label_text = l(("field_"+field.to_s.gsub(/\_id$/, "")).to_sym) + (options.delete(:required) ? @template.content_tag("span", " *", :class => "required"): "")
#    content_tag("label", label_text)
#  end
#
#  def labelled_tabular_form_for(name, object, options, &proc)
#    options[:html] ||= {}
#    options[:html][:class] = 'tabular' unless options[:html].has_key?(:class)
#    form_for(name, object, options.merge({ :builder => TabularFormBuilder, :lang => current_language}), &proc)
#  end
#
#  def back_url_hidden_field_tag
#    back_url = params[:back_url] || request.env['HTTP_REFERER']
#    back_url = CGI.unescape(back_url.to_s)
#    hidden_field_tag('back_url', CGI.escape(back_url)) unless back_url.blank?
#  end
#
#  def check_all_links(form_name)
#    link_to_function(l(:button_check_all), "checkAll('#{form_name}', true)") +
#    " | " +
#    link_to_function(l(:button_uncheck_all), "checkAll('#{form_name}', false)")
#  end
#
  function progress_bar($pcts, $options=array()) {
    if(!is_array($pcts)) $pcts = array($pcts, $pcts);
    $pcts[1] = $pcts[1] - $pcts[0];
    $pcts[] = (100 - $pcts[1] - $pcts[0]);
    $width = empty($options['width']) ? '100px;' : $options['width'];
    $legend = empty($options['legend']) ? '' : $options['legend'];
    $out = '<table class="progress" style="width: '.$width.';"><tbody>';
    $out .= '<tr>';
    $out .= ($pcts[0] > 0) ? '<td style="width: '.$pcts[0].'%;" class="closed" />' : '';
    $out .= ($pcts[1] > 0) ? '<td style="width: '.$pcts[1].'%;" class="done" />' : '';
    $out .= ($pcts[2] > 0) ? '<td style="width: '.$pcts[2].'%;" class="todo" />' : '';
    $out .= '</tr></tbody></table>';
    $out .= '<p class="pourcent">'.$legend.'</p>';
    return $out;
  }
#
#  def context_menu_link(name, url, options={})
#    options[:class] ||= ''
#    if options.delete(:selected)
#      options[:class] << ' icon-checked disabled'
#      options[:disabled] = true
#    end
#    if options.delete(:disabled)
#      options.delete(:method)
#      options.delete(:confirm)
#      options.delete(:onclick)
#      options[:class] << ' disabled'
#      url = '#'
#    end
#    link_to name, url, options
#  end
#
#  def calendar_for(field_id)
#    include_calendar_headers_tags
#    image_tag("calendar.png", {:id => "#{field_id}_trigger",:class => "calendar-trigger"}) +
#    javascript_tag("Calendar.setup({inputField : '#{field_id}', ifFormat : '%Y-%m-%d', button : '#{field_id}_trigger' });")
#  end
#
#  def include_calendar_headers_tags
#    unless @calendar_headers_tags_included
#      @calendar_headers_tags_included = true
#      content_for :header_tags do
#        javascript_include_tag('calendar/calendar') +
#        javascript_include_tag("calendar/lang/calendar-#{current_language}.js") +
#        javascript_include_tag('calendar/calendar-setup') +
#        stylesheet_link_tag('calendar')
#      end
#    end
#  end
#
#  def content_for(name, content = nil, &block)
#    @has_content ||= {}
#    @has_content[name] = true
#    super(name, content, &block)
#  end
#
#  def has_content?(name)
#    (@has_content && @has_content[name]) || false
#  end
#

  /**
   * avatar
   *
   * Returns the avatar image tag for the given +user+ if avatars are enabled
   * +user+ can be a User or a string that will be scanned for an email address
   * (eg. 'joe <joe@foo.bar>')
   */
  function avatar($user, $options = array())
  {
    if ($this->Settings->gravatar_enabled) {

      if (isset($user['User'])) {
        $user = $user['User'];
      }

      if (empty($user['email'])) {
        $email = null;
      } else {
        $email = $user['email'];
      }

#      if user.respond_to?(:mail)
#        email = user.mail
#      elsif user.to_s =~ %r{<(.+?)>}
#        email = $1
#      end

      if ($email == null) {
        return null;
      }

      // def gravatar_url
      $email_hash = md5($email);
      $options_default = array(
        'default' => null,
        'rating' => 'PG',
        'alt' => 'avatar',
        'class' => 'gravatar'
      );
      $options = array_merge($options, $options_default);
      if (!empty($options['default'])) {
        $options['default'] = htmlspecialchars($options['default'], ENT_QUOTES);
      }

      $url = "http://www.gravatar.com/avatar.php?gravatar_id=#{email_hash}";

      foreach (array('rating', 'size', 'default') as $opt) {
        if (!empty($opt)) {
          $value = htmlspecialchars($options[$opt], ENT_QUOTES);
          $url .= "&{$opt}={$value}";
        }
      }
      // end gravatar_url

      # Return the HTML img tag for the given email address's gravatar.
      foreach (array('class', 'alt', 'size') as $opt) {
        $options[$opt] = htmlspecialchars($options[$opt], ENT_QUOTES);
      }
      // TODO: replace with helper 
      return "<img class=\"{$options['class']}\" alt=\"{$options['alt']}\" width=\"{$options['size']}\" height=\"{$options['size']}\" src=\"{$url}\" />"      
;    }
  }

#  private
#
#  def wiki_helper
#    helper = Redmine::WikiFormatting.helper_for(Setting.text_formatting)
#    extend helper
#    return self
#  end

  function cycle($one = "odd", $two = "even") {
    if($this->row++ % 2) {
      return $two;
    } else {
      return $one;
    }
  }
  function label_text($text) {
    if (strpos($text, '.') !== false) {
      $text = array_pop(explode('.', $text));
    }
    if (substr($text, -3) == '_id') {
      $text = substr($text, 0, strlen($text) - 3);
    }
    return __(Inflector::humanize(Inflector::underscore($text)), true);
  }

  function check_all_links($form_name) {
    $tmp = $this->Html->link(__('Check all',true), '#', array('onclick' => "checkAll('" . $form_name . "', true); return false;"));
    $tmp .= ' | ';
    $tmp .= $this->Html->link(__('Uncheck all',true), '#', array('onclick' => "checkAll('" . $form_name . "', false); return false;"));
    return $tmp;
  }
  
  /**
   * rails 's ActionView::distance_of_time_in_words
   */
  function distance_of_time_in_words($begin, $end, $include_seconds=false)
  {
    if (!is_numeric($begin)) $begin = strtotime($begin);
    if (!is_numeric($end)) $end = strtotime($end);

    $distance_in_minutes = round(abs($begin - $end)/60);
    $distance_in_seconds = round(abs($begin - $end));

    switch(true) {
    case (($distance_in_minutes == 0) || ($distance_in_minutes == 1)) :
      if(!$include_seconds) return ($distance_in_minutes==0) ? __('less than a minute',true) : __('1 minute',true);
      switch(true) {
      case ($distance_in_seconds <= 5) :
        return ($distance_in_seconds < 1) ? __('less than a second',true) : sprintf(__('less than %d seconds',true), 5);
      case ($distance_in_seconds <= 10) :
        return sprintf(__('less than %d seconds',true), 10);
      case ($distance_in_seconds <= 20) :
        return sprintf(__('less than %d seconds',true), 20);
      case ($distance_in_seconds <= 40) :
        return __('half a minute',true);
      case ($distance_in_seconds <= 59) :
        return __('less than a minute',true);
      default :
        return __('1 minute',true);
      }
    case ($distance_in_minutes <= 45) :
      return sprintf(__('%d minutes',true), $distance_in_minutes);
    case ($distance_in_minutes <= 90) :
      return __('about an hour',true);
    case ($distance_in_minutes <= 1440) :
      return (round($distance_in_minutes / 60.0) == 1) ? __('about an hour',true) : sprintf(__('about %d hours',true),round($distance_in_minutes / 60.0));
    case ($distance_in_minutes <= 2880) :
      return  __('1 day',true);
    default :
      return sprintf(__('%d days',true),round($distance_in_minutes / 1440));
    }
  }
}
