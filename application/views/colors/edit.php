<h1><?php echo $title; ?></h1>
<?php
echo form_open('colors/edit', array('class' => 'form-horizontal'), array('id' => isset($id) ? $id : 0));
?>
	<div class="control-group">
		<label class="control-label" for="inputPackage">Package</label>
		<div class="controls">
			<input type="text" id="inputPackage" name="package" placeholder="com.example.app"> <!-- TODO Auto-populate drop down -->
		</div>
	</div>
<?php
foreach ($color_types as $color_type)
{
?>
	<div class="control-group">
		<label class="control-label" for="inputColortype_<?php echo $color_type->id; ?>"><?php echo $color_type->display_name; ?></label>
		<div class="controls">
			<input type="text" id="inputColortype_<?php echo $color_type->id; ?>" name="colortype_<?php echo $color_type->id; ?>" placeholder="<?php echo '#' . dechex($color_type->default_color); ?>" />
			<!-- TODO Color Picker -->
		</div>
	</div>
<?php
}
?>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save</button>
		<a href="/colors" class="btn">Cancel</a>
	</div>
</form>