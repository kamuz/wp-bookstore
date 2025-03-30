<?php
/**
 * Plugin Name: Bookstore
 * Description: A plugin to manage books
 * Version: 1.0.2
 *
 * @package bookstore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register CPT
 */
function bookstore_register_book_post_type() {
	$args = array(
		'labels'       => array(
			'name'          => 'Books',
			'singular_name' => 'Book',
			'menu_name'     => 'Books',
			'add_new'       => 'Add New Book',
			'add_new_item'  => 'Add New Book',
			'new_item'      => 'New Book',
			'edit_item'     => 'Edit Book',
			'view_item'     => 'View Book',
			'all_items'     => 'All Books',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'rest_base'    => 'books',
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'book', $args );
}
add_action( 'init', 'bookstore_register_book_post_type' );

/**
 * Register taxonomy
 */
function bookstore_register_genre_taxonomy() {
	$args = array(
		'labels'       => array(
			'name'          => 'Genres',
			'singular_name' => 'Genre',
			'edit_item'     => 'Edit Genre',
			'update_item'   => 'Update Genre',
			'add_new_item'  => 'Add New Genre',
			'new_item_name' => 'New Genre Name',
			'menu_name'     => 'Genre',
		),
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'genre' ),
		'show_in_rest' => true,
	);

	register_taxonomy( 'genre', 'book', $args );
}
add_action( 'init', 'bookstore_register_genre_taxonomy' );

/**
 * Add submenu to Books CPT menu
 */
function bookstore_add_booklist_submenu() {
	add_submenu_page(
		'edit.php?post_type=book',
		'Book List',
		'Book List',
		'edit_posts',
		'book-list',
		'bookstore_render_booklist'
	);
}
add_action( 'admin_menu', 'bookstore_add_booklist_submenu', 11 );

/**
 * Render booklist in admin
 */
function bookstore_render_booklist() {
	?>
	<div class="wrap">
		<div style="width:50%;" id="bookstore-booklist-admin">
			<h1 class="wp-heading-inline">Actions</h1>
			<div class="form-field">
				<button id="bookstore-load-books" class="button">Load Books</button>
				<button id="bookstore-fetch-books" class="button">Fetch Books</button>
				<h2>Books</h2>
				<textarea id="bookstore-booklist" cols="125" rows="15"></textarea>
			</div>
		</div>

		<div style="width:50%;" class="form-wrap">
			<h2>Add Book</h2>
			<form>
				<div class="form-field">
					<label for="bookstore-book-title">Book Title</label>
					<input type="text" id="bookstore-book-title" placeholder="Title">
				</div>
				<div class="form-field">
					<label for="bookstore-book-content">Book Content</label>
					<textarea id="bookstore-book-content" cols="100" rows="10"></textarea>
				</div>
				<div>
					<input type="button" id="bookstore-submit-book" class="button button-primary" value="Add New Book">
				</div>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Add CSS and JavaScript files
 */
function bookstore_enqueue_scripts() {
	$post = get_post();
	if ( 'book' !== $post->post_type ) {
		return;
	}
	wp_enqueue_style( 'bookstore-style', plugins_url() . '/bookstore/bookstore.css', null, '1.0.0' );
	wp_enqueue_script( 'bookstore-script', plugins_url() . '/bookstore/bookstore.js', null, '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'bookstore_enqueue_scripts' );

/**
 * Add admin CSS and JavaScript
 */
function bookstore_admin_enqueue_scripts() {
	wp_enqueue_script( 'bookstore-admin-script', plugins_url() . '/bookstore/admin-bookstore.js', array( 'wp-api', 'wp-api-fetch' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'bookstore_admin_enqueue_scripts' );