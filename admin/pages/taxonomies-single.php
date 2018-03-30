<?php 
$slug=isset($_GET['slug']) ? $_GET['slug'] : '';	
$taxonomy=pickle_calendar_get_taxonomy($slug); ?>

<h2 class="wp-heading-inline">Taxonomy</h2>

<a href="<?php echo admin_url('options-general.php?page=pickle-calendar&tab=taxonomies&action=edit'); ?>" class="page-title-action">Add New</a>

<form name="post" action="" method="post" class="taxonomy-form">
	<?php wp_nonce_field('update_taxonomy', 'pickle_calendar_admin'); ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="tax-input-row">
					<label for="tax_slug">Slug</label>
					<input type="text" name="tax_details[slug]" id="tax_slug" class="" value="<?php echo $taxonomy['slug']; ?>" />
				</div>

				<div class="tax-input-row">
					<label for="tax_label">Label</label>
					<input type="text" name="tax_details[label]" id="tax_label" class="" value="<?php echo $taxonomy['label']; ?>" />
				</div>
				
				<div class="tax-input-row">
					<label for="tax_label_plural">Label Plural</label>
					<input type="text" name="tax_details[label_plural]" id="tax_label_plural" class="" value="<?php echo $taxonomy['label_plural']; ?>" />
				</div>				
			</div>
			
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
					<div id="" class="postbox ">
						<div class="inside">
							<div id="delete-action">
								<a class="submitdelete deletion" href="<?php echo admin_url('options-general.php?page=pickle-calendar&tab=taxonomies&action=delete&slug='.$taxonomy['slug']); ?>">Delete</a>
							</div>
	
							<div id="publishing-action">
								<input name="save" type="submit" class="button button-primary button-large" id="publish" value="Update">
							</div>
						</div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</form>

