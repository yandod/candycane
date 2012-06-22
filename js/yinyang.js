(function() {
  var Template, TemplateLoop, TemplateText, TemplateVar, YinYang, href,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  YinYang = {
    version: '0.1.5',
    plugins: {},
    guid: function() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r, v;
        r = Math.random() * 16 | 0;
        v = c === 'x' ? r : r & 3 | 8;
        return v.toString(16);
      }).toUpperCase();
    }
  };

  YinYang.plugins.ajax = function(template, name, uri) {
    if (typeof console !== "undefined" && console !== null) {
      console.log("ajax request : " + uri);
    }
    return $.getJSON(uri).success(function(data) {
      template.setValue(name, data);
      return template.processPlaceholder(name);
    }).error(function() {
      return typeof console !== "undefined" && console !== null ? console.log("ajax error") : void 0;
    });
  };

  YinYang.plugins.hsql = function(tamplate, name, hsql) {
    if (typeof console !== "undefined" && console !== null) {
      console.log("hsql request : " + hsql);
    }
    return $.getJSON("/hsql.php?q=" + hsql).success(function(data) {
      template.setValue(name, data);
      return template.processPlaceholder(name);
    }).error(function() {
      return typeof console !== "undefined" && console !== null ? console.log("hsql error") : void 0;
    });
  };

  Template = (function() {

    Template.values = {
      meta: {},
      ajax: {},
      hsql: {}
    };

    Template.placeholders = {};

    Template.setup = function() {
      return $('meta').each(function(index) {
        if ($(this).attr('name') != null) {
          return Template.values.meta[$(this).attr('name').replace(/[^a-zA-Z0-9_]/g, '_')] = $(this).attr('content');
        } else if ($(this).attr('property') != null) {
          return Template.values.meta[$(this).attr('property').replace(/[^a-zA-Z0-9_]/g, '_')] = $(this).attr('content');
        }
      });
    };

    Template.fetch = function(html) {
      var content, flagment, meta, name, plugin, plugin_name, plugin_names, t, template, var_name, _i, _j, _len, _len2, _ref, _ref2;
      plugin_names = ((function() {
        var _ref, _results;
        _ref = YinYang.plugins;
        _results = [];
        for (name in _ref) {
          plugin = _ref[name];
          _results.push(name);
        }
        return _results;
      })()).join('|');
      _ref = html.match(new RegExp("<meta.*? name=\"(" + plugin_names + ")\\.[a-z][a-zA-Z0-9\\.]+\".*?>", 'gim'));
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        meta = _ref[_i];
        var_name = $(meta).attr('name');
        plugin_name = var_name.split('.')[0];
        content = $(meta).attr('content');
        YinYang.plugins[plugin_name](this, var_name, content);
      }
      t = template = new Template;
      _ref2 = html.split(/(<!--\{.+?\}-->|\#\{.+?\})/gim);
      for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
        flagment = _ref2[_j];
        if (flagment != null) t = t.add(flagment);
      }
      return template.display();
    };

    Template.valueExists = function(combinedKey) {
      var attr, attrs, tv, _ref;
      attrs = combinedKey.split('.');
      tv = Template.values;
      while ((tv != null) && (attr = attrs.shift())) {
        tv = (_ref = tv[attr]) != null ? _ref : null;
      }
      return tv != null;
    };

    Template.setValue = function(combinedKey, val) {
      var attr, attrs, lastattr, tv, _ref;
      attrs = combinedKey.split('.');
      lastattr = attrs.pop();
      tv = Template.values;
      while (attr = attrs.shift()) {
        tv = (_ref = tv[attr]) != null ? _ref : '';
      }
      return tv[lastattr] = val;
    };

    Template.setValues = function(vals) {
      var key, val, _results;
      _results = [];
      for (key in vals) {
        if (!__hasProp.call(vals, key)) continue;
        val = vals[key];
        _results.push(Template.values[key] = val);
      }
      return _results;
    };

    Template.getValue = function(combinedKey) {
      var attr, attrs, tv, _ref;
      attrs = combinedKey.split('.');
      tv = Template.values;
      while (attr = attrs.shift()) {
        tv = (_ref = tv[attr]) != null ? _ref : '';
      }
      return tv;
    };

    Template.addPlaceholder = function(name, callback) {
      return this.placeholders[name] = callback;
    };

    Template.processPlaceholder = function(name) {
      if (this.placeholders[name] != null) {
        this.placeholders[name]();
        return delete this.placeholders[name];
      }
    };

    function Template(parent, value, ignore) {
      this.parent = parent != null ? parent : null;
      this.value = value != null ? value : '';
      this.ignore = ignore != null ? ignore : false;
      this.children = [];
    }

    Template.prototype.add = function(value) {
      var re;
      re = {
        pend: /<!--\{end\}-->/,
        more: /<!--\{more\}-->/,
        pvar: /<!--\{(@[a-zA-Z0-9_\.\#>=\[\]]+|[a-zA-Z][a-zA-Z0-9_\.]*)\}-->/,
        ivar: /\#\{(@[a-zA-Z0-9_\.\#>=\[\]]+|[a-zA-Z][a-zA-Z0-9_\.]*)\}/,
        loop: /<!--\{[a-zA-Z][a-zA-Z0-9_\.]* in (@[a-zA-Z0-9_\.\#>=\[\]]+|[a-zA-Z][a-zA-Z0-9_\.]*)\}-->/
      };
      if (value.match(re.pend)) {
        this.ignore = false;
        return this.parent;
      } else if (value.match(re.more)) {
        this.ignore = true;
        return this;
      } else if (!this.ignore) {
        if (value.match(re.pvar)) {
          return this._add('child', new TemplateVar(this, value.replace(/<!--{|}-->/g, ''), true));
        } else if (value.match(re.ivar)) {
          return this._add('self', new TemplateVar(this, value.replace(/\#\{|\}/g, '')));
        } else if (value.match(re.loop)) {
          return this._add('child', new TemplateLoop(this, value.replace(/<!--{|}-->/g, '')));
        } else {
          return this._add('self', new TemplateText(this, value));
        }
      } else {
        return this;
      }
    };

    Template.prototype._add = function(ret, t) {
      this.children.push(t);
      switch (ret) {
        case 'child':
          return t;
        case 'self':
          return this;
      }
    };

    Template.prototype.display = function(localValues) {
      var child;
      if (localValues == null) localValues = {};
      return ((function() {
        var _i, _len, _ref, _results;
        _ref = this.children;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          child = _ref[_i];
          _results.push(child.display(localValues));
        }
        return _results;
      }).call(this)).join('');
    };

    return Template;

  })();

  TemplateLoop = (function(_super) {

    __extends(TemplateLoop, _super);

    function TemplateLoop() {
      TemplateLoop.__super__.constructor.apply(this, arguments);
    }

    TemplateLoop.prototype.display = function(localValues) {
      var arrName, elName, _ref;
      this.placeholder_id = YinYang.guid();
      _ref = this.value.split(/\s+in\s+/), elName = _ref[0], arrName = _ref[1];
      if (Template.valueExists(arrName)) {
        return this.displayLoop(localValues, elName, arrName);
      } else if (arrName.match(/^(ajax|hsql)\./)) {
        return this.diaplayPlaceholder(localValues, elName, arrName);
      } else {
        if (typeof console !== "undefined" && console !== null) {
          console.log('Template value not found.');
        }
        return '';
      }
    };

    TemplateLoop.prototype.displayLoop = function(localValues, elName, arrName) {
      var child, el, key, lv, val;
      return ((function() {
        var _i, _len, _ref, _results;
        _ref = Template.getValue(arrName);
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          el = _ref[_i];
          _results.push(((function() {
            var _j, _len2, _ref2, _results2;
            _ref2 = this.children;
            _results2 = [];
            for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
              child = _ref2[_j];
              lv = {};
              for (key in localValues) {
                val = localValues[key];
                lv[key] = val;
              }
              lv[elName] = el;
              _results2.push(child.display(lv));
            }
            return _results2;
          }).call(this)).join(''));
        }
        return _results;
      }).call(this)).join('');
    };

    TemplateLoop.prototype.diaplayPlaceholder = function(localValues, 　elName, arrName) {
      var _this = this;
      Template.addPlaceholder(arrName, function() {
        var html;
        html = _this.displayLoop(localValues, elName, arrName);
        return $("#" + _this.placeholder_id).before(html).remove();
      });
      return "<span class=\"loading\" id=\"" + this.placeholder_id + "\"></span>";
    };

    return TemplateLoop;

  })(Template);

  TemplateVar = (function(_super) {

    __extends(TemplateVar, _super);

    function TemplateVar() {
      TemplateVar.__super__.constructor.apply(this, arguments);
    }

    TemplateVar.prototype.display = function(localValues) {
      this.localValues = localValues;
      if (this.value[0] === '@') {
        return this.displayDom();
      } else {
        return this.displayVar();
      }
    };

    TemplateVar.prototype.displayDom = function() {
      return $(this.value.substring(1)).html();
    };

    TemplateVar.prototype.displayVar = function() {
      return (this.getLocalValue(this.value)) || Template.getValue(this.value);
    };

    TemplateVar.prototype.getLocalValue = function(combinedKey) {
      var attr, attrs, tv, _ref;
      attrs = combinedKey.split('.');
      tv = this.localValues;
      while (attr = attrs.shift()) {
        tv = (_ref = tv[attr]) != null ? _ref : '';
      }
      return tv;
    };

    return TemplateVar;

  })(Template);

  TemplateText = (function(_super) {

    __extends(TemplateText, _super);

    function TemplateText() {
      TemplateText.__super__.constructor.apply(this, arguments);
    }

    TemplateText.prototype.display = function() {
      return this.value;
    };

    return TemplateText;

  })(Template);

  Template.setup();

  href = $('link[rel=template]').attr('href');

  $.ajax({
    url: href,
    success: function(html) {
      var tdir;
      tdir = href.replace(/[^\/]+$/, '');
      html = html.replace(/(href|src)="([^#^/:]+)\//g, function() {
        return "" + arguments[1] + "=\"" + tdir + arguments[2];
      });
      html = Template.fetch(html);
      return $('html').html((html.split(/(<html.*?>|<\/html>)/ig))[2]);
    }
  });

}).call(this);
