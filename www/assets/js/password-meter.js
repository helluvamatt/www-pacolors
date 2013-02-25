/*
 * Password Meter
 *
 * Copyright 2013 Matt Schneeberger
 */

!function ($) {

  "use strict"; // jshint ;_;

  var PasswordMeter = function (element, options) {
    this.$element = $(element)
    this.options = options
    this.$element.on('keyup', $.proxy(this.render, this))
    this.render();
  }

  PasswordMeter.prototype = {

    calculate: function(password) {
      var c;
      for (var i = 0; i < this.options.complexities.length; i++)
      {
        c = this.options.complexities[i];
        c.index = i;
        if (typeof c.matches == 'undefined') return c;
        var re = new RegExp(c.matches);
        if (re.test(password)) {
          return c;
        }
      }
    }

  , render: function () {
      var password = this.$element.val()
        , complexity = this.calculate(password)
        , $parent = (this.options.renderTarget != null) ? $(this.options.renderTarget) : this.$element.parent();
      if (typeof this.meter == 'undefined' || typeof this.meterBar == 'undefined')
      {
        this.meter = $(this.options.renderContainer)
        this.meterBar = $(this.options.renderBar)
        this.meter.append(this.meterBar);
        $parent.append(this.meter);
      }
      this.meterBar.text(complexity.text);
      this.meterBar.css('width', (((this.options.complexities.length - complexity.index) / this.options.complexities.length ) * 100) + '%');
      this.meter.attr('class', complexity.cssClass);
    }
  }

  var old = $.fn.passwordMeter

  $.fn.passwordMeter = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('password')
        , options = $.extend({}, $.fn.passwordMeter.defaults, typeof option == 'object' && option)
      if (!data) $this.data('', (data = new PasswordMeter(this, options)))
    })
  }

  $.fn.passwordMeter.defaults = {
    complexities: [   // List of complexities to parse for, the first one that is hit is the one that is used, so they should be in order from most complex (most secure) to least
      {
        matches: '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\\~\\`\\!\\@\\#\\$\\%\\^\\&\\*\\(\\)\\_\\-\\+\\=\\[\\]\\{\\}\\;\\\'\\:\\"\\,\\.\\/\\\\\\|\\<\\>\\?]).{14,}$' // lower case, upper case, numbers, symbols AND greater than 14 chars
      , cssClass: 'progress progress-success' // cssClass to apply to message box
      , text: 'Strong' // Text to display in the message box
      }
    , {
        matches: '((^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$)|(^(?=.*[a-z])(?=.*[0-9]).{14,}$))' // lower case, upper case, and numbers OR lower case and numbers, greater than 14 chars
      , cssClass: 'progress progress-info'
      , text: 'Good'
      }
    , {
        matches: '^(?=.*[a-z])(?=.*[0-9]).{6,}$' // lower case or numbers, less than 14 chars
      , cssClass: 'progress progress-warning'
      , text: 'Weak'
      }
    , { // Default complexity in case none in the list above match
        cssClass: 'progress progress-danger'
      , text: 'Too Short'
      }
    ]
  , renderTarget: null  // Render the message box inside this container (use a jQuery selector), null to render in the parent of the input we are attached to
  , renderContainer: '<div></div>'
  , renderBar: '<div class="bar"></div>'
  }

  $.fn.passwordMeter.Constructor = PasswordMeter

  $.fn.passwordMeter.noConflict = function () {
    $.fn.passwordMeter = old
    return this
  }

}(window.jQuery);