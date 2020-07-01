<?php
function wpdocs_theme_name_scripts() {
    wp_enqueue_script( 'script-name', get_template_directory_uri() . '/scripts/general.js', array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts', 999 );
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'html5', array( 'search-form' ) );
global $content_width;
if ( ! isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'blankslate' ), 'social-menu' => esc_html__( 'Social Menu', 'blankslate' ) ) );
}
add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );
function blankslate_load_scripts() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'blankslate_footer_scripts' );
function blankslate_footer_scripts() {
?>
<script>
jQuery(document).ready(function ($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'blankslate_document_title_separator' );
function blankslate_document_title_separator( $sep ) {
$sep = '|';
return $sep;
}
add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
if ( $title == '' ) {
return '...';
} else {
return $title;
}
}
add_filter( 'the_content_more_link', 'blankslate_read_more_link' );
function blankslate_read_more_link() {
if ( ! is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">...</a>';
}
}
add_filter( 'excerpt_more', 'blankslate_excerpt_read_more_link' );
function blankslate_excerpt_read_more_link( $more ) {
if ( ! is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">...</a>';
}
}
add_filter( 'intermediate_image_sizes_advanced', 'blankslate_image_insert_override' );
function blankslate_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
return $sizes;
}
add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'blankslate' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'blankslate_pingback_header' );
function blankslate_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function blankslate_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}
add_filter( 'get_comments_number', 'blankslate_comment_count', 0 );
function blankslate_comment_count( $count ) {
if ( ! is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

add_action( 'wp_footer', 'my_action_javascript', 999 ); // Write our JS below here

function my_action_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var clicked = 0;
		$(".post_content").on('mouseover',function (event) {
			event.preventDefault();
		    var id_post = $(this).attr('id');
		    $.ajax({
		        type: 'POST',
		        url: '<?php echo admin_url('admin-ajax.php'); ?>',
		        data: {
		            'post_id': id_post,
		            'action': 'f711_get_post_content' //this is the name of the AJAX method called in WordPress
		        }, success: function (data) {
		        	if(clicked === 0) {
		           $('#image-back').attr('src', data.image);
		           $('#image-back').show();
		           $( '#content-post-ajax' ).append('<h1 id="title-post">' + data.title + '</h1>' + data.content);
		           clicked = 1;
		           }
		        },
		        error: function () {
		            alert("error");
		        }
		    });
		}).mouseout(function (event) {
			event.preventDefault();
			$('#image-back').hide();
		    $( '#content-post-ajax' ).empty();
		    clicked = 0;
		});
	});
	</script> <?php
}

add_action( 'wp_ajax_f711_get_post_content', 'f711_get_post_content_callback' );
add_action( 'wp_ajax_nopriv_f711_get_post_content', 'f711_get_post_content_callback' );

function f711_get_post_content_callback() {

    // retrieve post_id, and sanitize it to enhance security
    $post_id = intval($_POST['post_id'] );

    // Check if the input was a valid integer
    if ( $post_id == 0 ) {

        $response['error'] = 'true';
        $response['result'] = 'Invalid Input';

    } else {

        // get the post
        $thispost = get_post( $post_id );

        // check if post exists
        if ( !is_object( $thispost ) ) {

            $response['error'] = 'true';
            $response['result'] =  'There is no post with the ID ' . $post_id;

        } else {

            $response['image'] = get_the_post_thumbnail_url($thispost,'full');
            $response['title'] = get_the_title($thispost);
            $response['content'] = wpautop( $thispost->post_content );

        }

    }

    wp_send_json( $response );

}

function html5_search_form( $form ) { 
     $form = '<section class="search"><form role="search" method="get" id="search-form" action="' . home_url( '/' ) . '" >
    <label class="screen-reader-text" for="s">' . __('',  'domain') . '
     <input type="search" value="' . get_search_query() . '" name="s" id="s" placeholder="" /></label>
     </form></section>';
     return $form;
}

 add_filter( 'get_search_form', 'html5_search_form' );