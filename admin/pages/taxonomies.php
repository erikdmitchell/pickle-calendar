<h2 class="wp-heading-inline">Taxonomies</h2>

<a href="#" class="page-title-action">Add New</a>

<table class="wp-list-table widefat fixed striped pickle-calendar-taxonomies">
	<thead>
		<tr>
			<td id="cb" class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
				<input id="cb-select-all-1" type="checkbox">
			</td>
			<th scope="col" id="title" class="manage-column column-title column-primary">
				<a href="#"><span>Title</span></a>
			</th>
		</tr>
	</thead>

	<tbody id="the-list">
		<?php foreach (picklecalendar()->settings['taxonomies'] as $taxonomy) : ?>
			<tr id="taxonomy-<?php echo $taxonomy['slug']; ?>" class="taxonomy-<?php echo $taxonomy['slug']; ?> taxonomy hentry">
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-<?php echo $taxonomy['slug']; ?>">Select <?php echo ucwords($taxonomy['label']); ?></label>
					<input id="cb-select-<?php echo $taxonomy['slug']; ?>" type="checkbox" name="taxonomy[]" value="<?php echo $taxonomy['slug']; ?>">
				</th>
				<td class="title column-title column-primary" data-colname="Title">
					<strong><a class="row-title" href="#" aria-label="“<?php echo ucwords($taxonomy['label']); ?>” (Edit)"><?php echo ucwords($taxonomy['label']); ?></a></strong>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>