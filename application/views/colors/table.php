<?php
if (isset($color_list) && count($color_list) > 0)
{
?>
<!-- Color List Start -->
<form>
	<div class="accordion" id="color_list_accordion">
<?php
	foreach($color_list as $color)
	{
?>
		<!-- Color Start -->
		<div class="accordion-group">
			<div class="accordion-heading row">
				<div class="span1" style=" vertical-align: middle; line-height: 48px;">
					<div style="padding-left: 6px; line-height: 24px; display: inline-block;" id="votes_<?php echo $color->id; ?>">
						<span><?php echo $color->votes; ?></span>
<?php
		if (isset($user)) {
			if (isset($hide_vote_control)) {
?>
						<i class="<?php echo $color->user_voted ? "icon-star" : "icon-star-empty"; ?>"></i>
<?php
			} else {
?>
						<a class="btn btn-mini btn-info <?php if ($color->user_voted) echo "active"; ?>" href="Javascript:;" onclick="cast_vote(<?php echo $color->id; ?>);" title="Vote for this Color Setting"><i class=" icon-white <?php echo $color->user_voted ? "icon-star" : "icon-star-empty"; ?>"></i></a>
<?php
			}
		}
?>
					</div>
				</div>
				<a class="accordion-toggle pull-left no-underline" title="Click for details." style="vertical-align: middle;" data-toggle="collapse" data-parent="#color_list_accordion" href="#collapse_color_<?php echo $color->id; ?>">
					<span><?php printf("#%u", $color->id); ?></span>
					<!-- Color Controls -->
					<img class="color-control" src="<?php echo site_url('render/color/' . $color->get_color_navbar_bg()); ?>" alt="Navbar Background" title="Navbar Background">
					<img class="color-control" src="<?php echo site_url('render/color/' . $color->get_color_navbar_fg()); ?>" alt="Navbar Buttons" title="Navbar Buttons">
					<img class="color-control" src="<?php echo site_url('render/color/' . $color->get_color_navbar_gl()); ?>" alt="Navbar Glow" title="Navbar Glow">
					<img class="color-control" src="<?php echo site_url('render/color/' . $color->get_color_status_bg()); ?>" alt="Status Bar Background" title="Status Bar Background">
					<img class="color-control" src="<?php echo site_url('render/color/' . $color->get_color_status_fg()); ?>" alt="Status Bar Foreground" title="Status Bar Foreground">
					<!-- Suggested Application -->
					<span><?php echo $color->app_name; ?></span>
				</a>
				<div class="span2 pull-right" style="line-height: 48px; text-align: right; margin-right: 6px;">
					<a href="<?php echo site_url('user/colors/' . $color->userid); ?>"><?php echo $color->user_name; ?></a>
				</div>
			</div>
			<div class="accordion-body collapse" id="collapse_color_<?php echo $color->id; ?>">
				<div class="accordion-inner row">
					<div class="span7">
						<!-- Color Rendering -->
						<img src="<?php echo site_url('render/preview/' . $color->id); ?>" alt="Preview" title="Preview" class="preview-img" />
					</div>
					<div class="span3">
						<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Background:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_bg()); ?></p>
						<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Foreground:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_fg()); ?></p>
						<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Glow:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_gl()); ?></p>
						<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Background:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_status_bg()); ?></p>
						<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Foreground:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_status_fg()); ?></p>
						<!-- Application -->
						<p>
							<b style="display: inline-block; width: 180px;">Suggested Application:</b>
							<a href="<?php echo site_url('applications/view/' . $color->appid); ?>" title="<?php echo $color->app_package; ?>"><?php echo $color->app_name; ?></a>
						</p>
						<!-- User Name -->
						<p>
							<b style="display: inline-block; width: 180px;">User:</b>
<?php if (isset($color->user_name)) { ?>
							<a href="<?php echo site_url('user/colors/' . $color->userid); ?>" title="<?php echo $color->user_name; ?>"><?php echo $color->user_name; ?></a>
<?php } else { ?>
							<i>None</i>
<?php } ?>
						</p>
					</div>
					<div class="pull-right" style="text-align: right;">
						<!-- Tools -->
<?php if ( isset($user) ) { ?>
<?php if ( $color->userid == $user->id ) { ?>
						<p><a href="<?php echo site_url('colors/edit/' . $color->id); ?>" title="Edit my color setting"   class="btn btn-mini" data-placement="left">Edit&nbsp;<i class="icon-edit"></i></a></p>
						<p><a href="<?php echo site_url('colors/delete/' . $color->id); ?>" title="Hide my color setting" class="btn btn-mini btn-danger" data-placement="left">Delete&nbsp;<i class="icon-white icon-trash"></i></a></p>
<?php } else { ?>
						<p><a href="<?php echo site_url('colors/edit/' . $color->id); ?>" title="Create new color setting from this" class="btn btn-mini btn-info" data-placement="left">Duplicate&nbsp;<i class="icon-white icon-share"></i></a></p>
<?php } ?>
<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!-- Color End -->
<?php
	}
?>
	</div>
</form>
<script type="text/javascript">
var display_error = function(message)
{
	var $error = $('<div class="alert alert-block alert-error fade in"><button type="button" class="close" data-dismiss="alert">×</button><h4 class="alert-heading">There was a problem...</h4><p class="message"></p><p><a class="btn" href="Javascript:;" data-dismiss="alert">OK</a></p></div>');
	$error.css({
		'z-index': 1000,
		'position': 'absolute',
		'top': '0px',
		'left': '0px',
		'right': '0px'
	});
	$('p.message', $error).text(message);
	$('body').append($error);
}

var vote_success = function(data)
{
	var $data = $(data);
	var $vote = $('vote', $data);
	var msg = $vote.text();
	if ( $vote.attr('status') == 'error' ) return display_error(msg);
	var id = $vote.attr('id');
	var count = $vote.attr('count');
	var vote_cast = $vote.attr('vote_cast');
	$vote_control = $('#votes_' + id);
	$('span', $vote_control).text(count);
	$('a', $vote_control).removeClass('active');
	if (vote_cast == '1') $('a', $vote_control).addClass('active');
}

var vote_error = function(jqXHR, textStatus)
{
	display_error("Network Error: " + textStatus);
}

var cast_vote = function(id)
{
	$.ajax({
		url: '<?php echo site_url('colors/vote'); ?>/' + id,
		success: vote_success,
		error: vote_error
	});
}

$(function() {
	$('a[title]').tooltip();
});
</script>
<!-- Color List End -->
<?php
}
else
{
?>
<!-- No Colors -->
<div class="alert alert-block alert-error">
<h4>Achtung!</h4>
<p>There were no color settings that matched that query.</p>
</div>
<?php
}
?>
