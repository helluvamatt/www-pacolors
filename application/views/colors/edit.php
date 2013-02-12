<h1><?php echo $title; ?>&nbsp;<small>Hover over the image to change options.</small></h1>
<?php
echo form_open('colors/save', array('class' => 'form-horizontal'), array('id' => isset($color->id) ? $color->id : 0));
echo form_hidden('color_navbar_bg', Color_Object::format_color_string($color->get_color_navbar_bg(), FALSE));
echo form_hidden('color_navbar_fg', Color_Object::format_color_string($color->get_color_navbar_fg(), FALSE));
echo form_hidden('color_navbar_gl', Color_Object::format_color_string($color->get_color_navbar_gl(), FALSE));
echo form_hidden('color_status_bg', Color_Object::format_color_string($color->get_color_status_bg(), FALSE));
echo form_hidden('color_status_fg', Color_Object::format_color_string($color->get_color_status_fg(), FALSE));
echo form_hidden('app_id', $color->appid);
?>
	<div class="control-group">
		<label class="control-label" for="inputAppName">Application Name</label>
		<div class="controls">
			<input type="text" id="inputAppName" name="app_name" placeholder="Example App" autocomplete="off" value="<?php echo set_value('app_name', $color->app_name); ?>"> <!-- TODO Auto-populate drop down -->
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="inputAppPackage">Application Package</label>
		<div class="controls">
			<input type="text" id="inputAppPackage" name="app_package" placeholder="com.example.app" value="<?php echo set_value('app_package', $color->app_package); ?>">
		</div>
	</div>

	<div class="row">
		<div class="span8" style="position:relative;" id="preview_container">
			<img src="<?php echo site_url('render/live'); ?>" alt="preview" id="preview_img" />
			<a href="javascript:;" class="btn btn-mini autohide" style="position:absolute;top:12px;left:12px;"   data-param="status_bg" title="Status Bar Background"><i class="icon-tint"></i></a>
			<a href="javascript:;" class="btn btn-mini autohide" style="position:absolute;top:12px;left:560px;"  data-param="status_fg" title="Status Bar Foreground"><i class="icon-tint"></i></a>
			<a href="javascript:;" class="btn btn-mini autohide" style="position:absolute;top:188px;left:12px;"  data-param="navbar_bg" title="Navbar Background "><i class="icon-tint"></i></a>
			<a href="javascript:;" class="btn btn-mini autohide" style="position:absolute;top:188px;left:600px;" data-param="navbar_fg" title="Navbar Foreground"><i class="icon-tint"></i></a>
			<a href="javascript:;" class="btn btn-mini autohide" style="position:absolute;top:188px;left:260px;" data-param="navbar_gl" title="Navbar Glow"><i class="icon-tint"></i></a>
		</div>
		<div class="span4">
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Background:</b>&nbsp;<span data-param="navbar_bg" class="picker"></span></p>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Foreground:</b>&nbsp;<span data-param="navbar_fg" class="picker"></span></p>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Glow:</b>&nbsp;<span data-param="navbar_gl" class="picker"></span></p>
			<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Background:</b>&nbsp;<span data-param="status_bg" class="picker"></span></p>
			<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Foreground:</b>&nbsp;<span data-param="status_fg" class="picker"></span></p>
		</div>
	</div>

	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save</button>
		<a href="/colors" class="btn">Cancel</a>
	</div>
</form>
<script type="text/javascript" src="<?php echo base_url('assets/js/jpicker-1.1.6.custom.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/autocomplete.js'); ?>"></script>
<script type="text/javascript">
function get_color_rgba(param)
{
  var color_argb = get_color(param);
  return color_argb.substr(2) + color_argb.substr(0, 2);
}

function get_color(param)
{
  return $("[name='color_" + param + "']").val();
}

function set_color(param, value)
{
  $("[name='color_" + param + "']").val(value);
}

function rgba_to_render(color)
{
  return color.substr(6) + color.substr(0, 6);
}

function change() {
  var navbar_bg = get_color('navbar_bg');
  var navbar_fg = get_color('navbar_fg');
  var navbar_gl = get_color('navbar_gl');
  var status_bg = get_color('status_bg');
  var status_fg = get_color('status_fg');
  var img = '<?php echo site_url('render/live'); ?>?navbar_bg=' + navbar_bg + '&navbar_fg=' + navbar_fg + '&navbar_gl=' + navbar_gl + '&status_bg=' + status_bg + '&status_fg=' + status_fg;
  $('#preview_img').attr('src', img);
};

function open_picker(param)
{
  // Open our picker
  $.jPicker.List[param].show();
}

var current_items = [];

$(function() {
  $('#preview_container').hover(function() {
    $('div#preview_container a.autohide').show();
  }, function() {
    $('div#preview_container a.autohide').hide();
  });
  
  $('span.picker').each(function() {
    var $this = $(this);
    var param = $this.data('param');
    var color = get_color_rgba(param);
    
    $this.jPicker({
      id: param,
      window: {
        expandable: true,
        alphaSupport: true,
        position: {
          x: 'screenCenter',
          y: 'screenTop'
        },
        effects: {type: 'fade', speed: {show: 100, hide: 100} } // Fade in and out in 100 ms
      },
      color: {
        active: new $.jPicker.Color({ ahex: color }),
        quickList: [
          new $.jPicker.Color({ ahex: '000000FF' }),
          new $.jPicker.Color({ ahex: 'FFFFFFB2' }),
          new $.jPicker.Color({ ahex: 'FFFFFFFF' }),
          new $.jPicker.Color({ ahex: '000000FF' }),
          new $.jPicker.Color({ ahex: '33B5E5FF' }),
          new $.jPicker.Color()
        ]
      },
      images: {clientPath: "<?php echo base_url('assets/img'); ?>/"}
    }, function(color, context) {
      var c = rgba_to_render(color.val('ahex'));
      set_color(param, c);
      change();
    });
  });
  
  // Buttons activate color pickers
  $('div#preview_container a.autohide').click(function() {
    var $this = $(this);
    var param = $this.data('param');
    open_picker(param);
  });
  
  change();

  // Prepare the autocomplete app dropdown
  $('input#inputAppName').autocomplete({
    source: function(query, process_cb) {
      // applications/aci/<query>
      $.ajax({
        url: '<?php echo site_url("applications/aci"); ?>/' + query
      , success: function(data, status, jqXHR) {
          process_cb(data);
        }
      });
    }
  , matcher: function (item) { return true; }
  , sorter: function (items) {
      var beginswith = []
        , caseSensitive = []
        , caseInsensitive = []
        , item
      while (item = items.shift()) {
        var i = item.primary;
        if (!i.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(item)
        else if (~i.indexOf(this.query)) caseSensitive.push(item)
        else caseInsensitive.push(item)
      }
      return beginswith.concat(caseSensitive, caseInsensitive)
    }
  , updater: function (idx) {
      item = current_items[idx];
      $('input#inputAppPackage').val(item.secondary);
      return item.primary
    }
  , item: '<li><a href="#"><span class="primary"></span><br><i class="secondary"></i></a></li>'
  , render: function (items) {
      var that = this
      items = $(items).map(function (i, item) {
	    current_items[item.id] = item;
        i = $(that.options.item).attr('data-value', item.id)
        i.find('a span.primary').html(that.highlighter(item.primary))
		i.find('a i.secondary').html(item.secondary)
        return i[0]
      })
      items.first().addClass('active')
      this.$menu.html(items)
      return this
    }
  });
	
});
</script>