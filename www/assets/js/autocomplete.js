/* =============================================================
 * Autocomplete/Typeahead with custom renderer
 * Extends Bootstrap Typeahead v2.2.2
 * =============================================================
 * Copyright 2012 Matt Schneeberger
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */

!function($){

  "use strict"; // jshint ;_;
  
 /* AUTOCOMPLETE PUBLIC CLASS DEFINITION
  * ================================= */

  var Autocomplete = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, $.fn.typeahead.defaults, options)
    this.matcher = this.options.matcher || this.matcher
    this.sorter = this.options.sorter || this.sorter
    this.highlighter = this.options.highlighter || this.highlighter
    this.updater = this.options.updater || this.updater
    
    // Now, we can pull a custom render method from the options
    this.render = this.options.render || this.render
    
    this.source = this.options.source
    this.$menu = $(this.options.menu)
    this.shown = false
    this.listen()
  };
  
  Autocomplete.prototype = $.extend({}, $.fn.typeahead.Constructor.prototype, {
    // No additional custom methods
  });
  
    /* AUTOCOMPLETE PLUGIN DEFINITION
   * =========================== */

  $.fn.autocomplete = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('autocomplete')
        , options = typeof option == 'object' && option
      if (!data) $this.data('autocomplete', (data = new Autocomplete(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.autocomplete.defaults = $.extend({}, $.fn.typeahead.defaults, {
    // No additional defaults
  })

  $.fn.autocomplete.Constructor = Autocomplete;

}(window.jQuery);