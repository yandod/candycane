#!/usr/bin/ruby

master_file = "/Users/kabayaki91/ruby/redmine-0.8.1/lang/en.yml"
local_file = "/Users/kabayaki91/ruby/redmine-0.8.1/lang/ca.yml"

class Gloc2gettext
  def Gloc2gettext.parse(fname)
    tmpmap = Hash.new
    File.open(fname){|f|
      i = 0;
      f.each_line do |line|
        i += 1
        next if i < 1 # skip the header part.
        values = line.split(/\:\s/)
        next if values.count < 2
        tmpmap.store values[0], values[1].strip.gsub("\"","\\\"") 
      end
    }
    return tmpmap
  end
end

# extract english file
eng_map = Gloc2gettext.parse(master_file)
loc_map = Gloc2gettext.parse(local_file)



dup = Hash.new
eng_map.each do |key, value|
 next if dup.key? value
 dup.store value, 1 # based on enlgish message
 puts "# " + key
 puts "msgid \"" + value + "\""
 puts "msgstr \"" + loc_map[key] + "\""
 puts ""
end
