<?php
class Setting extends AppModel
{
  var $name = 'Setting' ;
  
  var $DATE_FORMATS = array(
	'%Y-%m-%d',
	'%d/%m/%Y',
	'%d.%m.%Y',
	'%d-%m-%Y',
	'%m/%d/%Y',
	'%d %b %Y',
	'%d %B %Y',
	'%b %d, %Y',
	'%B %d, %Y'
  );
    
  var $TIME_FORMATS = array(
    '%H:%M',
    '%I:%M %p'
    );

  var $USER_FORMATS = array(
      'firstname_lastname' => '%1$s %2$s',
      'firstname' => '%1$s',
      'lastname_firstname' => '%2$s %1$s',
      'lastname_coma_firstname' => '%2$s, %1$s',
      'username' => '%3$s'
  );
#  ENCODINGS = %w(US-ASCII
#                  windows-1250
#                  windows-1251
#                  windows-1252
#                  windows-1253
#                  windows-1254
#                  windows-1255
#                  windows-1256
#                  windows-1257
#                  windows-1258
#                  windows-31j
#                  ISO-2022-JP
#                  ISO-2022-KR
#                  ISO-8859-1
#                  ISO-8859-2
#                  ISO-8859-3
#                  ISO-8859-4
#                  ISO-8859-5
#                  ISO-8859-6
#                  ISO-8859-7
#                  ISO-8859-8
#                  ISO-8859-9
#                  ISO-8859-13
#                  ISO-8859-15
#                  KOI8-R
#                  UTF-8
#                  UTF-16
#                  UTF-16BE
#                  UTF-16LE
#                  EUC-JP
#                  Shift_JIS
#                  GB18030
#                  GBK
#                  ISCII91
#                  EUC-KR
#                  Big5
#                  Big5-HKSCS
#                  TIS-620)
#  
#  cattr_accessor :available_settings
#  @@available_settings = YAML::load(File.open("#{RAILS_ROOT}/config/settings.yml"))
#  Redmine::Plugin.all.each do |plugin|
#    next unless plugin.settings
#    @@available_settings["plugin_#{plugin.id}"] = {'default' => plugin.settings[:default], 'serialized' => true}    
#  end
#  
#  validates_uniqueness_of :name
#  validates_inclusion_of :name, :in => @@available_settings.keys
#  validates_numericality_of :value, :only_integer => true, :if => Proc.new { |setting| @@available_settings[setting.name]['format'] == 'int' }  
#
#  # Hash used to cache setting values
#  @cached_settings = {}
#  @cached_cleared_on = Time.now
#  
#  def value
#    v = read_attribute(:value)
#    # Unserialize serialized settings
#    v = YAML::load(v) if @@available_settings[name]['serialized'] && v.is_a?(String)
#    v = v.to_sym if @@available_settings[name]['format'] == 'symbol' && !v.blank?
#    v
#  end
#  
#  def value=(v)
#    v = v.to_yaml if v && @@available_settings[name]['serialized']
#    write_attribute(:value, v.to_s)
#  end
#  
#  # Returns the value of the setting named name
#  def self.[](name)
#    v = @cached_settings[name]
#    v ? v : (@cached_settings[name] = find_or_default(name).value)
#  end
  /**
   * instead of self.[] method of ruby version
   *
   * @param string $name
   * @param mixed $value
   */
  function store($name,$value)
  {
  	 $cond = array('name' => $name);
  	 $data = $this->find('all',array('conditions' => $cond));
     $id = null;
     if (isset($data[0]['Setting']['id'])) $id = $data[0]['Setting']['id'];
     
     //convert array value
     if (is_array($value)) {
       $tmp_value = "---\n";
       foreach($value as $v) {
         $tmp_value .= '- '.$v."\n";
       }
       $value = $tmp_value;
     }
     
     $arr = array(
       'id' => $id,
       'name' => $name,
       'value' => $value
     );
     $this->save($arr);
  	 #    setting = find_or_default(name)
#    setting.value = (v ? v : "")
#    @cached_settings[name] = nil
#    setting.save
#    setting.value
  }
#  
#  # Defines getter and setter for each setting
#  # Then setting values can be read using: Setting.some_setting_name
#  # or set using Setting.some_setting_name = "some value"
#  @@available_settings.each do |name, params|
#    src = <<-END_SRC
#    def self.#{name}
#      self[:#{name}]
#    end
#
#    def self.#{name}?
#      self[:#{name}].to_i > 0
#    end
#
#    def self.#{name}=(value)
#      self[:#{name}] = value
#    end
#    END_SRC
#    class_eval src, __FILE__, __LINE__
#  end
#  
#  # Helper that returns an array based on per_page_options setting
#  def self.per_page_options_array
#    per_page_options.split(%r{[\s,]}).collect(&:to_i).select {|n| n > 0}.sort
#  end
#  
#  # Checks if settings have changed since the values were read
#  # and clears the cache hash if it's the case
#  # Called once per request
#  def self.check_cache
#    settings_updated_on = Setting.maximum(:updated_on)
#    if settings_updated_on && @cached_cleared_on <= settings_updated_on
#      @cached_settings.clear
#      @cached_cleared_on = Time.now
#      logger.info "Settings cache cleared." if logger
#    end
#  end
#  
#private
#  # Returns the Setting instance for the setting named name
#  # (record found in database or new record with default value)
#  def self.find_or_default(name)
#    name = name.to_s
#    raise "There's no setting named #{name}" unless @@available_settings.has_key?(name)    
#    setting = find_by_name(name)
#    setting ||= new(:name => name, :value => @@available_settings[name]['default']) if @@available_settings.has_key? name
#  end
  public function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $var = include APP . 'Config' . DS.DS.'settings.php';
	App::import('Vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
    foreach ($var as $k => $v) {
    	$this->{$k} = $v;
    }
    $data = $this->find('all');
    foreach ($data as $k => $v) {
      switch ($v['Setting']['name']){
      	case 'per_page_options':
      	  $this->{$v['Setting']['name']} = explode(',',$v['Setting']['value']);
      	  break;
      	case 'issue_list_default_columns':
      	  $this->{$v['Setting']['name']} = Spyc::YAMLLoad($v['Setting']['value']);
      	  // array_slice(array_map('trim',explode('- ',$v['Setting']['value'])),1);
      	  break;
      	default:
          $this->{$v['Setting']['name']} = $v['Setting']['value'];
      }
    }
  }
}
