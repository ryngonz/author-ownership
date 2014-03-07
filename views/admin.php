<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Author_Ownership
 * @author    Ryan Gonzales <ryngonz@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Ryan Gonzales
 */
?>

<?php
	$post_types = get_post_types( '', 'names' ); 
	$updated = false;
	$message = "";
	$ao_length = 0;
	$ao_count = 0;
	if ( $_POST["o_email"] && !empty($_POST["o_email"]) ) {
		$ao_length = count($_POST["o_email"]);
		foreach ( $_POST["o_email"] as $ao_key => $ao_values ) {
			foreach ( $ao_values as $ao_value_key => $ao_value_val ) {
				// Sanitize the user input.
				$ao_email = sanitize_text_field( $ao_value_val );
				
				//Check if valid email
				if ( is_email( $ao_email ) ) {
					// Update the meta field.
					update_post_meta( $ao_value_key, '_ao_meta_value_key', $ao_email );
					$ao_count++;
				}
			}
		}
		if ( $ao_count == $ao_length ) {
			$message = "Updated all email Ownership.";
		}
		else {
			$message = "Ooops! There are invalid emails.";
		}
		$updated = true;
	}
?>

<div class="wrap">

	<?php //screen_icon(); ?>
	<div id="icon-users" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php
	if ( $updated ) :
	?>
	<div class="updated">
    	<p><?php echo $message; ?></p>
    </div>
    <?php
    endif;
    ?>
	<!-- TODO: Provide markup for your options page here. -->
	<h3>Ownership Rule:</h3>
		<form method="post" action="" name="ao_user_rule">
			<?php
			foreach ( $post_types as $post_type ) {
				if( post_type_supports( $post_type, 'author' )){
					echo '<h3 class="hndle toggle_table" style="font-weight:bold;"><span>'.$post_type.'</span> &#x25BC;</h3>';
					
					echo "<table class='ao-table wp-list-table widefat fixed posts' id='table_".$post_type."'>";
					echo "<tr>";
					echo "<th>ID</th>";
					echo "<th>Post Title</th>";
					echo "<th>Owner Email</th>";
					echo "</tr>";
					
					$args = array( 'post_type' => $post_type);
					$myposts = get_posts( $args );
					foreach ( $myposts as $post ) : setup_postdata( $post );
						echo "<tr>";
						echo "<td>".$post->ID."</td>";
						echo "<td>".$post->post_title."</td>";
						$value = get_post_meta( $post->ID, '_ao_meta_value_key', true );
						echo "<td><input type='text' name='o_email[".$post_type."][".$post->ID."]' value='".esc_attr( $value )."' /></td>";
						echo "</tr>";
					endforeach; 
					
					echo "</table>";
					echo "<br />";
					wp_reset_postdata();
				}
			}
			?>
			<input type="submit" class='button button-primary button-large' value="Save Changes"  />
		</form>
</div>