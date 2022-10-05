<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

define( 'LINK_LIBRARY_ADMIN_PAGE_NAME', 'edit.php?post_type=link_library_links' );

require_once( ABSPATH . '/wp-admin/includes/bookmark.php' );
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php' );

$rss_settings         = '';
$pagehookmoderate     = '';
$pagehooksettingssets = '';
$pagehookstylesheet   = '';
$pagehookreciprocal   = '';

class link_library_plugin_admin {

	function __construct() {
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );

		//add filter for WordPress 2.8 changed backend box system !
		add_filter( 'screen_layout_columns', array( $this, 'on_screen_layout_columns' ), 10, 2 );
		//register callback for admin menu  setup
		add_action( 'admin_menu', array( $this, 'on_admin_menu' ), 100 );

		if ( function_exists( 'is_network_admin' ) && is_network_admin() ) {
			add_action( 'network_admin_menu', array( $this, 'network_settings_menu' ) );
		}

		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widget' ) );

		add_filter( 'plugin_row_meta', array( $this, 'set_plugin_row_meta' ), 1, 2 );

		add_action( 'wpmu_new_blog', array( $this, 'new_network_site' ), 10, 6 );

		add_action( 'admin_head', array( $this, 'admin_header' ) );

		add_action( 'add_meta_boxes', array( $this, 'll_make_wp_editor_movable' ), 0 );
		add_action( 'save_post', array( $this, 'll_save_link_fields' ), 10, 2 );
		add_filter( 'manage_edit-link_library_links_columns', array( $this, 'll_add_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'll_populate_columns' ) );
		add_filter( 'manage_edit-link_library_links_sortable_columns', array( $this, 'll_column_sortable' ) );
		add_filter( 'request', array( $this, 'll_column_ordering' ) );
		add_action( 'pre_get_posts', array( $this, 'll_custom_post_order' ) );
		add_action( 'quick_edit_custom_box', array( $this, 'll_display_custom_quickedit_link' ), 10, 2 );

		add_action( 'link_library_category_edit_form_fields', array( $this, 'll_link_library_category_new_fields' ), 10, 2 );
		add_action( 'link_library_category_add_form_fields', array( $this, 'll_link_library_category_new_fields' ), 10, 2 );

		add_action( 'edited_link_library_category', array( $this, 'll_save_link_library_category_new_fields' ), 10, 2 );
		add_action( 'created_link_library_category', array( $this, 'll_save_link_library_category_new_fields' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 99 );

		add_action( 'restrict_manage_posts', array( $this, 'll_link_cat_filter_list' ) );

		add_filter( 'parse_query', array( $this, 'll_perform_link_cat_filtering' ) );

		add_filter( 'manage_edit-link_library_category_columns', array( $this, 'll_category_custom_column_header' ), 10);
		add_filter( 'manage_link_library_category_custom_column', array( $this, 'll_add_category_id' ), 10, 3 );

		add_filter( 'manage_edit-link_library_tags_columns', array( $this, 'll_category_tags_custom_column_header' ), 10);
		add_filter( 'manage_link_library_tags_custom_column', array( $this, 'll_add_category_tags_id' ), 10, 3 );

		if ( $this->is_edit_page() ) {
			add_action( 'media_buttons', 'link_library_render_editor_button', 20 );
			add_action( 'admin_footer',  array( $this, 'render_modal' ) );
		}

		add_action( 'wp_ajax_link_library_recipbrokencheck', 'link_library_reciprocal_link_checker' );
	}

	function is_edit_page( $new_edit = null ) {
		global $pagenow;
		//make sure we are on the backend
		if ( ! is_admin() ) {
			return false;
		}

		if ( 'edit' == $new_edit ) {
			return in_array( $pagenow, array( 'post.php', ) );
		} elseif ( 'new' == $new_edit ) { //check for new post page
			return in_array( $pagenow, array( 'post-new.php' ) );
		} else { //check for either new or edit
			if ( isset( $_GET['post_type'] ) && 'link_library_links' != $_GET['post_type'] ) {
				return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
			} else {
				return false;
			}
		}
	}

	public function admin_scripts( $hook ) {
		wp_enqueue_script( 'linklibrary-shortcodes-embed', plugins_url( "js/linklibrary-shortcode-embed.js", __FILE__ ), array( 'jquery' ), '', true );
		wp_enqueue_style( 'linklibraryadminstyle', plugins_url( 'adminstyle.css', __FILE__ ) );

		if ( 'edit.php' === $hook && isset( $_GET['post_type'] ) && 'link_library_links' === $_GET['post_type'] ) {
			wp_enqueue_script( 'll_quick_edit', plugins_url('js/ll_admin_edit.js', __FILE__), false, null, true );
		}
	}

	public function render_modal() {
		$genoptions = get_option( 'LinkLibraryGeneral' );
		?>
		<div id="select_linklibrary_shortcode" style="display:none;">
			<div class="wrap">
				<h3><?php _e( 'Insert a Link Library shortcode', 'link-library' ); ?></h3>
				<div class="alignleft">
					<select id="linklibrary_shortcode_selector">
						<option value="link-library"><?php _e( 'Link List', 'link-library' ); ?></option>
						<option value="link-library-cats"><?php _e( 'Link Category List', 'link-library' ); ?></option>
						<option value="link-library-search"><?php _e( 'Link Search', 'link-library' ); ?></option>
						<option value="link-library-addlink"><?php _e( 'Add Link Form', 'link-library' ); ?></option>
					</select>
				</div>
				<div class="alignright">
					<a id="linklibrary_insert" class="button-primary" href="#" style="color:#fff;"><?php esc_attr_e( 'Insert Shortcode', 'link-library' ); ?></a>
					<a id="linklibrary_cancel" class="button-secondary" href="#"><?php esc_attr_e( 'Cancel', 'link-library' ); ?></a>
				</div>
				<div id="shortcode_options" class="alignleft clear">
					<div class="linklibrary-shortcode-section alignleft" id="link-library_wrapper"><p><strong>[link-library]</strong> - <?php _e( 'Render a list of links.', 'link-library' ); ?></p>
						<div class="linklibrary_input alignleft">
							<label for="linklibrary_link-library_libraryid"><?php _e( 'Library ID', 'link-library' ); ?></label>
							<br/>
							<select class="linklibrary_settings select" id="linklibrary_settings" name="settings" data-slug="settings" data-shortcode="settings" />
							<?php if ( empty( $genoptions['numberstylesets'] ) ) {
								$numberofsets = 1;
							} else {
								$numberofsets = $genoptions['numberstylesets'];
							}
							for ( $counter = 1; $counter <= $numberofsets; $counter ++ ): ?>
								<?php $tempoptionname = "LinkLibraryPP" . $counter;
								$tempoptions          = get_option( $tempoptionname ); ?>
								<option value="<?php echo $counter ?>"><?php _e( 'Library', 'link-library' ); ?> <?php echo $counter ?><?php if ( !empty( $tempoptions ) ) {
										echo " (" . $tempoptions['settingssetname'] . ")";
									} ?></option>
							<?php endfor; ?>
							</select>
							<br /><br />
							<label for="linklibrary_link-library_categorylistoverride"><?php _e( 'Single Link ID', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_singlelinkid text" type="text" id="linklibrary_singlelinkid" name="singlelinkid" />
							<p class="description"><?php _e( 'Specify ID of single link to be displayed', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_categorylistoverride"><?php _e( 'Category Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_categorylistoverride text" type="text" id="linklibrary_categorylistoverride" name="categorylistoverride" />
							<p class="description"><?php _e( 'Single, or comma-separated list of categories IDs to be displayed in the link list', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_excludecategoryoverride"><?php _e( 'Excluded Category Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_excludecategoryoverride text" type="text" id="linklibrary_excludecategoryoverride" name="excludecategoryoverride" />
							<p class="description"><?php _e( 'Single, or comma-separated list of categories IDs to be excluded from the link list', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_notesoverride"><?php _e( 'Notes Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_notesoverride text" type="text" id="linklibrary_notesoverride" name="notesoverride" />
							<p class="description"><?php _e( 'Set to 0 or 1 to display or not display link notes', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_descoverride"><?php _e( 'Notes Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_descoverride text" type="text" id="linklibrary_descoverride" name="descoverride" />
							<p class="description"><?php _e( 'Set to 0 or 1 to display or not display link descriptions', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_rssoverride"><?php _e( 'Notes Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_rssoverride text" type="text" id="linklibrary_rssoverride" name="rssoverride" />
							<p class="description"><?php _e( 'Set to 0 or 1 to display or not display rss information', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_tableoverride"><?php _e( 'Notes Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_tableoverride text" type="text" id="linklibrary_tableoverride" name="tableoverride" />
							<p class="description"><?php _e( 'Set to 0 or 1 to display links in an unordered list or a table', 'link-library' ); ?></p>
						</div>
					</div>
					<div class="linklibrary-shortcode-section alignleft" id="link-library-cats_wrapper"><p><strong>[link-library-cats]</strong> - <?php _e( 'Render a list of link categories.', 'link-library' ); ?></p>
						<div class="linklibrary_input alignleft">
							<label for="linklibrary_link-library_libraryid"><?php _e( 'Library ID', 'link-library' ); ?></label>
							<br/>
							<select class="linklibrary_settings select" id="linklibrary_settings" name="settings" data-slug="settings" data-shortcode="settings" />
							<?php if ( empty( $genoptions['numberstylesets'] ) ) {
								$numberofsets = 1;
							} else {
								$numberofsets = $genoptions['numberstylesets'];
							}
							for ( $counter = 1; $counter <= $numberofsets; $counter ++ ): ?>
								<?php $tempoptionname = "LinkLibraryPP" . $counter;
								$tempoptions          = get_option( $tempoptionname ); ?>
								<option value="<?php echo $counter ?>"><?php _e( 'Library', 'link-library' ); ?> <?php echo $counter ?><?php if ( !empty( $tempoptions ) ) {
										echo " (" . $tempoptions['settingssetname'] . ")";
									} ?></option>
							<?php endfor; ?>
							</select>
							<br /><br />
							<label for="linklibrary_link-library_categorylistoverride"><?php _e( 'Category Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_categorylistoverride text" type="text" id="linklibrary_categorylistoverride" name="categorylistoverride" />
							<p class="description"><?php _e( 'Single, or comma-separated list of categories IDs to be displayed in the link list', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_excludecategoryoverride"><?php _e( 'Excluded Category Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_excludecategoryoverride text" type="text" id="linklibrary_excludecategoryoverride" name="excludecategoryoverride" />
							<p class="description"><?php _e( 'Single, or comma-separated list of categories IDs to be excluded from the link list', 'link-library' ); ?></p>
						</div>
					</div>
					<div class="linklibrary-shortcode-section alignleft" id="link-library-search_wrapper"><p><strong>[link-library-search]</strong> - <?php _e( 'Render a search box to search through links.', 'link-library' ); ?></p>
						<div class="linklibrary_input alignleft">
							<p class="description"><?php _e( 'There are no options for this shortcode.', 'link-library' ); ?></p>
						</div>
					</div>
					<div class="linklibrary-shortcode-section alignleft" id="link-library-addlink_wrapper"><p><strong>[link-library-addlink]</strong> - <?php _e( 'Render a form for visitors to submit new links.', 'link-library' ); ?></p>
						<div class="linklibrary_input alignleft">
							<label for="linklibrary_link-library_libraryid"><?php _e( 'Library ID', 'link-library' ); ?></label>
							<br/>
							<select class="linklibrary_settings select" id="linklibrary_settings" name="settings" data-slug="settings" data-shortcode="settings" />
							<?php if ( empty( $genoptions['numberstylesets'] ) ) {
								$numberofsets = 1;
							} else {
								$numberofsets = $genoptions['numberstylesets'];
							}
							for ( $counter = 1; $counter <= $numberofsets; $counter ++ ): ?>
								<?php $tempoptionname = "LinkLibraryPP" . $counter;
								$tempoptions          = get_option( $tempoptionname ); ?>
								<option value="<?php echo $counter ?>"><?php _e( 'Library', 'link-library' ); ?> <?php echo $counter ?><?php if ( !empty( $tempoptions ) ) {
										echo " (" . $tempoptions['settingssetname'] . ")";
									} ?></option>
							<?php endfor; ?>
							</select>
							<br /><br />
							<label for="linklibrary_link-library_categorylistoverride"><?php _e( 'Category Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_categorylistoverride text" type="text" id="linklibrary_categorylistoverride" name="categorylistoverride" />
							<p class="description"><?php _e( 'Single, or comma-separated list of categories IDs to be displayed in the link list', 'link-library' ); ?></p>
							<br />
							<label for="linklibrary_link-library_excludecategoryoverride"><?php _e( 'Excluded Category Override', 'link-library' ); ?></label>
							<br />
							<input class="linklibrary_excludecategoryoverride text" type="text" id="linklibrary_excludecategoryoverride" name="excludecategoryoverride" />
							<p class="description"><?php _e( 'Single, or comma-separated list of categories IDs to be excluded from the link list', 'link-library' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php
	}

	function ll_link_library_category_new_fields( $tag ) {

		$caturl = '';
		$cat_extra_query_string = '';

		if ( is_object( $tag ) ) {
			$mode   = "edit";
			$caturl = get_term_meta( $tag->term_id, 'linkcaturl', true );
			$cat_extra_query_string = get_term_meta( $tag->term_id, 'linkextraquerystring', true );
		} else {
			$mode = 'new';
		}

		?>

		<?php if ( $mode == 'edit' ) {
			echo '<tr class="form-field">';
		} elseif ( $mode == 'new' ) {
			echo '<div class="form-field">';
		} ?>

		<?php if ( $mode == 'edit' ) {
			echo '<th scope="row" valign="top">';
		} ?>
		<label for="tag-category-url">
			<?php _e( 'Category Link', 'link-library' ); ?></label>
		<?php if ( $mode == 'edit' ) {
			echo '</th>';
		} ?>

		<?php if ( $mode == 'edit' ) {
			echo '<td>';
		} ?>
		<input type="text" id="ll_category_url" name="ll_category_url" size="60" value="<?php echo $caturl; ?>" />
		<p class="description">Link that will be associated with category when displayed by Link Library</p>
		<?php if ( $mode == 'edit' ) {
			echo '</td>';
		} ?>
		<?php if ( $mode == 'edit' ) {
			echo '</tr>';
		} elseif ( $mode == 'new' ) {
			echo '</div>';
		}

		if ( $mode == 'edit' ) {
			echo '<tr class="form-field">';
		} elseif ( $mode == 'new' ) {
			echo '<div class="form-field">';
		} ?>

		<?php if ( $mode == 'edit' ) {
			echo '<th scope="row" valign="top">';
		} ?>
		<label for="tag-extra-query-string">
			<?php _e( 'Extra Query String', 'link-library' ); ?></label>
		<?php if ( $mode == 'edit' ) {
			echo '</th>';
		} ?>

		<?php if ( $mode == 'edit' ) {
			echo '<td>';
		} ?>
		<input type="text" id="cat_extra_query_string" name="cat_extra_query_string" size="60" value="<?php echo $cat_extra_query_string; ?>" />
		<p class="description">Query string arguments that will be added to all links in this category</p>
		<?php if ( $mode == 'edit' ) {
			echo '</td>';
		} ?>
		<?php if ( $mode == 'edit' ) {
			echo '</tr>';
		} elseif ( $mode == 'new' ) {
			echo '</div>';
		}
	}

	function ll_save_link_library_category_new_fields( $term_id, $tt_id ) {

		if ( !$term_id ) {
			return;
		}

		if ( isset( $_POST['ll_category_url'] ) ) {
			update_term_meta( $term_id, 'linkcaturl', $_POST['ll_category_url'] );
		}

		if ( isset( $_POST['cat_extra_query_string'] ) ) {
			update_term_meta( $term_id, 'linkextraquerystring', $_POST['cat_extra_query_string'] );
		}
	}

	function admin_header() {
		global $pagenow;
		if ( ( $pagenow == 'link.php' && $_GET['action'] == 'edit' ) || ( $pagenow == 'link-add.php' ) ) {
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
		}

		if ( isset( $_GET['page'] ) && ( ( $_GET['page'] == 'link-library-general-options' ) || $_GET['page'] == 'link-library-settingssets' || $_GET['page'] == 'link-library-moderate' || $_GET['page'] == 'link-library-stylesheet' || $_GET['page'] == 'link-library-reciprocal' || $_GET['page'] == 'link-library-accessibe' ) ) {
			wp_enqueue_style( 'LibraryLibraryAdminStyle', plugins_url( 'link-library-admin.css', __FILE__ ) );
		}
	}

	function set_plugin_row_meta( $links_array, $plugin_file ) {
		$genoptions = get_option( 'LinkLibraryGeneral' );

		if ( substr( $plugin_file, 0, 25 ) == substr( plugin_basename( __FILE__ ), 0, 25 ) && ( isset( $genoptions['hidedonation'] ) && !$genoptions['hidedonation'] ) ) {
			$links_array = array_merge( $links_array, array( '<a target="_blank" href="https://ylefebvre.home.blog/wordpress-plugins/link-library/">Donate</a>' ) );
		}

		return $links_array;
	}

	function db_prefix() {
		global $wpdb;  // Kept with CPT update
		if ( method_exists( $wpdb, "get_blog_prefix" ) ) {
			return $wpdb->get_blog_prefix();
		} else {
			return $wpdb->prefix;
		}
	}

	/* the function */
	function remove_querystring_var( $url, $key ) {

		$keypos = strpos( $url, $key );
		if ( $keypos ) {
			$ampersandpos = strpos( $url, '&', $keypos );
			$newurl       = substr( $url, 0, $keypos - 1 );

			if ( $ampersandpos ) {
				$newurl .= substr( $url, $ampersandpos );
			}
		} else {
			$newurl = $url;
		}

		return $newurl;
	}

	function ll_get_link_image( $url, $name, $mode, $linkid, $cid, $filepath, $filepathtype, $thumbnailsize, $thumbnailgenerator ) {
		if ( $url != "" && $name != "" ) {
			$protocol = is_ssl() ? 'https://' : 'http://';

			if ( $mode == 'thumb' || $mode == 'thumbonly' ) {
				if ( $thumbnailgenerator == 'robothumb' ) {
					$genthumburl = $protocol . "www.robothumb.com/src/?url=" . esc_html( $url ) . "&size=" . $thumbnailsize;
				} elseif ( $thumbnailgenerator == 'pagepeeker' ) {
					if ( empty( $cid ) ) {
						$genthumburl = $protocol . "free.pagepeeker.com/v2/thumbs.php?size=" . $thumbnailsize . "&url=" . esc_html( $url );
					} else {
						$genthumburl = $protocol . "api.pagepeeker.com/v2/thumbs.php?size=" . $thumbnailsize . "&url=" . esc_html( $url );
					}
				} elseif ( $thumbnailgenerator == 'shrinktheweb' ) {
					$genthumburl .= $protocol . "images.shrinktheweb.com/xino.php?stwembed=1&stwaccesskeyid=" . $cid . "&stwsize=" . $thumbnailsize . "&stwurl=" . esc_html( $url );
				} elseif ( $thumbnailgenerator == 'thumbshots' ) {
					if ( !empty ( $cid ) ) {
						$genthumburl = $protocol . "images.thumbshots.com/image.aspx?cid=" . rawurlencode( $cid ) . "&v1=w=120&url=" . esc_html( $url );
					}
				}
			} elseif ( $mode == 'favicon' || $mode == 'favicononly' ) {
				$genthumburl = $protocol . "www.google.com/s2/favicons?domain=" . $url;
			}

			$uploads = wp_upload_dir();

			if ( !file_exists( $uploads['basedir'] ) ) {
				return __( 'Please create a folder called uploads under your Wordpress /wp-content/ directory with write permissions to use this functionality.', 'link-library' );
			} elseif ( !is_writable( $uploads['basedir'] ) ) {
				return __( 'Please make sure that the /wp-content/uploads/ directory has write permissions to use this functionality.', 'link-library' );
			} else {
				if ( !file_exists( $uploads['basedir'] . '/' . $filepath ) ) {
					mkdir( $uploads['basedir'] . '/' . $filepath );
				}
			}

			$img    = $uploads['basedir'] . "/" . $filepath . "/" . $linkid . '.png';
			if ( $thumbnailgenerator != 'google' || $mode == 'favicon' || $mode == 'favicononly' ) {
				$status = file_put_contents( $img, @file_get_contents( $genthumburl ) );
			} elseif ( $thumbnailgenerator == 'google' && ( $mode == 'thumb' || $mode == 'thumbonly' ) ) {
				$screenshot = file_get_contents('https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=' . esc_html( $url ));
				$data_whole = json_decode($screenshot);

				if (isset($data_whole->error) || empty($screenshot)) {
					if (!(substr($url, 0, 4) == 'http')) {
						$url2 = 'https%3A%2F%2F' . $url;
						$screenshot = file_get_contents('https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url='.$url2);
						$data_whole = json_decode($screenshot);
					}
				}
				if (isset($data_whole->error) || empty($screenshot)) {
					if (!(substr($url, 0, 3) == 'www')) {
						$url3 = 'https%3A%2F%2F' . 'www.' . $url;
						$screenshot = file_get_contents('https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url='.$url3);
						$data_whole = json_decode($screenshot);
					}
				}
				if (isset($data_whole->error)) {
					$status = false;
				} else {
					if (isset($data_whole->lighthouseResult->audits->{'final-screenshot'}->details->data)) {
						$data = $data_whole->lighthouseResult->audits->{'final-screenshot'}->details->data;
						$data = str_replace('data:image/jpeg;base64','',$data);

						$data = str_replace('_', '/', $data);
						$data = str_replace('-', '+', $data);
						$base64img = str_replace('data:image/jpeg;base64,', '', $data);

						$data   		  = base64_decode($data);
						$upload_dir       = $uploads['basedir'] . '/' . $filepath; // Set upload folder
						$image_data       = $data; // img data
						$unique_file_name = wp_unique_filename( $uploads['basedir'] . '/' . $filepath, $linkid . '.png' ); // Generate unique name
						$filename         = basename( $unique_file_name ); // Create image file name

						// Create the image  file on the server
						file_put_contents( $img, $image_data );

						$exists = file_exists($tmp);
						$status = true;
					} else {
						$status = false;
					}

				}

			}

			if ( $status !== false ) {
				if ( $filepathtype == 'absolute' || empty( $filepathtype ) ) {
					$newimagedata = $uploads['baseurl'] . "/" . $filepath . "/" . $linkid . ".png";
				} elseif ( $filepathtype == 'relative' ) {
					$parsedaddress = parse_url( $uploads['baseurl'] );
					$newimagedata  = $parsedaddress['path'] . "/" . $filepath . "/" . $linkid . ".png";
				}

				if ( $mode == 'thumb' || $mode == 'favicon' ) {
					update_post_meta( $linkid, 'link_image', $newimagedata );

					if ( empty( $newimagedata ) ) {
						delete_post_thumbnail( $linkid );
					} else {
						$wpFileType = wp_check_filetype( $newimagedata, null);

						$attachment = array(
							'post_mime_type' => $wpFileType['type'],  // file type
							'post_title' => sanitize_file_name( $newimagedata ),  // sanitize and use image name as file name
							'post_content' => '',  // could use the image description here as the content
							'post_status' => 'inherit'
						);

						// insert and return attachment id
						$attachmentId = wp_insert_attachment( $attachment, $newimagedata, $linkid );
						$attachmentData = wp_generate_attachment_metadata( $attachmentId, $newimagedata );
						wp_update_attachment_metadata( $attachmentId, $attachmentData );
						set_post_thumbnail( $linkid, $attachmentId );
					}
				}

				return $newimagedata;
			} else {
				return "";
			}
		}

		return "Parameters are missing";
	}


	//for WordPress 2.8 we have to tell, that we support 2 columns !
	function on_screen_layout_columns( $columns, $screen ) {
		return $columns;
	}

	/**
	 * Returns the full URL of this plugin including trailing slash.
	 */

	function action_admin_init() {

		if ( isset($_GET['page']) && $_GET['page'] == 'link-library-faq' ) {
			wp_redirect( 'https://ylefebvre.home.blog/wordpress-plugins/link-library/link-library-faq/' );
			exit();
		}

		//register the callback been used if options of page been submitted and needs to be processed
		add_action( 'admin_post_save_link_library_general', array( $this, 'on_save_changes_general' ) );
		add_action( 'admin_post_save_link_library_settingssets', array( $this, 'on_save_changes_settingssets' ) );
		add_action( 'admin_post_save_link_library_moderate', array( $this, 'on_save_changes_moderate' ) );
		add_action( 'admin_post_save_link_library_stylesheet', array( $this, 'on_save_changes_stylesheet' ) );
		add_action( 'admin_post_save_link_library_reciprocal', array( $this, 'on_save_changes_reciprocal' ) );

		$catnames = get_terms( 'link_library_category', array( 'hide_empty' => false ) );

		if ( empty( $catnames ) ) {
			add_action( 'admin_notices', array( $this, 'll_missing_categories' ) );
		}

		$genoptions = get_option( 'LinkLibraryGeneral' );
		$genoptions = wp_parse_args( $genoptions, ll_reset_gen_settings( 'return' ) );
		extract( $genoptions );

		if ( !empty( $genoptions ) ) {
			if ( empty( $numberstylesets ) ) {
				$numberofsets = 1;
			} else {
				$numberofsets = $numberstylesets;
			}

			$thumbshotsactive = false;

			for ( $counter = 1; $counter <= $numberofsets; $counter ++ ) {
				$tempoptionname = "LinkLibraryPP" . $counter;
				$tempoptions    = get_option( $tempoptionname );
				$tempoptions = wp_parse_args( $tempoptions, ll_reset_options( 1, 'list', 'return' ) );
				if ( $tempoptions['usethumbshotsforimages'] ) {
					$thumbshotsactive = true;
				}
			}

			if ( $thumbshotsactive && empty( $genoptions['thumbshotscid'] ) && $genoptions['thumbnailgenerator'] == 'thumbshots' ) {
				add_action( 'admin_notices', array( $this, 'll_thumbshots_warning' ) );
			}

			if ( $thumbshotsactive && empty( $genoptions['shrinkthewebaccesskey'] ) && $genoptions['thumbnailgenerator'] == 'shrinktheweb' ) {
				add_action( 'admin_notices', array( $this, 'll_shrinktheweb_warning' ) );
			}
		}

		global $typenow;

		if ($typenow === 'link_library_links') {
			add_filter('posts_search', 'll_expand_posts_search', 10, 2);
		}
	}

	function ll_add_category_id( $content, $column_name, $term_id ){
		$content = $term_id;
		return $content;
	}

	function ll_category_custom_column_header( $columns ){
		$columns = array_merge( array_slice( $columns, 0, 2 ),
								array( 'taxonomy_id' => 'Category ID' ),
								array_slice( $columns, 2 ) );
		return $columns;
	}

	function ll_add_category_tags_id( $content, $column_name, $term_id ){
		$content = $term_id;
		return $content;
	}

	function ll_category_tags_custom_column_header( $columns ){
		$columns = array_merge( array_slice( $columns, 0, 2 ),
			array( 'taxonomy_id' => 'Tag ID' ),
			array_slice( $columns, 2 ) );
		return $columns;
	}


	function ll_make_wp_editor_movable() {
		add_meta_box( 'linklibrary_basic_meta_box', __( 'Basic Details', 'link-library' ), array( $this, 'll_link_basic_info' ), 'link_library_links', 'normal', 'high' );

		add_meta_box( 'linklibrary_image_meta_box', __( 'Image', 'link-library' ), array( $this, 'll_link_image_info' ), 'link_library_links', 'normal', 'high' );

		global $_wp_post_type_features;
		if ( isset( $_wp_post_type_features['link_library_links']['editor'] ) && $_wp_post_type_features['link_library_links']['editor'] ) {
			unset( $_wp_post_type_features['link_library_links']['editor'] );
			add_meta_box( 'link_library_fullpage_editor', __( 'Full-Page Content', 'link-library' ), array( $this, 'll_inner_custom_box' ), 'link_library_links', 'normal', 'high' );
		}

		add_meta_box( 'linklibrary_meta_box', __( 'Additional Parameters', 'link-library' ), array( $this, 'll_link_edit_extra' ), 'link_library_links', 'normal', 'high' );
	}

	function ll_inner_custom_box( $post ) {
		$editor_config = array( 'textarea_rows' => 8 );
		wp_editor( $post->post_content, 'content', $editor_config );
	}

	function ll_thumbshots_warning() {
		echo "
        <div id='ll-warning' class='updated fade'><p><strong>" . __( 'Link Library: Missing Thumbshots API Key', 'link-library' ) . "</strong></p> <p>" . __( 'One of your link libraries is configured to use Thumbshots for link thumbails, but you have not entered your Thumbshots.com API Key. Please visit Thumbshots.com to apply for a free or paid account and enter your API in the Link Library admin panel.', 'link-library' ) . " <a href='" . esc_url( add_query_arg( array( 'post_type' => 'link_library_links', 'page' => 'link-library-general-options', 'currenttab' => 'll-general' ), admin_url( 'edit.php' ) ) ) . "'>" . __( 'Jump to Link Library admin', 'link-library' ) . "</a></p></div>";
	}

	function ll_shrinktheweb_warning() {
		echo "
        <div id='ll-warning' class='updated fade'><p><strong>" . __( 'Link Library: Missing Shrink the Web API Key', 'link-library' ) . "</strong></p> <p>" . __( 'One of your link libraries is configured to use Shrink the Web for link thumbails, but you have not entered your shrinktheweb.com API Key. Please visit Shrink the Web.com to apply for a free or paid account and enter your API in the Link Library admin panel.', 'link-library' ) . " <a href='" . esc_url( add_query_arg( array( 'post_type' => 'link_library_links', 'page' => 'link-library-general-options', 'currenttab' => 'll-general' ), admin_url( 'edit.php' ) ) ) . "'>" . __( 'Jump to Link Library admin', 'link-library' ) . "</a></p></div>";
	}

	function ll_missing_categories() {
		echo "
        <div id='ll-warning' class='updated fade'><p><strong>" . __( 'Link Library: No Link Categories on your site', 'link-library' ) . "</strong></p> <p>" . __( 'There are currently no link categories defined in your WordPress site. Link Library will not work correctly without categories. Please create at least one before trying to use Link Library and make sure each link is assigned a category.', 'link-library' ) . "</p></div>";
	}

	function filter_mce_buttons( $buttons ) {

		array_push( $buttons, '|', 'scn_button' );

		return $buttons;
	}

	function filter_mce_external_plugins( $plugins ) {

		$plugins['LinkLibraryPlugin'] = plugins_url( 'tinymce/editor_plugin.js', __FILE__ );

		return $plugins;
	}

	function ajax_action_check_url() {

		$hadError = true;

		$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

		if ( strlen( $url ) > 0 && function_exists( 'get_headers' ) ) {

			$file_headers = @get_headers( $url );
			$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
			$hadError     = false;
		}

		echo '{ "exists": ' . ( $exists ? '1' : '0' ) . ( $hadError ? ', "error" : 1 ' : '' ) . ' }';

		die();
	}

	function dashboard_widget() {
		wp_add_dashboard_widget(
			'link_library_dashboard_widget',
			'Link Library',
			array( $this, 'render_dashboard_widget' )
		);
	}

	function render_dashboard_widget() {
		$linkmoderatecount = 0;
		$pendinglinksargs = array( 'post_type' => 'link_library_links', 'post_status' => 'pending' );
		$pending_links_query = new WP_Query( $pendinglinksargs );

		if ( !empty( $pending_links_query ) ) {
			$linkmoderatecount = $pending_links_query->found_posts;
		}
		wp_reset_postdata();

		echo '<strong>' . $linkmoderatecount . '</strong> ';
		_e( 'Links to moderate', 'link-library' );
	}


	//extend the admin menu
	function on_admin_menu() {
		//add our own option page, you can also add it to different sections or use your own one
		global $pagehookmoderate, $pagehooksettingssets, $pagehookstylesheet, $pagehookreciprocal, $pagehookaccessibe;

		$genoptions = get_option( 'LinkLibraryGeneral' );
		$genoptions = wp_parse_args( $genoptions, ll_reset_gen_settings( 'return' ) );

		$admin_capability = 'manage_options';
		if ( 'Editor' == $genoptions['rolelevel'] ) {
			$admin_capability = 'delete_pages';
		} elseif ( 'Author' == $genoptions['rolelevel'] ) {
			$admin_capability = 'delete_posts';
		} elseif ( 'Contributor' == $genoptions['rolelevel'] ) {
			$admin_capability = 'edit_posts';
		} elseif ( 'Subscriber' == $genoptions['rolelevel'] ) {
			$admin_capability = 'read';
		}

		$edit_capability = 'manage_options';
		if ( 'Editor' == $genoptions['editlevel'] ) {
			$edit_capability = 'delete_pages';
		} elseif ( 'Author' == $genoptions['editlevel'] ) {
			$edit_capability = 'delete_posts';
		} elseif ( 'Contributor' == $genoptions['editlevel'] ) {
			$edit_capability = 'edit_posts';
		} elseif ( 'Subscriber' == $genoptions['editlevel'] ) {
			$edit_capability = 'read';
		}

		if ( !current_user_can( $edit_capability ) ) {
			remove_menu_page( 'link-manager.php' );
		}

		$linkmoderatecount = 0;

		$args = array(
			'numberposts'   => -1,
			'post_type'     => 'link_library_links',
			'post_status'   => array( 'pending' )
		);
		$linkmoderatecount = count( get_posts( $args ) );

		$pagehookgeneraloptions = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'Global Options', 'link-library' ), __( 'Global Options', 'link-library' ), $admin_capability, 'link-library-general-options', array( $this, 'on_show_page' ) );

		$pagehooksettingssets = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'Configurations', 'link-library' ), __( 'Library Configurations', 'link-library' ), $admin_capability, 'link-library-settingssets', array( $this, 'on_show_page' ) );

		$pagehookaccessibe = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Accessibe', 'Accessibe', $admin_capability, 'link-library-accessibe', array( $this, 'on_show_page' ) );

		if ( $linkmoderatecount == 0 ) {
			$pagehookmoderate = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'Moderate', 'link-library' ), __( 'Moderate', 'link-library' ), $admin_capability, 'link-library-moderate', array( $this, 'on_show_page' ) );
		} else {
			$pagehookmoderate = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'Moderate', 'link-library' ), sprintf( __( 'Moderate', 'link-library' ) . ' %s', "<span class='update-plugins count-" . $linkmoderatecount . "'><span class='plugin-count'>" . number_format_i18n( $linkmoderatecount ) . "</span></span>" ), $admin_capability, 'link-library-moderate', array( $this, 'on_show_page' ) );
		}

		$pagehookstylesheet = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'Stylesheet', 'link-library' ), __( 'Stylesheet', 'link-library' ), $admin_capability, 'link-library-stylesheet', array( $this, 'on_show_page' ) );

		$pagehookreciprocal = add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'Link checking tools', 'link-library' ), __( 'Link checking tools', 'link-library' ), $admin_capability, 'link-library-reciprocal', array( $this, 'on_show_page' ) );

		add_submenu_page( LINK_LIBRARY_ADMIN_PAGE_NAME, 'Link Library - ' . __( 'FAQ', 'link-library' ), __( 'FAQ', 'link-library' ), $admin_capability, 'link-library-faq', array( $this, 'on_show_page' ) );

		//register  callback gets call prior your own page gets rendered
		add_action( 'load-' . $pagehookgeneraloptions, array( $this, 'on_load_page' ) );
		add_action( 'load-' . $pagehooksettingssets, array( $this, 'on_load_page' ) );
		add_action( 'load-' . $pagehookmoderate, array( $this, 'on_load_page' ) );
		add_action( 'load-' . $pagehookstylesheet, array( $this, 'on_load_page' ) );
		add_action( 'load-' . $pagehookreciprocal, array( $this, 'on_load_page' ) );
		add_action( 'load-' . $pagehookaccessibe, array( $this, 'on_load_page' ) );
	}

	//will be executed if wordpress core detects this page has to be rendered
	function on_load_page() {

		global $pagehookmoderate, $pagehooksettingssets, $pagehookstylesheet, $pagehookreciprocal;

		//ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
		wp_enqueue_script( 'tiptip', plugins_url( '/tiptip/jquery.tipTip.minified.js', __FILE__ ), "jQuery", "1.0rc3" );
		wp_enqueue_style( 'tiptipstyle', plugins_url( '/tiptip/tipTip.css', __FILE__ ) );
		add_thickbox();
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );

		$genoptions = get_option( 'LinkLibraryGeneral' );

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		add_meta_box( 'linklibrary_moderation_meta_box', __( 'Links awaiting moderation', 'link-library' ), array( $this, 'moderate_meta_box' ), $pagehookmoderate, 'normal', 'high' );
		add_meta_box( 'linklibrary_stylesheet_meta_box', __( 'Editor', 'link-library' ), array( $this, 'stylesheet_meta_box' ), $pagehookstylesheet, 'normal', 'high' );
		add_meta_box( 'linklibrary_reciprocal_meta_box', __( 'Link checking tools', 'link-library' ), array( $this, 'reciprocal_meta_box' ), $pagehookreciprocal, 'normal', 'high' );
		add_meta_box( 'linklibrary_reciprocal_save_meta_box', __( 'Save', 'link-library' ), array( $this, 'general_save_meta_box' ), $pagehookreciprocal, 'normal', 'high' );
	}

	//executed to show the plugins complete admin page
	function on_show_page() {
		//we need the global screen column value to beable to have a sidebar in WordPress 2.8
		global $screen_layout_columns;

		$settings = ( isset( $_GET['settings'] ) ? $_GET['settings'] : 1 );

		if ( isset( $_GET['settingscopy'] ) ) {
			$destination = $_GET['settingscopy'];
			$source      = $_GET['source'];

			$sourcesettingsname = 'LinkLibraryPP' . $source;
			$sourceoptions      = get_option( $sourcesettingsname );

			$destinationsettingsname = 'LinkLibraryPP' . $destination;
			update_option( $destinationsettingsname, $sourceoptions );

			$settings = $destination;
		}

		if ( isset( $_GET['deletesettings'] ) ) {
			check_admin_referer( 'link-library-delete' );

			$settings           = $_GET['deletesettings'];
			$deletesettingsname = 'LinkLibraryPP' . $settings;
			$options            = delete_option( $deletesettingsname );
			$settings           = 1;
		}

		// Retrieve general options
		$genoptions = get_option( 'LinkLibraryGeneral' );
		$genoptions = wp_parse_args( $genoptions, ll_reset_gen_settings( 'return' ) );

		// If general options don't exist, create them
		if ( $genoptions == false ) {
			$genoptions = ll_reset_gen_settings( 'return_and_set' );
		}

		$settingsname = 'LinkLibraryPP' . $settings;
		$options      = get_option( $settingsname );
		$options = wp_parse_args( $options, ll_reset_options( 1, 'list', 'return' ) );

		if ( empty( $options ) ) {
			$options = ll_reset_options( $settings, 'list', 'return_and_set' );
		}

		if ( isset( $_GET['genthumbs'] ) || isset( $_GET['genfavicons'] ) || isset( $_GET['genthumbsingle'] ) || isset( $_GET['genfaviconsingle'] ) ) {
			if ( isset( $_GET['genthumbs'] ) || isset( $_GET['genthumbsingle'] ) ) {
				$filepath = "link-library-images";
			} elseif ( isset( $_GET['genfavicons'] ) || isset( $_GET['genfaviconsingle'] ) ) {
				$filepath = "link-library-favicons";
			}

			$uploads = wp_upload_dir();

			if ( !file_exists( $uploads['basedir'] ) ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Please create a folder called uploads under your Wordpress /wp-content/ directory with write permissions to use this functionality.', 'link-library' ) . "</strong></p></div>";
			} elseif ( !is_writable( $uploads['basedir'] ) ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Please make sure that the /wp-content/uploads/ directory has write permissions to use this functionality.', 'link-library' ) . "</strong></p></div>";
			} else {
				if ( !file_exists( $uploads['basedir'] . '/' . $filepath ) ) {
					mkdir( $uploads['basedir'] . '/' . $filepath );
				}

				if ( isset( $_GET['genthumbs'] ) || isset( $_GET['genthumbsingle'] ) ) {
					$genmode = 'thumb';
				} elseif ( isset( $_GET['genfavicons'] ) || isset( $_GET['genfaviconsingle'] ) ) {
					$genmode = 'favicon';
				}

				$link_query_args = array( 'post_type' => 'link_library_links', 'posts_per_page' => -1, 'post_status' => array( 'publish', 'pending', 'draft', 'future', 'private' ) );

				if ( $options['categorylist_cpt'] != "" && !isset( $_GET['genthumbsingle'] ) && !isset( $_GET['genfaviconsingle'] ) ) {
					$link_query_args['tax_query'] = array(
														array( 'taxonomy' => 'link_library_category',
															    'field' => 'term-id',
															    'terms' => $options['categorylist_cpt'],
																'operator' => 'IN' )
														);
				} else if ( isset( $_GET['genthumbsingle'] ) || isset( $_GET['genfaviconsingle'] ) ) {
					$link_query_args['p'] = intval( $_GET['linkid'] );
				}

				$the_link_query = new WP_Query( $link_query_args );

				if ( $the_link_query->have_posts() ) {
					$filescreated = 0;
					$totallinks   = $the_link_query->found_posts;

					while ( $the_link_query->have_posts() ) {
						$the_link_query->the_post();

						$link_url = get_post_meta( get_the_ID(), 'link_url', true );
						$link_image = get_post_meta( get_the_ID(), 'link_image', true );

						if ( !$options['uselocalimagesoverthumbshots'] || ( $options['uselocalimagesoverthumbshots'] && empty( $link_image ) ) ) {
							if ( 'robothumb' == $genoptions['thumbnailgenerator'] || 'thumbshots' == $genoptions['thumbnailgenerator'] ) {
								$this->ll_get_link_image( $link_url, get_the_title(), $genmode, get_the_ID(), $genoptions['thumbshotscid'], $filepath, $genoptions['imagefilepath'], $genoptions['thumbnailsize'], $genoptions['thumbnailgenerator'] );
							} elseif ( 'pagepeeker' == $genoptions['thumbnailgenerator'] ) {
								$this->ll_get_link_image( $link_url, get_the_title(), $genmode, get_the_ID(), $genoptions['pagepeekerid'], $filepath, $genoptions['imagefilepath'], $genoptions['pagepeekersize'], $genoptions['thumbnailgenerator'] );
							} elseif ( 'shrinktheweb' == $genoptions['thumbnailgenerator'] ) {
								$this->ll_get_link_image( $link_url, get_the_title(), $genmode, get_the_ID(), $genoptions['shrinkthewebaccesskey'], $filepath, $genoptions['imagefilepath'], $genoptions['stwthumbnailsize'], $genoptions['thumbnailgenerator'] );
							}
						}
						$linkname = get_the_title();
					}

					wp_reset_postdata();

					if ( isset( $_GET['genthumbs'] ) ) {
						echo "<div id='message' class='updated fade'><p><strong>" . __( 'Thumbnails successfully generated!', 'link-library' ) . "</strong></p></div>";
					} elseif ( isset( $_GET['genfavicons'] ) ) {
						echo "<div id='message' class='updated fade'><p><strong>" . __( 'Favicons successfully generated!', 'link-library' ) . "</strong></p></div>";
					} elseif ( isset( $_GET['genthumbsingle'] ) ) {
						echo "<div id='message' class='updated fade'><p><strong>" . __( 'Thumbnail successfully generated for', 'link-library' ) . " " . $linkname . ".</strong></p></div>";
					} elseif ( isset( $_GET['genfaviconsingle'] ) ) {
						echo "<div id='message' class='updated fade'><p><strong>" . __( 'Favicon successfully generated for', 'link-library' ) . " " . $linkname . ".</strong></p></div>";
					}
				}
			}
		} elseif ( isset( $_GET['deleteallthumbs'] ) ) {
			$uploads = wp_upload_dir();

			if ( file_exists( $uploads['basedir'] ) ) {
				$files = glob( $uploads['basedir'] . "/link-library-images/*" );
				foreach( $files as $file ) { // iterate files
					if( is_file( $file ) ) {
						unlink($file); // delete file
					}
				}
			}
		} elseif ( isset( $_GET['deleteallicons'] ) ) {
			$uploads = wp_upload_dir();

			if ( file_exists( $uploads['basedir'] ) ) {
				$files = glob( $uploads['basedir'] . "/link-library-favicons/*" );
				foreach( $files as $file ) { // iterate files
					if( is_file( $file ) ) {
						unlink($file); // delete file
					}
				}
			}
		}

		// Check for current page to set some page=specific variables
		if ( $_GET['page'] == 'link-library-general-options' ) {
			if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'General Settings Saved', 'link-library' ) . ".</strong></p></div>";
			} else if ( isset( $_GET['message'] ) && $_GET['message'] == '3' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Link Library plugin directory needs to be writable to perform this action', 'link-library' ) . "</strong></p></div>";
			} else if ( isset( $_GET['message'] ) && $_GET['message'] == '4' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'You must set the Google reCAPTCHA Site and Secret Keys to be able to set the captcha generator to Google reCAPTCHA.', 'link-library' ) . "</strong></p></div>";
			} else if ( isset( $_GET['message'] ) && $_GET['message'] == '9' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . $_GET['importrowscount'] . " " . __( 'row(s) found', 'link-library' ) . ". " . ( isset( $_GET['successimportcount'] ) ? intval( $_GET['successimportcount'] ) : '0' ) . " " . __( 'link(s) imported', 'link-library' ) . ", " . ( isset( $_GET['successupdatecount'] ) ? intval( $_GET['successupdatecount'] ): '0' ) . " " . __( 'link(s) updated', 'link-library' ) . ".</strong></p></div>";
			}

			$formvalue = 'save_link_library_general';
			$pagetitle = '';
		} elseif ( $_GET['page'] == 'link-library-settingssets' ) {
			$formvalue = 'save_link_library_settingssets';

			if ( isset( $_GET['reset'] ) ) {
				$options = ll_reset_options( $settings, 'list', 'return_and_set' );
			}

			if ( isset( $_GET['newlayout'] ) ) {
				$options = ll_modify_layout( $settings, intval( $_GET['newlayout']) );
			}

			$pagetitle = __( 'Library', 'link-library' ) . ' #' . $settings . " - " . stripslashes( $options['settingssetname'] );

			if ( isset( $_GET['messages'] ) ) {
				$categoryid  = '';
				$messagelist = explode( ",", $_GET['messages'] );

				foreach ( $messagelist as $message ) {
					switch ( $message ) {

						case '1':
							echo "<div id='message' class='updated fade'><p><strong>" . __( 'Library #', 'link-library' ) . $settings . " " . __( 'Updated', 'link-library' ) . "!</strong></p></div>";
							break;

						case '2':
							echo '<br /><br />' . __( 'Included Category ID', 'link-library' ) . ' ' . $categoryid . ' ' . __( 'is invalid. Please check the ID in the Link Category editor.', 'link-library' );
							break;

						case '3':
							echo '<br /><br />' . __( 'Excluded Category ID', 'link-library' ) . ' ' . $categoryid . ' ' . __( 'is invalid. Please check the ID in the Link Category editor.', 'link-library' );
							break;

						case '4':
							echo "<div id='message' class='updated fade'><p><strong>" . __( 'Invalid column count for link on row. Compare against template.', 'link-library' ) . "</strong></p></div>";
							break;

						case '6':
							echo "<div id='message' class='updated fade'><p><strong>" . __( 'Link Library plugin directory needs to be writable to perform this action', 'link-library' ) . ".</strong></p></div>";
							break;

						case '7':
							echo "<div id='message' class='updated fade'><p><strong>" . __( 'Library Settings imported successfully', 'link-library' ) . ".</strong></p></div>";
							break;

						case '8':
							echo "<div id='message' class='updated fade'><p><strong>" . __( 'Library Settings Upload Failed', 'link-library' ) . "</strong></p></div>";
							break;

						case '9':
							echo "<div id='message' class='updated fade'><p><strong>" . ( isset( $_GET['successimportcount'] ) ? intval( $_GET['successimportcount'] ) : '0' ) . " " . __( 'link(s) imported', 'link-library' ) . ", " . ( isset( $_GET['successupdatecount'] ) ? intval( $_GET['successupdatecount'] ) : '0' ) . " " . __( 'link(s) updated', 'link-library' ) . ".</strong></p></div>";
							break;

						case '10':
							echo "<div id='message' class='updated fade'><p><strong>" . __( 'Links are missing categories', 'link-library' ) . "</strong></p></div>";
							break;

					}

				}

			}
		} elseif ( $_GET['page'] == 'link-library-moderate' ) {
			$formvalue = 'save_link_library_moderate';
			$pagetitle = '';

			if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Link(s) Approved', 'link-library' ) . "</strong></p></div>";
			} elseif ( isset( $_GET['message'] ) && $_GET['message'] == '2' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Link(s) Deleted', 'link-library' ) . "</strong></p></div>";
			}

			?>

		<?php
		} elseif ( $_GET['page'] == 'link-library-stylesheet' ) {
			$formvalue = 'save_link_library_stylesheet';
			$pagetitle = '';

			if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Stylesheet updated', 'link-library' ) . ".</strong></p></div>";
			} elseif ( isset( $_GET['message'] ) && $_GET['message'] == '2' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Stylesheet reset to original state', 'link-library' ) . ".</strong></p></div>";
			}
		} elseif ( $_GET['page'] == 'link-library-reciprocal' ) {
			$formvalue = 'save_link_library_reciprocal';
			$pagetitle = '';

			if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) {
				echo "<div id='message' class='updated fade'><p><strong>" . __( 'Settings updated', 'link-library' ) . ".</strong></p></div>";
			} elseif ( isset( $_GET['message'] ) && ( $_GET['message'] == '2' || $_GET['message'] == '3' ) ) { ?>
				<div id='message' class='updated fade'><p>
				<strong>
				<?php
					if ( $_GET['message'] == '2' ) {
						_e( 'Reciprocal Link Checker Report', 'link-library' );
					} else {
						_e( 'Broken Link Checker Report', 'link-library' );
					}
				?></strong><br />
				<span class="loadingicon"><img src="<?php echo plugins_url( 'icons/Ajax-loader.gif', __FILE__ ); ?> " /></span><span class="processinglinks">Processing Link <span class="currentlinknumber">0</span> / <span class="totallinknumber">
				<?php
				$post_count = wp_count_posts( 'link_library_links' );
				$total_post_count = $post_count->publish + $post_count->future + $post_count->draft + $post_count->pending + $post_count->private;
				echo $total_post_count;
				?>
				</span></span>
				<br />
				<div class="nextcheckitem"></div>
				<script type="text/javascript">
					var currentlinkindex = 1;
					var linkcheckflag = 0;
					var maxlinks = <?php echo $total_post_count; ?>;

					linkloop = setInterval( function(){ testlink(); }, 3000 );

					function testlink() {
						if ( linkcheckflag == 0 ) {
							linkcheckflag = 1;

							jQuery('.currentlinknumber').html( currentlinkindex );

							jQuery.ajax({
								type   : 'POST',
								url    : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
								data   : {
									action      : 'link_library_recipbrokencheck',
									_ajax_nonce : '<?php echo wp_create_nonce( 'link_library_recipbrokencheck' ); ?>',
									mode        : '<?php if ( $_GET['message'] == '2' ) { echo 'reciprocal'; } elseif ( $_GET['message'] == 3 ) { echo 'broken'; } ?>',
									index		: currentlinkindex,
									RecipCheckAddress: jQuery('#recipcheckaddress').val(),
									recipcheckdelete403 : jQuery('#recipcheckdelete403').is(':checked')
								},
								success: function (data) {
									if (data != '' ) {
										if ( ( data != 'There are no links with reciprocal links associated with them.<br />' && data != 'There are no links to check.<br />' ) || currentlinkindex == 1 ) {
											jQuery('.nextcheckitem').replaceWith(data);
										}

										if ( data != 'There are no links with reciprocal links associated with them.<br />' && data != 'There are no links to check.<br />' ) {
											currentlinkindex++;
											if ( currentlinkindex > maxlinks ) {
												clearInterval( linkloop );
												jQuery( '.loadingicon' ).html( '' );
												jQuery( '.processinglinks' ).html( 'All links processed' );
											}
											linkcheckflag = 0;
										}
									}
								}
							});
						}
					}
				</script>
				</p></div>
			<?php } elseif ( isset( $_GET['message'] ) && $_GET['message'] == '4' ) {
				echo "<div id='message' class='updated fade'><p>";
				$this->link_library_duplicate_link_checker( $this );
				echo "</p></div>";
			}
		} elseif ( $_GET['page'] == 'link-library-accessibe' ) {
			$formvalue = 'save_link_library_accessibe';
		}

		$data               = array();
		$data['settings']   = $settings;
		$data['options']    = isset( $options ) ? $options : '';
		$data['genoptions'] = $genoptions;
		global $pagehookmoderate, $pagehookstylesheet, $pagehooksettingssets, $pagehookreciprocal;
		?>
		<div class="ll-content">
			<div class="ll-frame">
				<div class="header">
					<nav role="navigation" class="header-nav drawer-nav nav-horizontal">

						<ul class="main-nav">
							<li class="link-library-logo">
								<img src="<?php echo plugins_url( 'icons/folder-beige-internet-icon32.png', __FILE__ ); ?>" /><span>Link Library</span>
							</li>
							<li class="link-library-page">
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'link-library-general-options' ), admin_url( 'admin.php' ) ) ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library' ) {
									echo 'class="current"';
								} ?>><?php _e( 'General Options', 'link-library' ); ?></a>
							</li>
							<li class="link-library-page">
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'link-library-settingssets' ), admin_url( 'admin.php' ) ) ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-settingssets' ) {
									echo 'class="current"';
								} ?>><?php _e( 'Library Settings', 'link-library' ); ?></a>
							</li>
							<li class="link-library-page">
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'link-library-moderate' ), admin_url( 'admin.php' ) ) ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-moderate' ) {
									echo 'class="current"';
								} ?>><?php _e( 'Moderate', 'link-library' ); ?></a>
							</li>
							<li class="link-library-page">
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'link-library-stylesheet' ), admin_url( 'admin.php' ) ) ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-stylesheet' ) {
									echo 'class="current"';
								} ?>><?php _e( 'Stylesheet', 'link-library' ); ?></a>
							</li>
							<li class="link-library-page">
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'link-library-reciprocal' ), admin_url( 'admin.php' ) ) ); ?>" <?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'link-library-reciprocal' ) {
									echo 'class="current"';
								} ?>><?php _e( 'Reciprocal Check', 'link-library' ); ?></a>
							</li>
							<li class="link-library-page">
								<a target="LinkLibraryFAQ" href="https://ylefebvre.home.blog/wordpress-plugins/link-library/link-library-faq/"><?php _e( 'FAQ', 'link-library' ); ?></a>
							</li>
							<?php if ( isset( $genoptions['hidedonation'] ) && !$genoptions['hidedonation'] ) { ?>
								<li class="link-library-page">
									<a href="https://ylefebvre.home.blog/wordpress-plugins/link-library/"><img src="<?php echo plugins_url( '/icons/btn_donate_LG.gif', __FILE__ ); ?>" /></a>
								</li>
							<?php } ?>

						</ul>

					</nav>
				</div>
				<!-- .header -->
			</div>
		</div>
		<div id="link-library-general" class="wrap">
			<div class='icon32'>
				<img src="<?php echo plugins_url( 'icons/folder-beige-internet-icon32.png', __FILE__ ); ?>" />
			</div>
			<div><h2><?php if ( !empty( $pagetitle ) ) {
						echo $pagetitle;
					} ?>
			</h2>
			</div>
			<div>
				<form name='linklibrary' enctype="multipart/form-data" action="admin-post.php" method="post">
					<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />

					<?php wp_nonce_field( 'link-library' ); ?>
					<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
					<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
					<input type="hidden" name="action" value="<?php echo $formvalue; ?>" />

					<style type="text/css">
						#sortable {
							list-style-type: none;
							margin: 0;
							padding: 0;
							white-space: nowrap;
							list-style-type: none;
						}

						#sortable li {
							list-style: none;
							margin: 0 3px 3px 3px;
							padding: 7px 7px 7px 7px;
							border: #CCCCCC solid 1px;
							color: #fff;
							display: inline;
							width: 100px;
							height: 30px;
							cursor: move
						}

						#sortable li span {
							position: absolute;
							margin-left: -1.3em;
						}
					</style>

					<div id="poststuff" class="metabox-holder">
						<div id="post-body" class="has-sidebar">
							<div id="post-body-content" class="has-sidebar-content">
								<?php
								if ( $_GET['page'] == 'link-library-general-options' ) {
									$this->display_menu( 'general', $genoptions );
									$this->general_meta_box( $data );
									$this->general_singleitemlayout_meta_box( $data );
									$this->general_image_meta_box( $data );
									$this->general_meta_bookmarklet_box( $data );
									$this->general_moderation_meta_box( $data );
									if ( isset( $genoptions['hidedonation'] ) && !$genoptions['hidedonation'] ) {
										$this->general_hide_donation_meta_box( $data );
									}
									$this->general_importexport_meta_box( $data );

									$this->general_save_meta_box();

								} elseif ( $_GET['page'] == 'link-library-settingssets' ) {
									if ( isset( $genoptions['hidedonation'] ) && !$genoptions['hidedonation'] ) {
										$this->display_accessibe_ad();
									}
									$this->settingssets_selection_meta_box( $data );
									$this->display_menu( 'settingsset' );
									$this->settingssets_usage_meta_box( $data );
									$this->settingssets_presets_meta_box( $data );
									$this->settingssets_common_meta_box( $data );
									$this->settingssets_categories_meta_box( $data );
									$this->settingssets_linkelement_meta_box( $data );
									$this->settingssets_subfieldtable_meta_box( $data );
									$this->settingssets_linkpopup_meta_box( $data );
									$this->settingssets_rssconfig_meta_box( $data );
									$this->settingssets_thumbnails_meta_box( $data );
									$this->settingssets_rssgen_meta_box( $data );
									$this->settingssets_search_meta_box( $data );
									$this->settingssets_linksubmission_meta_box( $data );
									$this->settingssets_importexport_meta_box( $data );

									$this->general_save_meta_box( $data );

									//do_meta_boxes( $pagehooksettingssets, 'normal', $data );
								} elseif ( $_GET['page'] == 'link-library-moderate' ) {
									do_meta_boxes( $pagehookmoderate, 'normal', $data );
								} elseif ( $_GET['page'] == 'link-library-stylesheet' ) {
									do_meta_boxes( $pagehookstylesheet, 'normal', $data );
								} elseif ( $_GET['page'] == 'link-library-reciprocal' ) {
									do_meta_boxes( $pagehookreciprocal, 'normal', $data );
								} elseif ( $_GET['page'] == 'link-library-accessibe' ) {
									$this->display_accessibe_page( $data );
								}
								?>
							</div>
						</div>
						<br class="clear" />
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready(function ($) {
				// close postboxes that should be closed
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				// postboxes setup
				postboxes.add_postbox_toggles('<?php
				if ($_GET['page'] == 'link-library-settingssets')
					{echo $pagehooksettingssets;}
				elseif ($_GET['page'] == 'link-library-moderate')
					{echo $pagehookmoderate;}
				elseif ($_GET['page'] == 'link-library-stylesheet')
					{echo $pagehookstylesheet;}
				elseif ($_GET['page'] == 'link-library-reciprocal')
					{echo $pagehookreciprocal;}
				elseif ($_GET['page'] == 'link-library-accessibe')
					{echo $pagehookstylesheet;}
				?>');
			});
			//]]>

			Array.prototype.clean = function(deleteValue) {
				for (var i = 0; i < this.length; i++) {
					if (this[i] == deleteValue) {
						this.splice(i, 1);
						i--;
					}
				}
				return this;
			};

			// Create the tooltips only on document load
			jQuery(document).ready(function () {
				jQuery('.lltooltip').each(function () {
						jQuery(this).tipTip();
					}
				);

				jQuery("#sortable").sortable({
					opacity: 0.6, cursor: 'move', update: function () {
						var order = jQuery("#sortable").sortable('toArray');
						order.clean("");
						stringorder = order.join(',');
						document.getElementById('dragndroporder').value = stringorder;
					}
				});

			});

		</script>

	<?php
	}

	function display_menu( $menu_name = 'settingsset', $genoptions = '' ) {
		if ( $menu_name == 'general' ) {
			$tabitems = array ( 'll-general' => __( 'General', 'link-library' ),
								'll-singleitem' => __( 'Single Item Layout', 'link-library' ),
			                    'll-images' => __( 'Images', 'link-library' ),
			                    'll-bookmarklet' => __( 'Bookmarklet', 'link-library' ),
			                    'll-moderation' => __( 'Moderation', 'link-library' ),
			                    'll-hidedonation' => __( 'Hide Donation', 'link-library' ),
			                    'll-importexport' => __( 'Import/Export Links', 'link-library' ),
			);

			if ( isset( $genoptions['ll-hidedonation'] ) && $genoptions['ll-hidedonation'] ) {
				unset ( $tabitems['hidedonation'] );
			}
		} elseif ( $menu_name == 'settingsset' ) {
			$tabitems = array ( 'll-usage' => __( 'Usage', 'link-library' ),
			                    'll-presets' => __( 'Presets', 'link-library' ),
			                    'll-common' => __( 'Common', 'link-library' ),
			                    'll-categories' => __( 'Categories', 'link-library' ),
			                    'll-links' => __( 'Links', 'link-library' ),
			                    'll-advanced' => __( 'Advanced', 'link-library' ),
			                    'll-popup' => __( 'Pop-Ups', 'link-library' ),
			                    'll-rssdisplay' => __( 'RSS Display', 'link-library' ),
			                    'll-thumbnails' => __( 'Thumbnails', 'link-library' ),
			                    'll-rssfeed' => __( 'RSS Feed', 'link-library' ),
			                    'll-searchfield' => __( 'Search', 'link-library' ),
			                    'll-userform' => __( 'User Submission', 'link-library' ),
			                    'll-importexport' => __( 'Import/Export Settings', 'link-library' ),
			);
		}

		$array_keys = array_keys( $tabitems );

		if ( isset( $_GET['currenttab'] ) ) {
			$currenttab = array_search( $_GET['currenttab'], $array_keys );
		} else {
			$currenttab = 0;
		}

		?>
		<div>
			<input type="hidden" name="currenttab" class="current-tab" value="<?php echo $array_keys[$currenttab]; ?>">
		<ul id="settings-sections" class="subsubsub hide-if-no-js">
			<?php
				$index = 0;
				foreach ( $tabitems as $tabkey => $tabitem ) { ?>
				<li><a href="#<?php echo $tabkey; ?>" class="ll-tab <?php echo $tabkey; ?> ll-general <?php if ( $currenttab == $index ) echo 'll-current'; ?>"><?php echo $tabitem; ?></a> | </li>
			<?php
				$index++;
				} ?>
		</ul>
		</div>
		<br /><br />

		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.content-section:not(:eq(<?php echo $currenttab; ?>))').hide();
				jQuery('.subsubsub a.ll-tab').click(function(e) {

					// Move the "current" CSS class.
					jQuery(this).parents('.subsubsub').find('.current').removeClass('current');
					jQuery(this).addClass('current');

					// If the link is a tab, show only the specified tab.
					var toShow = jQuery(this).attr('href');

					// Remove the first occurance of # from the selected string (will be added manually below).
					toShow = toShow.replace('#', '');

					jQuery('.content-section:not(#' + toShow + ')').hide();
					jQuery('.content-section#' + toShow).show();

					jQuery('.current-tab').val(toShow);

					return false;
				});
			});
		</script>
	<?php }

	//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_general() {
		//user permission check
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Not allowed', 'link-library' ) );
		}
		//cross check the given referer
		check_admin_referer( 'link-library' );

		$message          = 1;
		$row              = 0;
		$successfulimport = 0;
		$successfulupdate = 0;

		if ( isset( $_POST['importlinks'] ) ) {
			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );
			wp_suspend_cache_addition( true );
			define( 'WP_IMPORTING', true );
			set_time_limit( 1800 );

			$handle = fopen( $_FILES['linksfile']['tmp_name'], "r" );

			if ( $handle ) {
				$skiprow = 1;
				$import_columns = array();

				while ( ( $data = fgetcsv( $handle, 5000, "," ) ) !== false ) {
					$row += 1;
					if ( $skiprow == 1 && $row >= 2 ) {
						$skiprow = 0;
					}

					if ( 1 == $row ) {
						foreach ( $data as $index => $column_name ) {
							$import_columns[$column_name] = $index;
						}
					} else {
						$existing_link_post_id = '';
						$matched_link_cats = array();
						$matched_link_tags = array();

						if ( ( isset( $import_columns['Category Slugs'] ) && !empty( $data[$import_columns['Category Slugs']] ) ) ) {
							$new_link_cats_slugs_array = array();
							if ( isset( $import_columns['Category Slugs'] ) ) {
								$new_link_cats_slugs_array = explode( ',', $data[$import_columns['Category Slugs']] );
							}

							if ( ( isset( $import_columns['Category Names'] ) && !empty( $data[$import_columns['Category Names']] ) ) || ( isset( $import_columns['cat_name'] ) && !empty( $data[$import_columns['cat_name']] ) ) ) {
								if ( isset( $import_columns['Category Names'] ) ) {
									$new_link_cats_array = explode( ',', $data[$import_columns['Category Names']] );
								} elseif( isset( $import_columns['cat_name'] ) ) {
									$new_link_cats_array = explode( ',', $data[$import_columns['cat_name']] );
								}
							}

							foreach ( $new_link_cats_slugs_array as $index => $new_link_cat_slug ) {
								$cat_matched_term = get_term_by( 'slug', $new_link_cat_slug, 'link_library_category' );

								if ( false !== $cat_matched_term ) {
									$matched_link_cats[] = $cat_matched_term->term_id;
								} else {
									$new_link_cat = '';
									if ( !empty( $new_link_cats_array ) && isset( $new_link_cats_array[$index] ) ) {
										$new_link_cat = $new_link_cats_array[$index];
									} else {
										$new_link_cat = $new_link_cat_slug;
									}

									$new_cat_term_data   = wp_insert_term( $new_link_cat, 'link_library_category', array( 'slug' => $new_link_cat_slug ) );
									if ( is_wp_error( $new_cat_term_data ) ) {
										print_r( 'Failed creating category ' . $new_link_cat );
									} else {
										$matched_link_cats[] = $new_cat_term_data['term_id'];
									}
								}
							}
						}

						if ( ( isset( $import_columns['Tag Slugs'] ) && !empty( $data[$import_columns['Tag Slugs']] ) ) ) {
							$new_link_tags_slugs_array = array();
							if ( isset( $import_columns['Tag Slugs'] ) ) {
								$new_link_tags_slugs_array = explode( ',', $data[$import_columns['Tag Slugs']] );
							}

							if ( ( isset( $import_columns['Tag Names'] ) && !empty( $data[$import_columns['Tag Names']] ) ) ) {
								if ( isset( $import_columns['Tag Names'] ) ) {
									$new_link_tags_array = explode( ',', $data[$import_columns['Tag Names']] );
								}
							}

							foreach ( $new_link_tags_slugs_array as $index => $new_link_tag_slug ) {
								$tag_matched_term = get_term_by( 'slug', $new_link_tag_slug, 'link_library_tags' );

								if ( false !== $tag_matched_term ) {
									$matched_link_tags[] = $tag_matched_term->term_id;
								} else {
									$new_link_tag = '';
									if ( !empty( $new_link_tags_array ) && isset( $new_link_tags_array[$index] ) ) {
										$new_link_tag = $new_link_tags_array[$index];
									} else {
										$new_link_tag = $new_link_tag_slug;
									}

									$new_tag_term_data   = wp_insert_term( $new_link_tag, 'link_library_tags', array( 'slug' => $new_link_tag_slug ) );
									if ( is_wp_error( $new_tag_term_data ) ) {
										print_r( 'Failed creating tag ' . $new_link_tag );
									} else {
										$matched_link_tags[] = $new_tag_term_data['term_id'];
									}
								}
							}
						}

						$link_url = '';
						$url_labels = array( 'Address', 'link_url' );
						foreach( $url_labels as $url_label ) {
							if ( isset( $import_columns[$url_label] ) ) {
								if ( !empty( $data[$import_columns[$url_label]] ) ) {
									$link_url = esc_url( $data[$import_columns[$url_label]] );
								}
							}
						}

						if ( isset( $_POST['updatesameurl'] ) ) {
							$find_post_args = array( 'post_type' => 'link_library_links',
													 'meta_key' => 'link_url',
													 'meta_value' => $link_url,
													 'numberposts' => 1 );

							$posts_same_url_array = get_posts( $find_post_args );

							if ( !empty( $posts_same_url_array ) ) {
								$existing_link_post_id = $posts_same_url_array[0]->ID;
							}
						}

						$post_status = 'publish';
						$post_status_import_value = '';

						$visible_labels = array( 'Status', 'Visible', 'link_visible' );
						foreach( $visible_labels as $visible_label ) {
							if ( isset( $import_columns[$visible_label] ) ) {
								$post_status_import_value = $data[$import_columns[$visible_label]];
							}
						}

						if ( in_array( $post_status_import_value, array( 'publish', 'draft', 'private' ) ) ) {
							$post_status = $post_status_import_value;
						} elseif ( 'N' == $post_status_import_value ) {
							$post_status = 'private';
						}

						$post_title = '';
						$title_labels = array( 'Link Name', 'link_name', 'Name' );
						foreach( $title_labels as $title_label ) {
							if ( isset( $import_columns[$title_label] ) ) {
								if ( ! empty( $data[ $import_columns[$title_label] ] ) ) {
									$post_title = sanitize_text_field( $data[ $import_columns[$title_label] ] );
								}
							}
						}

						$new_link_data = array(
							'post_type' => 'link_library_links',
							'post_content' => '',
							'post_title' => $post_title,
							'tax_input' => array( 'link_library_category' => $matched_link_cats, 'link_library_tags' => $matched_link_tags ),
							'post_status' => $post_status
						);

						if ( !empty( $existing_link_post_id ) ) {
							$new_link_data['ID'] = $existing_link_post_id;
							$new_link_ID = wp_insert_post( $new_link_data );
							$successfulupdate++;
						} else {
							$new_link_ID = wp_insert_post( $new_link_data );
							$successfulimport++;
						}

						update_post_meta( $new_link_ID, 'link_url', $link_url );

						$link_image = '';
						$image_labels = array( 'Image Address', 'link_image' );
						foreach( $image_labels as $image_label ) {
							if ( isset( $import_columns[$image_label] ) ) {
								$link_image = esc_url( $data[$import_columns[$image_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_image', $link_image );

						if ( empty( $link_image ) ) {
							delete_post_thumbnail( $new_link_ID );
						} else {
							$wpFileType = wp_check_filetype( $link_image, null);

							$attachment = array(
								'post_mime_type' => $wpFileType['type'],  // file type
								'post_title' => sanitize_file_name( $link_image ),  // sanitize and use image name as file name
								'post_content' => '',  // could use the image description here as the content
								'post_status' => 'inherit'
							);

							// insert and return attachment id
							$attachmentId = wp_insert_attachment( $attachment, $link_image, $new_link_ID );
							$attachmentData = wp_generate_attachment_metadata( $attachmentId, $link_image );
							wp_update_attachment_metadata( $attachmentId, $attachmentData );
							set_post_thumbnail( $new_link_ID, $attachmentId );
						}

						$link_target = '';
						$target_labels = array( 'Link Target', 'link_target' );
						foreach( $target_labels as $target_label ) {
							if ( isset( $import_columns[$target_label] ) ) {
								$link_target = sanitize_text_field( $data[$import_columns[$target_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_target', $link_target );

						$link_description = '';
						$description_labels = array( 'Description', 'link_description' );
						foreach( $description_labels as $description_label ) {
							if ( isset( $import_columns[$description_label] ) ) {
								$link_description = sanitize_text_field( $data[$import_columns[$description_label]] );
								$link_description = str_replace( '(LinkLibrary:AwaitingModeration:RemoveTextToApprove)', '', $link_description );
							}
						}
						update_post_meta( $new_link_ID, 'link_description', $link_description );

						$link_rating = '';
						$rating_labels = array( 'Description', 'link_description' );
						foreach( $rating_labels as $rating_label ) {
							if ( isset( $import_columns[$rating_label] ) ) {
								$newrating = intval( $data[$import_columns[$rating_label]] );
								if ( $newrating < 0 ) {
									$newrating = 0;
								}
								$link_rating = $newrating;
							}
						}
						update_post_meta( $new_link_ID, 'link_rating', $link_rating );

						$link_updated = current_time( 'timestamp' );
						$updated_labels = array( 'Updated Date - Empty for none', 'link_updated' );
						foreach( $updated_labels as $updated_label ) {
							if ( isset( $import_columns[$updated_label] ) ) {
								if ( !empty( $import_columns[$updated_label] ) ) {
									$link_updated = strtotime( $data[ $import_columns[$updated_label] ] );
								}
							}
						}
						update_post_meta( $new_link_ID, 'link_updated', $link_updated );

						$link_notes = '';
						$notes_labels = array( 'Notes', 'link_notes' );
						foreach( $notes_labels as $notes_label ) {
							if ( isset( $import_columns[$notes_label] ) ) {
								$link_notes = sanitize_text_field( $data[$import_columns[$notes_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_notes', $link_notes );

						$link_rss = '';
						$rss_labels = array( 'RSS', 'link_rss' );
						foreach( $rss_labels as $rss_label ) {
							if ( isset( $import_columns[$rss_label] ) ) {
								$link_rss = esc_url( $data[$import_columns[$rss_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_rss', $link_rss );

						$link_second_url = '';
						$second_url_labels = array( 'Secondary URL', 'link_second_url' );
						foreach( $second_url_labels as $second_url_label ) {
							if ( isset( $import_columns[$second_url_label] ) ) {
								$link_second_url = esc_url( $data[$import_columns[$second_url_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_second_url',  $link_second_url );

						$link_telephone = '';
						$telephone_labels = array( 'Telephone', 'link_telephone' );
						foreach( $telephone_labels as $telephone_label ) {
							if ( isset( $import_columns[$telephone_label] ) ) {
								$link_telephone = sanitize_text_field( $data[$import_columns[$telephone_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_telephone', $link_telephone );

						$link_email = '';
						$email_labels = array( 'E-mail', 'link_email' );
						foreach( $email_labels as $email_label ) {
							if ( isset( $import_columns[$email_label] ) ) {
								$link_email = sanitize_email( $data[$import_columns[$email_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_email', $link_email );

						if ( empty( $existing_link_post_id ) ) {
							$link_visits = 0;
							$link_visits_labels = array( 'Link Visits' );
							foreach( $link_visits_labels as $link_visits_label ) {
								if ( isset( $import_columns[$link_visits_label] ) ) {
									$link_visits = intval( $data[$import_columns[$link_visits_label]] );
								}
							}

							update_post_meta( $new_link_ID, 'link_visits', $link_visits );
						}

						$link_reciprocal = '';
						$reciprocal_labels = array( 'Reciprocal Link', 'link_reciprocal' );
						foreach( $reciprocal_labels as $reciprocal_label ) {
							if ( isset( $import_columns[$reciprocal_label] ) ) {
								$link_reciprocal = esc_url( $data[$import_columns[$reciprocal_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_reciprocal', $link_reciprocal );

						$link_large_description = '';
						$large_description_labels = array( 'Large Description', 'link_textfield' );
						foreach( $large_description_labels as $large_description_label ) {
							if ( isset( $import_columns[$large_description_label] ) ) {
								$link_large_description = sanitize_text_field( $data[$import_columns[$large_description_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_textfield', $link_large_description );

						$link_no_follow = 0;
						$no_follow_labels = array( 'No Follow', 'link_no_follow' );
						foreach( $no_follow_labels as $no_follow_labels ) {
							if ( isset( $import_columns[$no_follow_labels] ) ) {
								$link_no_follow = $data[$import_columns[$no_follow_labels]];
							}
						}

						if ( '1' == $link_no_follow || 'Y' == $link_no_follow ) {
							update_post_meta( $new_link_ID, 'link_no_follow', true );
						} else {
							update_post_meta( $new_link_ID, 'link_no_follow', false );
						}

						$link_featured = 0;
						$featured_labels = array( 'Link Featured' );
						foreach( $featured_labels as $featured_label ) {
							if ( isset( $import_columns[$featured_label] ) ) {
								$link_featured = $data[$import_columns[$featured_label]];
							}
						}

						if ( '1' == $link_featured || 'Y' == $link_featured ) {
							update_post_meta( $new_link_ID, 'link_featured', true );
						} else {
							update_post_meta( $new_link_ID, 'link_featured', false );
						}

						$link_submitter_name = '';
						$submitter_name_labels = array( 'Link Submitter Name' );
						foreach( $submitter_name_labels as $submitter_name_label ) {
							if ( isset( $import_columns[$submitter_name_label] ) ) {
								$link_submitter_name = sanitize_text_field( $data[$import_columns[$submitter_name_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_submitter_name', $link_submitter_name );

						$link_submitter_email = '';
						$submitter_email_labels = array( 'Link Submitter E-mail' );
						foreach( $submitter_email_labels as $submitter_email_label ) {
							if ( isset( $import_columns[$submitter_email_label] ) ) {
								$link_submitter_email = sanitize_email( $data[$import_columns[$submitter_email_label]] );
							}
						}
						update_post_meta( $new_link_ID, 'link_submitter_email', $link_submitter_email );
					}
				}
			}

			$row -= 1;

			$message = '9';

			wp_suspend_cache_addition( false );
			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );
		} elseif ( isset( $_POST['siteimport'] ) ) {
			wp_suspend_cache_addition( true );
			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );
			define( 'WP_IMPORTING', true );

			set_time_limit( 600 );

			$all_content = array();

			$post_args = array();
			$post_types = array( 'post' );

			$site_post_types = get_post_types( array( '_builtin' => false ) );
			foreach ( $site_post_types as $site_post_type ) {
				$post_types[] = $site_post_type;
			}

			if ( 'allpagesposts' == $_POST['siteimportlinksscope']
			     || 'allpagespostscpt' == $_POST['siteimportlinksscope']
			     || 'specificpage' == $_POST['siteimportlinksscope'] ) {

				$page_args = array();

				if ( 'specificpage' == $_POST['siteimportlinksscope'] ) {
					$page_args['include'] = $_POST['page_id'];
				}

				$all_pages = get_pages( $page_args );

				foreach ( $all_pages as $current_page ) {
					$all_content[] = $current_page->post_content;
				}
			}

			if ( 'allpagesposts' == $_POST['siteimportlinksscope']
			     || 'allpagespostscpt' == $_POST['siteimportlinksscope'] ) {

				$post_args = array();

				if ( 'allpagesposts' == $_POST['siteimportlinksscope'] ) {
					$sub_post_types[] = 'post';
				} else {
					$sub_post_types = $post_types;
				}

				foreach ( $sub_post_types as $post_type ) {
					$post_args['post_type'] = $post_type;
					$all_posts = get_posts( $post_args );
					foreach ( $all_posts as $current_post ) {
						$all_content[] = $current_post->post_content;
					}
				}
			}

			foreach ( $post_types as $post_type ) {
				if ( 'specific' . $post_type == $_POST['siteimportlinksscope'] ) {
					$post_args = array();
					$post_id = $_POST[$post_type . '_id'];
					$post_args['post_type'] = get_post_type( $post_id );
					$post_args['include'] = $_POST[$post_type . '_id'];
					$all_posts = get_posts( $post_args );
					foreach ( $all_posts as $current_post ) {
						$all_content[] = $current_post->post_content;
					}
				}
			}

			foreach ( $all_content as $content_item ) {
				$row++;
				$dom = new DOMDocument;
				$dom->loadHTML( $content_item );
				foreach ( $dom->getElementsByTagName( 'a' ) as $node ) {
					$incomingcatdata = $_POST['siteimportcat'];

					if ( isset( $_POST['siteimportupdatesameurl'] ) ) {
						$find_post_args = array( 'post_type' => 'link_library_links',
						                         'meta_key' => 'link_url',
						                         'meta_value' => esc_url( $node->getAttribute( "href" ) ),
						                         'numberposts' => 1 );

						$posts_same_url_array = get_posts( $find_post_args );

						if ( !empty( $posts_same_url_array ) ) {
							$existing_link_post_id = $posts_same_url_array[0]->ID;
						}
					}

					$new_link_data = array(
						'post_type' => 'link_library_links',
						'post_content' => '',
						'post_title' => esc_html( $node->nodeValue ),
						'tax_input' => array( 'link_library_category' => $incomingcatdata ),
						'post_status' => 'publish'
					);

					if ( !empty( $existing_link_post_id ) ) {
						$new_link_data['ID'] = $existing_link_post_id;
						$new_link_ID = wp_insert_post( $new_link_data );
						$successfulupdate++;
					} else {
						$new_link_ID = wp_insert_post( $new_link_data );
						$successfulimport++;
					}

					update_post_meta( $new_link_ID, 'link_url', esc_url( $node->getAttribute( "href" ) ) );
					update_post_meta( $new_link_ID, 'link_image', '' );
					update_post_meta( $new_link_ID, 'link_target', '' );
					update_post_meta( $new_link_ID, 'link_description', '' );
					update_post_meta( $new_link_ID, 'link_rating', 0 );

					update_post_meta( $new_link_ID, 'link_updated', current_time( 'timestamp' ) );

					update_post_meta( $new_link_ID, 'link_notes', '' );
					update_post_meta( $new_link_ID, 'link_rss', '' );
					update_post_meta( $new_link_ID, 'link_second_url', '' );
					update_post_meta( $new_link_ID, 'link_telephone', '' );
					update_post_meta( $new_link_ID, 'link_email', '' );

					if ( empty( $existing_link_post_id ) ) {
						update_post_meta( $new_link_ID, 'link_visits', 0 );
					}

					update_post_meta( $new_link_ID, 'link_reciprocal', '' );
					update_post_meta( $new_link_ID, 'link_textfield', '' );
					update_post_meta( $new_link_ID, 'link_no_follow', false );
					update_post_meta( $new_link_ID, 'link_featured', false );

					update_post_meta( $new_link_ID, 'legacy_link_id', $newlinkid );
				}
			}
			$message = '9';

			wp_suspend_cache_addition( false );
			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );
		} elseif ( isset( $_POST['exportalllinks'] ) ) {
			$upload_dir = wp_upload_dir();

			if ( is_writable( $upload_dir['path'] ) ) {
				$myFile = $upload_dir['path'] . "/LinksExport.csv";
				$fh = fopen( $myFile, 'w' ) or die( "can't open file" );

				$links_query_args = array( 'post_type' => 'link_library_links', 'posts_per_page' => -1, 'post_status' => array( 'publish', 'pending', 'draft', 'future', 'private' ) );

				$links_to_export = new WP_Query( $links_query_args );

				if ( $links_to_export->have_posts() ) {
					$link_items = array();
					while ( $links_to_export->have_posts() ) {
						$link_object = array();
						$links_to_export->the_post();

						$link_cats_array = array();
						$link_cats_slugs_array = array();
						$link_categories = wp_get_post_terms( get_the_ID(), 'link_library_category' );
						if ( $link_categories ) {
							foreach ( $link_categories as $link_category ) {
								$link_cats_array[] = $link_category->name;
								$link_cats_slugs_array[] = $link_category->slug;
							}
							if ( !empty( $link_cats_array ) ) {
								$link_cats = implode( ', ', $link_cats_array );
							}
							if ( !empty( $link_cats_slugs_array ) ) {
								$link_cats_slugs = implode( ', ', $link_cats_slugs_array );
							}
						}

						$link_tags_array = array();
						$link_tags_slugs_array = array();
						$link_tags_string = '';
						$link_tags_slugs = '';
						$link_tags = wp_get_post_terms( get_the_ID(), 'link_library_tags' );
						if ( $link_tags ) {
							foreach ( $link_tags as $link_tag ) {
								$link_tags_array[] = $link_tag->name;
								$link_tags_slugs_array[] = $link_tag->slug;
							}
							if ( !empty( $link_tags_array ) ) {
								$link_tags_string = implode( ', ', $link_tags_array );
							}
							if ( !empty( $link_cats_slugs_array ) ) {
								$link_tags_slugs = implode( ', ', $link_tags_slugs_array );
							}
						}

						$link_object['Name'] = get_the_title();
						$link_object['Address'] = get_post_meta( get_the_ID(), 'link_url', true );
						$link_object['RSS'] = get_post_meta( get_the_ID(), 'link_rss', true );
						$link_object['Description'] = get_post_meta( get_the_ID(), 'link_description', true );
						$link_object['Notes'] = get_post_meta( get_the_ID(), 'link_notes', true );
						$link_object['Category Slugs'] = $link_cats_slugs;
						$link_object['Category Names'] = $link_cats;
						$link_object['Tag Slugs'] = $link_tags_slugs;
						$link_object['Tag Names'] = $link_tags_string;
						$link_object['Status'] = get_post_status();
						$link_object['Secondary URL'] = get_post_meta( get_the_ID(), 'link_second_url', true );
						$link_object['Telephone'] = get_post_meta( get_the_ID(), 'link_telephone', true );
						$link_object['E-mail'] = get_post_meta( get_the_ID(), 'link_email', true );
						$link_object['Reciprocal Link'] = get_post_meta( get_the_ID(), 'link_reciprocal', true );
						$link_object['Image Address'] = get_post_meta( get_the_ID(), 'link_image', true );
						$link_object['Large Description'] = get_post_meta( get_the_ID(), 'link_textfield', true );
						$link_object['No Follow'] = get_post_meta( get_the_ID(), 'link_no_follow', true );
						$link_object['Rating'] = get_post_meta( get_the_ID(), 'link_rating', true );
						$link_object['Link Target'] = get_post_meta( get_the_ID(), 'link_target', true );
						$link_object['Updated Date - Empty for none'] = date( 'Y-m-d', intval( get_post_meta( get_the_ID(), 'link_updated', true ) ) );
						$link_object['Link Featured'] = get_post_meta( get_the_ID(), 'link_featured', true );
						$link_object['Link Submitter Name'] = get_post_meta( get_the_ID(), 'link_submitter_name', true );
						$link_object['Link Submitter E-mail'] = get_post_meta( get_the_ID(), 'link_submitter_email', true );
						$link_object['Link Visits'] = get_post_meta( get_the_ID(), 'link_visits', true );

						$link_items[] = $link_object;
					}
				}

				wp_reset_postdata();

				if ( $link_items ) {
					$headerrow = array();

					foreach ( $link_items[0] as $key => $option ) {
						$headerrow[] = '"' . $key . '"';
					}

					$headerdata = join( ',', $headerrow ) . "\n";
					fwrite( $fh, $headerdata );

					foreach ( $link_items as $link_item ) {
						$datarow = array();
						foreach ( $link_item as $key => $value ) {
							$datarow[] = $value;
						}
						fputcsv( $fh, $datarow, ',', '"' );
					}

					fclose( $fh );

					if ( file_exists( $myFile ) ) {
						header( 'Content-Description: File Transfer' );
						header( 'Content-Type: application/octet-stream' );
						header( 'Content-Disposition: attachment; filename=' . basename( $myFile ) );
						header( 'Expires: 0' );
						header( 'Cache-Control: must-revalidate' );
						header( 'Pragma: public' );
						header( 'Content-Length: ' . filesize( $myFile ) );
						readfile( $myFile );
						exit;
					}
				}
			} else {
				$message = '3';
			}
		} elseif ( isset( $_POST['ll60catmapping'] ) ) {
			$upload_dir = wp_upload_dir();

			if ( is_writable( $upload_dir['path'] ) ) {
				$myFile = $upload_dir['path'] . "/LinkLibraryCatMapping.csv";
				$fh = fopen( $myFile, 'w' ) or die( "can't open file" );

				global $wpdb;

				$all_link_cats_query = 'SELECT distinct t.name, t.term_id, tt.description ';
				$all_link_cats_query .= 'FROM ' . $this->db_prefix() . 'terms t ';
				$all_link_cats_query .= 'LEFT JOIN ' . $this->db_prefix() . 'term_taxonomy tt ON (t.term_id = tt.term_id) ';
				$all_link_cats_query .= 'LEFT JOIN ' . $this->db_prefix() . 'term_relationships tr ON (tt.term_taxonomy_id = tr.term_taxonomy_id) ';
				$all_link_cats_query .= 'WHERE tt.taxonomy = "link_category" ';
				$all_link_cats_query .= 'ORDER by t.term_id ASC ';

				$all_link_cats = $wpdb->get_results( $all_link_cats_query );

				if ( !empty( $all_link_cats ) ) {
					$link_cat_items = array();
					foreach ( $all_link_cats as $link_cat ) {
						$link_cat_object = array();
						$link_cat_object['Category Name'] = $link_cat->name;
						$link_cat_object['Version 5.9 Category ID'] = $link_cat->term_id;

						$cat_string = $link_cat->name;
						$cat_matched_term = get_term_by( 'name', $cat_string, 'link_library_category' );

						if ( false !== $cat_matched_term ) {
							$link_cat_object['Version 6.0 Category ID'] = $cat_matched_term->term_id;
						}

						$link_cat_items[] = $link_cat_object;
					}
				}

				if ( $link_cat_items ) {
					$headerrow = array();

					foreach ( $link_cat_items[0] as $key => $option ) {
						$headerrow[] = '"' . $key . '"';
					}

					$headerdata = join( ',', $headerrow ) . "\n";
					fwrite( $fh, $headerdata );

					foreach ( $link_cat_items as $link_cat_item ) {
						$datarow = array();
						foreach ( $link_cat_item as $key => $value ) {
							$datarow[] = '"' . $value . '"';
						}
						$data = join( ',', $datarow ) . "\n";
						fwrite( $fh, $data );
					}

					fclose( $fh );

					if ( file_exists( $myFile ) ) {
						header( 'Content-Description: File Transfer' );
						header( 'Content-Type: application/octet-stream' );
						header( 'Content-Disposition: attachment; filename=' . basename( $myFile ) );
						header( 'Expires: 0' );
						header( 'Cache-Control: must-revalidate' );
						header( 'Pragma: public' );
						header( 'Content-Length: ' . filesize( $myFile ) );
						readfile( $myFile );
						exit;
					}
				}
			} else {
				$message = '3';
			}
		} elseif ( isset( $_POST['deletell59links'] ) ) {
			global $wpdb;

			$delete_links_query = 'DELETE FROM ' . $this->db_prefix() . 'links ';
			$wpdb->get_results( $delete_links_query );
		} else {
			$genoptions = get_option( 'LinkLibraryGeneral' );

			foreach (
				array(
					'numberstylesets', 'includescriptcss', 'pagetitleprefix', 'pagetitlesuffix', 'schemaversion', 'thumbshotscid', 'approvalemailtitle',
					'moderatorname', 'moderatoremail', 'rejectedemailtitle', 'approvalemailbody', 'rejectedemailbody', 'moderationnotificationtitle',
					'linksubmissionthankyouurl', 'recipcheckaddress', 'imagefilepath', 'catselectmethod', 'expandiconpath', 'collapseiconpath', 'updatechannel',
					'extraprotocols', 'thumbnailsize', 'thumbnailgenerator', 'rsscachedelay', 'single_link_layout', 'rolelevel', 'editlevel', 'cptslug',
					'defaultlinktarget', 'bp_link_page_url', 'bp_link_settings', 'defaultprotocoladmin', 'pagepeekerid', 'pagepeekersize', 'stwthumbnailsize', 'shrinkthewebaccesskey'
				) as $option_name
			) {
				if ( isset( $_POST[$option_name] ) ) {
					$genoptions[$option_name] = $_POST[$option_name];
				}
			}

			if ( isset( $_POST['captchagenerator'] ) && 'recaptcha' == $_POST['captchagenerator'] ) {
				if ( empty( $_POST['recaptchasitekey'] ) || empty( $_POST['recaptchasecretkey'] ) ) {
					$genoptions['captchagenerator'] = 'easycaptcha';
					$message = 4;
				} else {
					$genoptions['captchagenerator'] = 'recaptcha';
					$genoptions['recaptchasitekey'] = sanitize_text_field( $_POST['recaptchasitekey'] );
					$genoptions['recaptchasecretkey'] = sanitize_text_field( $_POST['recaptchasecretkey'] );
				}
			} elseif ( isset( $_POST['captchagenerator'] ) && 'easycaptcha' == $_POST['captchagenerator'] ) {
				$genoptions['captchagenerator'] = 'easycaptcha';
				$genoptions['recaptchasitekey'] = '';
				$genoptions['recaptchasecretkey'] = '';
			}

			foreach ( array( 'debugmode', 'emaillinksubmitter', 'suppressemailfooter', 'usefirstpartsubmittername', 'hidedonation', 'publicly_queryable', 'exclude_from_search', 'bp_log_activity' ) as $option_name ) {
				if ( isset( $_POST[$option_name] ) ) {
					$genoptions[$option_name] = true;
				} else {
					if ( $option_name != 'hidedonation' ) {
						$genoptions[$option_name] = false;
					}
				}
			}

			update_option( 'LinkLibraryGeneral', $genoptions );

			update_option( 'links_updated_date_format', $_POST['links_updated_date_format'] );
		}

		global $wp_rewrite;
		$wp_rewrite->flush_rules( false );

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		$redirecturl = remove_query_arg( array( 'message', 'importrowscount', 'successimportcount' ), $_POST['_wp_http_referer'] );

		if ( !empty( $message ) ) {
			$redirecturl = add_query_arg( 'message', $message, $redirecturl );
		}

		if ( isset( $_POST['currenttab'] ) ) {
			$redirecturl = add_query_arg( 'currenttab', $_POST['currenttab'], $redirecturl );
		}

		if ( isset( $row ) && $row != 0 ) {
			$redirecturl = add_query_arg( 'importrowscount', $row, $redirecturl );
		}

		if ( isset( $successfulimport ) && $successfulimport != 0 ) {
			$redirecturl = add_query_arg( 'successimportcount', $successfulimport, $redirecturl );
		}

		if ( isset( $successfulupdate ) && $successfulupdate != 0 ) {
			$redirecturl = add_query_arg( 'successupdatecount', $successfulupdate, $redirecturl );
		}

		wp_redirect( $redirecturl );
		exit;
	}

	function ll_post_exists( $id ) {
		return is_string( get_post_status( $id ) );
	}

	//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_settingssets() {
		//user permission check
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Not allowed', 'link-library' ) );
		}
		//cross check the given referer
		check_admin_referer( 'link-library' );

		if ( isset( $_POST['exportsettings'] ) ) {
			$upload_dir = wp_upload_dir();
			if ( is_writable( $upload_dir['path'] ) ) {
				$myFile = $upload_dir['path'] . "/SettingSet" . $_POST['settingsetid'] . "Export.csv";
				$fh = fopen( $myFile, 'w' ) or die( "can't open file" );

				$sourcesettingsname = 'LinkLibraryPP' . $_POST['settingsetid'];
				$sourceoptions      = get_option( $sourcesettingsname );

				$headerrow = array();

				foreach ( $sourceoptions as $key => $option ) {
					$headerrow[] = '"' . $key . '"';
				}

				$headerdata = join( ',', $headerrow ) . "\n";
				fwrite( $fh, $headerdata );

				$datarow = array();

				foreach ( $sourceoptions as $key => $option ) {
					$datarow[] = '"' . $option . '"';
				}

				$data = join( ',', $datarow ) . "\n";
				fwrite( $fh, $data );

				fclose( $fh );

				if (file_exists($myFile)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.basename($myFile));
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($myFile));
					readfile($myFile);
				exit;
				}
			} else {
				$messages[] = '6';
			}
		} elseif ( isset( $_POST['importsettings'] ) ) {
			if ( $_FILES['settingsfile']['tmp_name'] != "" ) {
				$handle = fopen( $_FILES['settingsfile']['tmp_name'], "r" );

				$row         = 1;
				$optionnames = array();
				$options     = array();

				while ( ( $data = fgetcsv( $handle, 5000, "," ) ) !== false ) {
					if ( $row == 1 ) {
						$optionnames = $data;
						$row ++;
					} else if ( $row == 2 ) {
						for ( $counter = 0; $counter <= count( $data ) - 1; $counter ++ ) {
							$options[$optionnames[$counter]] = $data[$counter];
						}
						$row ++;
					}
				}

				if ( $options != "" ) {
					$settingsname = 'LinkLibraryPP' . $_POST['settingsetid'];

					update_option( $settingsname, $options );

					$messages[] = '7';
				}

				fclose( $handle );
			} else {
				$messages[] = '8';
			}
		} else {
			$settingsetid = $_POST['settingsetid'];
			$settings     = $_POST['settingsetid'];

			$settingsname = 'LinkLibraryPP' . $settingsetid;

			$options = get_option( $settingsname );

			$genoptions = get_option( 'LinkLibraryGeneral' );

			foreach (
				array(
					'order', 'table_width', 'num_columns', 'position',
					'beforecatlist1', 'beforecatlist2', 'beforecatlist3', 'catnameoutput', 'linkaddfrequency',
					'defaultsinglecat_cpt', 'rsspreviewcount', 'rssfeedinlinecount', 'linksperpage', 'catdescpos',
					'catlistdescpos', 'rsspreviewwidth', 'rsspreviewheight', 'numberofrssitems',
					'displayweblink', 'sourceweblink', 'showtelephone', 'sourcetelephone', 'showemail', 'sourceimage', 'sourcename', 'popup_width', 'popup_height', 'rssfeedinlinedayspublished', 'tooltipname', 'catlistchildcatdepthlimit', 'childcatdepthlimit', 'showcurrencyplacement', 'tooltipname', 'showupdatedpos', 'datesource', 'taglinks', 'linkcurrencyplacement'
				)
				as $option_name
			) {
				if ( isset( $_POST[$option_name] ) ) {
					$options[$option_name] = str_replace( "\"", "'", strtolower( $_POST[$option_name] ) );
				}
			}

			foreach ( array( 'categorylist_cpt', 'excludecategorylist_cpt', 'taglist_cpt', 'excludetaglist_cpt' ) as $option_name ) {
				if ( isset( $_POST[$option_name] ) ) {
					if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) {
						$options[$option_name] = str_replace( "\"", "'", strtolower( $_POST[$option_name] ) );
					} else if ( $genoptions['catselectmethod'] == 'multiselectlist' ) {
						$options[$option_name] = implode( ',', $_POST[$option_name] );
					}
				} else {
					$options[$option_name] = '';
				}
			}

			foreach (
				array(
					'columnheaderoverride','linktarget', 'settingssetname', 'loadingicon',
					'direction', 'linkdirection', 'linkorder', 'addnewlinkmsg', 'linknamelabel', 'linkaddrlabel', 'linkrsslabel',
					'linkcatlabel', 'linkdesclabel', 'linknoteslabel', 'addlinkbtnlabel', 'newlinkmsg', 'moderatemsg', 'imagepos',
					'imageclass', 'rssfeedtitle', 'rssfeeddescription', 'showonecatmode', 'linkcustomcatlabel', 'linkcustomcatlistentry',
					'searchlabel', 'dragndroporder', 'cattargetaddress', 'beforeweblink', 'afterweblink', 'weblinklabel', 'beforetelephone',
					'aftertelephone', 'telephonelabel', 'beforeemail', 'afteremail', 'emaillabel', 'beforelinkhits', 'afterlinkhits',
					'linkreciprocallabel', 'linksecondurllabel', 'linktelephonelabel', 'linkemaillabel', 'emailcommand', 'rewritepage',
					'maxlinks', 'beforedate', 'afterdate', 'beforeimage', 'afterimage', 'beforerss', 'afterrss', 'beforenote', 'afternote',
					'beforelink', 'afterlink', 'beforeitem', 'afteritem', 'beforedesc', 'afterdesc', 'addbeforelink', 'addafterlink',
					'beforelinkrating', 'afterlinkrating', 'linksubmitternamelabel', 'linksubmitteremaillabel', 'linksubmittercommentlabel',
					'addlinkcatlistoverride', 'beforelargedescription', 'afterlargedescription', 'customcaptchaquestion', 'customcaptchaanswer',
					'rssfeedaddress', 'linklargedesclabel', 'flatlist', 'searchresultsaddress', 'link_popup_text', 'linktitlecontent', 'paginationposition',
					'showaddlinkrss', 'showaddlinkdesc', 'showaddlinkcat', 'showaddlinknotes', 'addlinkcustomcat',
					'showaddlinkreciprocal', 'showaddlinksecondurl', 'showaddlinktelephone', 'showaddlinkemail', 'showcustomcaptcha', 'showlinksubmittername',
					'showaddlinksubmitteremail', 'showlinksubmittercomment', 'showuserlargedescription', 'cat_letter_filter', 'beforefirstlink', 'afterlastlink',
					'searchfieldtext', 'catfilterlabel', 'searchnoresultstext', 'addlinkdefaultcat', 'beforesubmittername', 'aftersubmittername',
					'beforecatdesc', 'aftercatdesc', 'displayastable', 'extraquerystring', 'emailextracontent', 'beforelinktags', 'afterlinktags', 'beforelinkprice', 'afterlinkprice', 'linkcurrency',
					'toppagetext', 'updatedlabel', 'weblinktarget', 'linktagslabel', 'showaddlinktags', 'addlinktaglistoverride', 'linkcustomtaglabel',
					'addlinkcustomtag', 'linkcustomtaglistentry', 'maxlinkspercat', 'linkaddrdefvalue', 'userlinkcatselectionlabel', 'dropdownselectionprompttext',
					'beforecatname', 'aftercatname', 'linkimagelabel', 'showaddlinkimage', 'linknametooltip', 'linkaddrtooltip', 'linkrsstooltip',
					'linkcattooltip', 'linkusercattooltip', 'linkusertagtooltip', 'linkdesctooltip', 'linknotestooltip', 'linkimagetooltip', 'linkreciptooltip',
					'linksecondtooltip', 'linktelephonetooltip', 'linkemailtooltip', 'submitternametooltip', 'submitteremailtooltip',
					'submittercommenttooltip', 'largedesctooltip', 'linktagtooltip'
				) as $option_name
			) {
				if ( isset( $_POST[$option_name] ) ) {
					$options[$option_name] = str_replace( "\"", "'", $_POST[$option_name] );
				}
			}

			foreach (
				array(
					'hide_if_empty', 'catanchor', 'showdescription', 'shownotes', 'showrating', 'showupdated', 'show_images',
					'use_html_tags', 'show_rss', 'nofollow', 'showcolumnheaders', 'show_rss_icon', 'showcategorydescheaders',
					'showcategorydesclinks', 'showadmineditlinks', 'showonecatonly', 'rsspreview', 'rssfeedinline', 'rssfeedinlinecontent',
					'pagination', 'hidecategorynames', 'showinvisible', 'showdate', 'showuserlinks', 'emailnewlink', 'usethumbshotsforimages', 'uselocalimagesoverthumbshots',
					'addlinkreqlogin', 'showcatlinkcount', 'publishrssfeed', 'showname', 'enablerewrite', 'storelinksubmitter', 'showlinkhits', 'showcaptcha',
					'showlargedescription', 'addlinknoaddress', 'featuredfirst', 'usetextareaforusersubmitnotes', 'showcatonsearchresults', 'shownameifnoimage',
					'enable_link_popup', 'nocatonstartup', 'showlinksonclick', 'showinvisibleadmin', 'combineresults', 'showifreciprocalvalid',
					'cat_letter_filter_autoselect', 'cat_letter_filter_showalloption', 'emailsubmitter', 'addlinkakismet', 'rssfeedinlineskipempty',
					'current_user_links', 'showsubmittername', 'onereciprocaldomain', 'nooutputempty', 'showcatdesc', 'hidechildcatlinks',
					'hidechildcattop', 'catlinkspermalinksmode', 'showbreadcrumbspermalinks', 'showlinktags', 'showlinkprice', 'show0asfree',
					'allowcolumnsorting', 'showsearchreset', 'showscheduledlinks', 'suppressnoreferrer', 'dropdownselectionprompt',
					'showcatname', 'onelinkperdomain', 'showupdatedtooltip'
				)
				as $option_name
			) {
				if ( isset( $_POST[$option_name] ) ) {
					$options[$option_name] = true;
				} else {
					$options[$option_name] = false;
				}
			}

			foreach (
				array(
					'divorheader'
				) as $option_name
			) {
				if ( $_POST[$option_name] == 'true' ) {
					$options[$option_name] = true;
				} elseif ( $_POST[$option_name] == 'false' ) {
					$options[$option_name] = false;
				}
			}

			foreach ( array( 'catlistwrappers' ) as $option_name ) {
				if ( isset( $_POST[$option_name] ) ) {
					$options[$option_name] = (int) ( $_POST[$option_name] );
				}
			}

			update_option( $settingsname, $options );
			$messages[] = "1";

			if ( !empty( $options['categorylist_cpt'] ) ) {
				$categoryids = explode( ',', $options['categorylist_cpt'] );

				foreach ( $categoryids as $categoryid ) {
					$link_categories_query_args = array( 'hide_empty' => false );

					$link_categories_query_args['include'] = array( $categoryid );
			        $catnames = get_terms( 'link_library_category', $link_categories_query_args );

					if ( !$catnames ) {
						$messages[] = '2';
					}
				}
			}

			if ( !empty( $options['excludecategorylist_cpt'] ) ) {
				$categoryids = explode( ',', $options['excludecategorylist_cpt'] );

				foreach ( $categoryids as $categoryid ) {
					$link_categories_query_args = array( 'hide_empty' => false );

					$link_categories_query_args['include'] = array( $categoryid );
			        $catnames = get_terms( 'link_library_category', $link_categories_query_args );

					if ( !$catnames ) {
						$messages[] = '3';
					}
				}
			}
			global $wp_rewrite;
			$wp_rewrite->flush_rules( false );
		}

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		$messagelist      = implode( ",", $messages );

		$redirecturl = add_query_arg( array( 'post_type' => 'link_library_links', 'currenttab' => $_POST['currenttab'], 'page' => 'link-library-settingssets' ), admin_url( 'edit.php' ) );

		if ( $settingsetid > 1 ) {
			$redirecturl = add_query_arg( 'settings', $settingsetid, $redirecturl );
		}

		if ( isset( $row ) && $row != 0 ) {
			$redirecturl = add_query_arg( 'importrowscount', $row, $redirecturl );
		}

		if ( isset( $successfulimport ) && $successfulimport != 0 ) {
			$redirecturl = add_query_arg( 'successimportcount', $successfulimport, $redirecturl );
		}

		if ( isset( $successfulupdate ) && $successfulupdate != 0 ) {
			$redirecturl = add_query_arg( 'successupdatecount', $successfulupdate, $redirecturl );
		}

		if ( !empty( $message ) ) {
			$redirecturl = add_query_arg( array( 'message' => $message ), $redirecturl );
		}

		wp_redirect( $redirecturl );
		exit;
	}

	//executed if the post arrives initiated by pressing the submit button of form
	function on_save_changes_moderate() {
		//user permission check
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Not allowed', 'link-library' ) );
		}
		//cross check the given referer
		check_admin_referer( 'link-library' );

		$message = '';

		$genoptions = get_option( 'LinkLibraryGeneral' );

		if ( isset( $_POST['approvelinks'] ) && ( isset( $_POST['links'] ) && count( $_POST['links'] ) > 0 ) ) {
			$section = 'moderate';

			foreach ( $_POST['links'] as $approved_link ) {
				$link_data = get_post( $approved_link );

				if ( !empty( $link_data ) ) {
					wp_update_post( array( 'ID' => $approved_link, 'post_status' => 'publish' ) );
				}

				$submitter_email = get_post_meta( $approved_link, 'link_submitter_email', true );
				$submitter_name = get_post_meta( $approved_link, 'link_submitter_name', true );
				$link_url = get_post_meta( $approved_link, 'link_url', true );

				if ( $genoptions['emaillinksubmitter'] == true && !empty( $submitter_email ) ) {
					if ( $genoptions['usefirstpartsubmittername'] == true ) {
						$spacepos = strpos( $submitter_name, ' ' );
						if ( $spacepos !== false ) {
							$submitter_name = substr( $submitter_name, 0, $spacepos );
						}
					}

					$emailtitle = str_replace( '%linkname%', get_the_title( $approved_link ), $genoptions['approvalemailtitle'] );
					$emailbody  = nl2br( $genoptions['approvalemailbody'] );
					$emailbody  = str_replace( '%submittername%', stripslashes( $submitter_name ), stripslashes( $emailbody ) );
					$emailbody  = str_replace( '%linkname%', get_the_title( $approved_link ), $emailbody );
					$emailbody  = str_replace( '%linkurl%', $link_url, $emailbody );

					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

					if ( !empty( $genoptions['moderatorname'] ) && !empty( $genoptions['moderatoremail'] ) ) {
						$headers .= "From: \"" . $genoptions['moderatorname'] . "\" <" . $genoptions['moderatoremail'] . ">\n";
					}

					$message = $emailbody;

					if ( !$genoptions['suppressemailfooter'] ) {
						$message .= "<br /><br />" . __( 'Message generated by', 'link-library' ) . " <a href='https://ylefebvre.home.blog/wordpress-plugins/link-library/'>Link Library</a> for Wordpress";
					}

					wp_mail( $submitter_email, $emailtitle, $message, $headers );

					do_action( 'link_library_approval_email', $approved_link );
				}
			}

			$message = '1';
		} elseif ( isset( $_POST['deletelinks'] ) && ( isset( $_POST['links'] ) && count( $_POST['links'] ) > 0 ) ) {
			$section = 'moderate';

			foreach ( $_POST['links'] as $approved_link ) {
				$link_data = get_post( $approved_link );

				$submitter_email = get_post_meta( $approved_link, 'link_submitter_email', true );
				$submitter_name = get_post_meta( $approved_link, 'link_submitter_name', true );
				$link_url = get_post_meta( $approved_link, 'link_url', true );

				if ( $genoptions['emaillinksubmitter'] == true && !empty( $submitter_email ) ) {
					if ( $genoptions['usefirstpartsubmittername'] == true ) {
						$spacepos = strpos( $submitter_name, ' ' );
						if ( $spacepos !== false ) {
							$submitter_name = substr( $submitter_name, 0, $spacepos );
						}
					}

					$emailtitle = str_replace( '%linkname%', get_the_title( $approved_link ), $genoptions['rejectedemailtitle'] );
					$emailbody  = nl2br( $genoptions['rejectedemailbody'] );
					$emailbody  = str_replace( '%submittername%', stripslashes( $submitter_name ), stripslashes( $emailbody ) );
					$emailbody  = str_replace( '%linkname%', get_the_title( $approved_link ), $emailbody );
					$emailbody  = str_replace( '%linkurl%', $link_url, $emailbody );

					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

					if ( !empty( $genoptions['moderatorname'] ) && !empty( $genoptions['moderatoremail'] ) ) {
						$headers .= "From: \"" . $genoptions['moderatorname'] . "\" <" . $genoptions['moderatoremail'] . ">\n";
					}

					$message = $emailbody;

					$message .= "<br /><br />" . __( 'Message generated by', 'link-library' ) . " <a href='https://ylefebvre.home.blog/wordpress-plugins/link-library/'>Link Library</a> for Wordpress";

					wp_mail( $submitter_email, $emailtitle, $message, $headers );

					do_action( 'link_library_rejection_email', $approved_link );
				}

				wp_delete_post( $approved_link );
			}

			$message = '2';
		}

		$redirecturl = remove_query_arg( array( 'message' ), $_POST['_wp_http_referer'] );

		if ( !empty( $message ) ) {
			$redirecturl = add_query_arg( 'message', $message, $redirecturl );
		}

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		wp_redirect( $redirecturl );
		exit;
	}

	function on_save_changes_stylesheet() {
		//user permission check
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Not allowed', 'link-library' ) );
		}
		//cross check the given referer
		check_admin_referer( 'link-library' );

		if ( isset( $_POST['submitstyle'] ) ) {
			$genoptions = get_option( 'LinkLibraryGeneral' );

			$genoptions['fullstylesheet'] = $_POST['fullstylesheet'];

			update_option( 'LinkLibraryGeneral', $genoptions );
			$message = 1;
		} elseif ( isset( $_POST['resetstyle'] ) ) {
			$genoptions = get_option( 'LinkLibraryGeneral' );

			$stylesheetlocation = plugin_dir_path( __FILE__ ) . 'stylesheettemplate.css';

			if ( file_exists( $stylesheetlocation ) ) {
				$genoptions['fullstylesheet'] = file_get_contents( $stylesheetlocation );
			}

			update_option( 'LinkLibraryGeneral', $genoptions );

			$message = 2;
		}

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		$redirect_url = remove_query_arg( array( 'message' ), $_POST['_wp_http_referer'] );
		$redirect_url = add_query_arg( 'message', $message, $redirect_url );
		wp_redirect( $redirect_url );
		exit;
	}

	function on_save_changes_reciprocal() {
		//user permission check
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Not allowed', 'link-library' ) );
		}
		//cross check the given referer
		check_admin_referer( 'link-library' );

		$message = - 1;

		$genoptions = get_option( 'LinkLibraryGeneral' );

		$genoptions['recipcheckaddress']   = ( ( isset( $_POST['recipcheckaddress'] ) && $_POST['recipcheckaddress'] !== '' ) ? $_POST['recipcheckaddress'] : "" );
		$genoptions['recipcheckdelete403'] = ( ( isset( $_POST['recipcheckdelete403'] ) && $_POST['recipcheckdelete403'] !== '' ) ? $_POST['recipcheckdelete403'] : "" );

		update_option( 'LinkLibraryGeneral', $genoptions );

		if ( !isset( $_POST['recipcheck'] ) && !isset( $_POST['brokencheck'] ) && !isset( $_POST['duplicatecheck'] ) ) {
			$message = 1;
		} elseif ( isset( $_POST['recipcheck'] ) ) {
			$message = 2;
		} elseif ( isset( $_POST['brokencheck'] ) ) {
			$message = 3;
		} elseif ( isset( $_POST['duplicatecheck'] ) ) {
			$message = 4;
		}

		//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
		$redirect_url = remove_query_arg( array( 'message' ), $_POST['_wp_http_referer'] );
		$redirect_url = add_query_arg( 'message', $message, $redirect_url );
		wp_redirect( $redirect_url );
		exit;
	}

	function general_meta_box( $data ) {
		$genoptions = $data['genoptions'];
		$genoptions = wp_parse_args( $genoptions, ll_reset_gen_settings( 'return' ) );
		extract( $genoptions );

		?>

		<div style='padding-top:15px' id="ll-general" class="content-section">
		<table>
			<tr>
				<td>
					<input type='hidden' value='<?php echo $genoptions['schemaversion']; ?>' name='schemaversion' id='schemaversion' />
					<table>
						<tr>
							<td colspan="2"><h4>Link Library 6.0 Upgrade Tools</h4></td>
						</tr>
						<tr>
							<td><?php _e( 'Re-import', 'link-library' ); ?></td>
							<td><button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( __( "Using the re-import function will delete all links in your Link Library and re-import links from the old Link Library 5.9 format to version 6.x. Only use this function if you recently upgraded from 5.9 to 6.x and are having issues with the converter links.", "link-library" ) ) . "') ) window.location.href='edit.php?page=link-library-general-options&amp;post_type=link_library_links&amp;ll60reupdate=1' \""; ?>><?php _e( 'Re-import links', 'link-library' ); ?></button></td>
						</tr>
						<tr>
							<td><?php _e( 'Category mapping table', 'link-library' ); ?></td>
							<td><input type="submit" id="ll60catmapping" name="ll60catmapping" value="<?php _e( 'Export category mapping', 'link-library' ); ?>" /></td>
						</tr>
						<tr>
							<td><?php _e( 'Delete old Link Library 5.9 Links', 'link-library' ); ?></td>
							<td><input type="submit" id="deletell59links" name="deletell59links" value="<?php _e( 'Delete old links', 'link-library' ); ?>" /></td>
						</tr>
						<tr>
							<td colspan="2"><h4>General Options</h4></td>
						</tr>
						<?php if ( !is_multisite() ) { ?>
						<tr>
							<td><?php _e( 'Update channel', 'link-library' ); ?></td>
							<td><select id="updatechannel" name="updatechannel">
									<option value="standard" <?php selected( $genoptions['updatechannel'], 'standard' ); ?>><?php _e( 'Standard channel - Updates as they are released', 'link-library' ); ?>
									<option value="monthly" <?php selected( $genoptions['updatechannel'], 'monthly' ); ?>><?php _e( 'Monthly Channel - Updates once per month', 'link-library' ); ?>
								</select></td>
						</tr>
						<?php } ?>
						<tr>
							<td class='lltooltip' title='<?php _e( 'The stylesheet is now defined and stored using the Link Library admin interface. This avoids problems with updates from one version to the next.', 'link-library' ); ?>' style='width:200px'><?php _e( 'Stylesheet', 'link-library' ); ?></td>
							<td class='lltooltip' title='<?php _e( 'The stylesheet is now defined and stored using the Link Library admin interface. This avoids problems with updates from one version to the next.', 'link-library' ); ?>'>
								<a href="<?php echo esc_url( add_query_arg( array( 'page' => 'link-library-stylesheet', 'section' => 'stylesheet' ), admin_url( 'admin.php' ) ) ); ?>"><?php _e( 'Editor', 'link-library' ); ?></a>
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Number of Libraries', 'link-library' ); ?></td>
							<td>
								<input type="text" id="numberstylesets" name="numberstylesets" size="5" value="<?php echo $genoptions['numberstylesets']; ?>" />
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Link Library Post Slug', 'link-library' ); ?></td>
							<td>
								<input type="text" id="cptslug" name="cptslug" size="20" value="<?php echo $genoptions['cptslug']; ?>" />
							</td>
						</tr>
						<tr>
							<td>Individual link pages can be seen by visitors</td>
							<td><input type="checkbox" id="publicly_queryable" name="publicly_queryable" <?php checked( $genoptions['publicly_queryable'] ); ?>/></td>
						</tr>
						<tr>
							<td>Links appear in search results</td>
							<td><input type="checkbox" id="exclude_from_search" name="exclude_from_search" <?php checked( $genoptions['exclude_from_search'] ); ?>/></td>
						</tr>
						<tr>
							<td>Minimum role for Link Library configuration</td>
							<td>
								<?php global $wp_roles;
								if ( $wp_roles ):?>
									<select name='rolelevel' style='width: 200px'>
										<?php $roles = $wp_roles->roles;

										foreach ( $roles as $role ):
											$selectedterm = selected( $genoptions['rolelevel'], $role['name'], false ); ?>
											<option value='<?php echo $role['name']; ?>' <?php echo $selectedterm; ?>><?php echo $role['name']; ?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td>Minimum role for Link editing</td>
							<td>
								<?php global $wp_roles;
								if ( $wp_roles ):?>
									<select name='editlevel' style='width: 200px'>
										<?php $roles = $wp_roles->roles;

										foreach ( $roles as $role ):
											$selectedterm = selected( $genoptions['editlevel'], $role['name'], false ); ?>
											<option value='<?php echo $role['name']; ?>' <?php echo $selectedterm; ?>><?php echo $role['name']; ?></option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td class="lltooltip" title="<?php _e( 'Changes how categories to be displayed are selected in library configurations. Specifying through a comma-separated list allows user to specify order to display the categories, when used in conjunction with the Results Order option' ); ?>"><?php _e( 'Category selection method', 'link-library' ); ?></td>
							<td><select id="catselectmethod" name="catselectmethod">
									<option value="commalist" <?php selected( $genoptions['catselectmethod'], 'commalist' ); ?>><?php _e( 'Comma-separated ID list', 'link-library' ); ?>
									<option value="multiselectlist" <?php selected( $genoptions['catselectmethod'], 'multiselectlist' ); ?>><?php _e( 'Multi-select List', 'link-library' ); ?>
								</select></td>
						</tr>
						<tr>
							<td><?php _e( 'Default link target in editor', 'link-library' ); ?></td>
							<td><?php $target_array = array( '_blank' => '_blank (new window or tab)', '' => '_none (same window or tab)', '_top' => '_top (current window or tab, with no frames)' );
								echo '<select name="defaultlinktarget" id="defaultlinktarget">';
									foreach ( $target_array as $target_value => $target_item ) {
									echo '<option value="' . $target_value . '" ' . selected( $target_value, $genoptions['defaultlinktarget'] ) . '>' . $target_item . '</option>';
									}
									echo '</select>';
								?></td>
						</tr>
						<tr>
							<td><?php _e( 'Default protocol for new links in admin when not specified', 'link-library' ); ?></td>
							<td><?php $target_array = array( 'http' => 'http://', 'https' => 'https://' );
								echo '<select name="defaultprotocoladmin" id="defaultprotocoladmin">';
								foreach ( $target_array as $target_value => $target_item ) {
									echo '<option value="' . $target_value . '" ' . selected( $target_value, $genoptions['defaultprotocoladmin'] ) . '>' . $target_item . '</option>';
								}
								echo '</select>';
								?></td>
						</tr>
						<tr>
							<td class="lltooltip" title="<?php _e( 'Enter comma-separate list of pages on which the Link Library stylesheet and scripts should be loaded. Primarily used if you display Link Library using the API', 'link-library' ); ?>"><?php _e( 'Additional pages to load styles and scripts', 'link-library' ); ?></td>
							<td class="lltooltip" title="<?php _e( 'Enter comma-separate list of pages on which the Link Library stylesheet and scripts should be loaded. Primarily used if you display Link Library using the API', 'link-library' ); ?>">
								<input type="text" id="includescriptcss" name="includescriptcss" size="40" value="<?php echo $genoptions['includescriptcss']; ?>" />
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Debug Mode', 'link-library' ); ?></td>
							<td>
								<input type="checkbox" id="debugmode" name="debugmode" <?php checked( $genoptions['debugmode'] ); ?>/></td>
						</tr>
						<tr>
							<td class="lltooltip" title="<?php _e( 'This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library' ); ?>"><?php _e( 'Page Title Prefix', 'link-library' ); ?></td>
							<td class="lltooltip" title="<?php _e( 'This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library' ); ?>">
								<input type="text" id="pagetitleprefix" name="pagetitleprefix" size="10" value="<?php echo $genoptions['pagetitleprefix']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="lltooltip" title="<?php _e( 'This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library' ); ?>"><?php _e( 'Page Title Suffix', 'link-library' ); ?></td>
							<td class="lltooltip" title="<?php _e( 'This function is only possible when showing one category at a time and while the default category is not shown.', 'link-library' ); ?>">
								<input type="text" id="pagetitlesuffix" name="pagetitlesuffix" size="10" value="<?php echo $genoptions['pagetitlesuffix']; ?>" />
							</td>
						</tr>
						<tr>
							<td class='lltooltip' title='<?php _e( 'Path for images files that are uploaded manually or generated through thumbnail generation service', 'link-library' ); ?>'><?php _e( 'Link Image File Path', 'link-library' ); ?></td>
							<td colspan='4' class='lltooltip' title='<?php _e( 'Path for images files that are uploaded manually or generated through thumbnail generation service', 'link-library' ); ?>'>
								<select id="imagefilepath" name="imagefilepath">
									<option value="absolute" <?php selected( $genoptions['imagefilepath'], 'absolute' ); ?>><?php _e( 'Absolute', 'link-library' ); ?>
									<option value="relative" <?php selected( $genoptions['imagefilepath'], 'relative' ); ?>><?php _e( 'Relative', 'link-library' ); ?>
								</select></td>
						</tr>
						<tr>
							<td colspan="2"><hr /></td>
						</tr>
						<tr>
							<td><?php _e( 'Thumbnail Generator', 'link-library' ); ?></td>
							<td>
								<select id="thumbnailgenerator" name="thumbnailgenerator">
									<option value="robothumb" <?php selected( $genoptions['thumbnailgenerator'], 'robothumb' ); ?>>Robothumb.com
									<option value="shrinktheweb" <?php selected( $genoptions['thumbnailgenerator'], 'shrinktheweb' ); ?>>Shrink The Web
									<option value="pagepeeker" <?php selected( $genoptions['thumbnailgenerator'], 'pagepeeker' ); ?>>PagePeeker
									<option value="thumbshots" <?php selected( $genoptions['thumbnailgenerator'], 'thumbshots' ); ?>>Thumbshots.org
									<option value="google" <?php selected( $genoptions['thumbnailgenerator'], 'google' ); ?>>Google PageSpeed
								</select>
							</td>
						</tr>
						<tr class="thumbshotsapikey" <?php if ( $genoptions['thumbnailgenerator'] != 'thumbshots' ) {
							echo 'style="display:none;"';
						} ?>>
							<td class='lltooltip' title='<?php _e( 'API Key for Thumbshots.com thumbnail generation accounts', 'link-library' ); ?>'><?php _e( 'Thumbshots API Key', 'link-library' ); ?></td>
							<td colspan='4' class='lltooltip' title='<?php _e( 'API Key for Thumbshots.com thumbnail generation accounts', 'link-library' ); ?>'>
								<input type="text" id="thumbshotscid" name="thumbshotscid" size="20" value="<?php echo $genoptions['thumbshotscid']; ?>" />
							</td>
						</tr>
						<tr class="shrinkthewebaccesskey" <?php if ( $genoptions['thumbnailgenerator'] != 'shrinktheweb' ) {
							echo 'style="display:none;"';
						} ?>>
							<td class='lltooltip' title='<?php _e( 'Access Key for shrinktheweb.com thumbnail generation accounts', 'link-library' ); ?>'><?php _e( 'Shrink The Web Access Key', 'link-library' ); ?></td>
							<td colspan='4' class='lltooltip' title='<?php _e( 'Access Key for shrinktheweb.com thumbnail generation accounts', 'link-library' ); ?>'>
								<input type="text" id="shrinkthewebaccesskey" name="shrinkthewebaccesskey" size="20" value="<?php echo $genoptions['shrinkthewebaccesskey']; ?>" />
							</td>
						</tr>
						<tr class="shrinkthewebsizes" <?php if ( $genoptions['thumbnailgenerator'] != 'shrinktheweb' ) {
							echo 'style="display:none;"';
						} ?>>
							<td><?php _e( 'Shrink the web Thumbnail size' ); ?>
							</td>
							<td>
								<select id="stwthumbnailsize" name="stwthumbnailsize">
									<?php $sizes = array( '75x57', '90x68', '100x75', '120x90', '200x150', '320x240' );

									foreach ( $sizes as $size ) { ?>
									<option value="<?php echo $size; ?>" <?php selected( $genoptions['stwthumbnailsize'], $size ); ?>><?php echo $size; ?>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="pagepeekersizes" <?php if ( $genoptions['thumbnailgenerator'] != 'pagepeeker' ) {
							echo 'style="display:none;"';
						} ?>>
							<td><?php _e( 'PagePeeker Thumbnail size' ); ?>
							</td>
							<td>
								<select id="pagepeekersize" name="pagepeekersize">
									<?php $sizes = array( 't' => '90 x 68', 's' => '120x90', 'm' => '200 x 150', 'l' => '400 x 300', 'x'=> '480 x 360' );

									foreach ( $sizes as $code => $size ) { ?>
									<option value="<?php echo $code; ?>" <?php selected( $genoptions['pagepeekersize'], $code ); ?>><?php echo $size; ?>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr class="pagepeekerid" <?php if ( $genoptions['thumbnailgenerator'] != 'pagepeeker' ) {
							echo 'style="display:none;"';
						} ?>>
							<td><?php _e( 'PagePeeker API Key (for paid or free unbranded accounts)' ); ?>
							</td>
							<td colspan='4' class='lltooltip' title='<?php _e( 'Pagepeeker API Key for premium thumbnail generation', 'link-library' ); ?>'>
								<input type="text" id="pagepeekerid" name="pagepeekerid" size="20" value="<?php echo $genoptions['pagepeekerid']; ?>" />
							</td>
							</td>
						</tr>
						<tr class="robothumbsize" <?php if ( $genoptions['thumbnailgenerator'] != 'robothumb' ) {
							echo 'style="display:none;"';
						} ?>>
							<td><?php _e( 'Robothumb Thumbnail size' ); ?>
							</td>
							<td>
								<select id="thumbnailsize" name="thumbnailsize">
								<?php $sizes = array( '100x75', '120x90', '160x120', '180x135', '240x180', '320x240', '560x420', '640x480', '800x600' );

								foreach ( $sizes as $size ) { ?>
									<option value="<?php echo $size; ?>" <?php selected( $genoptions['thumbnailsize'], $size ); ?>><?php echo $size; ?>
								<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr /></td>
						</tr>
						<tr>
							<td><?php _e( 'Log link creation activity on BuddyPress feed', 'link-library' ); ?></td>
							<td>
								<input type="checkbox" id="bp_log_activity" name="bp_log_activity" <?php checked( $genoptions['bp_log_activity'] ); ?> />
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Link Page URL (relative or absolute)', 'link-library' ); ?></td>
							<td>
								<input type="text" id="bp_link_page_url" name="bp_link_page_url" size="60" value="<?php echo $genoptions['bp_link_page_url']; ?>" />
							</td>
						</tr>
						<tr>
							<td><?php _e( 'Library Configuration used for Links page', 'link-library' ); ?></td>
							<td>
								<SELECT id="bp_link_settings" name="bp_link_settings" style='width: 300px'>
									<option>Select a library configuration</option>
									<?php if ( empty( $genoptions['numberstylesets'] ) ) {
										$numberofsets = 1;
									} else {
										$numberofsets = $genoptions['numberstylesets'];
									}
									for ( $counter = 1; $counter <= $numberofsets; $counter ++ ): ?>
										<?php $tempoptionname = "LinkLibraryPP" . $counter;
										$tempoptions          = get_option( $tempoptionname ); ?>
										<option value="<?php echo $counter ?>" <?php selected( $genoptions['bp_link_settings'], $counter ); ?>><?php _e( 'Library', 'link-library' ); ?> <?php echo $counter ?><?php if ( ! empty( $tempoptions ) && isset( $tempoptions['settingssetname'] ) ) {
												echo " (" . stripslashes( $tempoptions['settingssetname'] ) . ")";
											} ?></option>
									<?php endfor; ?>
								</SELECT>
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr /></td>
						</tr>
						<tr class="captchagenerator">
							<td><?php _e( 'Captcha generator' ); ?>
							</td>
							<td>
								<select id="captchagenerator" name="captchagenerator">
									<?php $captcha_generators = array( 'easycaptcha' => 'Easy Captcha', 'recaptcha' => 'Google reCAPTCHA' );

									foreach ( $captcha_generators as $key => $captcha_generator ) { ?>
									<option value="<?php echo $key; ?>" <?php selected( $genoptions['captchagenerator'], $key ); ?>><?php echo $captcha_generator; ?>
										<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="lltooltip" title="<?php _e( 'Sign up for the reCAPTCHA service before activating this feature to get your Site and Secret Keys', 'link-library' ); ?>"><?php _e( 'Google reCAPTCHA Site Key' ); ?>
							</td>
							<td class="lltooltip" title="<?php _e( 'Sign up for the reCAPTCHA service before activating this feature to get your Site and Secret Keys', 'link-library' ); ?>">
								<input type="text" id="recaptchasitekey" name="recaptchasitekey" size="60" value="<?php echo $genoptions['recaptchasitekey']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="lltooltip" title="<?php _e( 'Sign up for the reCAPTCHA service before activating this feature to get your Site and Secret Keys', 'link-library' ); ?>"><?php _e( 'Google reCAPTCHA Secret Key' ); ?>
							</td>
							<td class="lltooltip" title="<?php _e( 'Sign up for the reCAPTCHA service before activating this feature to get your Site and Secret Keys', 'link-library' ); ?>">
								<input type="text" id="recaptchasecretkey" name="recaptchasecretkey" size="60" value="<?php echo $genoptions['recaptchasecretkey']; ?>" />
							</td>
						</tr>
						<tr>
							<td colspan="2"><hr /></td>
						</tr>
						<tr>
							<td><?php _e( 'Links Date Format', 'link-library' ); ?> (<a target="datehelp" href="https://codex.wordpress.org/Formatting_Date_and_Time"><?php _e( 'Help', 'link-library' ); ?></a>)
							</td>
							<td>
								<input type="text" id="links_updated_date_format" name="links_updated_date_format" size="20" value="<?php echo get_option( 'links_updated_date_format' ); ?>" />
							</td>
						</tr>
						<tr>
							<td class='lltooltip' title='<?php _e( 'Enter list of additional link protocols, seperated by commas', 'link-library' ); ?>'><?php _e( 'Additional protocols', 'link-library' ); ?></td>
							<td class='lltooltip' title='<?php _e( 'Enter list of additional link protocols, seperated by commas', 'link-library' ); ?>'><input type="text" id="extraprotocols" name="extraprotocols" size="20" value="<?php echo $genoptions['extraprotocols']; ?>" /></td>
						</tr>
						<tr>
							<td><?php _e( 'Time before clearing RSS display cache (in seconds)', 'link-library' ); ?></td>
							<td>
								<input type="text" id="rsscachedelay" name="rsscachedelay" size="5" value="<?php echo intval( $genoptions['rsscachedelay'] ); ?>" /></td>
						</tr>
					</table>
				</td>
				<?php if ( isset( $genoptions['hidedonation'] ) && !$genoptions['hidedonation'] ) { ?>
				<td style='padding: 8px; border: 1px solid #cccccc;vertical-align:top !important;'>

						<div style="width: 400px"><h3>Support the author - Second Edition</h3><br />
							<table>
								<tr>
									<td>
										<a href="http://www.packtpub.com/wordpress-plugin-development-cookbook/book"><img src='<?php echo plugins_url( 'icons/7683os_cover_small.jpg', __FILE__ ); ?>'>
									</td>
									<td></a>The second edition of my plugin development cookbook is now available. Learn how to create your own plugins with my book.<br /><br />Order now!<br /><br /><a href="https://www.packtpub.com/web-development/wordpress-plugin-development-cookbook-second-edition">Packt Publishing</a><br /><a href="https://amzn.to/2s1U7GP">Amazon.com</a><br /><a href="https://www.amazon.ca/WordPress-Plugin-Development-Cookbook-powerful-ebook/dp/B073V39F6X/ref=sr_1_2?ie=UTF8&qid=1526738915&sr=8-2&keywords=wordpress+plugin+development+cookbook">Amazon.ca</a>
									</td>
								</tr>
							</table>
						</div><br /><br />

					<a href="https://accessibe.go2cloud.org/SHL"><img src='<?php echo plugins_url( 'icons/Accessibe.png', __FILE__ ); ?>'>
				</td>
				<?php } ?>
		</table>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('.lltooltip').each(function () {
						jQuery(this).tipTip();
					}
				);

				jQuery("#thumbnailgenerator").change(function () {
					jQuery(".thumbshotsapikey").hide();
					jQuery(".robothumbsize").hide();
					jQuery(".pagepeekerid").hide();
					jQuery(".pagepeekersizes").hide();
					jQuery(".shrinkthewebsizes").hide();
					jQuery(".shrinkthewebaccesskey").hide();

					if ( jQuery( '#thumbnailgenerator').val() == 'thumbshots' ) {
						jQuery(".thumbshotsapikey").show();
					} else if ( jQuery( '#thumbnailgenerator').val() == 'robothumb' ) {
						jQuery(".robothumbsize").show();
					} else if ( jQuery( '#thumbnailgenerator').val() == 'pagepeeker' ) {
						jQuery(".pagepeekerid").show();
						jQuery(".pagepeekersizes").show();
					} else if ( jQuery( '#thumbnailgenerator').val() == 'shrinktheweb' ) {
						jQuery(".shrinkthewebsizes").show();
						jQuery(".shrinkthewebaccesskey").show();
					}
				});
			});
		</script>
	<?php
	}

	function general_singleitemlayout_meta_box( $data ) {
		$genoptions  = $data['genoptions'];
		?>
		<div style='padding-top:15px' id="ll-singleitem" class="content-section">
			<p>This section allows you to specify a template for individual link pages. These individual pages will be shown to visitors if you set the Link Source to Dedicated Page under the Advanced settings of Library Configurations and allow users to create a more complete internal page describing an external link, before visitors go to this external page.</p>
			<?php
			$editorsettings = array( 'media_buttons' => false,
			                         'textarea_rows' => 15,
			                         'textarea_name' => 'single_link_layout',
			                         'wpautop' => false );

			wp_editor( isset( $genoptions['single_link_layout'] ) ? stripslashes( $genoptions['single_link_layout'] ) : '', 'single_link_layout', $editorsettings ); ?>
			<p>The codes that are available to put in this layout template are:</p>
			<table>
				<tr>
					<th>Tag Name</th>
					<th>Description</th>
				</tr>
				<tr>
					<td>[link_content]</td>
					<td>Text added in the new full-page content field of the link editor</td>
				</tr>
				<tr>
					<td>[link_title]</td>
					<td>The name of the link, text only</td>
				</tr>
				<tr>
					<td>[link]</td>
					<td>Link title, with link tag and link url</td>
				</tr>
				<tr>
					<td>[link_address]</td>
					<td>Link URL only, without link tag</td>
				</tr>
				<tr>
					<td>[link_description]</td>
					<td>The link description</td>
				</tr>
				<tr>
					<td>[link_large_description]</td>
					<td>The link large description</td>
				</tr>
				<tr>
					<td>[link_category]</td>
					<td>Category or categories that are assigned to link, listed in alphabetical order and separated with commas</td>
				</tr>
				<tr>
					<td>[link_image]</td>
					<td>Link image URL. You need to add img src tag or other code to display image.</td>
				</tr>
				<tr>
					<td>[link_email]</td>
					<td>Link e-mail</td>
				</tr>
				<tr>
					<td>[link_telephone]</td>
					<td>Link telephone number</td>
				</tr>
				<tr>
					<td>[link_price_or_free]</td>
					<td>Display link price, or the word Free if the price is 0</td>
				</tr>
			</table>
		</div>
		<?php
	}

	function general_image_meta_box( $data ) {
		$genoptions = $data['genoptions'];
		?>
		<div style='padding-top:15px' id="ll-images" class="content-section">
		<table>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Custom full URL for expand icon. Uses default image if left empty.', 'link-library' ); ?>'><?php _e( 'Expand Icon Image', 'link-library' ); ?></td>
				<td colspan='4' class='lltooltip' title='<?php _e( 'Custom full URL for expand icon. Uses default image if left empty.', 'link-library' ); ?>'>
					<input type="text" id="expandiconpath" name="expandiconpath" style="width:100%" value="<?php if ( isset( $genoptions['expandiconpath'] ) ) {
						echo $genoptions['expandiconpath'];
					} ?>" /></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Custom full URL for collapse icon. Uses default image if left empty.', 'link-library' ); ?>'><?php _e( 'Collapse Icon Image', 'link-library' ); ?></td>
				<td colspan='4' class='lltooltip' title='<?php _e( 'Custom full URL for collapse icon. Uses default image if left empty.', 'link-library' ); ?>'>
					<input type="text" id="collapseiconpath" name="collapseiconpath" style="width:100%" value="<?php if ( isset( $genoptions['collapseiconpath'] ) ) {
						echo $genoptions['collapseiconpath'];
					} ?>" /></td>
			</tr>
		</table>
		</div>
	<?php
	}

	function general_meta_bookmarklet_box( $data ) {
		$bookmarkletcode = 'javascript:void(linkmanpopup=window.open(\'' . get_bloginfo( 'wpurl' ) . '/wp-admin/post-new.php?post_type=link_library_links&action=popup&linkurl=\'+escape(location.href)+\'&post_title=\'+(document.title),\'LinkManager\',\'scrollbars=yes,width=900px,height=600px,left=15,top=15,status=yes,resizable=yes\'));linkmanpopup.focus();window.focus();linkmanpopup.focus();';
		?>
		<div style='padding-top:15px' id="ll-bookmarklet" class="content-section">
		<p><?php _e( 'Add new links to your site with this bookmarklet.', 'link-library' ); ?></p>
		<p><?php _e( 'To use this feature, drag-and-drop the button below to your favorite / bookmark toolbar.', 'link-library' ); ?></p>
		<a href="<?php echo $bookmarkletcode; ?>" class='button' title="<?php _e( 'Add to Links', 'link-library' ); ?>"><?php _e( 'Add to Links', 'link-library' ); ?></a>
		</div>

	<?php
	}

	function general_moderation_meta_box( $data ) {
		$genoptions = $data['genoptions'];
		?>
		<div style='padding-top:15px' id="ll-moderation" class="content-section">
		<table>
			<tr>
				<td colspan="2">
					<strong><?php _e( 'Approval and rejection e-mail functionality will only work correctly if the submitter e-mail field is displayed on the user link submission form', 'link-library' ); ?></strong>
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'URL that user will be redirected to after submitting new link. When used, the short code [link-library-addlinkcustommsg] should be placed on the destination page.', 'link-library' ); ?>.' style='width:250px'><?php _e( 'Link Acknowledgement URL', 'link-library' ); ?></td>
				<td class='lltooltip' style='width:75px;padding-right:20px' title='<?php _e( 'URL that user will be redirected to after submitting new link. When used, the short code [link-library-addlinkcustommsg] should be placed on the destination page.', 'link-library' ); ?>.'>
					<input type="text" id="linksubmissionthankyouurl" name="linksubmissionthankyouurl" size="60" value='<?php echo $genoptions['linksubmissionthankyouurl']; ?>' />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Title of e-mail sent to site admin when new links are submitted. Use %linkname% as a variable to be replaced by the actual link name', 'link-library' ); ?>.' style='width:250px'><?php _e( 'Moderation Notification Title', 'link-library' ); ?></td>
				<td style='width:75px;padding-right:20px'>
					<input type="text" id="moderationnotificationtitle" name="moderationnotificationtitle" size="60" value='<?php echo $genoptions['moderationnotificationtitle']; ?>' />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Will send a confirmation e-mail to link submitter if they provided their contact information', 'link-library' ); ?>.' style='width:250px'><?php _e( 'E-mail submitter on link approval or rejection', 'link-library' ); ?></td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="emaillinksubmitter" name="emaillinksubmitter" <?php checked( $genoptions['emaillinksubmitter'] ); ?>/></td>
			</tr>
			<tr>
				<td class='lltooltip' style='width:250px'><?php _e( 'Suppress Link Library message in e-mail footer', 'link-library' ); ?></td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="suppressemailfooter" name="suppressemailfooter" <?php checked( $genoptions['suppressemailfooter'] ); ?>/></td>
			</tr>
			<tr>
				<td style='width:250px'><?php _e( 'Only use first part of submitter name', 'link-library' ); ?></td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="usefirstpartsubmittername" name="usefirstpartsubmittername" <?php checked( $genoptions['usefirstpartsubmittername'] ); ?>/></td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'The name of the e-mail account that the approval e-mail will be sent from', 'link-library' ); ?>'><?php _e( 'Moderator Name', 'link-library' ); ?></td>
				<td>
					<input type="text" id="moderatorname" name="moderatorname" size="60" value="<?php echo $genoptions['moderatorname']; ?>" />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'The e-mail address that the approval e-mail will be sent from', 'link-library' ); ?>'><?php _e( 'Moderator E-mail', 'link-library' ); ?></td>
				<td>
					<input type="text" id="moderatoremail" name="moderatoremail" size="60" value="<?php echo $genoptions['moderatoremail']; ?>" />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Title of approval e-mail. Use %linkname% as a variable to be replaced by the actual link name', 'link-library' ); ?>'><?php _e( 'Approval e-mail title', 'link-library' ); ?></td>
				<td>
					<input type="text" id="approvalemailtitle" name="approvalemailtitle" size="60" value="<?php echo $genoptions['approvalemailtitle']; ?>" />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Body of approval e-mail. Use %linkname% as a variable to be replaced by the actual link name, %submittername% for the submitter name and %linkurl% for the link address', 'link-library' ); ?>'><?php _e( 'Approval e-mail body', 'link-library' ); ?></td>
				<td>
					<textarea id="approvalemailbody" name="approvalemailbody" cols="60"><?php echo stripslashes( $genoptions['approvalemailbody'] ); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Title of rejection e-mail. Use %linkname% as a variable to be replaced by the actual link name', 'link-library' ); ?>'><?php _e( 'Rejection e-mail title', 'link-library' ); ?></td>
				<td>
					<input type="text" id="rejectedemailtitle" name="rejectedemailtitle" size="60" value="<?php echo $genoptions['rejectedemailtitle']; ?>" />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Body of rejection e-mail. Use %linkname% as a variable to be replaced by the actual link name, %submittername% for the submitter name and %linkurl% for the link address', 'link-library' ); ?>'><?php _e( 'Rejection e-mail body', 'link-library' ); ?></td>
				<td>
					<textarea id="rejectedemailbody" name="rejectedemailbody" cols="60"><?php echo stripslashes( $genoptions['rejectedemailbody'] ); ?></textarea>
				</td>
			</tr>
		</table>
		</div>
	<?php
	}

	function general_hide_donation_meta_box() {
	?>
	<div style='padding-top:15px' id="ll-hidedonation" class="content-section">
		<p><?php _e( 'The following option allows you to hide the Donate button and Support the Author section in the Link Library Admin pages. If you enjoy this plugin and use it regularly, please consider making a donation to the author before turning off these messages. This menu section will disappear along with the other elements.', 'link-library' ); ?></p>
		<table>
			<tr>
				<td class='lltooltip'><?php _e( 'Hide Donation and Support Links', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="hidedonation" name="hidedonation" <?php checked( isset( $genoptions['hidedonation'] ) && $genoptions['hidedonation'] ); ?>/></td>
			</tr>
		</table>
	</div>
	<?php
	}

	function general_importexport_meta_box() {
		require_once plugin_dir_path( __FILE__ ) . 'wp_dropdown_posts.php';
		?>
		<div style='padding-top:15px' id="ll-importexport" class="content-section">
			<table>
				<tr>
					<td><?php _e( 'Export all links to a CSV file', 'link-library' ); ?></td>
					<td>
						<input type="submit" id="exportalllinks" name="exportalllinks" value="<?php _e( 'Export All Links', 'link-library' ); ?>" />
					</td>
				</tr>
			</table>
			<hr />
			<table>
				<tr>
					<td class='lltooltip' title='<?php _e( 'Allows for links to be added in batch to the Wordpress links database. CSV file needs to follow template for column layout.', 'link-library' ); ?>' style='width: 330px'><?php _e( 'CSV file to upload to import links', 'link-library' ); ?> (<a href="<?php echo plugins_url( 'importtemplate.csv', __FILE__ ); ?>"><?php _e( 'file template', 'link-library' ); ?></a>)
					</td>
					<td><input size="80" name="linksfile" type="file" /></td>
					<td><input type="submit" name="importlinks" value="<?php _e( 'Import Links from CSV', 'link-library' ); ?>" />
					</td>
				</tr>
				<tr>
					<td><?php _e( 'Update items when URL is identical', 'link-library' ); ?></td>
					<td><input type="checkbox" id="updatesameurl" name="updatesameurl" checked="checked" /></td>
				</tr>
			</table>
			<hr />

			<table>
				<tr>
					<td style='width: 230px'><?php _e( 'Import links from site pages', 'link-library' ); ?></td>
					<td style='width: 350px'><input type="radio" name="siteimportlinksscope" value="allpagesposts" checked> <?php _e( 'All Pages and Posts', 'link-library' ); ?><br />
						<input type="radio" name="siteimportlinksscope" value="allpagespostscpt"> <?php _e( 'All Pages, Posts and Custom Post Types', 'link-library' ); ?><br />
						<input type="radio" name="siteimportlinksscope" value="specificpage"> <?php _e( 'Specific Page', 'link-library' ); ?>
						<?php wp_dropdown_pages(); ?><br />
						<?php $post_count = wp_count_posts();
						if ( $post_count->publish < 200 ) { ?>
						<input type="radio" name="siteimportlinksscope" value="specificpost"> <?php _e( 'Specific Post', 'link-library' ); ?>
						<?php wp_dropdown_posts(); ?><br />
						<?php }
							$site_post_types = get_post_types( array( '_builtin' => false ) );
							if ( !empty( $site_post_types ) ) {
								foreach( $site_post_types as $site_post_type ) {
									if ( 'link_library_links' != $site_post_type ) {
										$any_posts = get_posts( array( 'post_type' => $site_post_type ) );
										if ( !empty( $any_posts ) && count( $any_posts ) < 200 ) {
											if ( !empty( $any_posts ) ) {
												$post_type_data = get_post_type_object( $site_post_type ); ?>

												<input type="radio" name="siteimportlinksscope" value="specific<?php echo $site_post_type; ?>"> <?php _e( 'Specific ' . $post_type_data->labels->singular_name, 'link-library' ); ?>
												<?php wp_dropdown_posts( array( 'post_type' => $site_post_type, 'select_name' => $site_post_type . '_id' ) ); ?><br /><br />
											<?php } }
									}
								}
							}
						?>
						<input type="checkbox" id="siteimportupdatesameurl" name="siteimportupdatesameurl" checked="checked" /> <?php _e( 'Update items when URL is identical', 'link-library' ); ?><br />

						<?php

						$linkcats = get_terms( 'link_library_category', array( 'hide_empty' => false ) );

						if ( $linkcats ) { ?>
							Category for new links <select name="siteimportcat" id="siteimportcat">
								<?php foreach ( $linkcats as $linkcat ) { ?>
									<option value="<?php echo $linkcat->term_id; ?>"><?php echo $linkcat->name; ?></option>
								<?php } ?>
							</select>
						<?php } ?>
					</td>
					<td><input type="submit" name="siteimport" value="<?php _e( 'Import Links from Site', 'link-library' ); ?>" /></td>
				</tr>
			</table>
		</div>
		<?php
	}

	function general_save_meta_box() {
		?>
		<div class="submitbox" style="padding-top: 15px;clear:both">
			<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Settings', 'link-library' ); ?>" />
		</div>
	<?php
	}

	function settingssets_save_meta_box() {
		?>

		<div class="submitbox">
			<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Update Settings', 'link-library' ); ?>" />
		</div>
	<?php
	}

	function moderate_meta_box() {
		$genoptions = get_option( 'LinkLibraryGeneral' );
		?>
		<table class='widefat' style='clear:none;width:100%;background-color:#F1F1F1;background-image: linear-gradient(to top, #ECECEC, #F9F9F9);background-position:initial initial;background-repeat: initial initial'>
			<tr>
				<th style='width: 30px'></th>
				<th style='width: 200px'><?php _e( 'Link Name', 'link-library' ); ?></th>
				<th style='width: 200px'><?php _e( 'Link Category', 'link-library' ); ?></th>
				<th style='width: 200px'><?php _e( 'Link Tags', 'link-library' ); ?></th>
				<th style='width: 300px'><?php _e( 'Link URL', 'link-library' ); ?></th>
				<th><?php _e( 'Link Description', 'link-library' ); ?></th>
			</tr>
			<?php
			$links_query_args = array( 'post_type' => 'link_library_links', 'posts_per_page' => -1, 'post_status' => 'pending' );

			$links_to_export = new WP_Query( $links_query_args );

			if ( $links_to_export->have_posts() ) {
				while ( $links_to_export->have_posts() ) {
					$links_to_export->the_post();

					$link_url = esc_url( get_post_meta( get_the_ID(), 'link_url', true ) );
					$link_description = esc_html( get_post_meta( get_the_ID(), 'link_description', true ) );
					$link_categories = wp_get_post_terms( get_the_ID(), 'link_library_category' );
					$link_cat_string = '';
					if ( !empty( $link_categories ) ) {
						$link_cat_array = array();
						foreach ( $link_categories as $link_category ) {
							$link_cat_array[] = $link_category->name;
						}
						if ( !empty( $link_cat_array ) ) {
							$link_cat_string = implode( ', ', $link_cat_array );
						}
					} else {
						$link_cat_string = 'None Assigned';
					}

					$link_tags = wp_get_post_terms( get_the_ID(), 'link_library_tags' );
					$link_tags_string = '';
					if ( !empty( $link_tags ) ) {
						$link_tags_array = array();
						foreach ( $link_tags as $link_tag ) {
							$link_tags_array[] = $link_tag->name;
						}
						if ( !empty( $link_tags_array ) ) {
							$link_tags_string = implode( ', ', $link_tags_array );
						}
					} else {
						$link_tags_string = 'None Assigned';
					}

					?>
					<tr style='background: #FFF'>
						<td><input type="checkbox" name="links[]" value="<?php echo get_the_ID(); ?>" /></td>
						<td><?php echo "<a title='Edit Link: " . get_the_title() . "' href='" . esc_url( add_query_arg( array( 'action' => 'edit', 'post' => get_the_ID() ), admin_url( 'post.php' ) ) ) . "'>" . get_the_title() . "</a>"; ?></td>
						<td><?php echo $link_cat_string; ?></td>
						<td><?php echo $link_tags_string; ?></td>
						<td><?php echo "<a href='" . $link_url . "'>" . $link_url . "</a>"; ?></td>
						<td><?php echo $link_description; ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td></td>
					<td><?php _e( 'No Links Found to Moderate', 'link-library' ); ?></td>
					<td></td>
					<td></td>
				</tr>
			<?php }

			wp_reset_postdata();
			?>

		</table><br />
		<input type="button" id="CheckAll" value="<?php _e( 'Check All', 'link-library' ); ?>">
		<input type="button" id="UnCheckAll" value="<?php _e( 'Uncheck All', 'link-library' ); ?>">

		<input type="submit" name="approvelinks" value="<?php _e( 'Approve Selected Items', 'link-library' ); ?>" />
		<input type="submit" name="deletelinks" value="<?php _e( 'Delete Selected Items', 'link-library' ); ?>" />

		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#CheckAll').click(function () {
					jQuery("INPUT[type='checkbox']").attr('checked', true);
				});

				jQuery('#UnCheckAll').click(function () {
					jQuery("INPUT[type='checkbox']").attr('checked', false);
				});
			});
		</script>

		</div>

	<?php
	}

	function stylesheet_meta_box( $data ) {
		$genoptions = $data['genoptions'];
		?>

		<?php _e( 'If the stylesheet editor is empty after upgrading, reset to the default stylesheet using the button below or copy/paste your backup stylesheet into the editor.', 'link-library' ); ?>
		<br /><br />

		<textarea name='fullstylesheet' id='fullstylesheet' style='font-family:Courier' rows="30" cols="100"><?php echo stripslashes( $genoptions['fullstylesheet'] ); ?></textarea>
		<div>
			<input type="submit" name="submitstyle" value="<?php _e( 'Submit', 'link-library' ); ?>" /><span style='padding-left: 650px'><input type="submit" name="resetstyle" value="<?php _e( 'Reset to default', 'link-library' ); ?>" /></span>
		</div>
	<?php
	}

	function settingssets_selection_meta_box( $data ) {
		$options    = $data['options'];
		$settings   = $data['settings'];
		$genoptions = $data['genoptions'];
		?>
		<div>
		<?php _e( 'Select Current Library Settings', 'link-library' ); ?> :
		<SELECT id="settingsetlist" name="settingsetlist" style='width: 300px'>
			<?php if ( empty( $genoptions['numberstylesets'] ) ) {
				$numberofsets = 1;
			} else {
				$numberofsets = $genoptions['numberstylesets'];
			}
			for ( $counter = 1; $counter <= $numberofsets; $counter ++ ): ?>
				<?php $tempoptionname = "LinkLibraryPP" . $counter;
				$tempoptions          = get_option( $tempoptionname ); ?>
				<option value="<?php echo $counter ?>" <?php selected( $settings == $counter ); ?>><?php _e( 'Library', 'link-library' ); ?> <?php echo $counter ?><?php if ( ! empty( $tempoptions ) && isset( $tempoptions['settingssetname'] ) ) {
						echo " (" . stripslashes( $tempoptions['settingssetname'] ) . ")";
					} ?></option>
			<?php endfor; ?>
		</SELECT>
		<INPUT type="button" name="go" value="<?php _e( 'Go', 'link-library' ); ?>!" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=' + jQuery('#settingsetlist').val()">
		<?php if ( $numberofsets > 1 ): ?>
			<?php _e( 'Copy from:', 'link-library' ); ?>
			<SELECT id="copysource" name="copysource" style='width: 300px'>
				<?php for ( $counter = 1; $counter <= $numberofsets; $counter ++ ): ?>
					<?php $tempoptionname = "LinkLibraryPP" . $counter;
					$tempoptions          = get_option( $tempoptionname );
					if ( $counter != $settings ):?>
						<option value="<?php echo $counter ?>" <?php selected( $settings == $counter ); ?>><?php _e( 'Library', 'link-library' ); ?> <?php echo $counter ?><?php if ( $tempoptions != "" ) {
								echo " (" . stripslashes( $tempoptions['settingssetname'] ) . ")";
							} ?></option>
					<?php endif;
				endfor;
				?>
			</SELECT>
			<?php $copypath = "'admin.php?page=link-library-settingssets&settings=" . $settings . "&settingscopy=" . $settings . "&source=' + jQuery('#copysource').val();"; ?>
			<INPUT type="button" name="copy" value="<?php _e( 'Copy', 'link-library' ); ?>!" onClick="if (confirm('Are you sure you want to copy the contents of the selected library over the current library settings?')) { var copyurl = <?php echo $copypath; ?> window.location.href = copyurl; };">
		<?php endif; ?>
		</div>
	<?php }

	function display_accessibe_ad() { ?>
		<div class="accessibebanner"><a href="https://accessibe.go2cloud.org/SHL"><img src='<?php echo plugins_url( 'icons/AccessibeBanner.png', __FILE__ ); ?>'></a></div>
	<?php }

	function display_accessibe_page( $data ) { ?>

	<div class="accessibead" style="width: 50%; background-color: #fff; padding: 20px; text-align: center">
		<img src="<?php echo plugins_url( 'icons/AccessibeLogoLarge.png', __FILE__ ) ?>" style="max-width: 100%" />
		<h1 style="font-size: 30px">The Forefront of <strong>Web Accessibility</strong> Technology</h1>
		accessiBe is the first and only fully automated web accessibility technology that complies with the WCAG 2.1 and keeps your website accessible at all times.<br /><br />

		<a href="https://accessibe.go2cloud.org/SHL"><div class="button button-primary"><span class="large_text">Get started now</span><br />7-day FREE trial</div></a>
		<a href="https://accessibe.go2cloud.org/aff_c?offer_id=5&aff_id=8&url_id=7"><div class="button button-primary"><span class="large_text">FREE accesssibility test</span></div></a>
	</div>

	<?php }

	function settingssets_usage_meta_box( $data ) {
		$options    = $data['options'];
		$settings   = $data['settings'];
		$genoptions = $data['genoptions'];
		?>
		<div style='padding-top:15px' id="ll-usage" class="content-section">
			<table class='widefat' style='clear:none;width:100%;background-color:#F1F1F1;background-image: linear-gradient(to top, #ECECEC, #F9F9F9);background-position:initial initial;background-repeat: initial initial'>
				<thead>
				<tr>
					<th style='width:80px' class="lltooltip" title='<?php _e( 'Link Library Supports the Creation of an unlimited number of configurations to display link lists on your site', 'link-library' ); ?>'>
						<?php _e( 'Library #', 'link-library' ); ?>
					</th>
					<th style='width:130px' class="lltooltip" title='<?php _e( 'Link Library Supports the Creation of an unlimited number of configurations to display link lists on your site', 'link-library' ); ?>'>
						<?php _e( 'Library Name', 'link-library' ); ?>
					</th>
					<th style='width: 230px'><?php _e( 'Feature', 'link-library' ); ?></th>
					<th class="lltooltip" title='<?php _e( 'Link Library Supports the Creation of an unlimited number of configurations to display link lists on your site', 'link-library' ); ?>'>
						<?php _e( 'Code to insert on a Wordpress page', 'link-library' ); ?>
					</th>
					<th>Optional Parameters</th>
				</tr>
				</thead>
				<tr>
					<td style='background: #FFF'><?php echo $settings; ?></td>
					<td style='background: #FFF'><?php echo stripslashes( $options['settingssetname'] ); ?></a></td>
					<td style='background: #FFF'><?php _e( 'Display basic link library', 'link-library' ); ?></td>
					<td style='background: #FFF'><?php echo "[link-library settings=" . $settings . "]"; ?></td>
					<td style='background: #FFF'><table style="padding-left:16px;">
							<tr>
								<td><strong>Parameter</strong></td><td><strong>Description</strong></td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" categorylistoverride="28"]</td>
								<td>Overrides the list of categories to be displayed in the link list, comma-separated list of category IDs</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" excludecategoryoverride="28"]</td>
								<td>Overrides the list of categories to be excluded in the link list, comma-separated list of category IDs</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" notesoverride=0]</td>
								<td>Set to 0 or 1 to display or not display link notes</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" descoverride=0]</td>
								<td>Set to 0 or 1 to display or not display link descriptions</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" rssoverride=0]</td>
								<td>Set to 0 or 1 to display or not display rss information</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" tableoverride=0]</td>
								<td>Set to 0 or 1 to display links in an unordered list or a table</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" maxlinksoverride=5]</td>
								<td>Overrides the max number of links to be displayed</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" linkorderoverride="date"]</td>
								<td>Changes the link display order. Valid values are 'name', 'id', 'random', 'date', 'hits', 'scpo'</td>
							</tr>
							<tr>
								<td>[link-library settings="<?php echo $settings; ?>" linkdirectionoverride="date"]</td>
								<td>Changes the order in which links are displayed. Valid values are 'ASC', 'DESC'</td>
							</tr>
						</table></td>
				</tr>
				<tr>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'><?php _e( 'Display list of link categories', 'link-library' ); ?></td>
					<td style='background: #FFF'><?php echo "[link-library-cats settings=" . $settings . "]"; ?></td>
					<td style='background: #FFF'>
						<table style="padding-left:16px;">
							<tr>
								<td><strong>Parameter</strong></td><td><strong>Description</strong></td>
							</tr>
							<tr>
								<td>[link-library-cats settings="<?php echo $settings; ?>" categorylistoverride="28"]</td>
								<td>Overrides the list of categories to be displayed in the category list, comma-separated list of category IDs</td>
							</tr>
							<tr>
								<td>[link-library-cats settings="<?php echo $settings; ?>" excludecategoryoverride="28"]</td>
								<td>Overrides the list of categories to be excluded in the category list, comma-separated list of category IDs</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'><?php _e( 'Display search box', 'link-library' ); ?></td>
					<td style='background: #FFF'><?php echo "[link-library-search settings=" . $settings . "]"; ?></td>
					<td style='background: #FFF'></td>
				</tr>
				<tr>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'><?php _e( 'Display link submission form', 'link-library' ); ?></td>
					<td style='background: #FFF'><?php echo "[link-library-addlink settings=" . $settings . "]"; ?></td>
					<td style='background: #FFF'></td>
				</tr>
				<tr>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'></td>
					<td style='background: #FFF'><?php _e( 'Display count of links in a library', 'link-library' ); ?></td>
					<td style='background: #FFF'><?php echo "[link-library-count settings=" . $settings . "]"; ?></td>
					<td style='background: #FFF'></td>
				</tr>
			</table>
			<table>
				<tr>
					<td style='text-align:right'>
						<span><button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to Delete Library #'%s'\n  'Cancel' to stop, 'OK' to delete.", "link-library" ), $settings ) ) . "') ) window.location.href='" . wp_nonce_url( 'admin.php?page=link-library-settingssets&amp;deletesettings=' . $settings, 'link-library-delete' ) . "'\""; ?>><?php _e( 'Delete Library', 'link-library' ); ?> <?php echo $settings ?></button></span>
						<span><button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to reset Library '%s'\n  'Cancel' to stop, 'OK' to reset.", "link-library" ), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&reset=" . $settings . "'\""; ?>><?php _e( 'Reset current Library', 'link-library' ); ?></button></span>
					</td>
				</tr>
			</table>
		</div>
	<?php
	}

	function settingssets_presets_meta_box( $data ) {
		$options    = $data['options'];
		$settings   = $data['settings'];
		$genoptions = $data['genoptions'];
		?>
		<div style='padding-top:15px' id="ll-presets" class="content-section">
			<div style="text-align: center;float:left;padding:16px;" class="#preset1">
				<strong>Layout 1: Simple Unordered List</strong><br /><br />
				<img style="max-width: 400px; border: 2px solid black;" src="<?php echo plugins_url( "layoutimages/Layout1-SimpleListNamesOnly.png", __FILE__ ); ?>"<br /><br /><br />
				<button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to change the layout of Library '%s'\n  'Cancel' to stop, 'OK' to modify.", "link-library" ), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&newlayout=1'\""; ?>><?php _e( 'Apply Layout 1', 'link-library' ); ?> </button>
			</div>
			<div style="text-align: center;float:left;padding:16px;" class="#preset2">
				<strong>Layout 2: Unordered List with link descriptions</strong><br /><br />
				<img style="max-width: 400px; border: 2px solid black;" src="<?php echo plugins_url( "layoutimages/Layout2-SimpleListWithDesc.png", __FILE__ ); ?>"<br /><br /><br />
				<button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to change the layout of Library '%s'\n  'Cancel' to stop, 'OK' to modify.", "link-library" ), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&newlayout=2'\""; ?>><?php _e( 'Apply Layout 2', 'link-library' ); ?> </button>
			</div>
			<div style="text-align: center;float:left;padding:16px;" class="#preset3">
				<strong>Layout 3: Table with links and descriptions</strong><br /><br />
				<img style="max-width: 400px; border: 2px solid black;" src="<?php echo plugins_url( "layoutimages/Layout3-TableView.png", __FILE__ ); ?>"<br /><br /><br />
				<button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to change the layout of Library '%s'\n  'Cancel' to stop, 'OK' to modify.", "link-library" ), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&newlayout=3'\""; ?>><?php _e( 'Apply Layout 3', 'link-library' ); ?> </button>
			</div>
			<div style="text-align: center;float:left;padding:16px;" class="#preset4">
				<strong>Layout 4: Table with link images and descriptions</strong><br /><br />
				<img style="max-width: 400px; border: 2px solid black;" src="<?php echo plugins_url( "layoutimages/Layout4-TableWithImages.png", __FILE__ ); ?>"<br /><br /><br />
				<button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to change the layout of Library '%s'\n  'Cancel' to stop, 'OK' to modify.", "link-library" ), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&newlayout=4'\""; ?>><?php _e( 'Apply Layout 4', 'link-library' ); ?> </button>
			</div>
			<div style="text-align: center;float:left;padding:16px;" class="#preset4">
				<strong>Layout 5: Table with images separated from link info</strong><br /><br />
				<img style="max-width: 400px; border: 2px solid black;" src="<?php echo plugins_url( "layoutimages/Layout5-TableWithImagesInSplitCells.png", __FILE__ ); ?>"<br /><br /><br />
				<button type="button" <?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to change the layout of Library '%s'\n  'Cancel' to stop, 'OK' to modify.", "link-library" ), $settings ) ) . "') ) window.location.href='admin.php?page=link-library-settingssets&amp;settings=" . $settings . "&newlayout=5'\""; ?>><?php _e( 'Apply Layout 5', 'link-library' ); ?> </button>
			</div>
		</div>
	<?php }

	function render_category_list( $categories, $select_name, $depth, $selected_items, $order ) {

		$output = '';
		if ( !empty( $categories ) ) {
			if ( 0 == $depth ) {
				$output .= '<select style="width:100%" id="' . $select_name . '" name="' . $select_name . '[]" multiple ';
				if ( 1 == count( $selected_items ) && empty( $selected_items[0] ) ) {
					$output .= 'disabled';
				}
				$output .= '>';
			}

			foreach ( $categories as $category ) {
				$output .= '<option value="' . $category->term_id . '" ' . selected( in_array( $category->term_id, $selected_items ), true, false ) . ' >' . str_repeat( '&nbsp', 4 * $depth ) . $category->name . '</option>';
				$child_categories = get_terms( 'link_library_category', array( 'orderby' => 'name', 'parent' => $category->term_id, 'order' => $order, 'hide_empty' => false ) );

				if ( !empty( $child_categories ) ) {
					$output .= $this->render_category_list( $child_categories, $select_name, $depth + 1, $selected_items, $order );
				}
			}

			if ( 0 == $depth ) {
				$output .= '</select>';
			}

		} else {
			$output .= _e( 'No link categories! Create some!', 'link-library' );
		}

		return $output;
	}

	function settingssets_common_meta_box( $data ) {
		$options    = $data['options'];
		$settings   = $data['settings'];
		$genoptions = $data['genoptions'];
		?>

		<div style='padding-top: 15px' id="ll-common" class="content-section">
			<input type='hidden' value='<?php echo $settings; ?>' name='settingsetid' id='settingsetid' />
			<table>
				<tr>
					<td style='width: 300px;padding-right: 50px'>
						<?php _e( 'Current Library Name', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="settingssetname" name="settingssetname" size="40" value="<?php echo stripslashes( $options['settingssetname'] ); ?>" />
					</td>
				</tr>
				<tr>
					<td class="lltooltip" title="<?php _e( 'Leave Empty to see all categories', 'link-library' ); ?><br /><br /><?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric category IDs', 'link-library' ); ?><br /><br /><?php _e( 'To find the IDs, go to the Link Categories admin page. For example', 'link-library' ); ?>: 2,4,56">
						<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) {
							_e( 'Categories to be displayed (Empty=All)', 'link-library' );
						} else if ( $genoptions['catselectmethod'] == 'multiselectlist' ) {
							_e( 'Categories to be displayed', 'link-library' );
						} ?>
					</td>
					<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) { ?>
						<td class="lltooltip" title="<?php _e( 'Leave Empty to see all categories', 'link-library' ); ?><br /><br /><?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric category IDs', 'link-library' ); ?><br /><br /><?php _e( 'For example', 'link-library' ); ?>: 2,4,56">
							<input type="text" id="categorylist_cpt" name="categorylist_cpt" size="40" value="<?php echo $options['categorylist_cpt']; ?>" />
						</td>
					<?php
					} else {
						$top_categories = get_terms( 'link_library_category', array( 'orderby' => 'name', 'order' => $options['direction'], 'parent' => 0, 'hide_empty' => false ) );

						$categorylistarray = explode( ',', $options['categorylist_cpt'] );
						?>
						<td>
							<?php echo $this->render_category_list( $top_categories, 'categorylist_cpt', 0, $categorylistarray, $options['direction'] ); ?>
							<?php _e( 'Show all categories', 'link-library' ); ?>
							<input type="checkbox" id="nospecificcats" name="nospecificcats" <?php checked( empty( $options['categorylist_cpt'] ) ); ?>/>

						</td>
					<?php } ?>
					<td class="lltooltip" title="<?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric category IDs that should not be shown', 'link-library' ); ?><br /><br /><?php _e( 'For example', 'link-library' ); ?>: 5,34,43">
						<?php _e( 'Categories to be excluded', 'link-library' ); ?>
					</td>
					<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) { ?>
						<td class="lltooltip" title="<?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric category IDs that should not be shown', 'link-library' ); ?><br /><br /><?php _e( 'For example', 'link-library' ); ?>: 5,34,43">
							<input type="text" id="excludecategorylist_cpt" name="excludecategorylist_cpt" size="40" value="<?php echo $options['excludecategorylist_cpt']; ?>" />
						</td>
					<?php
					} else {
						$excludecategorylistarray = explode( ',', $options['excludecategorylist_cpt'] );
						?>
						<td>
							<?php echo $this->render_category_list( $top_categories, 'excludecategorylist_cpt', 0, $excludecategorylistarray, $options['direction'] ); ?><br />
							<?php _e( 'No Exclusions', 'link-library' ); ?>
							<input type="checkbox" id="noexclusions" name="noexclusions" <?php checked( empty( $options['excludecategorylist_cpt'] ) ); ?>/>

						</td>
					<?php } ?>
				</tr>
				<tr>
					<td class="lltooltip" title="<?php _e( 'Leave Empty to see all tags', 'link-library' ); ?><br /><br /><?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric tag IDs', 'link-library' ); ?><br /><br /><?php _e( 'To find the IDs, go to the Link Categories admin page. For example', 'link-library' ); ?>: 2,4,56">
						<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) {
							_e( 'Tags to be displayed (Empty=All)', 'link-library' );
						} else if ( $genoptions['catselectmethod'] == 'multiselectlist' ) {
							_e( 'Tags to be displayed', 'link-library' );
						} ?>
					</td>
					<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) { ?>
						<td class="lltooltip" title="<?php _e( 'Leave Empty to see all tags', 'link-library' ); ?><br /><br /><?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric tag IDs', 'link-library' ); ?><br /><br /><?php _e( 'For example', 'link-library' ); ?>: 2,4,56">
							<input type="text" id="taglist_cpt" name="taglist_cpt" size="40" value="<?php echo $options['taglist_cpt']; ?>" />
						</td>
						<?php
					} else {
						$top_tags = get_terms( 'link_library_tags', array( 'orderby' => 'name', 'order' => $options['direction'], 'parent' => 0, 'hide_empty' => false ) );

						$taglistarray = explode( ',', $options['taglist_cpt'] );
						?>
						<td>
							<?php echo $this->render_category_list( $top_tags, 'taglist_cpt', 0, $taglistarray, $options['direction'] ); ?>
							<?php _e( 'Show all tags', 'link-library' ); ?>
							<input type="checkbox" id="nospecifictags" name="nospecifictags" <?php checked( empty( $options['taglist_cpt'] ) ); ?>/>

						</td>
					<?php } ?>
					<td class="lltooltip" title="<?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric tag IDs that should not be shown', 'link-library' ); ?><br /><br /><?php _e( 'For example', 'link-library' ); ?>: 5,34,43">
						<?php _e( 'Tags to be excluded', 'link-library' ); ?>
					</td>
					<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) { ?>
						<td class="lltooltip" title="<?php _e( 'Enter list of comma-separated', 'link-library' ); ?><br /><?php _e( 'numeric tag IDs that should not be shown', 'link-library' ); ?><br /><br /><?php _e( 'For example', 'link-library' ); ?>: 5,34,43">
							<input type="text" id="excludetaglist_cpt" name="excludetaglist_cpt" size="40" value="<?php echo $options['excludetaglist_cpt']; ?>" />
						</td>
						<?php
					} else {
						$excludetaglistarray = explode( ',', $options['excludetaglist_cpt'] );
						?>
						<td>
							<?php echo $this->render_category_list( $top_tags, 'excludetaglist_cpt', 0, $excludetaglistarray, $options['direction'] ); ?><br />
							<?php _e( 'No Exclusions', 'link-library' ); ?>
							<input type="checkbox" id="notagexclusions" name="notagexclusions" <?php checked( empty( $options['excludetaglist_cpt'] ) ); ?>/>

						</td>
					<?php } ?>
				</tr>
				<tr>
					<td class="lltooltip" title="<?php _e( 'Only show one category of links at a time', 'link-library' ); ?>">
						<?php _e( 'Only show one category at a time', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title="<?php _e( 'Only show one category of links at a time', 'link-library' ); ?>">
						<input type="checkbox" id="showonecatonly" name="showonecatonly" <?php checked( $options['showonecatonly'] ); ?>/>
					</td>
					<td style='width: 200px' class="lltooltip" title="<?php _e( 'Select if AJAX should be used to only reload the list of links without reloading the whole page or HTML GET to reload entire page with a new link. The Permalinks option must be enabled for HTML GET + Permalink to work correctly.', 'link-library' ); ?>"><?php _e( 'Switching Method', 'link-library' ); ?></td>
					<td>
						<select name="showonecatmode" id="showonecatmode" style="width:200px;">
							<option value="AJAX"<?php selected( $options['showonecatmode'] == 'AJAX' || empty( $options['showonecatmode'] ) ); ?>>AJAX
							</option>
							<option value="HTMLGET"<?php selected( $options['showonecatmode'] == 'HTMLGET' ); ?>>HTML GET
							</option>
							<option value="HTMLGETSLUG"<?php selected( $options['showonecatmode'] == 'HTMLGETSLUG' ); ?>>HTML GET Using Slugs
							</option>
							<option value="HTMLGETCATNAME"<?php selected( $options['showonecatmode'] == 'HTMLGETCATNAME' ); ?>>HTML GET Using Category Name
							</option>
							<option value="HTMLGETPERM"<?php selected( $options['showonecatmode'] == 'HTMLGETPERM' ); ?>>HTML GET + Permalink
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Default category to be shown when only showing one at a time (numeric ID)', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="defaultsinglecat_cpt" name="defaultsinglecat_cpt" size="4" value="<?php echo $options['defaultsinglecat_cpt']; ?>" />
					</td>
					<td><?php _e( 'Hide category on start in single cat mode', 'link-library' ); ?></td>
					<td>
						<input type="checkbox" id="nocatonstartup" name="nocatonstartup" <?php checked( $options['nocatonstartup'] ); ?>/></td>
				</tr>
				<tr>
					<td class="lltooltip" title="<?php _e( 'File path is relative to Link Library plugin directory', 'link-library' ); ?>">
						<?php _e( 'Icon to display when performing AJAX queries', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title="<?php _e( 'File path is relative to Link Library plugin directory', 'link-library' ); ?>">
						<input type="text" id="loadingicon" name="loadingicon" size="40" value="<?php if ( empty( $options['loadingicon'] ) ) {
							echo '/icons/Ajax-loader.gif';
						} else {
							echo strval( $options['loadingicon'] );
						} ?>" />
					</td>
				</tr>
				<tr>
					<td class="lltooltip" title='<?php _e( 'Only show a limited number of links and add page navigation links', 'link-library' ); ?>'>
						<?php _e( 'Paginate Results', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title='<?php _e( 'Only show a limited number of links and add page navigation links', 'link-library' ); ?>'>
						<input type="checkbox" id="pagination" name="pagination" <?php checked( $options['pagination'] ); ?>/>
					</td>
					<td class="lltooltip" title="<?php _e( 'Number of Links to be Displayed per Page in Pagination Mode', 'link-library' ); ?>">
						<?php _e( 'Links per Page', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title="<?php _e( 'Number of Links to be Displayed per Page in Pagination Mode', 'link-library' ); ?>">
						<input type="text" id="linksperpage" name="linksperpage" size="3" value="<?php echo $options['linksperpage']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Pagination Position', 'link-library' ); ?>
					</td>
					<td>
						<select name="paginationposition" id="paginationposition" style="width:200px;">
							<option value="AFTER"<?php selected( $options['paginationposition'] == 'AFTER' || empty( $options['paginationposition'] ) ); ?>><?php _e( 'After Links', 'link-library' ); ?></option>
							<option value="BEFORE"<?php selected( $options['paginationposition'] == 'BEFORE' ); ?>><?php _e( 'Before Links', 'link-library' ); ?></option>
						</select>
					</td>
					<td>
						<?php _e( 'Hide Results if Empty', 'link-library' ); ?>
					</td>
					<td>
						<input type="checkbox" id="hide_if_empty" name="hide_if_empty" <?php checked( $options['hide_if_empty'] ); ?>/>
					</td>
				</tr>
				<tr>
					<td colspan="4"><hr /></td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Enable Permalinks', 'link-library' ); ?>
					</td>
					<td>
						<input type="checkbox" id="enablerewrite" name="enablerewrite" <?php checked( $options['enablerewrite'] ); ?>/>
					</td>
					<td>
						<?php _e( 'Permalinks Page', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="rewritepage" name="rewritepage" size="40" value="<?php echo $options['rewritepage']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Permalink Top Page Text', 'link-library' ); ?>
					</td>
					<td colspan="3" rows="8" cols="50">
						<textarea name="toppagetext" style="width:90%"><?php echo $options['toppagetext']; ?></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Category links in permalinks mode', 'link-library' ); ?>
					</td>
					<td>
						<input type="checkbox" id="catlinkspermalinksmode" name="catlinkspermalinksmode" <?php checked( $options['catlinkspermalinksmode'] ); ?>/>
					</td>
					<td>
						<?php _e( 'Show breadcrumbs in permalinks mode', 'link-library' ); ?>
					</td>
					<td>
						<input type="checkbox" id="showbreadcrumbspermalinks" name="showbreadcrumbspermalinks" <?php checked( $options['showbreadcrumbspermalinks'] ); ?>/>
					</td>
				</tr>
				<tr>
					<td colspan="4"><hr /></td>
				</tr>
				<tr>
					<td><?php _e( 'Display alphabetic cat filter', 'link-library' ); ?></td>
					<td><?php $letterfilteroptions = array( 'no' => __( 'Do not display', 'link-library' ), 'beforecats' => __( 'Before Categories', 'link-library' ), 'beforelinks' => __( 'Before Links', 'link-library' ), 'beforecatsandlinks' => __( 'Before Categories and Links', 'link-library' )  ); ?>
						<select name="cat_letter_filter" id="cat_letter_filter" style="width:200px;">
							<?php foreach ( $letterfilteroptions as $letterfilteroption => $letteroptiontext ) { ?>
							<option value="<?php echo $letterfilteroption; ?>" <?php selected( $options['cat_letter_filter'] == $letterfilteroption ); ?>><?php echo $letteroptiontext; ?></option>
							<?php } ?>
						</select>
					</td>
					<td><?php _e( 'Auto-select first alphabetic cat item', 'link-library' ); ?></td>
					<td><input type="checkbox" id="cat_letter_filter_autoselect" name="cat_letter_filter_autoselect" <?php checked( $options['cat_letter_filter_autoselect'] ); ?>/></td>
				</tr>
				<tr>
					<td><?php _e( 'Display ALL box in alphabetic cat filter', 'link-library' ); ?></td>
					<td><input type="checkbox" id="cat_letter_filter_showalloption" name="cat_letter_filter_showalloption" <?php checked( $options['cat_letter_filter_showalloption'] ); ?>/></td>
					<td><?php _e( 'Cat filter label', 'link-library' ); ?></td>
					<td><input type="text" id="catfilterlabel" name="catfilterlabel" size="20" value="<?php echo $options['catfilterlabel']; ?>" /></td>
				</tr>
				<tr>
					<td><?php _e( 'Only display links submitted by current user', 'link-library' ); ?></td>
					<td><input type="checkbox" id="current_user_links" name="current_user_links" <?php checked( $options['current_user_links'] ); ?>/></td>
					<td></td><td></td>
				</tr>
			</table>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#nospecificcats').click(function () {
					if (jQuery("#nospecificcats").is(":checked")) {
						jQuery('#categorylist_cpt').prop('disabled', 'disabled');
						jQuery("#categorylist_cpt").val([]);
					}
					else {
						jQuery('#categorylist_cpt').prop('disabled', false);
					}
				});
			});

			jQuery(document).ready(function () {
				jQuery('#noexclusions').click(function () {
					if (jQuery("#noexclusions").is(":checked")) {
						jQuery('#excludecategorylist_cpt').prop('disabled', 'disabled');
						jQuery("#excludecategorylist_cpt").val([]);
					}
					else {
						jQuery('#excludecategorylist_cpt').prop('disabled', false);
					}
				});
			});

			jQuery(document).ready(function () {
				jQuery('#nospecifictags').click(function () {
					if (jQuery("#nospecifictags").is(":checked")) {
						jQuery('#taglist_cpt').prop('disabled', 'disabled');
						jQuery("#taglist_cpt").val([]);
					}
					else {
						jQuery('#taglist_cpt').prop('disabled', false);
					}
				});
			});

			jQuery(document).ready(function () {
				jQuery('#notagexclusions').click(function () {
					if (jQuery("#notagexclusions").is(":checked")) {
						jQuery('#excludetaglist_cpt').prop('disabled', 'disabled');
						jQuery("#excludetaglist_cpt").val([]);
					}
					else {
						jQuery('#excludetaglist_cpt').prop('disabled', false);
					}
				});
			});
		</script>

	<?php
	}

	function settingssets_categories_meta_box( $data ) {
		$options    = $data['options'];
		$settings   = $data['settings'];
		$genoptions = $data['genoptions'];
		?>
		<div style='padding-top:15px' id="ll-categories" class="content-section">
			<table>
				<tr>
					<td>
						<?php _e( 'Results Order', 'link-library' ); ?>
					</td>
					<td>
						<select name="order" id="order" style="width:200px;">
							<option value="name"<?php selected( $options['order'] == 'name' ); ?>><?php _e( 'Order by Name', 'link-library' ); ?></option>
							<option value="id"<?php selected( $options['order'] == 'id' ); ?>><?php _e( 'Order by ID', 'link-library' ); ?></option>
							<option value="slug"<?php selected( $options['order'] == 'slug' ); ?>><?php _e( 'Order by Category Slug', 'link-library' ); ?></option>
							<?php if ( $genoptions['catselectmethod'] == 'commalist' || empty( $genoptions['catselectmethod'] ) ) { ?>
								<option value="catlist"<?php selected( $options['order'] == 'catlist' ); ?>><?php _e( 'Order of categories based on included category list', 'link-library' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td style='width:100px'></td>
					<td style='width:200px'>
						<?php _e( 'Link Categories Display Format', 'link-library' ); ?>
					</td>
					<td>
						<select name="flatlist" id="flatlist" style="width:200px;">
							<option value="table"<?php selected( $options['flatlist'] == 'table' ); ?>><?php _e( 'Table', 'link-library' ); ?></option>
							<option value="unordered"<?php selected( $options['flatlist'] == 'unordered' ); ?>><?php _e( 'Unordered List', 'link-library' ); ?></option>
							<option value="dropdown"<?php selected( $options['flatlist'] == 'dropdown' ); ?>><?php _e( 'Drop-Down List', 'link-library' ); ?></option>
							<option value="dropdowndirect"<?php selected( $options['flatlist'] == 'dropdowndirect' ); ?>><?php _e( 'Drop-Down List Direct Access', 'link-library' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php _e( 'Show selection prompt in Drop-down list mode', 'link-library' ); ?></td>
					<td><input type="checkbox" id="dropdownselectionprompt" name="dropdownselectionprompt" <?php checked( $options['dropdownselectionprompt'] ); ?>/></td>
					<td></td>
					<td><?php _e( 'Drop-down list mode selection prompt text', 'link-library' ); ?></td>
					<td><input type="text" id="dropdownselectionprompttext" name="dropdownselectionprompttext" size="20" value="<?php echo $options['dropdownselectionprompttext']; ?>" /></td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Display link counts', 'link-library' ); ?>
					</td>
					<td>
						<input type="checkbox" id="showcatlinkcount" name="showcatlinkcount" <?php checked( $options['showcatlinkcount'] ); ?>/>
					</td>
					<td style='width:100px'></td>
					<td style='width:200px'><?php _e( 'Display categories with search results', 'link-library' ); ?>    </td>
					<td>
						<input type="checkbox" id="showcatonsearchresults" name="showcatonsearchresults" <?php checked( $options['showcatonsearchresults'] ); ?>/></td>
				</tr>
				<tr>
					<td class="lltooltip">
						<?php _e( 'Direction', 'link-library' ); ?>
					</td>
					<td class="lltooltip">
						<select name="direction" id="direction" style="width:100px;">
							<option value="ASC"<?php selected( $options['direction'] == 'ASC' ); ?>><?php _e( 'Ascending', 'link-library' ); ?></option>
							<option value="DESC"<?php selected( $options['direction'] == 'DESC' ); ?>><?php _e( 'Descending', 'link-library' ); ?></option>
						</select>
					</td>
					<td></td>
					<td class="lltooltip" title="<?php _e( 'Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library' ); ?>">
						<?php _e( 'Show Category Description', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title="<?php _e( 'Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library' ); ?>">
						<input type="checkbox" id="showcategorydescheaders" name="showcategorydescheaders" <?php checked( $options['showcategorydescheaders'] ); ?>/>
						<span style='margin-left: 17px'><?php _e( 'Position', 'link-library' ); ?>:</span>
						<select name="catlistdescpos" id="catlistdescpos" style="width:100px;">
							<option value="right"<?php selected( $options['catlistdescpos'] == 'right' ); ?>><?php _e( 'Right', 'link-library' ); ?></option>
							<option value="left"<?php selected( $options['catlistdescpos'] == 'left' ); ?>><?php _e( 'Left', 'link-library' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Child category depth limit', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="catlistchildcatdepthlimit" name="catlistchildcatdepthlimit" size="2" value="<?php echo $options['catlistchildcatdepthlimit']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Width of Categories Table in Percents', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="table_width" name="table_width" size="10" value="<?php echo strval( $options['table_width'] ); ?>" />
					</td>
					<td></td>
					<td class="lltooltip" title='<?php _e( 'Determines the number of alternating div tags that will be placed before and after each link category', 'link-library' ); ?>.<br /><br /><?php _e( 'These div tags can be used to style of position link categories on the link page', 'link-library' ); ?>.'>
						<?php _e( 'Number of alternating div classes', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title='<?php _e( 'Determines the number of alternating div tags that will be placed before and after each link category', 'link-library' ); ?>.<br /><br /><?php _e( 'These div tags can be used to style of position link categories on the link page', 'link-library' ); ?>.'>
						<select name="catlistwrappers" id="catlistwrappers" style="width:200px;">
							<option value="1"<?php selected( $options['catlistwrappers'] == 1 ); ?>>1
							</option>
							<option value="2"<?php selected( $options['catlistwrappers'] == 2 ); ?>>2
							</option>
							<option value="3"<?php selected( $options['catlistwrappers'] == 3 ); ?>>3
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Number of columns in Categories Table', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="num_columns" name="num_columns" size="10" value="<?php echo strval( $options['num_columns'] ); ?>">
					</td>
					<td></td>
					<td>
						<?php _e( 'First div class name', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="beforecatlist1" name="beforecatlist1" size="40" value="<?php echo $options['beforecatlist1']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Use Div Class or Heading tag around Category Names', 'link-library' ); ?>
					</td>
					<td>
						<select name="divorheader" id="divorheader" style="width:200px;">
							<option value="false"<?php selected( $options['divorheader'] == false ); ?>><?php _e( 'Div Class', 'link-library' ); ?></option>
							<option value="true"<?php selected( $options['divorheader'] == true ); ?>><?php _e( 'Heading Tag', 'link-library' ); ?></option>
						</select>
					</td>
					<td></td>
					<td>
						<?php _e( 'Second div class name', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="beforecatlist2" name="beforecatlist2" size="40" value="<?php echo $options['beforecatlist2']; ?>" />
					</td>
				</tr>
				<tr>
					<td class="lltooltip" title="<?php _e( 'Example div class name: linklistcatname, Example Heading Label: h3', 'link-library' ); ?>">
						<?php _e( 'Div Class Name or Heading label', 'link-library' ); ?>
					</td>
					<td class="lltooltip" title="<?php _e( 'Example div class name: linklistcatname, Example Heading Label: h3', 'link-library' ); ?>">
						<input type="text" id="catnameoutput" name="catnameoutput" size="30" value="<?php echo strval( $options['catnameoutput'] ); ?>" />
					</td>
					<td></td>
					<td>
						<?php _e( 'Third div class name', 'link-library' ); ?>
					</td>
					<td>
						<input type="text" id="beforecatlist3" name="beforecatlist3" size="40" value="<?php echo $options['beforecatlist3']; ?>" />
					</td>
				</tr>
				<tr>
					<td class="lltooltip" title="<?php _e( 'Set this address to a page running Link Library to place categories on a different page. Should always be used with the Show One Category at a Time and HTMLGET fetch method.', 'link-library' ); ?>">
						<?php _e( 'Category Target Address', 'link-library' ); ?>
					</td>
					<td colspan="4" class="lltooltip" title="<?php _e( 'Set this address to a page running Link Library to place categories on a different page. Should always be used with the Show One Category at a Time and HTMLGET fetch method.', 'link-library' ); ?>">
						<input type="text" id="cattargetaddress" name="cattargetaddress" size="120" value="<?php echo $options['cattargetaddress']; ?>" />
					</td>
				</tr>
			</table>
		</div>
	<?php
	}

	function settingssets_linkelement_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>
		<div style='padding-top:15px' id="ll-links" class="content-section">
		<table>
			<tr>
				<td>
					<?php _e( 'Link Results Order', 'link-library' ); ?>
				</td>
				<td>
					<select name="linkorder" id="linkorder" style="width:250px;">
						<option value="name"<?php selected( $options['linkorder'] == 'name' ); ?>><?php _e( 'Order by Name', 'link-library' ); ?></option>
						<option value="id"<?php selected ( $options['linkorder'] == 'id' ); ?>><?php _e( 'Order by ID', 'link-library' ); ?></option>
						<option value="random"<?php selected( $options['linkorder'] == 'random' ); ?>><?php _e( 'Order randomly', 'link-library' ); ?></option>
						<option value="date"<?php selected( $options['linkorder'] == 'date' ); ?>><?php _e( 'Order by updated date', 'link-library' ); ?></option>
						<option value="pubdate"<?php selected( $options['linkorder'] == 'pubdate' ); ?>><?php _e( 'Order by publication date', 'link-library' ); ?></option>
						<option value="hits"<?php selected( $options['linkorder'] == 'hits' ); ?>><?php _e( 'Order by number of link visits', 'link-library' ); ?></option>
						<option value="scpo"<?php selected( $options['linkorder'] == 'scpo' ); ?>><?php _e( 'Order specified using Simple Custom Post Order plugin', 'link-library' ); ?></option>
					</select>
				</td>
				<td style='width:100px'></td>
				<td class="lltooltip" title="<?php _e( 'Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library' ); ?>">
					<?php _e( 'Show Category Description', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title="<?php _e( 'Use [ and ] in the description to perform special actions using HTML such as inserting images instead of < and >', 'link-library' ); ?>">
					<input type="checkbox" id="showcategorydesclinks" name="showcategorydesclinks" <?php checked( $options['showcategorydesclinks'] ); ?>/>
					<span style='margin-left: 17px'><?php _e( 'Position', 'link-library' ); ?>:</span>
					<select name="catdescpos" id="catdescpos" style="width:100px;">
						<option value="right"<?php selected( $options['catdescpos'] == 'right' ); ?>><?php _e( 'Right', 'link-library' ); ?></option>
						<option value="left"<?php selected( $options['catdescpos'] == 'left' ); ?>><?php _e( 'Left', 'link-library' ); ?></option>
						<option value="aftercatname"<?php selected( $options['catdescpos'] == 'aftercatname' ); ?>><?php _e( 'After Category Name', 'link-library' ); ?></option>
						<option value="aftertoplevelcatname"<?php selected( $options['catdescpos'] == 'aftertoplevelcatname' ); ?>><?php _e( 'After Top-Level Category Name', 'link-library' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'List Featured Links ahead of Regular Links', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="featuredfirst" name="featuredfirst" <?php checked( $options['featuredfirst'] ); ?>/></td>
				<td></td>
				<td><?php _e( 'Show Expand Link button and hide links', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="showlinksonclick" name="showlinksonclick" <?php checked( $options['showlinksonclick'] ); ?>/></td>
			</tr>
			<tr>
				<td><?php _e( 'Combine all results without categories', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="combineresults" name="combineresults" <?php checked( $options['combineresults'] ); ?>/></td>
				<td style='width:100px'></td>
				<td><?php _e( 'Link Title Content', 'link-library' ); ?></td>
				<td>
					<select name="linktitlecontent">

						<?php $modes = array( 'linkname' => __( 'Link Name', 'link-library' ), 'linkdesc' => __( 'Link Description', 'link-library' ) );

						// Generate all items of drop-down list
						foreach ($modes as $mode => $modename) {
						?>
						<option value="<?php echo $mode; ?>"
							<?php selected( $options['linktitlecontent'], $mode ); ?>>
							<?php echo $modename; ?>
							<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="lltooltip">
					<?php _e( 'Direction', 'link-library' ); ?>
				</td>
				<td class="lltooltip">
					<select name="linkdirection" id="linkdirection" style="width:200px;">
						<option value="ASC"<?php selected( $options['linkdirection'] == 'ASC' ); ?>><?php _e( 'Ascending', 'link-library' ); ?></option>
						<option value="DESC"<?php selected( $options['linkdirection'] == 'DESC' ); ?>><?php _e( 'Descending', 'link-library' ); ?></option>
					</select>
				</td>
				<td></td>
				<td class="lltooltip" title="<?php _e( 'Leave empty to show all results', 'link-library' ); ?>">
					<?php _e( 'Total max number of links to display', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title="<?php _e( 'Leave empty to show all results', 'link-library' ); ?>">
					<input type="text" id="maxlinks" name="maxlinks" size="4" value="<?php echo $options['maxlinks']; ?>" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td class="lltooltip" title="<?php _e( 'Leave empty to show all results', 'link-library' ); ?>">
					<?php _e( 'Max number of links to display per category', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title="<?php _e( 'Leave empty to show all results for each category', 'link-library' ); ?>">
					<input type="text" id="maxlinkspercat" name="maxlinkspercat" size="4" value="<?php echo $options['maxlinkspercat']; ?>" />
				</td>
			</tr>
			<tr>
				<td style='width:150px'>
					<?php _e( 'Show Link Updated Flag', 'link-library' ); ?>
				</td>
				<td style='width:75px;'>
					<input type="checkbox" id="showupdated" name="showupdated" <?php checked( $options['showupdated'] ); ?>/>
				</td>
				<td></td>
				<td style='width:150px'>
					<?php _e( 'Show Link Updated Date in Tooltip', 'link-library' ); ?>
				</td>
				<td style='width:75px;'>
					<input type="checkbox" id="showupdatedtooltip" name="showupdatedtooltip" <?php checked( $options['showupdatedtooltip'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td style='width:150px'>
					<?php _e( 'Link Updated Flag Position', 'link-library' ); ?>
				</td>
				<td style='width:75px;'>
					<select id="showupdatedpos" name="showupdatedpos">
						<?php
						$show_updated_pos_options = array( 'before' => 'Before Link Fields', 'after' => 'After Link Fields' );

						foreach( $show_updated_pos_options as $show_updated_pos_option_key => $show_updated_pos_option ) { ?>
							<option value="<?php echo $show_updated_pos_option_key; ?>" <?php selected( $options['showupdatedpos'], $show_updated_pos_option_key ); ?>><?php echo $show_updated_pos_option; ?></option>
						<?php }
						?>
					</select>
				</td>
				<td style='width:20px'>
				</td>
				<td class="lltooltip" title="<?php _e( 'Label to be displayed before new links', 'link-library' ); ?>">
					<?php _e( 'Updated link label', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title="<?php _e( 'Label to be displayed before new links', 'link-library' ); ?>">
					<input type="text" id="updatedlabel" name="updatedlabel" size="40" value="<?php echo $options['updatedlabel']; ?>" />
				</td>
			</tr>
			<tr>
				<td class="lltooltip" title="<?php _e( 'Sets default link target window, does not override specific targets set in links', 'link-library' ); ?>">
					<?php _e( 'Link Target', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title="<?php _e( 'Sets default link target window, does not override specific targets set in links', 'link-library' ); ?>">
					<input type="text" id="linktarget" name="linktarget" size="40" value="<?php echo $options['linktarget']; ?>" />
				</td>
				<td></td>
				<td>
					<?php
						_e( 'Link Display Format', 'link-library' );

						$display_as_table = 'false';

						if ( is_bool( $options['displayastable'] ) && $options['displayastable'] ) {
							$display_as_table = 'true';
						} elseif( is_bool( $options['displayastable'] ) && !$options['displayastable'] ) {
							$display_as_table = 'false';
						} elseif ( in_array( $options['displayastable'], array( 'true', 'false', 'nosurroundingtags' ) ) ) {
							$display_as_table = $options['displayastable'];
						}
					?>
				</td>
				<td>
					<select name="displayastable" id="displayastable" style="width:200px;">
						<option value="true"<?php selected( $display_as_table === 'true' ); ?>><?php _e( 'Table', 'link-library' ); ?></option>
						<option value="false"<?php selected( $display_as_table === 'false' ); ?>><?php _e( 'Unordered List', 'link-library' ); ?></option>
						<option value="nosurroundingtags"<?php selected( $display_as_table, 'nosurroundingtags' ); ?>><?php _e( 'No surrounding tags', 'link-library' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="lltooltip" title="<?php _e( 'Allows extra query string to be added to all links in library', 'link-library' ); ?>">
					<?php _e( 'Additional link query string', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title="<?php _e( 'Allows extra query string to be added to all links in library', 'link-library' ); ?>">
					<input type="text" id="extraquerystring" name="extraquerystring" size="40" value="<?php echo $options['extraquerystring']; ?>" />
				</td>
				<td></td>
				<td>
					<?php _e( 'Show Scheduled Links (published with future date)', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="showscheduledlinks" name="showscheduledlinks" <?php checked( $options['showscheduledlinks'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Show Column Headers', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="showcolumnheaders" name="showcolumnheaders" <?php checked( $options['showcolumnheaders'] ); ?>/>
				</td>
				<td></td>
				<td>
					<?php _e( 'Column Headers Override', 'link-library' ); ?>
				</td>
				<td>
					<input class="lltooltip" title="<?php _e( 'Comma-separated list of column header labels', 'link-library' ); ?>" type="text" id="columnheaderoverride" name="columnheaderoverride" size="40" value="<?php echo $options['columnheaderoverride']; ?>" />
				</td>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Allow sorting through column headers', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="allowcolumnsorting" name="allowcolumnsorting" <?php checked( $options['allowcolumnsorting'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Hide Category Names', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="hidecategorynames" name="hidecategorynames" <?php checked( $options['hidecategorynames'] ); ?>/>
				</td>
				<td></td>
				<td>
					<?php _e( 'Show Hidden Links', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="showinvisible" name="showinvisible" <?php checked( $options['showinvisible'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Hide links of children categories', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="hidechildcatlinks" name="hidechildcatlinks" <?php checked( $options['hidechildcatlinks'] ); ?>/>
				</td>
				<td></td>
				<td>
					<?php _e( 'Child category depth limit', 'link-library' ); ?>
				</td>
				<td>
					<input type="text" id="childcatdepthlimit" name="childcatdepthlimit" size="2" value="<?php echo $options['childcatdepthlimit']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Hide children categories on top page', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="hidechildcattop" name="hidechildcattop" <?php checked( $options['hidechildcattop'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td class="lltooltip" title='<?php _e( 'Need to be active for Link Categories to work', 'link-library' ); ?>'>
					<?php _e( 'Embed HTML anchors', 'link-library' ); ?>
				</td>
				<td class="lltooltip" title='<?php _e( 'Need to be active for Link Categories to work', 'link-library' ); ?>'>
					<input type="checkbox" id="catanchor" name="catanchor" <?php checked( $options['catanchor'] ); ?>/>
				</td>
				<td></td>
				<td>
					<?php _e( 'Show Hidden Links to Admins/Editors', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="showinvisibleadmin" name="showinvisibleadmin" <?php checked( $options['showinvisibleadmin'] ); ?>/>
				</td>
			</tr>
		</table>
		</div>
	<?php
	}

	function settingssets_subfieldtable_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>

		<div style='padding-top:15px' id="ll-advanced" class="content-section">
		<?php _e( 'Arrange the items below via drag-and-drop to order the various Link Library elements.', 'link-library' ); ?>
		<br /><br />
		<ul id="sortable">
			<?php if ( empty( $options['dragndroporder'] ) ) {
				$dragndroporder = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';
			} else {
				$dragndroporder = $options['dragndroporder'];
			}

			$dragndroparray = explode( ',', $dragndroporder );

			$new_entries = array( '13', '14', '15', '16', '17' );

			foreach ( $new_entries as $new_entry ) {
				if ( !in_array( $new_entry, $dragndroparray ) ) {
					$dragndroparray[] = $new_entry;
				}
			}

			if ( $dragndroparray ) {
				foreach ( $dragndroparray as $arrayelements ) {
					switch ( $arrayelements ) {
						case 1:
							?>
							<li id="1" style='background-color: #1240ab'><?php _e( 'Image', 'link-library' ); ?></li>
							<?php break;
						case 2:
							?>
							<li id="2" style='background-color: #4671d5'><?php _e( 'Name', 'link-library' ); ?></li>
							<?php break;
						case 3:
							?>
							<li id="3" style='background-color: #39e639'><?php _e( 'Date', 'link-library' ); ?></li>
							<?php break;
						case 4:
							?>
							<li id="4" style='background-color: #009999'><?php _e( 'Desc', 'link-library' ); ?></li>
							<?php break;
						case 5:
							?>
							<li id="5" style='background-color: #00cc00'><?php _e( 'Notes', 'link-library' ); ?></li>
							<?php break;
						case 6:
							?>
							<li id="6" style='background-color: #008500'><?php _e( 'RSS', 'link-library' ); ?></li>
							<?php break;
						case 7:
							?>
							<li id="7" style='background-color: #5ccccc'><?php _e( 'Web Link', 'link-library' ); ?></li>
							<?php break;
						case 8:
							?>
							<li id="8" style='background-color: #6c8cd5'><?php _e( 'Phone', 'link-library' ); ?></li>
							<?php break;
						case 9:
							?>
							<li id="9" style='background-color: #67e667'><?php _e( 'E-mail', 'link-library' ); ?></li>
							<?php break;
						case 10:
							?>
							<li id="10" style='background-color: #33cccc'><?php _e( 'Hits', 'link-library' ); ?></li>
							<?php break;
						case 11:
							?>
							<li id="11" style='background-color: #33cc00'><?php _e( 'Rating', 'link-library' ); ?></li>
							<?php break;
						case 12:
							?>
							<li id="12" style='background-color: #33ccff'><?php _e( 'Large Desc', 'link-library' ); ?></li>
							<?php break;
						case 13:
							?>
							<li id="13" style='background-color: #33eecc'><?php _e( 'Submitter Name', 'link-library' ); ?></li>
							<?php break;
						case 14:
							?>
							<li id="14" style='background-color: #33eeff'><?php _e( 'Cat Desc', 'link-library' ); ?></li>
							<?php break;
						case 15:
							?>
							<li id="15" style='background-color: #c4d1ee'><?php _e( 'Tags', 'link-library' ); ?></li>
							<?php break;
						case 16:
							?>
							<li id="16" style='background-color: #238e00'><?php _e( 'Price', 'link-library' ); ?></li>
							<?php break;
						case 17:
							?>
							<li id="17" style='background-color: #23A023'><?php _e( 'Cat Name', 'link-library' ); ?></li>
							<?php break;
					}
				}
			}
			?>
		</ul>
		<input type="hidden" id="dragndroporder" name="dragndroporder" size="60" value="<?php echo $options['dragndroporder']; ?>" />
		<br />
		<table class='widefat' style='width: 1000px;margin:15px 5px 10px 0px;clear:none;background-color:#F1F1F1;background-image: linear-gradient(to top, #ECECEC, #F9F9F9);background-position:initial initial;background-repeat: initial initial'>
		<thead>
		<th style='width: 100px'></th>
		<th style='width: 40px'><?php _e( 'Display', 'link-library' ); ?></th>
		<th style='width: 80px'><?php _e( 'Before', 'link-library' ); ?></th>
		<th style='width: 80px'><?php _e( 'After', 'link-library' ); ?></th>
		<th style='width: 80px'><?php _e( 'Additional Details', 'link-library' ); ?></th>
		<th style='width: 80px'><?php _e( 'Link Source', 'link-library' ); ?></th>
		</thead>
		<tr>
			<td class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before the first link in each category', 'link-library' ); ?>'><?php _e( 'Before first link', 'link-library' ); ?></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Output of text/code before the first link in each category', 'link-library' ); ?>'>
				<input type="text" id="beforefirstlink" name="beforefirstlink" size="22" value="<?php echo stripslashes( $options['beforefirstlink'] ); ?>" />
			</td>
			<td style='background: #FFF'></td><td style='background: #FFF'></td><td style='background: #FFF'></td>
		</tr>
		<tr>
			<td class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before a number of links determined by the Display field', 'link-library' ); ?>'><?php _e( 'Intermittent Before Link', 'link-library' ); ?></td>
			<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Frequency of additional output before and after complete link group', 'link-library' ); ?>'>
				<input type="text" id="linkaddfrequency" name="linkaddfrequency" size="10" value="<?php echo strval( $options['linkaddfrequency'] ); ?>" />
			</td>
			<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Output before complete link group (link, notes, desc, etc...)', 'link-library' ); ?>'>
				<input type="text" id="addbeforelink" name="addbeforelink" size="22" value="<?php echo stripslashes( $options['addbeforelink'] ); ?>" />
			</td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
		</tr>
		<tr>
			<td class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before each link', 'link-library' ); ?>'><?php _e( 'Before Link', 'link-library' ); ?></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Output before complete link group (link, notes, desc, etc...)', 'link-library' ); ?>'>
				<input type="text" id="beforeitem" name="beforeitem" size="22" value="<?php echo stripslashes( $options['beforeitem'] ); ?>" />
			</td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
		</tr>
		<?php if ( empty( $options['dragndroporder'] ) ) {
			$dragndroporder = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17';
		} else {
			$dragndroporder = $options['dragndroporder'];
		}
		$dragndroparray = explode( ',', $dragndroporder );

		$new_entries = array( '13', '14', '15', '16', '17' );

		foreach ( $new_entries as $new_entry ) {
			if ( !in_array( $new_entry, $dragndroparray ) ) {
				$dragndroparray[] = $new_entry;
			}
		}

		if ( $dragndroparray ) {
			foreach ( $dragndroparray as $arrayelements ) {
				switch ( $arrayelements ) {
					case 1: /* -------------------------------- Link Image -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #1240ab; color: #fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before each link image', 'link-library' ); ?>'><?php _e( 'Image', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="show_images" name="show_images" <?php checked( $options['show_images'] );?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before each link image', 'link-library' ); ?>'>
								<input type="text" id="beforeimage" name="beforeimage" size="22" value="<?php echo stripslashes( $options['beforeimage'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after each link image', 'link-library' ); ?>'>
								<input type="text" id="afterimage" name="afterimage" size="22" value="<?php echo stripslashes( $options['afterimage'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'CSS Class to be assigned to link image', 'link-library' ); ?>'>
								<input type="text" id="imageclass" name="imageclass" size="22" value="<?php echo $options['imageclass']; ?>" />
							</td>
							<td style='background: #FFF'>
								<select name="sourceimage" id="sourceimage" style="width:200px;">
									<option value="primary"<?php selected( $options['sourceimage'] == "primary" ); ?>><?php _e( 'Primary', 'link-library' ); ?></option>
									<option value="secondary"<?php selected( $options['sourceimage'] == "secondary" ); ?>><?php _e( 'Secondary', 'link-library' ); ?></option>
								</select>
							</td>
						</tr>
						<?php break;
					case 2: /* -------------------------------- Link Name -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #4671d5; color: #fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after each link name', 'link-library' ); ?>'><?php _e( 'Link Name', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showname" name="showname" <?php checked( $options['showname'] == true ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before each link', 'link-library' ); ?>'>
								<input type="text" id="beforelink" name="beforelink" size="22" value="<?php echo stripslashes( $options['beforelink'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after each link', 'link-library' ); ?>'>
								<input type="text" id="afterlink" name="afterlink" size="22" value="<?php echo stripslashes( $options['afterlink'] ); ?>" />
							</td>
							<td style='background: #FFF'>
								<select name="tooltipname" id="tooltipname" style="width:200px;">
									<option value="no_tooltip"<?php selected( $options['tooltipname'], 'no_tooltip' ); ?>><?php _e( 'No Tooltip', 'link-library' ); ?></option>
									<option value="description"<?php selected( $options['tooltipname'], 'description' ); ?>><?php _e( 'Description', 'link-library' ); ?></option>
								</select>
							</td>
							<td style='background: #FFF'>
								<select name="sourcename" id="sourcename" style="width:200px;">
									<option value="primary"<?php selected( $options['sourcename'] == "primary" ); ?>><?php _e( 'Primary', 'link-library' ); ?></option>
									<option value="secondary"<?php selected( $options['sourcename'] == "secondary" ); ?>><?php _e( 'Secondary', 'link-library' ); ?></option>
									<option value="permalink"<?php selected( $options['sourcename'] == "permalink" ); ?>><?php _e( 'Dedicated page', 'link-library' ); ?></option>
								</select>
							</td>
						</tr>
						<?php break;
					case 3: /* -------------------------------- Link Date -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #39e639; color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after each link date stamp', 'link-library' ); ?>'><?php _e( 'Link Date', 'link-library' ); ?></td>
							<td style='background: #FFF;text-align:center' class="lltooltip" title='<?php _e( 'Check to display link date', 'link-library' ); ?>'>
								<input type="checkbox" id="showdate" name="showdate" <?php checked( $options['showdate'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before each date', 'link-library' ); ?>'>
								<input type="text" id="beforedate" name="beforedate" size="22" value="<?php echo stripslashes( $options['beforedate'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after each date', 'link-library' ); ?>'>
								<input type="text" id="afterdate" name="afterdate" size="22" value="<?php echo stripslashes( $options['afterdate'] ); ?>" />
							</td>
							<td style='background: #FFF'>
								<select name="datesource" id="datesource" style="width:200px;">
									<option value="linkupdated"<?php selected( $options['datesource'] == "linkupdated" ); ?>><?php _e( 'Updated Date', 'link-library' ); ?></option>
									<option value="linkpublication"<?php selected( $options['datesource'] == "linkpublication" ); ?>><?php _e( 'Publication Date', 'link-library' ); ?></option>
								</select>
							</td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 4: /* -------------------------------- Link Description -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #009999;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after each link description', 'link-library' ); ?>'><?php _e( 'Link Description', 'link-library' ); ?></td>
							<td style='background: #FFF;text-align: center' class="lltooltip" title='<?php _e( 'Check to display link descriptions', 'link-library' ); ?>'>
								<input type="checkbox" id="showdescription" name="showdescription" <?php checked( $options['showdescription'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before each description', 'link-library' ); ?>'>
								<input type="text" id="beforedesc" name="beforedesc" size="22" value="<?php echo stripslashes( $options['beforedesc'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after each description', 'link-library' ); ?>'>
								<input type="text" id="afterdesc" name="afterdesc" size="22" value="<?php echo stripslashes( $options['afterdesc'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 5: /* -------------------------------- Link Notes -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #00cc00;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after each link notes', 'link-library' ); ?>'><?php _e( 'Link Notes', 'link-library' ); ?></td>
							<td style='background: #FFF;text-align: center' class="lltooltip" title='<?php _e( 'Check to display link notes', 'link-library' ); ?>'>
								<input type="checkbox" id="shownotes" name="shownotes" <?php checked( $options['shownotes'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before each note', 'link-library' ); ?>'>
								<input type="text" id="beforenote" name="beforenote" size="22" value="<?php echo stripslashes( $options['beforenote'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after each note', 'link-library' ); ?>'>
								<input type="text" id="afternote" name="afternote" size="22" value="<?php echo stripslashes( $options['afternote'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 6: /* -------------------------------- Link RSS Icons -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #008500;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the RSS icons', 'link-library' ); ?>'><?php _e( 'RSS Icons', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<?php _e( 'See below', 'link-library' ); ?>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before RSS Icons', 'link-library' ); ?>'>
								<input type="text" id="beforerss" name="beforerss" size="22" value="<?php echo stripslashes( $options['beforerss'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after RSS Icons', 'link-library' ); ?>'>
								<input type="text" id="afterrss" name="afterrss" size="22" value="<?php echo stripslashes( $options['afterrss'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 7: /* -------------------------------- Web Link -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #5ccccc;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Web Link', 'link-library' ); ?>'><?php _e( 'Web Link', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<select name="displayweblink" id="displayweblink" style="width:80px;">
									<option value="false"<?php selected( $options['displayweblink'] == 'false' ); ?>><?php _e( 'False', 'link-library' ); ?></option>
									<option value="address"<?php selected( $options['displayweblink'] == 'address' ); ?>><?php _e( 'Web Address Link', 'link-library' ); ?></option>
									<option value="addressonly"<?php selected( $options['displayweblink'] == 'addressonly' ); ?>><?php _e( 'Plain Web Address', 'link-library' ); ?></option>
									<option value="label"<?php selected( $options['displayweblink'] == 'label' ); ?>><?php _e( 'Text Label with Link', 'link-library' ); ?></option>
								</select>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Web Link', 'link-library' ); ?>'>
								<input type="text" id="beforeweblink" name="beforeweblink" size="22" value="<?php echo stripslashes( $options['beforeweblink'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Web Link', 'link-library' ); ?>'>
								<input type="text" id="afterweblink" name="afterweblink" size="22" value="<?php echo stripslashes( $options['afterweblink'] ); ?>" />
							</td>
							<td style='background: #FFF'>
								<input class="lltooltip" title='<?php _e( 'Text Label that the web link will be assigned to.', 'link-library' ); ?>' type="text" id="weblinklabel" name="weblinklabel" size="9" value="<?php echo stripslashes( $options['weblinklabel'] ); ?>" />
								<input class="lltooltip" title='<?php _e( 'Target that will be assigned to web links.', 'link-library' ); ?>'  type="text" id="weblinktarget" name="weblinktarget" size="9" value="<?php echo stripslashes( $options['weblinktarget'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Select which link address will be displayed / used for link', 'link-library' ); ?>'>
								<select name="sourceweblink" id="sourceweblink" style="width:200px;">
									<option value="primary"<?php selected( $options['sourceweblink'] == "primary" ); ?>><?php _e( 'Primary', 'link-library' ); ?></option>
									<option value="secondary"<?php selected( $options['sourceweblink'] == "secondary" ); ?>><?php _e( 'Secondary', 'link-library' ); ?></option>
								</select>
							</td>
						</tr>
						<?php break;
					case 8: /* -------------------------------- Telephone -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #6c8cd5;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Telephone Number', 'link-library' ); ?>'><?php _e( 'Telephone', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<select name="showtelephone" id="showtelephone" style="width:80px;">
									<option value="false"<?php selected( $options['showtelephone'] == "false" ); ?>><?php _e( 'False', 'link-library' ); ?></option>
									<option value="plain"<?php selected( $options['showtelephone'] == "plain" ); ?>><?php _e( 'Plain Text', 'link-library' ); ?></option>
									<option value="link"<?php selected( $options['showtelephone'] == "link" ); ?>><?php _e( 'Link', 'link-library' ); ?></option>
									<option value="label"<?php selected( $options['showtelephone'] == "label" ); ?>><?php _e( 'Label', 'link-library' ); ?></option>
								</select>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Telephone Number', 'link-library' ); ?>'>
								<input type="text" id="beforetelephone" name="beforetelephone" size="22" value="<?php echo stripslashes( $options['beforetelephone'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Telephone Number', 'link-library' ); ?>'>
								<input type="text" id="aftertelephone" name="aftertelephone" size="22" value="<?php echo stripslashes( $options['aftertelephone'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Text Label that the telephone will be assigned to.', 'link-library' ); ?>'>
								<input type="text" id="telephonelabel" name="telephonelabel" size="22" value="<?php echo stripslashes( $options['telephonelabel'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Select which link address will be displayed / used for link', 'link-library' ); ?>'>
								<select name="sourcetelephone" id="sourcetelephone" style="width:200px;">
									<option value="primary"<?php selected( $options['sourcetelephone'] == 'primary' ); ?>><?php _e( 'Primary', 'link-library' ); ?></option>
									<option value="secondary"<?php selected( $options['sourcetelephone'] == 'secondary' ); ?>><?php _e( 'Secondary', 'link-library' ); ?></option>
									<option value="phone"<?php selected( $options['sourcetelephone'] == 'phone' ); ?>><?php _e( 'Phone', 'link-library' ); ?></option>
								</select>
							</td>
						</tr>
						<?php break;
					case 9: /* -------------------------------- E-mail -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #67e667;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the E-mail', 'link-library' ); ?>'><?php _e( 'E-mail', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<select name="showemail" id="showemail" style="width:80px;">
									<option value="false"<?php selected( $options['showemail'] == "false" ); ?>><?php _e( 'False', 'link-library' ); ?></option>
									<option value="plain"<?php selected( $options['showemail'] == "plain" ); ?>><?php _e( 'Plain Text', 'link-library' ); ?></option>
									<option value="mailto"<?php selected( $options['showemail'] == "mailto" ); ?>><?php _e( 'MailTo Link', 'link-library' ); ?></option>
									<option value="mailtolabel"<?php selected( $options['showemail'] == "mailtolabel" ); ?>><?php _e( 'MailTo Link with Label', 'link-library' ); ?></option>
									<option value="command"<?php selected( $options['showemail'] == "command" ); ?>><?php _e( 'Formatted Command', 'link-library' ); ?></option>
									<option value="commandlabel"<?php selected( $options['showemail'] == "commandlabel" ); ?>><?php _e( 'Formatted Command with Labels', 'link-library' ); ?></option>
								</select>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before E-mail', 'link-library' ); ?>'>
								<input type="text" id="beforeemail" name="beforeemail" size="22" value="<?php echo stripslashes( $options['beforeemail'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after E-mail', 'link-library' ); ?>'>
								<input type="text" id="afteremail" name="afteremail" size="22" value="<?php echo stripslashes( $options['afteremail'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Text Label that the e-mail will be assigned to represent the e-mail link.', 'link-library' ); ?>'>
								<input type="text" id="emaillabel" name="emaillabel" size="22" value="<?php echo stripslashes( $options['emaillabel'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Command that the e-mail will be embedded in. In the case of a command, use the symbols #email and #company to indicate the position where these elements should be inserted.', 'link-library' ); ?>'>
								<input type="text" id="emailcommand" name="emailcommand" size="22" value="<?php echo stripslashes( $options['emailcommand'] ); ?>" />
							</td>
						</tr>
						<?php break;
					case 10: /* -------------------------------- Link Hits -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #33cccc;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after Link Hits', 'link-library' ); ?>'><?php _e( 'Link Hits', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showlinkhits" name="showlinkhits" <?php checked( $options['showlinkhits'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Hits', 'link-library' ); ?>'>
								<input type="text" id="beforelinkhits" name="beforelinkhits" size="22" value="<?php echo stripslashes( $options['beforelinkhits'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Hits', 'link-library' ); ?>'>
								<input type="text" id="afterlinkhits" name="afterlinkhits" size="22" value="<?php echo stripslashes( $options['afterlinkhits'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 11: /* -------------------------------- Link Rating -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #33cc00;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Link Rating', 'link-library' ); ?>'><?php _e( 'Link Rating', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showrating" name="showrating" <?php checked( $options['showrating'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Rating', 'link-library' ); ?>'>
								<input type="text" id="beforelinkrating" name="beforelinkrating" size="22" value="<?php echo stripslashes( $options['beforelinkrating'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Rating', 'link-library' ); ?>'>
								<input type="text" id="afterlinkrating" name="afterlinkrating" size="22" value="<?php echo stripslashes( $options['afterlinkrating'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 12: /* -------------------------------- Large Description -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #33ccff;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Link Large Description', 'link-library' ); ?>'><?php _e( 'Link Large Description', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showlargedescription" name="showlargedescription" <?php checked( $options['showlargedescription'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="beforelargedescription" name="beforelargedescription" size="22" value="<?php echo stripslashes( $options['beforelargedescription'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="afterlargedescription" name="afterlargedescription" size="22" value="<?php echo stripslashes( $options['afterlargedescription'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 13: /* -------------------------------- Link Submitter Name -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #33eecc;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Link Large Description', 'link-library' ); ?>'><?php _e( 'Submitter Name', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showsubmittername" name="showsubmittername" <?php checked( $options['showsubmittername'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="beforesubmittername" name="beforesubmittername" size="22" value="<?php echo stripslashes( $options['beforesubmittername'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="aftersubmittername" name="aftersubmittername" size="22" value="<?php echo stripslashes( $options['aftersubmittername'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 14: /* -------------------------------- Category Description -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #33eeff;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Link Large Description', 'link-library' ); ?>'><?php _e( 'Category Description', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showcatdesc" name="showcatdesc" <?php checked( $options['showcatdesc'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="beforecatdesc" name="beforecatdesc" size="22" value="<?php echo stripslashes( $options['beforecatdesc'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="aftercatdesc" name="aftercatdesc" size="22" value="<?php echo stripslashes( $options['aftercatdesc'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 15: /* -------------------------------- Link Tags -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #c4d1ee;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of tags associated with the link', 'link-library' ); ?>'><?php _e( 'Link Tags', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showlinktags" name="showlinktags" <?php checked( $options['showlinktags'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Tags', 'link-library' ); ?>'>
								<input type="text" id="beforelinktags" name="beforelinktags" size="22" value="<?php echo stripslashes( $options['beforelinktags'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Tags', 'link-library' ); ?>'>
								<input type="text" id="afterlinktags" name="afterlinktags" size="22" value="<?php echo stripslashes( $options['afterlinktags'] ); ?>" />
							</td>
							<td style='background: #FFF'>
								<select name="taglinks" id="taglinks" style="width:200px;">
									<option value="inactive"<?php selected( $options['taglinks'], 'inactive' ); ?>><?php _e( 'Tag links inactive', 'link-library' ); ?></option>
									<option value="active"<?php selected( $options['taglinks'], 'active' ); ?>><?php _e( 'Tag links active', 'link-library' ); ?></option>
								</select>
							</td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
					case 16: /* -------------------------------- Link Price -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #238e00;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of the price field associated with the link', 'link-library' ); ?>'><?php _e( 'Link Price', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showlinkprice" name="showlinkprice" <?php checked( $options['showlinkprice'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Link Price', 'link-library' ); ?>'>
								<input type="text" id="beforelinkprice" name="beforelinkprice" size="22" value="<?php echo stripslashes( $options['beforelinkprice'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Price', 'link-library' ); ?>'>
								<input type="text" id="afterlinkprice" name="afterlinkprice" size="22" value="<?php echo stripslashes( $options['afterlinkprice'] ); ?>" />
							</td>
							<td style='background: #FFF'>
								<input type="text" id="linkcurrency" name="linkcurrency" size="3" value="<?php echo stripslashes( $options['linkcurrency'] ); ?>"  class="lltooltip" title='<?php _e( 'Currency symbol to be displayed next to price', 'link-library' ); ?>' />
								Show 0.00 as free <input type="checkbox" id="show0asfree" name="show0asfree" <?php checked( $options['show0asfree'] ); ?>/>
							</td>
							<td style='background: #FFF'>
								<select name="linkcurrencyplacement" id="linkcurrencyplacement" style="width:200px;">
									<option value="before"<?php selected( $options['linkcurrencyplacement'] == 'before' ); ?>><?php _e( 'Before Price', 'link-library' ); ?></option>
									<option value="after"<?php selected( $options['linkcurrencyplacement'] == 'after' ); ?>><?php _e( 'After Price', 'link-library' ); ?></option>
								</select>
							</td>
						</tr>
						<?php break;
					case 17: /* -------------------------------- Category Name -------------------------------------------*/
						?>
						<tr>
							<td style='background-color: #23A023;color:#fff' class="lltooltip" title='<?php _e( 'This column allows for the output of text/code before and after the Link Category Name', 'link-library' ); ?>'><?php _e( 'Category Name', 'link-library' ); ?></td>
							<td style='text-align:center;background: #FFF'>
								<input type="checkbox" id="showcatname" name="showcatname" <?php checked( $options['showcatname'] ); ?>/>
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed before Category Name', 'link-library' ); ?>'>
								<input type="text" id="beforecatname" name="beforecatname" size="22" value="<?php echo stripslashes( $options['beforecatname'] ); ?>" />
							</td>
							<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Code/Text to be displayed after Link Large Description', 'link-library' ); ?>'>
								<input type="text" id="aftercatname" name="aftercatname" size="22" value="<?php echo stripslashes( $options['aftercatname'] ); ?>" />
							</td>
							<td style='background: #FFF'></td>
							<td style='background: #FFF'></td>
						</tr>
						<?php break;
				}
			}
		}
		?>
		<tr>
			<td class="lltooltip" title='<?php _e( 'This column allows for the output of text/code after each link', 'link-library' ); ?>'><?php _e( 'After Link Block', 'link-library' ); ?></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF' class="lltooltip" title='<?php _e( 'Output after complete link group (link, notes, desc, etc...)', 'link-library' ); ?>'>
				<input type="text" id="afteritem" name="afteritem" size="22" value="<?php echo stripslashes( $options['afteritem'] ); ?>" />
			</td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
		</tr>
		<tr>
			<td class="lltooltip" title='<?php _e( 'This column allows for the output of text/code after a number of links determined in the first column', 'link-library' ); ?>'><?php _e( 'Intermittent After Link', 'link-library' ); ?></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'>
				<input type="text" id="addafterlink" name="addafterlink" size="22" value="<?php echo stripslashes( $options['addafterlink'] ); ?>" />
			</td>
			<td style='background: #FFF'></td>
			<td style='background: #FFF'></td>
		</tr>
		<tr>
			<td class="lltooltip" title='<?php _e( 'This column allows for the output of text/code after the last link in each category', 'link-library' ); ?>'><?php _e( 'After last link', 'link-library' ); ?></td>
			<td style='background: #FFF'></td><td style='background: #FFF'></td>
			<td style='background: #FFF'>
				<input type="text" id="afterlastlink" name="afterlastlink" size="22" value="<?php echo stripslashes( $options['afterlastlink'] ); ?>" />
			</td>
			<td style='background: #FFF'></td><td style='background: #FFF'></td>
		</tr>
		</table>
		</table>
		<br />
		<table>
			<tr>
				<td>
					<?php _e( 'Convert [] to &lt;&gt; in Link Description and Notes', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding:0px 20px 0px 20px'>
					<input type="checkbox" id="use_html_tags" name="use_html_tags" <?php checked( $options['use_html_tags'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Add nofollow tag to outgoing links', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding:0px 20px 0px 20px'>
					<input type="checkbox" id="nofollow" name="nofollow" <?php checked( $options['nofollow'] ); ?>/>
				</td>
				<td></td>
				<td>
					<?php _e( 'Suppress noreferrer and noopener tags on links', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding:0px 20px 0px 20px'>
					<input type="checkbox" id="suppressnoreferrer" name="suppressnoreferrer" <?php checked( $options['suppressnoreferrer'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Show edit links when logged in as editor or administrator', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding:0px 20px 0px 20px'>
					<input type="checkbox" id="showadmineditlinks" name="showadmineditlinks" <?php checked( $options['showadmineditlinks'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Show link name when no image is assigned', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding:0px 20px 0px 20px'>
					<input type="checkbox" id="shownameifnoimage" name="shownameifnoimage" <?php checked( $options['shownameifnoimage'] ); ?>/>
				</td>
				<td></td>
				<td>
					<?php _e( 'Do not output fields with no value', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding:0px 20px 0px 20px'>
					<input type="checkbox" id="nooutputempty" name="nooutputempty" <?php checked( $options['nooutputempty'] ); ?>/>
				</td>
			</tr>
		</table>
		</div>
	<?php
	}

	function settingssets_linkpopup_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>
		<div style='padding-top:15px' id="ll-popup" class="content-section">
		<table>
			<tr>
				<td style='width:175px;'><?php _e( 'Enable link Pop-Ups', 'link-library' ); ?></td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="enable_link_popup" name="enable_link_popup" <?php ( isset( $options['enable_link_popup'] ) ? checked( $options['enable_link_popup'] ) : '' ); ?>/>
				</td>
				<td><?php _e( 'Pop-Up Width', 'link-library' ); ?></td>
				<td>
					<input type="text" id="popup_width" name="popup_width" size="4" value="<?php if ( !isset( $options['popup_width'] ) || empty( $options['popup_width'] ) ) {
						echo '300';
					} else {
						echo strval( $options['popup_width'] );
					} ?>" /></td>
				<td><?php _e( 'Pop-Up Height', 'link-library' ); ?></td>
				<td>
					<input type="text" id="popup_height" name="popup_height" size="4" value="<?php if ( !isset( $options['popup_height'] ) || empty( $options['popup_height'] ) ) {
						echo '400';
					} else {
						echo strval( $options['popup_height'] );
					} ?>" /></td>
			</tr>
			<tr>
				<td><?php _e( 'Dialog contents', 'link-library' ); ?></td>
				<td colspan="5">
					<textarea id="link_popup_text" name="link_popup_text" cols="80" /><?php echo( isset( $options['link_popup_text'] ) ? stripslashes( $options['link_popup_text'] ) : '' ); ?></textarea>
				</td>
			</tr>
		</table>
		</div>
	<?php
	}

	function settingssets_rssconfig_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>
		<div style='padding-top:15px' id="ll-rssdisplay" class="content-section">
		<table>
			<tr>
				<td>
					<?php _e( 'Show RSS Link using Text', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="show_rss" name="show_rss" <?php checked( $options['show_rss'] ); ?>/>
				</td>
				<td>
					<?php _e( 'Show RSS Link using Standard Icon', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="show_rss_icon" name="show_rss_icon" <?php checked( $options['show_rss_icon'] ); ?>/>
				</td>
				<td></td>
				<td style='width:75px;padding-right:20px'></td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Show RSS Preview Link', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="rsspreview" name="rsspreview" <?php checked( $options['rsspreview'] ); ?>/>
				</td>
				<td>
					<?php _e( 'Number of articles shown in RSS Preview', 'link-library' ); ?>
				</td>
				<td>
					<input type="text" id="rsspreviewcount" name="rsspreviewcount" size="2" value="<?php echo strval( $options['rsspreviewcount'] ); ?>" />
				</td>
				<td>
					<?php _e( 'Show RSS Feed Headers in Link Library output', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="rssfeedinline" name="rssfeedinline" <?php checked( $options['rssfeedinline'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Show RSS Feed Content in Link Library output', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="rssfeedinlinecontent" name="rssfeedinlinecontent" <?php checked( $options['rssfeedinlinecontent'] ); ?>/>
				</td>
				<td>
					<?php _e( 'Number of RSS articles shown in Link Library Output', 'link-library' ); ?>
				</td>
				<td>
					<input type="text" id="rssfeedinlinecount" name="rssfeedinlinecount" size="2" value="<?php echo strval( $options['rssfeedinlinecount'] ); ?>" />
				</td>
				<td><?php _e( 'Max number of days since published', 'link-library' ); ?></td>
				<td><input type="text" id="rssfeedinlinedayspublished" name="rssfeedinlinedayspublished" size="2" value="<?php echo strval( $options['rssfeedinlinedayspublished'] ); ?>" /></td>
			</tr>
			<tr>
				<td><?php _e( 'RSS Preview Width', 'link-library' ); ?></td>
				<td>
					<input type="text" id="rsspreviewwidth" name="rsspreviewwidth" size="5" value="<?php echo strval( $options['rsspreviewwidth'] ); ?>" /></td></td>
				<td><?php _e( 'RSS Preview Height', 'link-library' ); ?></td>
				<td><input type="text" id="rsspreviewheight" name="rsspreviewheight" size="5" value="<?php echo strval( $options['rsspreviewheight'] ); ?>" /></td>
				<td><?php _e( 'Skip links with no RSS inline items', 'link-library' ); ?></td>
				<td><input type="checkbox" id="rssfeedinlineskipempty" name="rssfeedinlineskipempty" <?php checked( $options['rssfeedinlineskipempty'] ); ?>/></td>
			</tr>
		</table>
		</div>
	<?php
	}

	function settingssets_thumbnails_meta_box( $data ) {
		$options    = $data['options'];
		$genoptions = $data['genoptions'];
		$settings   = $data['settings'];
		?>

		<div style='padding-top:15px' id="ll-thumbnails" class="content-section">
		<table>
			<tr>
				<td style='width: 400px' class='lltooltip' title='<?php _e( 'Checking this option will get images from the Robothumb web site every time', 'link-library' ); ?>.'>
					<?php _e( 'Use thumbnail service for dynamic link images', 'link-library' ); ?>
				</td>
				<td class='lltooltip' title='<?php _e( 'Checking this option will get images from the thumbshots web site every time', 'link-library' ); ?>.' style='width:75px;padding-right:20px'>
					<input type="checkbox" id="usethumbshotsforimages" name="usethumbshotsforimages" <?php checked( $options['usethumbshotsforimages'] ); ?>/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Give priority to images assigned to links if present', 'link-library' ); ?>
				</td>
				<td>
					<input type="checkbox" id="uselocalimagesoverthumbshots" name="uselocalimagesoverthumbshots" <?php checked( $options['uselocalimagesoverthumbshots'] ); ?>/></td>
			</tr>
			<tr>
				<td><?php _e( 'Generate Images / Favorite Icons', 'link-library' ); ?></td>
				<td class="lltooltip" title="<?php if ( $genoptions['thumbnailgenerator'] == 'thumbshots' && empty( $genoptions['thumbshotscid'] ) ) {
					_e( 'This button is only available when a valid API key is entered under the Link Library General Settings.', 'link-library' );
				} ?>"><INPUT type="button" name="genthumbs" <?php disabled( $genoptions['thumbnailgenerator'] == 'thumbshots' && empty( $genoptions['thumbshotscid'] ) ); ?> value="<?php _e( 'Generate Thumbnails and Store locally', 'link-library' ); ?>" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=<?php echo $settings; ?>&amp;genthumbs=<?php echo $settings; ?>'">
				</td>
				<td>
					<INPUT type="button" name="genfavicons" value="<?php _e( 'Generate Favorite Icons and Store locally', 'link-library' ); ?>" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;settings=<?php echo $settings; ?>&amp;genfavicons=<?php echo $settings; ?>'">
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Delete all local thumbnails and icons', 'link-library' ); ?></td>
				<td><INPUT type="button" name="deleteallthumbs" value="<?php _e( 'Delete all local thumbnails', 'link-library' ); ?>" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;deleteallthumbs=1'"></td>
				<td><INPUT type="button" name="deleteallicons" value="<?php _e( 'Delete all local icons', 'link-library' ); ?>" onClick="window.location= 'admin.php?page=link-library-settingssets&amp;deleteallicons=1'"></td>
			</tr>
		</table>
		</div>
	<?php
	}

	function settingssets_rssgen_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>

		<div style='padding-top:15px' id="ll-rssfeed" class="content-section">
		<table>
			<tr>
				<td>
					<?php _e( 'Publish RSS Feed', 'link-library' ); ?>
				</td>
				<td style='width:75px;padding-right:20px'>
					<input type="checkbox" id="publishrssfeed" name="publishrssfeed" <?php checked( $options['publishrssfeed'] ); ?>/>
				</td>
				<td><?php _e( 'Number of items in RSS feed', 'link-library' ); ?></td>
				<td style='width:75px;padding-right:20px'>
					<input type="text" id="numberofrssitems" name="numberofrssitems" size="3" value="<?php if ( empty( $options['numberofrssitems'] ) ) {
						echo '10';
					} else {
						echo strval( $options['numberofrssitems'] );
					} ?>" /></td>
			</tr>
			<tr>
				<td><?php _e( 'RSS Feed Title', 'link-library' ); ?></td>
				<td colspan=3>
					<input type="text" id="rssfeedtitle" name="rssfeedtitle" size="80" value="<?php echo strval( esc_html( stripslashes( $options['rssfeedtitle'] ) ) ); ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'RSS Feed Description', 'link-library' ); ?></td>
				<td colspan=3>
					<input type="text" id="rssfeeddescription" name="rssfeeddescription" size="80" value="<?php echo strval( esc_html( stripslashes( $options['rssfeeddescription'] ) ) ); ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'RSS Feed Web Address (default yoursite.com/feed/linklibraryfeed?settingsset=1 )', 'link-library' ); ?></td>
				<td colspan=3>
					<input type="text" id="rssfeedaddress" name="rssfeedaddress" size="80" value="<?php echo strval( esc_html( stripslashes( $options['rssfeedaddress'] ) ) ); ?>" />
				</td>
			</tr>
		</table>
		</div>
	<?php
	}

	function settingssets_search_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>
		<div style='padding-top:15px' id="ll-searchfield" class="content-section">
			<table>
				<tr>
					<td style='width:200px'><?php _e( 'Search Label', 'link-library' ); ?></td>
					<?php if ( empty( $options['searchlabel'] ) ) {
						$options['searchlabel'] = __( 'Search', 'link-library' );
					} ?>
					<td style='padding-right:20px'>
						<input type="text" id="searchlabel" name="searchlabel" size="30" value="<?php echo $options['searchlabel']; ?>" />
					</td>
				</tr>
				<tr>
					<td style='width:200px'><?php _e( 'Search Field Initial Text', 'link-library' ); ?></td>
					<?php if ( empty( $options['searchfieldtext'] ) ) {
						$options['searchfieldtext'] = __( 'Search', 'link-library' );
					} ?>
					<td style='padding-right:20px'>
						<input type="text" id="searchfieldtext" name="searchfieldtext" size="30" value="<?php echo $options['searchfieldtext']; ?>" />
					</td>
				</tr>
				<tr>
					<td style='width:200px'><?php _e( 'Search No Results Text', 'link-library' ); ?></td>
					<?php if ( empty( $options['searchnoresultstext'] ) ) {
						$options['searchnoresultstext'] = __( 'No links found matching your search criteria', 'link-library' );
					} ?>
					<td style='padding-right:20px'>
						<input type="text" id="searchnoresultstext" name="searchnoresultstext" size="80" value="<?php echo $options['searchnoresultstext']; ?>" />
					</td>
				</tr>
				<tr>
					<td class="lltooltip" title='<?php _e( 'Leave empty when links are to be displayed on same page as search box', 'link-library' ); ?>'><?php _e( 'Results Page Address', 'link-library' ); ?></td>
					<td class="lltooltip" title='<?php _e( 'Leave empty when links are to be displayed on same page as search box', 'link-library' ); ?>'>
						<input type="text" id="searchresultsaddress" name="searchresultsaddress" size="80" value="<?php echo strval( esc_html( stripslashes( $options['searchresultsaddress'] ) ) ); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<?php _e( 'Show Reset Search button', 'link-library' ); ?>
					</td>
					<td style='width:75px;padding-right:20px'>
						<input type="checkbox" id="showsearchreset" name="showsearchreset" <?php checked( $options['showsearchreset'] ); ?>/>
					</td>
				</tr>
			</table>
		</div>
	<?php
	}

	function settingssets_linksubmission_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];

		if ( $options['showaddlinkrss'] === false ) {
			$options['showaddlinkrss'] = 'hide';
		} elseif ( $options['showaddlinkrss'] === true ) {
			$options['showaddlinkrss'] = 'show';
		}

		if ( $options['showaddlinkdesc'] === false ) {
			$options['showaddlinkdesc'] = 'hide';
		} elseif ( $options['showaddlinkdesc'] === true ) {
			$options['showaddlinkdesc'] = 'show';
		}

		if ( $options['showaddlinkcat'] === false ) {
			$options['showaddlinkcat'] = 'hide';
		} elseif ( $options['showaddlinkcat'] === true ) {
			$options['showaddlinkcat'] = 'show';
		}

		if ( $options['showaddlinknotes'] === false ) {
			$options['showaddlinknotes'] = 'hide';
		} elseif ( $options['showaddlinknotes'] === true ) {
			$options['showaddlinknotes'] = 'show';
		}

		if ( $options['addlinkcustomcat'] === false ) {
			$options['addlinkcustomcat'] = 'hide';
		} elseif ( $options['addlinkcustomcat'] === true ) {
			$options['addlinkcustomcat'] = 'show';
		}

		if ( $options['showaddlinkreciprocal'] === false ) {
			$options['showaddlinkreciprocal'] = 'hide';
		} elseif ( $options['showaddlinkreciprocal'] === true ) {
			$options['showaddlinkreciprocal'] = 'show';
		}

		if ( $options['showaddlinksecondurl'] === false ) {
			$options['showaddlinksecondurl'] = 'hide';
		} elseif ( $options['showaddlinksecondurl'] === true ) {
			$options['showaddlinksecondurl'] = 'show';
		}

		if ( $options['showaddlinktelephone'] === false ) {
			$options['showaddlinktelephone'] = 'hide';
		} elseif ( $options['showaddlinktelephone'] === true ) {
			$options['showaddlinktelephone'] = 'show';
		}

		if ( $options['showaddlinkemail'] === false ) {
			$options['showaddlinkemail'] = 'hide';
		} elseif ( $options['showaddlinkemail'] === true ) {
			$options['showaddlinkemail'] = 'show';
		}

		if ( $options['showlinksubmittername'] === false ) {
			$options['showlinksubmittername'] = 'hide';
		} elseif ( $options['showlinksubmittername'] === true ) {
			$options['showlinksubmittername'] = 'show';
		}

		if ( $options['showaddlinksubmitteremail'] === false ) {
			$options['showaddlinksubmitteremail'] = 'hide';
		} elseif ( $options['showaddlinksubmitteremail'] === true ) {
			$options['showaddlinksubmitteremail'] = 'show';
		}

		if ( $options['showlinksubmittercomment'] === false ) {
			$options['showlinksubmittercomment'] = 'hide';
		} elseif ( $options['showlinksubmittercomment'] === true ) {
			$options['showlinksubmittercomment'] = 'show';
		}

		if ( $options['showcustomcaptcha'] === false ) {
			$options['showcustomcaptcha'] = 'hide';
		} elseif ( $options['showcustomcaptcha'] === true ) {
			$options['showcustomcaptcha'] = 'show';
		}

		if ( $options['showuserlargedescription'] === false ) {
			$options['showuserlargedescription'] = 'hide';
		} elseif ( $options['showuserlargedescription'] === true ) {
			$options['showuserlargedescription'] = 'show';
		}
		?>
		<div style='padding-top:15px' id="ll-userform" class="content-section">
		<table>
		<tr>
			<td colspan=5 class="lltooltip" title='<?php _e( 'Following this link shows a list of all links awaiting moderation', 'link-library' ); ?>.'>
				<a href="<?php echo esc_url( add_query_arg( 's', 'LinkLibrary%3AAwaitingModeration%3ARemoveTextToApprove', admin_url( 'link-manager.php' ) ) ); ?>"><?php _e( 'View list of links awaiting moderation', 'link-library' ); ?></a>
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Show user links immediately', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="showuserlinks" name="showuserlinks" <?php checked( $options['showuserlinks'] ); ?>/></td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:250px'><?php _e( 'E-mail admin on link submission', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="emailnewlink" name="emailnewlink" <?php checked( $options['emailnewlink'] ); ?>/></td>
			<td style='width: 20px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Validate links with Akismet', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'><input type="checkbox" id="addlinkakismet" name="addlinkakismet" <?php checked( $options['addlinkakismet'] ); ?>/></td></td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:250px'><?php _e( 'E-mail submitter', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="emailsubmitter" name="emailsubmitter" <?php checked ( $options['emailsubmitter'] ); ?>/></td>
			<td style='width: 20px'></td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Additional text for link submitter e-mail', 'link-library' ); ?>
			</td>
			<td colspan="5">
				<textarea style="width:100%" name="emailextracontent"><?php echo $options['emailextracontent']; ?></textarea>
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Require login to display form', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="addlinkreqlogin" name="addlinkreqlogin" <?php checked( $options['addlinkreqlogin'] ); ?>/></td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:250px'><?php _e( 'Allow link submission with empty link', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="addlinknoaddress" name="addlinknoaddress" <?php checked( $options['addlinknoaddress'] ); ?>/></td>
			<td style='width: 20px'></td>
		</tr>
		<tr>
			<td style='width:200px' class='lltooltip' title='<?php _e( 'Determine if a captcha will be displayed on the user submission form. Select the captcha system (Easy Captcha or Google reCAPTCHA) to be used from General Options section', 'link-library' ); ?>.'><?php _e( 'Display captcha', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px' class='lltooltip' title='<?php _e( 'Determine if a captcha will be displayed on the user submission form. Select the captcha system (Easy Captcha or Google reCAPTCHA) to be used from General Options section', 'link-library' ); ?>.'>
				<input type="checkbox" id="showcaptcha" name="showcaptcha" <?php checked( $options['showcaptcha'] ); ?>/></td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td class='lltooltip' title='<?php _e( 'This function will only store data when users are logged in to Wordpress', 'link-library' ); ?>.' style='width:250px'><?php _e( 'Store login name on link submission', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="storelinksubmitter" name="storelinksubmitter" <?php checked( $options['storelinksubmitter'] ); ?>/></td>
			<td style='width: 20px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Add new link label', 'link-library' ); ?></td>
			<?php if ( $options['addnewlinkmsg'] == "" ) {
				$options['addnewlinkmsg'] = __( 'Add new link', 'link-library' );
			} ?>
			<td>
				<input type="text" id="addnewlinkmsg" name="addnewlinkmsg" size="30" value="<?php echo $options['addnewlinkmsg']; ?>" />
			</td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link address default value', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkaddrdefvalue" name="linkaddrdefvalue" size="30" value="<?php echo $options['linkaddrdefvalue']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link name label', 'link-library' ); ?></td>
			<?php if ( $options['linknamelabel'] == "" ) {
				$options['linknamelabel'] = __( 'Link Name', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linknamelabel" name="linknamelabel" size="30" value="<?php echo $options['linknamelabel']; ?>" />
			</td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link name tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linknametooltip" name="linknametooltip" size="30" value="<?php echo $options['linknametooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link address label', 'link-library' ); ?></td>
			<?php if ( $options['linkaddrlabel'] == "" ) {
				$options['linkaddrlabel'] = __( 'Link Address', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkaddrlabel" name="linkaddrlabel" size="30" value="<?php echo $options['linkaddrlabel']; ?>" />
			</td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link address tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkaddrtooltip" name="linkaddrtooltip" size="30" value="<?php echo $options['linkaddrtooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link RSS label', 'link-library' ); ?></td>
			<?php if ( $options['linkrsslabel'] == "" ) {
				$options['linkrsslabel'] = __( 'Link RSS', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkrsslabel" name="linkrsslabel" size="30" value="<?php echo $options['linkrsslabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinkrss" id="showaddlinkrss" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinkrss'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinkrss'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinkrss'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link RSS tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkrsstooltip" name="linkrsstooltip" size="30" value="<?php echo $options['linkrsstooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link category label', 'link-library' ); ?></td>
			<?php if ( $options['linkcatlabel'] == "" ) {
				$options['linkcatlabel'] = __( 'Link Category', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkcatlabel" name="linkcatlabel" size="30" value="<?php echo $options['linkcatlabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinkcat" id="showaddlinkcat" style="width:120px;">
					<option value="hide"<?php selected( $options['showaddlinkcat'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinkcat'] == 'show' ); ?>><?php _e( 'Drop-down list', 'link-library' ); ?></option>
					<option value="selectmultiple"<?php selected( $options['showaddlinkcat'] == 'selectmultiple' ); ?>><?php _e( 'Multi-select list', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px' class='lltooltip' title='<?php _e( 'Comma-seperated list of categories to be displayed in category selection box (e.g. 1,5,4) instead of displaying the set of categories specified by the library.', 'link-library' ); ?>'><?php _e( 'Link category override selection list', 'link-library' ); ?></td>
			<td colspan=3 class='lltooltip' title='<?php _e( 'Comma-seperated list of categories to be displayed in category selection box (e.g. 1,5,4)', 'link-library' ); ?>'>
				<input type="text" id="addlinkcatlistoverride" name="addlinkcatlistoverride" size="50" value="<?php echo $options['addlinkcatlistoverride']; ?>" />
			<td style='width:200px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Default category', 'link-library' ); ?></td>
			<td>
				<?php

				$include_links_array = explode( ',', $options['categorylist_cpt'] );
				$excluded_links_array = explode( ',', $options['excludecategorylist_cpt'] );
				$link_categories_query_args = array( 'hide_empty' => false );
				$link_categories_query_args['include'] = $include_links_array;
				$link_categories_query_args['exclude'] = $excluded_links_array;
				$linkcats = get_terms( 'link_library_category', $link_categories_query_args );

				if ( $linkcats ) { ?>
					<select name="addlinkdefaultcat" id="addlinkdefaultcat" value="<?php echo $options['addlinkdefaultcat']; ?>">
						<option value="nodefaultcat">No default category</option>
						<?php foreach ( $linkcats as $linkcat ) { ?>
							<option value="<?php echo $linkcat->term_id; ?>" <?php selected( $linkcat->term_id, $options['addlinkdefaultcat'] ); ?>><?php echo $linkcat->name; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
			</td>
			<td></td><td></td>
			<td style='width:200px'><?php _e( 'Select a category label', 'link-library' ); ?></td>
			<td>
				<input type="text" id="userlinkcatselectionlabel" name="userlinkcatselectionlabel" size="30" value="<?php echo $options['userlinkcatselectionlabel']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link category tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkcattooltip" name="linkcattooltip" size="30" value="<?php echo $options['linkcattooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'User-submitted category', 'link-library' ); ?></td>
			<?php if ( $options['linkcustomcatlabel'] == "" ) {
				$options['linkcustomcatlabel'] = __( 'User-submitted category', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkcustomcatlabel" name="linkcustomcatlabel" size="30" value="<?php echo $options['linkcustomcatlabel']; ?>" />
			</td>
			<td>
				<select name="addlinkcustomcat" id="addlinkcustomcat" style="width:60px;">
					<option value="hide"<?php selected( $options['addlinkcustomcat'] == 'hide' ); ?>><?php _e( 'No', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['addlinkcustomcat'] == 'show' ); ?>><?php _e( 'Allow', 'link-library' ); ?></option>
				</select>
			</td>
			<td></td>
			<td style='width:200px'><?php _e( 'User-submitted category prompt', 'link-library' ); ?></td>
			<?php if ( $options['linkcustomcatlistentry'] == "" ) {
				$options['linkcustomcatlistentry'] = __( 'User-submitted category (define below)', 'link-library' );
			} ?>
			<td colspan=3>
				<input type="text" id="linkcustomcatlistentry" name="linkcustomcatlistentry" size="50" value="<?php echo $options['linkcustomcatlistentry']; ?>" />
			</td>
			<td style='width:200px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'User-defined category tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkusercattooltip" name="linkusercattooltip" size="30" value="<?php echo $options['linkusercattooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Tags label', 'link-library' ); ?></td>
			<?php if ( empty( $options['linktagslabel'] ) ) {
				$options['linktagslabel'] = __( 'Link Tags', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linktagslabel" name="linktagslabel" size="30" value="<?php echo $options['linktagslabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinktags" id="showaddlinktags" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinktags'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinktags'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px' class='lltooltip' title='<?php _e( 'Comma-seperated list of tag IDs to be displayed in category selection box (e.g. 1,5,4) instead of displaying all tags', 'link-library' ); ?>'><?php _e( 'Link tags override selection list', 'link-library' ); ?></td>
			<td colspan=3 class='lltooltip' title='<?php _e( 'Comma-seperated list of tag IDs to be displayed in category selection box (e.g. 1,5,4)', 'link-library' ); ?>'>
				<input type="text" id="addlinktaglistoverride" name="addlinktaglistoverride" size="50" value="<?php echo $options['addlinktaglistoverride']; ?>" />
			<td style='width:200px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link tags tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linktagtooltip" name="linktagtooltip" size="30" value="<?php echo $options['linktagtooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'User-submitted tags label', 'link-library' ); ?></td>
			<?php if ( $options['linkcustomtaglabel'] == "" ) {
				$options['linkcustomtaglabel'] = __( 'User-submitted tags', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkcustomtaglabel" name="linkcustomtaglabel" size="30" value="<?php echo $options['linkcustomtaglabel']; ?>" />
			</td>
			<td>
				<select name="addlinkcustomtag" id="addlinkcustomtag" style="width:60px;">
					<option value="hide"<?php selected( $options['addlinkcustomtag'] == 'hide' ); ?>><?php _e( 'No', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['addlinkcustomtag'] == 'show' ); ?>><?php _e( 'Allow', 'link-library' ); ?></option>
				</select>
			</td>
			<td></td>
			<td style='width:200px'><?php _e( 'User-submitted tags prompt', 'link-library' ); ?></td>
			<?php if ( $options['linkcustomtaglistentry'] == "" ) {
				$options['linkcustomtaglistentry'] = __( 'User-submitted tag (define below)', 'link-library' );
			} ?>
			<td colspan=3>
				<input type="text" id="linkcustomtaglistentry" name="linkcustomtaglistentry" size="50" value="<?php echo $options['linkcustomtaglistentry']; ?>" />
			</td>
			<td style='width:200px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'User-defined tags tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkusertagtooltip" name="linkusertagtooltip" size="30" value="<?php echo $options['linkusertagtooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link description label', 'link-library' ); ?></td>
			<?php if ( $options['linkdesclabel'] == "" ) {
				$options['linkdesclabel'] = __( 'Link Description', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkdesclabel" name="linkdesclabel" size="30" value="<?php echo $options['linkdesclabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinkdesc" id="showaddlinkdesc" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinkdesc'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinkdesc'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinkdesc'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link description tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkdesctooltip" name="linkdesctooltip" size="30" value="<?php echo $options['linkdesctooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link notes label', 'link-library' ); ?></td>
			<?php if ( $options['linknoteslabel'] == "" ) {
				$options['linknoteslabel'] = __( 'Link Notes', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linknoteslabel" name="linknoteslabel" size="30" value="<?php echo $options['linknoteslabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinknotes" id="showaddlinknotes" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinknotes'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinknotes'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinknotes'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link notes tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linknotestooltip" name="linknotestooltip" size="30" value="<?php echo $options['linknotestooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td class='lltooltip' title="<?php _e('Reciprocal link must be configured for this option to work correctly', 'link-library' ); ?>"><?php _e( 'Show immediately if reciprocal link valid', 'link-library' ); ?></td>
			<td class='lltooltip' title="<?php _e('Reciprocal link must be configured for this option to work correctly', 'link-library' ); ?>"><input type="checkbox" id="showifreciprocalvalid" name="showifreciprocalvalid" <?php checked( $options['showifreciprocalvalid'] ); ?>/></td>
			<td></td>
			<td></td>
			<td><?php _e( 'Use Text Area for Notes', 'link-library' ); ?></td>
			<td>
				<input type="checkbox" id="usetextareaforusersubmitnotes" name="usetextareaforusersubmitnotes" <?php checked( $options['usetextareaforusersubmitnotes'] ); ?>/></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link image label', 'link-library' ); ?></td>
			<?php if ( $options['linkimagelabel'] == "" ) {
				$options['linkimagelabel'] = __( 'Link Image', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkimagelabel" name="linkimagelabel" size="30" value="<?php echo $options['linkimagelabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinkimage" id="showaddlinkimage" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinkimage'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinkimage'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinkimage'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link image tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkimagetooltip" name="linkimagetooltip" size="30" value="<?php echo $options['linkimagetooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Only allow one reciprocal link per domain', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="onereciprocaldomain" name="onereciprocaldomain" <?php checked( $options['onereciprocaldomain'] ); ?>/></td>
			<td style='width: 20px'></td>
			<td></td>
			<td style='width:200px'><?php _e( 'Only allow one link per domain', 'link-library' ); ?></td>
			<td style='width:75px;padding-right:20px'>
				<input type="checkbox" id="onelinkperdomain" name="onelinkperdomain" <?php checked( $options['onelinkperdomain'] ); ?>/></td>
			<td style='width: 20px'></td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Reciprocal Link label', 'link-library' ); ?></td>
			<?php if ( $options['linkreciprocallabel'] == "" ) {
				$options['linkreciprocallabel'] = __( 'Reciprocal Link', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkreciprocallabel" name="linkreciprocallabel" size="30" value="<?php echo $options['linkreciprocallabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinkreciprocal" id="showaddlinkreciprocal" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinkreciprocal'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinkreciprocal'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinkreciprocal'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Reciprocal Link tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkreciptooltip" name="linkreciptooltip" size="30" value="<?php echo $options['linkreciptooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Secondary Address label', 'link-library' ); ?></td>
			<?php if ( $options['linksecondurllabel'] == "" ) {
				$options['linksecondurllabel'] = __( 'Secondary Address', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linksecondurllabel" name="linksecondurllabel" size="30" value="<?php echo $options['linksecondurllabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinksecondurl" id="showaddlinksecondurl" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinksecondurl'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinksecondurl'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinksecondurl'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Secondary Address tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linksecondtooltip" name="linksecondtooltip" size="30" value="<?php echo $options['linksecondtooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link Telephone label', 'link-library' ); ?></td>
			<?php if ( $options['linktelephonelabel'] == "" ) {
				$options['linktelephonelabel'] = __( 'Telephone', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linktelephonelabel" name="linktelephonelabel" size="30" value="<?php echo $options['linktelephonelabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinktelephone" id="showaddlinktelephone" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinktelephone'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinktelephone'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinktelephone'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link Telephone tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linktelephonetooltip" name="linktelephonetooltip" size="30" value="<?php echo $options['linktelephonetooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link E-mail label', 'link-library' ); ?></td>
			<?php if ( $options['linkemaillabel'] == "" ) {
				$options['linkemaillabel'] = __( 'E-mail', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linkemaillabel" name="linkemaillabel" size="30" value="<?php echo $options['linkemaillabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinkemail" id="showaddlinkemail" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinkemail'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinkemail'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinkemail'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Link E-mail tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="linkemailtooltip" name="linkemailtooltip" size="30" value="<?php echo $options['linkemailtooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link Submitter Name label', 'link-library' ); ?></td>
			<?php if ( $options['linksubmitternamelabel'] == "" ) {
				$options['linksubmitternamelabel'] = __( 'Submitter Name', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linksubmitternamelabel" name="linksubmitternamelabel" size="30" value="<?php echo $options['linksubmitternamelabel']; ?>" />
			</td>
			<td>
				<select name="showlinksubmittername" id="showlinksubmittername" style="width:60px;">
					<option value="hide"<?php selected( $options['showlinksubmittername'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showlinksubmittername'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showlinksubmittername'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Submitter Name tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="submitternametooltip" name="submitternametooltip" size="30" value="<?php echo $options['submitternametooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link Submitter E-mail label', 'link-library' ); ?></td>
			<?php if ( $options['linksubmitteremaillabel'] == "" ) {
				$options['linksubmitteremaillabel'] = __( 'Submitter E-mail', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linksubmitteremaillabel" name="linksubmitteremaillabel" size="30" value="<?php echo $options['linksubmitteremaillabel']; ?>" />
			</td>
			<td>
				<select name="showaddlinksubmitteremail" id="showaddlinksubmitteremail" style="width:60px;">
					<option value="hide"<?php selected( $options['showaddlinksubmitteremail'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showaddlinksubmitteremail'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showaddlinksubmitteremail'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Submitter E-mail tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="submitteremailtooltip" name="submitteremailtooltip" size="30" value="<?php echo $options['submitteremailtooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Link Submitter Comment Label', 'link-library' ); ?></td>
			<?php if ( $options['linksubmittercommentlabel'] == "" ) {
				$options['linksubmittercommentlabel'] = __( 'Submitter Comment', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linksubmittercommentlabel" name="linksubmittercommentlabel" size="30" value="<?php echo $options['linksubmittercommentlabel']; ?>" />
			</td>
			<td>
				<select name="showlinksubmittercomment" id="showlinksubmittercomment" style="width:60px;">
					<option value="hide"<?php selected( $options['showlinksubmittercomment'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showlinksubmittercomment'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showlinksubmittercomment'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Submitter Comment tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="submittercommenttooltip" name="submittercommenttooltip" size="30" value="<?php echo $options['submittercommenttooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Large Description Label', 'link-library' ); ?></td>
			<?php if ( $options['linklargedesclabel'] == "" ) {
				$options['linklargedesclabel'] = __( 'Large Description', 'link-library' );
			} ?>
			<td>
				<input type="text" id="linklargedesclabel" name="linklargedesclabel" size="30" value="<?php echo $options['linklargedesclabel']; ?>" />
			</td>
			<td>
				<select name="showuserlargedescription" id="showuserlargedescription" style="width:60px;">
					<option value="hide"<?php selected( $options['showuserlargedescription'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showuserlargedescription'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
					<option value="required"<?php selected( $options['showuserlargedescription'] == 'required' ); ?>><?php _e( 'Required', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Large Description tooltip', 'link-library' ); ?></td>
			<td>
				<input type="text" id="largedesctooltip" name="largedesctooltip" size="30" value="<?php echo $options['largedesctooltip']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Custom Captcha Question', 'link-library' ); ?></td>
			<?php if ( $options['customcaptchaquestion'] == "" ) {
				$options['customcaptchaquestion'] = __( 'Is boiling water hot or cold?', 'link-library' );
			} ?>
			<td>
				<input type="text" id="customcaptchaquestion" name="customcaptchaquestion" size="30" value="<?php echo $options['customcaptchaquestion']; ?>" />
			</td>
			<td>
				<select name="showcustomcaptcha" id="showcustomcaptcha" style="width:60px;">
					<option value="hide"<?php selected( $options['showcustomcaptcha'] == 'hide' ); ?>><?php _e( 'Hide', 'link-library' ); ?></option>
					<option value="show"<?php selected( $options['showcustomcaptcha'] == 'show' ); ?>><?php _e( 'Show', 'link-library' ); ?></option>
				</select>
			</td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'Custom Captcha Answer', 'link-library' ); ?></td>
			<?php if ( $options['customcaptchaanswer'] == "" ) {
				$options['customcaptchaanswer'] = __( 'hot', 'link-library' );
			} ?>
			<td>
				<input type="text" id="customcaptchaanswer" name="customcaptchaanswer" size="30" value="<?php echo $options['customcaptchaanswer']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'Add Link button label', 'link-library' ); ?></td>
			<?php if ( $options['addlinkbtnlabel'] == "" ) {
				$options['addlinkbtnlabel'] = __( 'Add Link', 'link-library' );
			} ?>
			<td>
				<input type="text" id="addlinkbtnlabel" name="addlinkbtnlabel" size="30" value="<?php echo $options['addlinkbtnlabel']; ?>" />
			</td>
			<td style='width: 20px'></td>
			<td style='width: 20px'></td>
			<td style='width:200px'><?php _e( 'New Link Message', 'link-library' ); ?></td>
			<?php if ( $options['newlinkmsg'] == "" ) {
				$options['newlinkmsg'] = __( 'New link submitted', 'link-library' );
			} ?>
			<td>
				<input type="text" id="newlinkmsg" name="newlinkmsg" size="30" value="<?php echo $options['newlinkmsg']; ?>" />
			</td>
		</tr>
		<tr>
			<td style='width:200px'><?php _e( 'New Link Moderation Label', 'link-library' ); ?></td>
			<?php if ( $options['moderatemsg'] == "" ) {
				$options['moderatemsg'] = __( 'it will appear in the list once moderated. Thank you.', 'link-library' );
			} ?>
			<td colspan=6>
				<input type="text" id="moderatemsg" name="moderatemsg" size="90" value="<?php echo $options['moderatemsg']; ?>" />
			</td>
		</tr>
		</table>
		</div>

	<?php
	}

	function settingssets_importexport_meta_box( $data ) {
		$options  = $data['options'];
		$settings = $data['settings'];
		?>

		<div style='padding-top:15px' id="ll-importexport" class="content-section">
		<input type='hidden' value='<?php echo $settings; ?>' name='settingsetid' id='settingsetid' />
		<table>
			<tr>
				<td class='lltooltip' title='<?php _e( 'Overwrites current library settings with contents of CSV file', 'link-library' ); ?>' style='width: 330px'><?php _e( 'Library Settings CSV file to import', 'link-library' ); ?></td>
				<td><input size="80" name="settingsfile" type="file" /></td>
				<td>
					<input type="submit" name="importsettings" value="<?php _e( 'Import Library Settings', 'link-library' ); ?>" />
				</td>
			</tr>
			<tr>
				<td class='lltooltip' style='width: 330px' title='<?php _e( 'Generates CSV file with current library configuration for download', 'link-library' ); ?>'><?php _e( 'Export current library settings', 'link-library' ); ?></td>
				<td>
					<input type="submit" name="exportsettings" value="<?php _e( 'Export Library Settings', 'link-library' ); ?>" />
				</td>
			</tr>
		</table>
		</div>
	<?php
	}

	function reciprocal_meta_box( $data ) {
		$genoptions = $data['genoptions'];
		?>
		<table>
			<tr>
				<td style='width: 250px'><?php _e( 'Search string', 'link-library' ); ?></td>
				<td>
					<input type="text" id="recipcheckaddress" name="recipcheckaddress" size="60" value="<?php echo $genoptions['recipcheckaddress']; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Delete links that return a 403 error', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="recipcheckdelete403" name="recipcheckdelete403" <?php checked( $genoptions['recipcheckdelete403'] ); ?>/></td>
			</tr>
			<tr>
				<td>
					<input type='submit' id="recipcheck" name="recipcheck" value="<?php _e( 'Check Reciprocal Links', 'link-library' ); ?>" />
				</td>
			</tr>
			<tr><td colspan="2"><hr /></td></tr>
			<tr>
				<td>
					<input type='submit' id="brokencheck" name="brokencheck" value="<?php _e( 'Check Broken Links', 'link-library' ); ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<input type='submit' id="duplicatecheck" name="duplicatecheck" value="<?php _e( 'Check Duplicate Links', 'link-library' ); ?>" />
				</td>
			</tr>
		</table>

	<?php
	}


	/************************************************ Render Custom Meta Box in Link Editor *******************************************/

	function ll_link_basic_info( $link ) {
		$genoptions = get_option( 'LinkLibraryGeneral' );
		$genoptions = wp_parse_args( $genoptions, ll_reset_gen_settings( 'return' ) );
		extract( $genoptions );

		$link_url = get_post_meta( $link->ID, 'link_url', true );

		if ( empty( $link_url ) && isset( $_GET['linkurl'] ) ) {
			$link_url = esc_url( urldecode( $_GET['linkurl'] ) );
		}

		$link_description = get_post_meta( $link->ID, 'link_description', true );
		$link_description = htmlentities( $link_description );
		$link_textfield = get_post_meta( $link->ID, 'link_textfield', true );

		if ( metadata_exists( 'post', $link->ID, 'link_target' ) ) {
			$link_target = get_post_meta( $link->ID, 'link_target', true );
		} else {
			$link_target = $defaultlinktarget;
		}

		$link_rss = get_post_meta( $link->ID, 'link_rss', true );
		$link_notes = get_post_meta( $link->ID, 'link_notes', true );
		$link_notes = htmlentities( $link_notes );
		wp_nonce_field( plugin_basename( __FILE__ ), 'link_edit_nonce' );
		?>

		<table style="width:100%">
			<tr>
				<td style="width:20%"><?php _e( 'Web Address', 'link-library' ); ?></td>
				<td><input type="text" style="width:100%" id="link_url" type="link_url" name="link_url" value="<?php echo $link_url; ?>" tabindex="1"></td>
			</tr>
			<tr>
				<td style="width:20%"><?php _e( 'Description', 'link-library' ); ?></td>
				<td><input type="text" style="width:100%" id="link_description" type="link_description" name="link_description" value="<?php echo $link_description; ?>" tabindex="2"></td>
			</tr>
			<tr>
				<td><?php _e( 'Notes', 'link-library' ); ?></td>
				<td><textarea style="width:100%" name="link_notes" id="link_notes" rows="5"><?php echo $link_notes; ?></textarea></td>
			</tr>
			<tr>
				<td><?php _e( 'Large Description', 'link-library' ); ?></td>
				<td>
					<?php
					$editorsettings = array( 'media_buttons' => false,
											 'textarea_rows' => 5,
											 'textarea_name' => 'link_textfield',
											 'wpautop' => false );

					wp_editor( isset( $link_textfield ) ? stripslashes( $link_textfield ) : '', 'link_textfield', $editorsettings ); ?>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'RSS Address', 'link-library' ); ?></td>
				<td><input type="text" style="width:100%" id="link_rss" type="link_rss" name="link_rss" value="<?php echo $link_rss; ?>"></td>
			</tr>
			<tr>
				<td><?php _e( 'Target', 'link-library' ); ?></td>
				<td><?php
					$target_array = array( '_blank' => '_blank (new window or tab)', '' => '_none (same window or tab)', '_top' => '_top (current window or tab, with no frames)' );
					echo '<select name="link_target" id="link_target">';
					echo '<option value="' . $defaultlinktarget . '" ' . selected( $defaultlinktarget, $link_target ) . '>' . $target_array[$defaultlinktarget] . '</option>';
					unset( $target_array[$defaultlinktarget] );
					foreach ( $target_array as $target_value => $target_item ) {
						echo '<option value="' . $target_value . '" ' . selected( $target_value, $link_target ) . '>' . $target_item . '</option>';
					}
					echo '</select>';
					?></td>
			</tr>
		</table>
	<?php }

	function ll_link_image_info( $link ) {
		$genoptions = get_option( 'LinkLibraryGeneral' );
		$link_image = get_post_meta( $link->ID, 'link_image', true );
		?>
		<table>
			<tr>
				<td><?php _e( 'Current Image', 'link-library' ); ?></td>
				<td>
					<div id='current_link_image'>
						<?php if ( isset( $link_image ) && !empty( $link_image ) ): ?>
							<img id="actual_link_image" src="<?php echo $link_image ?>" />
						<?php else: ?>
							<span id="noimage"><?php _e( 'None Assigned', 'link-library' ); ?></span>
						<?php endif; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Image URL', 'link-library' ); ?></td>
				<td><input type="text" style="width:100%" id="link_image" type="link_image" name="link_image" value="<?php echo $link_image; ?>"></td>
			</tr>
			<?php if ( isset( $link->ID ) && !empty( $link->ID ) ): ?>
				<tr>
					<td><?php _e( 'Automatic Image Generation', 'link-library' ); ?></td>
					<td title="<?php if ( ( $genoptions['thumbnailgenerator'] == 'thumbshots' && empty( $genoptions['thumbshotscid'] ) ) || ( $genoptions['thumbnailgenerator'] == 'shrinktheweb' && empty( $genoptions['shrinkthewebaccesskey'] ) ) ) {
						_e( 'This button is only available when a valid API key is entered under the Link Library General Settings.', 'link-library' );
					} ?>">
						<INPUT type="button" id="genthumbs" name="genthumbs" <?php disabled( ( $genoptions['thumbnailgenerator'] == 'thumbshots' && empty( $genoptions['thumbshotscid'] ) ) || ( $genoptions['thumbnailgenerator'] == 'shrinktheweb' && empty( $genoptions['shrinkthewebaccesskey'] ) ) );?>value="<?php _e( 'Generate Thumbnail and Store locally', 'link-library' ); ?>">
						<INPUT type="button" id="genfavicons" name="genfavicons" value="<?php _e( 'Generate Favorite Icon and Store locally', 'link-library' ); ?>">
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<td><?php _e( 'Automatic Image Generation', 'link-library' ); ?></td>
					<td><?php _e( 'Only available once link is saved', 'link-library' ); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( function_exists( 'wp_enqueue_media' ) ) { ?>
				<tr>
					<td><?php _e( 'Image Upload', 'link-library' ); ?></td>
					<td>
						<input type="button" class="upload_image_button" value="<?php _e( 'Launch Media Uploader', 'link-library' ); ?>">
					</td>
				</tr>
			</table>
			<?php } ?>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				// Uploading files
				var file_frame;

				jQuery('.upload_image_button').on('click', function (event) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if (file_frame) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title   : jQuery(this).data('uploader_title'),
						button  : {
							text: jQuery(this).data('uploader_button_text')
						},
						multiple: false  // Set to true to allow multiple files to be selected
					});

					// When an image is selected, run a callback.
					file_frame.on('select', function () {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();

						// Do something with attachment.id and/or attachment.url here
						jQuery('#link_image').val(attachment.url);

						jQuery('#current_link_image').replaceWith("<div id='current_link_image'><img src='" + attachment.url + "' /></div>");
						jQuery('#current_link_image').fadeIn('fast');
					});

					// Finally, open the modal
					file_frame.open();
				});

				jQuery("#ll_updated_manual").click(function () {
					if (jQuery('#ll_updated_manual').is(':checked')) {
						jQuery('#ll_link_updated').attr('disabled', false);
					} else {
						jQuery('#ll_link_updated').attr('disabled', true);
					}
				});
				// Using jQuery, set both the enctype and the encoding
				// attributes to be multipart/form-data.
				jQuery("form#editlink")
					.attr("enctype", "multipart/form-data")
					.attr("encoding", "multipart/form-data")
					.attr( "accept-charset", "UTF-8" )
				;
				jQuery("form#addlink")
					.attr("enctype", "multipart/form-data")
					.attr("encoding", "multipart/form-data")
					.attr( "accept-charset", "UTF-8" )
				;
				jQuery('#genthumbs').click(function () {
					var linkname = jQuery('#title').val();
					var linkurl = jQuery('#link_url').val();

					if ( linkname != '' && linkurl != '' ) {
						jQuery('#current_link_image').fadeOut('fast');

						jQuery.ajax({
							type   : 'POST',
							url    : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							data   : {
								action      : 'link_library_generate_image',
								_ajax_nonce : '<?php echo wp_create_nonce( 'link_library_generate_image' ); ?>',
								name        : linkname,
								url         : linkurl,
								mode        : 'thumbonly',
								cid         : '<?php echo $genoptions['thumbshotscid']; ?>',
								filepath    : 'link-library-images',
								filepathtype: 'absolute',
								linkid      : <?php if( isset( $link->ID ) ) { echo $link->ID; } else { echo "''"; } ?>
							},
							success: function (data) {
								if (data != '') {
									jQuery('#current_link_image').replaceWith("<div id='current_link_image'><img src='" + data + "' /></div>");
									jQuery('#current_link_image').fadeIn('fast');
									jQuery('#link_image').val(data);
									alert('<?php _e('Thumbnail successfully generated for', 'link-library'); ?> ' + linkname);
								}
							}
						});
					}
					else {
						alert("<?php _e('Cannot generate thumbnail when no name and no web address are specified.', 'link-library'); ?>");
					}
				});

				jQuery('#genfavicons').click(function () {
					var linkname = jQuery('#title').val();
					var linkurl = jQuery('#link_url').val();

					if (linkname != '' && linkurl != '') {
						jQuery('#current_link_image').fadeOut('fast');
						jQuery.ajax({
							type   : 'POST',
							url    : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							data   : {
								action      : 'link_library_generate_image',
								_ajax_nonce : '<?php echo wp_create_nonce( 'link_library_generate_image' ); ?>',
								name        : linkname,
								url         : linkurl,
								cid         : '<?php echo $genoptions['thumbshotscid']; ?>',
								mode        : 'favicononly',
								filepath    : 'link-library-favicons',
								filepathtype: 'absolute',
								linkid      : <?php if( isset( $link->ID ) ) { echo $link->ID; } else { echo "''"; }?>
							},
							success: function (data) {
								if (data != '') {
									jQuery('#current_link_image').replaceWith("<div id='current_link_image'><img src='" + data + "' /></div>");
									jQuery('#current_link_image').fadeIn('fast');
									jQuery('#link_image').val(data);
									alert('<?php _e('Favicon successfully generated for', 'link-library') ?> ' + linkname);
								}
							}
						});
					}
					else {
						alert("<?php _e('Cannot generate favorite icon when no name and no web address are specified.', 'link-library'); ?>");
					}
				});

			});
		</script>
	<?php }

	function ll_link_edit_extra( $link ) {
		$link_featured = get_post_meta( $link->ID, 'link_featured', true );
		$link_no_follow = get_post_meta( $link->ID, 'link_no_follow', true );
		$link_rating = get_post_meta( $link->ID, 'link_rating', true );
		$link_second_url = get_post_meta( $link->ID, 'link_second_url', true );
		$link_telephone = get_post_meta( $link->ID, 'link_telephone', true );
		$link_email = get_post_meta( $link->ID, 'link_email', true );
		$link_submitter = get_post_meta( $link->ID, 'link_submitter', true );
		$link_submitter_name = get_post_meta( $link->ID, 'link_submitter_name', true );
		$link_submitter_email = get_post_meta( $link->ID, 'link_submitter_email', true );
		$link_price = get_post_meta( $link->ID, 'link_price', true );
		$link_reciprocal = get_post_meta( $link->ID, 'link_reciprocal', true );
		$link_rel = get_post_meta( $link->ID, 'link_rel', true );

		$link_updated = get_post_meta( $link->ID, 'link_updated', true );

		$link_updated_manual = get_post_meta( $link->ID, 'link_updated_manual', true );

		if ( empty( $link_updated ) ) {
			$link_updated = current_time( 'mysql' );
		} else {
			$link_updated = date( "Y-m-d H:i", $link_updated );
		}

		$link_visits = get_post_meta( $link->ID, 'link_visits', true );

		if ( empty( $link_visits ) ) {
			$link_visits = 0;
		}
		?>

		<input type="hidden" name="form_submitted" value="true">
		<table>
			<tr>
				<td style='width: 200px'><?php _e( 'Featured Item', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="link_featured" name="link_featured" <?php checked( $link_featured ); ?>/>
				</td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e( 'No Follow', 'link-library' ); ?></td>
				<td>
					<input type="checkbox" id="link_no_follow" name="link_no_follow" <?php checked( $link_no_follow ); ?>/>
				</td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e( 'Rating', 'link-library' ); ?></td>
				<td>
					<select name="link_rating" id="link_rating">
					<?php for( $counter = 0; $counter <= 10; $counter++ ) {
						echo '<option value="' . $counter . '" ' . selected( $counter, $link_rating, false ). '>' . $counter . '</option>';
					} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e( 'Updated Date', 'link-library' ); ?></td>
				<td><span style="float:left;padding-top:6px">Set Manually
					<input type="checkbox" id="link_updated_manual" name="link_updated_manual" <?php checked( $link_updated_manual ); ?>/></span>
					<input type="text" <?php if ( !$link_updated_manual || empty( $link_updated_manual ) ) {
						echo 'disabled="disabled"';
					} ?> id="link_updated" name="link_updated" size="50%" value="<?php echo $link_updated; ?>" />
				</td>
			</tr>
			<tr>
				<td style='width: 200px'><?php _e( 'Secondary Web Address', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_second_url" name="link_second_url" style="width:100%" value="<?php echo $link_second_url; ?>" /> <?php if ( !empty( $link_second_url ) ) {
						echo " <a href=" . esc_html( $link_second_url ) . ">" . __( 'Visit', 'link-library' ) . "</a>";
					} ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Telephone', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_telephone" name="link_telephone" style="width:100%" value="<?php echo $link_telephone; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'E-mail', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_email" name="link_email" style="width:100%" value="<?php echo $link_email; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Reciprocal Link', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_reciprocal" name="link_reciprocal" style="width:100%" value="<?php echo $link_reciprocal; ?>" /> <?php if ( !empty( $link_reciprocal ) ) {
						echo " <a href=" . esc_url( stripslashes( $link_reciprocal ) ) . ">" . __( 'Visit', 'link-library' ) . "</a>";
					} ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Number of views', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_visits" name="link_visits" style="width:100%" value="<?php echo $link_visits; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Submitter', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_submitter" name="link_submitter" style="width:100%" value="<?php echo $link_submitter; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Submitter Name', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_submitter_name" name="link_submitter_name" style="width:100%" value="<?php echo $link_submitter_name; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Submitter E-mail', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_submitter_email" name="link_submitter_email" style="width:100%" value="<?php echo $link_submitter_email; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Rel Tags', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_rel" name="link_rel" size="80" value="<?php echo $link_rel; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Price', 'link-library' ); ?></td>
				<td>
					<input type="text" id="link_price" name="link_price" size="10" value="<?php echo $link_price; ?>" />
				</td>
			</tr>
		</table>

		<script type="text/javascript">
		jQuery( document ).ready(function() {
            jQuery("#link_updated_manual").click(function () {
				if (jQuery('#link_updated_manual').is(':checked')) {
					jQuery('#link_updated').attr('disabled', false);
				} else {
					jQuery('#link_updated').attr('disabled', true);
				}
			});
		});
		</script>

	<?php
	}

	function ll_save_link_fields( $link_id, $link ) {
		$genoptions = get_option( 'LinkLibraryGeneral' );
		$genoptions = wp_parse_args( $genoptions, ll_reset_gen_settings( 'return' ) );

		if ( $link->post_type == 'link_library_links' && isset( $_POST['action'] ) && 'editpost' == $_POST['action'] ) {
			$array_urls = array( 'link_rss', 'link_second_url', 'link_reciprocal', 'link_image', 'link_url' );
			foreach ( $array_urls as $array_url ) {
				if ( isset( $_POST[$array_url] ) ) {
					$submitted_url = $_POST[$array_url];

					if ( 'link_image' == $array_url ) {
						if ( empty( $submitted_url ) ) {
							delete_post_thumbnail( $link_id );
						} else {
							$previous_image_url = get_post_meta( $link_id, 'link_image', true );

							if ( has_post_thumbnail( $link_id ) && $submitted_url == $previous_image_url ) {
								break;
							}

							global $wpdb;
							$upload_dir_pos = strpos( $submitted_url, wp_upload_dir()['baseurl'] );

							if ( $upload_dir_pos !== false ) {
								$relative_file_path = substr( $submitted_url, strlen ( wp_upload_dir()['baseurl'] ) + 1 );
							}

							$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$submitted_url'";
							$attachmentId = intval($wpdb->get_var($query));

							if ( empty( $attachmentId ) ) {
								$wpFileType = wp_check_filetype( $submitted_url, null);

								$attachment = array(
									'post_mime_type' => $wpFileType['type'],  // file type
									'post_title' => sanitize_file_name( $submitted_url ),  // sanitize and use image name as file name
									'post_content' => '',  // could use the image description here as the content
									'post_status' => 'inherit'
								);

								// insert and return attachment id
								$attachmentId = wp_insert_attachment( $attachment, $submitted_url, $link_id );
								$attachmentData = wp_generate_attachment_metadata( $attachmentId, $submitted_url );
								wp_update_attachment_metadata( $attachmentId, $attachmentData );
							} else {
								set_post_thumbnail( $link_id, $attachmentId );
							}
						}
					}

					if ( 'https' == $genoptions['defaultprotocoladmin'] ) {
						$submitted_url = str_replace( ' ', '%20', $submitted_url );
						$submitted_url = preg_replace( '|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $submitted_url );

						if ( 0 !== stripos( $submitted_url, 'mailto:' ) ) {
							$strip = array( '%0d', '%0a', '%0D', '%0A' );
							$submitted_url   = _deep_replace( $strip, $submitted_url );
						}

						$submitted_url = str_replace( ';//', '://', $submitted_url );
						/* If the URL doesn't appear to contain a scheme, we
						 * presume it needs http:// prepended (unless a relative
						 * link starting with /, # or ? or a php file).
						 */
						if ( !empty( $submitted_url ) && strpos( $submitted_url, ':' ) === false && ! preg_match( '/^[a-z0-9-]+?\.php/i', $submitted_url ) ) {
							$submitted_url = 'https://' . $submitted_url;
						}
					}

					update_post_meta( $link_id, $array_url, esc_url( $submitted_url ) );
				}
			}

			$array_escape_items = array( 'link_description', 'link_notes', 'link_telephone', 'link_reciprocal', 'link_submitter', 'link_submitter_name', 'link_rel' );
			foreach ( $array_escape_items as $array_escape_item ) {
				if ( isset( $_POST[$array_escape_item] ) ) {
					update_post_meta( $link_id, $array_escape_item, sanitize_text_field( $_POST[$array_escape_item] ) );
				}
			}

			if ( isset( $_POST['link_updated'] ) && !empty( $_POST['link_updated'] ) ) {
				update_post_meta( $link_id, 'link_updated', strtotime( $_POST['link_updated'] ) );
			} else {
				update_post_meta( $link_id, 'link_updated', current_time( 'timestamp' ) );
			}

			$array_kses_items = array( 'link_textfield' );
			foreach ( $array_kses_items as $array_kses_item ) {
				if ( isset( $_POST[$array_kses_item] ) ) {
					update_post_meta( $link_id, $array_kses_item, wp_kses_post( $_POST[$array_kses_item] ) );
				}
			}

			$array_email_items = array( 'link_email', 'link_submitter_email' );
			foreach ( $array_email_items as $array_email_item ) {
				if ( isset( $_POST[$array_email_item] ) ) {
					update_post_meta( $link_id, $array_email_item, sanitize_email( $_POST[$array_email_item] ) );
				}
			}

			$array_price_items = array( 'link_price' );
			foreach ( $array_price_items as $array_price_item ) {
				if ( isset( $_POST[$array_price_item] ) ) {
					update_post_meta( $link_id, $array_price_item, floatval( $_POST[$array_price_item] ) );
				}
			}

			$array_num_items = array( 'link_rating', 'link_visits' );
			foreach ( $array_num_items as $array_num_item ) {
				if ( isset( $_POST[$array_num_item] ) ) {
					update_post_meta( $link_id, $array_num_item, intval( $_POST[$array_num_item] ) );
				}
			}

			$array_direct_items = array( 'link_target' );
			foreach ( $array_direct_items as $array_direct_item ) {
				if ( isset( $_POST[$array_direct_item] ) ) {
					update_post_meta( $link_id, $array_direct_item, $_POST[$array_direct_item] );
				}
			}

			$array_boolean_items = array( 'link_featured', 'link_no_follow', 'link_updated_manual' );
			foreach ( $array_boolean_items as $array_boolean_item ) {
				if ( isset( $_POST[$array_boolean_item] ) ) {
					update_post_meta( $link_id, $array_boolean_item, true );
				} else {
					update_post_meta( $link_id, $array_boolean_item, false );
				}
			}

			if ( isset( $_POST['link_featured'] ) ) {
				update_post_meta( $link_id, 'link_featured', 1 );
			} else {
				update_post_meta( $link_id, 'link_featured', 0 );
			}

			$newrating = intval( $_POST['link_rating'] );
			if ( $newrating < 0 ) {
				$newrating = 0;
			} elseif ( $newrating > 10 ) {
				$newrating = 10;
			}
		} elseif ( $link->post_type == 'link_library_links' && isset( $_POST['action'] ) && 'inline-save' == $_POST['action'] ) {
			$array_urls_nonce = array( 'link_url' );
			foreach ( $array_urls_nonce as $array_url_nonce ) {
				if ( isset( $_POST[$array_url_nonce] ) && !empty( $_POST[$array_url_nonce] ) ) {
					if ( wp_verify_nonce( $_POST['link_edit_nonce'], plugin_basename( __FILE__ ) ) ) {
						update_post_meta( $link_id, $array_url_nonce, esc_url( $_POST[$array_url_nonce] ) );
					}
				}
			}
		}
	}

	function ll_add_columns( $columns ) {
		$columns['link_library_updated'] = 'Updated';
		$columns['link_library_url'] = 'URL';
		$columns['link_library_categories'] = 'Categories';
		$columns['link_library_tags'] = 'Tags';
		$columns['link_library_rating'] = 'Rating';
		$columns['link_library_visits'] = 'Hits';
		$columns['date'] = 'Publication Date';
		unset( $columns['comments'] );
		return $columns;
	}

	function ll_populate_columns( $column ) {
		if ( 'link_library_updated' == $column ) {
			$link_updated = get_post_meta( get_the_ID(), 'link_updated', true );

			if ( !empty( $link_updated ) ) {
				$date_diff = time() - intval( $link_updated );

				echo date( "Y-m-d H:i", $link_updated );

				if ( $date_diff < 604800 ) {
					echo '<br /><strong>** RECENTLY UPDATED **</strong>';
				}
			}
		} elseif ( 'link_library_url' == $column ) {
			$link_url = esc_url( get_post_meta( get_the_ID(), 'link_url', true ) );
			echo '<a href="' . $link_url . '">' . $link_url . '</a>';
		} elseif ( 'link_library_categories' == $column ) {
			$link_categories = wp_get_post_terms( get_the_ID(), 'link_library_category' );
			if ( $link_categories ) {
				$countcats = 0;
				foreach ( $link_categories as $link_category ) {
					if ( $countcats >= 1 ) {
						echo ', ';
					}
					echo $link_category->name;
					$countcats++;
				}
			} else {
				echo 'None Assigned';
			}
		} elseif ( 'link_library_tags' == $column ) {
			$link_tags = wp_get_post_terms( get_the_ID(), 'link_library_tags' );
			if ( $link_tags ) {
				$counttags = 0;
				foreach ( $link_tags as $link_tag ) {
					if ( $counttags >= 1 ) {
						echo ', ';
					}
					echo $link_tag->name;
					$counttags++;
				}
			} else {
				echo 'None Assigned';
			}
		} elseif ( 'link_library_rating' == $column ) {
			$link_rating = esc_html( get_post_meta( get_the_ID(), 'link_rating', true ) );
			echo $link_rating;
		} elseif ( 'link_library_visits' == $column ) {
			$link_visits = esc_html( get_post_meta( get_the_ID(), 'link_visits', true ) );
			echo $link_visits;
		}
	}

	function ll_column_sortable( $columns ) {
		$columns['link_library_updated'] = 'link_library_updated';
		$columns['link_library_url'] = 'link_library_url';
		$columns['link_library_rating'] = 'link_library_rating';

		return $columns;
	}

	function ll_column_ordering( $vars ) {
		if ( !is_admin() ) {
			return $vars;
		}

		if ( isset( $vars['orderby'] ) && 'link_library_updated' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'link_updated',
				'orderby' => 'meta_value' ) );
		} elseif ( isset( $vars['orderby'] ) && 'link_library_url' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'link_url',
				'orderby' => 'meta_value' ) );
		} elseif ( isset( $vars['orderby'] ) && 'link_library_rating' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'link_rating',
				'orderby' => 'meta_value_num' ) );
		}

		return $vars;
	}

	function ll_custom_post_order( $query ) {
        /* The current post type. */
        $post_type = $query->get('post_type');
        /* Check post types. */
        if ( $post_type == 'link_library_links' ) {
	        /* Post Column: e.g. title */
	        if ( $query->get('orderby') == '' ) {
	            $query->set('orderby', 'title');
	        }
	        /* Post Order: ASC / DESC */
	        if ( $query->get('order') == '' ) {
	            $query->set( 'order', 'ASC' );
	        }
        }
	}

	function ll_display_custom_quickedit_link( $column_name, $post_type ) {
		static $printNonce = TRUE;
	    if ( $printNonce ) {
	        $printNonce = FALSE;
	        wp_nonce_field( plugin_basename( __FILE__ ), 'link_edit_nonce' );
	    }

	    ?>
	    <fieldset class="inline-edit-col-right inline-edit-link">
	      <div class="inline-edit-col column-<?php echo $column_name; ?>">
	        <label class="inline-edit-group">
	        <?php
	         switch ( $column_name ) {
	         case 'link_library_url':
	             ?><span class="title">URL</span><input type="text" name="link_url" id="link_url" /><?php
	             break;
	         }
	        ?>
	        </label>
	      </div>
	    </fieldset>
	    <?php
	}



	function network_settings_menu() {
		add_submenu_page( 'settings.php', 'Link Library Network Config', 'Link Library Network Config', 'manage_options', 'link_library_network_admin_page', array( $this, 'link_library_network_admin_page' ) );
	}

	function link_library_network_admin_page() {

		if ( isset( $_POST['link-library-submit-settings'] ) && check_admin_referer( 'link-library-network' ) ) {

			$optionnames = array( 'updatechannel' );

			foreach ( $optionnames as $optionname ) {
				if ( isset( $_POST[$optionname] ) && !empty( $_POST[$optionname] ) ) {
					$networkoptions[$optionname] = $_POST[$optionname];
				}
			}

			update_site_option( 'LinkLibraryNetworkOptions', $networkoptions );

			echo '<div id="message" class="updated fade"><p><strong>Network Settings Saved</strong></p></div>';
		}

		$networkoptions = get_site_option( 'LinkLibraryNetworkOptions' );

		if ( empty( $networkoptions ) ) {
			$networkoptions['updatechannel'] = 'standard';
		}
		?>

		<div id="link_library_network_options" class="wrap">
			<h2>Link Library Network Options</h2>

			<form name="link_library_network_options_form" method="post">
				<input type="hidden" name="link-library-submit-settings" value="1">
				<?php wp_nonce_field( 'link-library-network' ); ?>
				<table>
					<tr>
						<td><?php _e( 'Update channel', 'link-library' ); ?></td>
						<td><select id="updatechannel" name="updatechannel">
							<option value="standard" <?php selected( $networkoptions['updatechannel'], 'standard' ); ?>><?php _e( 'Standard channel - Updates as they are released', 'link-library' ); ?>
							<option value="monthly" <?php selected( $networkoptions['updatechannel'], 'monthly' ); ?>><?php _e( 'Monthly Channel - Updates once per month', 'link-library' ); ?>
							</select></td>
					</tr>
				</table><br />
				<input type="submit" value="Submit" class="button-primary" />
			</form>
		</div>

	<?php }

	/************************************************ Delete extra field data when link is deleted ***********************************/
	function ll_link_cat_filter_list() {
		$screen = get_current_screen();
		global $wp_query;

		if ( $screen->post_type == 'link_library_links' ) {
			wp_dropdown_categories(array(
					'show_option_all' => 'All Link Categories',
					'taxonomy' => 'link_library_category',
					'name' => 'link_library_category',
					'orderby' => 'name',
					'selected' =>
						( isset( $wp_query->query['link_library_category'] ) ?
							$wp_query->query['link_library_category'] : '' ),
					'hierarchical' => true,
					'depth' => 3,
					'show_count' => false,
					'hide_empty' => false,
				)
			);
		}

		if ( $screen->post_type == 'link_library_links' ) {
			wp_dropdown_categories(array(
					'show_option_all' => 'All Link Tags',
					'taxonomy' => 'link_library_tags',
					'name' => 'link_library_tags',
					'orderby' => 'name',
					'selected' =>
						( isset( $wp_query->query['link_library_tags'] ) ?
							$wp_query->query['link_library_tags'] : '' ),
					'hierarchical' => true,
					'depth' => 3,
					'show_count' => false,
					'hide_empty' => false,
				)
			);
		}
	}

	function ll_perform_link_cat_filtering( $query ) {
		$qv = &$query->query_vars;

		if ( !empty( $qv['link_library_category'] ) && is_numeric( $qv['link_library_category'] ) ) {
			$term = get_term_by( 'id', $qv['link_library_category'], 'link_library_category' );
			$qv['link_library_category'] = $term->slug;
		}

		if ( !empty( $qv['link_library_tags'] ) && is_numeric( $qv['link_library_tags'] ) ) {
			$term = get_term_by( 'id', $qv['link_library_tags'], 'link_library_tags' );
			$qv['link_library_tags'] = $term->slug;
		}
	}

	function link_library_duplicate_link_checker( $ll_admin_class ) {
		global $wpdb;  // Kept with CPT update
		echo "<strong>" . __( 'Duplicate Link Checker Report', 'link-library' ) . "</strong><br /><br />";

		echo "<strong>" . __( 'Duplicate URLs', 'link-library' ) . "</strong><br /><br />";
		$linkquery = "SELECT p.ID, p.post_title, pm.meta_value FROM " . $ll_admin_class->db_prefix() . "posts p, " . $ll_admin_class->db_prefix() . "postmeta pm INNER JOIN";
		$linkquery .= "(SELECT trim( TRAILING '/' FROM meta_value ) as trim_link_url ";
		$linkquery .= "FROM " . $ll_admin_class->db_prefix() . "posts p, " . $ll_admin_class->db_prefix() . "postmeta pm where pm.post_id = p.ID and pm.meta_key = 'link_url' and p.post_type = 'link_library_links' and p.post_status in ( 'publish', 'pending', 'draft', 'future', 'private' ) GROUP BY meta_value HAVING count(p.ID) > 1) dup ";
		$linkquery .= "ON pm.meta_value = dup.trim_link_url ";
		$linkquery .= "WHERE p.ID = pm.post_id and p.post_type = 'link_library_links' and pm.meta_key = 'link_url' and p.post_status in ( 'publish', 'pending', 'draft', 'future', 'private' )";

		$links  = $wpdb->get_results( $linkquery );

		if ( $links ) {
			foreach ( $links as $link ) {
				echo $link->ID . ' - ' . $link->post_title . ': ' . $link->meta_value . '<br /><br />';
			}
		} else {
			echo 'No duplicate URL links found';
		}

		echo "<br /><br /><strong>" . __( 'Duplicate Names', 'link-library' ) . "</strong><br /><br />";

		$linkquery = "SELECT p.ID, p.post_title FROM " . $ll_admin_class->db_prefix() . "posts p INNER JOIN";
		$linkquery .= "(SELECT trim( TRAILING '/' FROM post_title ) as trim_post_title ";
		$linkquery .= "FROM " . $ll_admin_class->db_prefix() . "posts p WHERE p.post_status in ( 'publish', 'pending', 'draft', 'future', 'private' ) GROUP BY post_title HAVING count(p.ID) > 1) dup ";
		$linkquery .= "ON p.post_title = dup.trim_post_title ";
		$linkquery .= "WHERE p.post_type = 'link_library_links' AND p.post_status in ( 'publish', 'pending', 'draft', 'future', 'private' ) ";

		$links  = $wpdb->get_results( $linkquery );

		if ( $links ) {
			foreach ( $links as $link ) {
				echo $link->ID . ' - ' . $link->post_title;
				echo ' - <a href="' . esc_url( add_query_arg( array( 'action' => 'edit', 'post' => $link->ID ), admin_url( 'post.php' ) ) );
				echo '">(' . __('Edit', 'link-library') . ')</a>';
				echo '<br /><br />';
			}
		} else {
			echo 'No duplicate name links found';
		}

		echo '<br />';
	}
}

function link_library_reciprocal_link_checker() {

	$RecipCheckAddress = ( isset( $_POST['RecipCheckAddress'] ) && !empty( $_POST['RecipCheckAddress'] ) ? $_POST['RecipCheckAddress'] : '' );
	$recipcheckdelete403 = ( isset( $_POST['recipcheckdelete403'] ) && !empty( $_POST['recipcheckdelete403'] ) && 'true' == $_POST['recipcheckdelete403'] ? true : false );
	$check_type = ( isset( $_POST['mode'] ) && !empty( $_POST['mode'] ) ? $_POST['mode'] : 'reciprocal' );

	if ( ! empty( $RecipCheckAddress ) || ( empty( $RecipCheckAddress ) && 'reciprocal' != $check_type ) ) {
		$args = array(
			'post_type' => 'link_library_links',
			'post_status' => array( 'publish', 'pending', 'draft', 'future', 'private' ),
			'orderby' => 'post_title',
			'order' => 'ASC',
			'meta_value' => ' ',
			'meta_compare' => '!=',
			'posts_per_page' => 1,
			'paged' => ( isset( $_POST['index'] ) ? $_POST['index'] : 1 )
		);

		if ( 'reciprocal' == $check_type ) {
			$args['meta_key'] = 'link_reciprocal';

		} elseif ( 'broken' == $check_type ) {
			$args['meta_key'] = 'link_url';
		}

		$the_link_query = new WP_Query( $args );

		if ( $the_link_query->have_posts() ) {
			while ( $the_link_query->have_posts() ) {
				$the_link_query->the_post();

				global $my_link_library_plugin;
				$link_url = get_post_meta( get_the_ID(), 'link_url', true );

				if ( 'reciprocal' == $check_type ) {
					$link_reciprocal = get_post_meta( get_the_ID(), 'link_reciprocal', true );
					$reciprocal_result = $my_link_library_plugin->CheckReciprocalLink( $RecipCheckAddress, $link_reciprocal );
				} elseif ( 'broken' == $check_type ) {
					$reciprocal_result = $my_link_library_plugin->CheckReciprocalLink( $RecipCheckAddress, $link_url );
				}

				if ( ( 'reciprocal' == $check_type && $reciprocal_result == 'exists_found' ) || 'broken' == $check_type && strpos( $reciprocal_result, 'exists' ) !== false ) {
					echo '<div class="nextcheckitem"></div>';
					continue;
				}

				echo '<a href="' . $link_url . '">' . get_the_title() . '</a>: ';

				if ( 'reciprocal' == $check_type && $reciprocal_result == 'exists_notfound' ) {
					echo '<span style="color: #FF0000">' . __( 'Not Found', 'link-library' ) . '</span>';
				} elseif ( $reciprocal_result == 'error_403' && $recipcheckdelete403 == true ) {
					wp_delete_post( get_the_ID() );
					echo '<span style="color: #FF0000">' . __( 'Error 403: Link Deleted', 'link-library' ) . '</span>';
				} elseif ( $reciprocal_result == 'error_403' && $recipcheckdelete403 == false ) {
					echo '<span style="color: #FF0000">' . __( 'Error 403', 'link-library' ) . '</span>';
				} elseif ( $reciprocal_result == 'unreachable' ) {
					echo '<span style="color: #FF0000">' . __( 'Website Unreachable', 'link-library' ) . '</span>';
				}

				echo ' - <a target="linkedit' . get_the_ID() . '" href="' . esc_url( add_query_arg( array( 'action' => 'edit', 'post' => get_the_ID() ), admin_url( 'post.php' ) ) );
				echo '">(' . __('Edit', 'link-library') . ')</a><br />';
			}
		} else {
			if ( 'reciprocal' == $check_type ) {
				echo __( 'There are no links with reciprocal links associated with them', 'link-library' ) . ".<br />";
			} elseif ( 'broken' == $check_type ) {
				echo __( 'There are no links to check', 'link-library' ) . ".<br />";
			}

		}

		wp_reset_postdata();
	}
	die();
}

function link_library_render_editor_button() {
	echo '<a id="insert_linklibrary_shortcodes" href="#TB_inline?width=660&height=800&inlineId=select_linklibrary_shortcode" class="thickbox button linklibrary_media_link" data-width="800">' . __( 'Add Link Library Shortcode', 'link-library' ) . '</a>';
}