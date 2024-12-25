<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

// Developer code 
add_action('admin_enqueue_scripts', 'custom_admin_styles');
function custom_admin_styles() {
    wp_enqueue_style(
        'custom-admin-css', 
        get_template_directory_uri() . '/style.css', 
        [], 
        '1.0', 
        'all' 
    );
}

add_action('admin_menu', 'document_upload_menu');
function document_upload_menu() {
    add_menu_page(
        'File Upload', // Page title
        'File Upload', // Menu title
        'manage_options', // Capability
        'file-upload', // Menu slug
        'file_upload_page', // Callback function
        'dashicons-upload', // Icon
        20 // Position
    );
}


function file_upload_page() {
    ?>
	<style>
		.wrapper {
		text-align: center;
		}

		.btn {
			background: #fefefe;
			border: .625em;
			color: #009688;
			padding: .5em 1em;
		}

		.iframe {
			width: 100%;
			height: 600px;
			display: none;
		}

		.uploaded_doc_outer {
			margin-top: 2em;
		}
	</style>
    <div class="wrap csv_outer">
        <div class="upload_file">
            <h2 class="main_heading">Upload File</h2>
            <form method="post" enctype="multipart/form-data" id="file-upload-form">
                <?php wp_nonce_field('csv_doc_upload_action', 'csv_doc_upload_nonce'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="file">Select File</label></th>
                    </tr>
                    <tr>
                        <td>
                            <div class="field">
                                <input type="file" name="file" id="file" accept=".doc,.docx" required>
                            </div>
                            <p class="description">Allowed file types: DOC, DOCX</p>
                        </td>
                    </tr>
                </table>
                <button type="button" class="button button-primary" id="upload-button">Upload File</button>
            </form>
        </div>
        
        <div id="upload-status"></div>

        <div class="uploaded_doc_outer">
            <!-- <h2 id="flip">Document Content</h2> -->
			<h2 class="office-viewer btn">Document Content</h2>

            <!-- <div id="panel" style="display: none;">
                <div id="document-read"></div>
            </div> -->
        </div>
        
        <!-- Office Apps iframe (Initially hidden) -->
        <iframe class="office iframe" id="office-iframe" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" role="document" aria-label="Doc document" title="Doc document"></iframe>
		

	</iframe>

    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('.office-viewer').on('click', function() {
                if (jQuery('.office-iframe').css('display') !== 'none') {
                    jQuery('.office-iframe').slideToggle();
                }
                jQuery('.office').slideToggle();
            });
        });

        // jQuery(document).ready(function () {
        //     jQuery("#flip").click(function () {
        //         jQuery("#panel").slideToggle("slow");
        //     });
        // });

        document.getElementById('upload-button').addEventListener('click', async () => {
            const form = document.getElementById('file-upload-form');
            const formData = new FormData(form);
            const statusDiv = document.getElementById('upload-status');
            const documentContentDiv = document.getElementById('document-read');

            statusDiv.innerHTML = 'Uploading...';

            try {
                const response = await fetch('http://localhost:4000/v1/documents/uploadFile', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (response.ok) {
                    statusDiv.innerHTML = `<p style="color:green;">${result.message}</p>`;
					fetchDocContentAPI();
                } else {
                    statusDiv.innerHTML = `<p style="color:red;">${result.message || 'File upload failed.'}</p>`;
                }
            } catch (error) {
                statusDiv.innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
            }
        });

    
		fetchDocContentAPI();
		function fetchDocContentAPI() {
			fetch('http://localhost:4000/v1/documents/getDocument')
				.then(response => response.json())
				.then(data => {
					const documentReadDiv = jQuery("#document-read");
					const iframe = document.getElementById('office-iframe');

					if (data?.data?.document) {
						const document = data.data.document;

						if (document.fullPath) {
							try {
								// Fix the URL (replace backslashes with forward slashes and ensure it's https://)
								let cleanUrl = document.fullPath.replace(/\\/g, '/');

								// Check if the URL starts with https:\ and replace with https://
								if (cleanUrl.indexOf("https:/") === 0) {
									cleanUrl = "https:/" + cleanUrl.substring(6);  // Remove "https:/" and prepend "https://"
								}								
								
								iframe.src = `https://docs.google.com/gview?url=${cleanUrl}&embedded=true`;
								iframe.style.display = 'block';
							} catch (error) {
								console.error("Error processing URL:", error);
								documentReadDiv.html('<p style="color:red;">Invalid file URL.</p>');
								iframe.style.display = 'none';
							}
						} else {
							documentReadDiv.html('<p>No file URL provided by the server.</p>');
							iframe.style.display = 'none';
						}
					} else {
						documentReadDiv.html('<p>No document data available from the API.</p>');
						iframe.style.display = 'none';
					}
				})
				.catch(error => {
					jQuery("#document-read").html(
						`<p style="color:red;">Error fetching document: ${error.message}</p>`
					);
					iframe.style.display = 'none';
				});
		}

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

    <?php
}


add_action('wp_footer', 'addChatBot');
function addChatBot() {
    ?>
    <div id="chatbot-icon">
		<svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  width="30px" height="30px" viewBox="0 0 30.743 30.744" xml:space="preserve">
			<g>
				<path d="M28.585,9.67h-0.842v9.255c0,1.441-0.839,2.744-2.521,2.744H8.743v0.44c0,1.274,1.449,2.56,2.937,2.56h12.599l4.82,2.834
					L28.4,24.669h0.185c1.487,0,2.158-1.283,2.158-2.56V11.867C30.743,10.593,30.072,9.67,28.585,9.67z"/>
				<path d="M22.762,3.24H3.622C1.938,3.24,0,4.736,0,6.178v11.6c0,1.328,1.642,2.287,3.217,2.435l-1.025,3.891L8.76,20.24h14.002
					c1.684,0,3.238-1.021,3.238-2.462V8.393V6.178C26,4.736,24.445,3.24,22.762,3.24z M6.542,13.032c-0.955,0-1.729-0.774-1.729-1.729
					s0.774-1.729,1.729-1.729c0.954,0,1.729,0.774,1.729,1.729S7.496,13.032,6.542,13.032z M13,13.032
					c-0.955,0-1.729-0.774-1.729-1.729S12.045,9.574,13,9.574s1.729,0.774,1.729,1.729S13.955,13.032,13,13.032z M19.459,13.032
					c-0.955,0-1.73-0.774-1.73-1.729s0.775-1.729,1.73-1.729c0.953,0,1.729,0.774,1.729,1.729S20.412,13.032,19.459,13.032z"/>
			</g>
		</svg>
    </div>
    <div id="chatbot-modal">
        <div class="chat_outer" id="chat-history">
			<div class="chat_header">
				<div class="image">
					<img src="https://w7.pngwing.com/pngs/695/655/png-transparent-head-the-dummy-avatar-man-tie-jacket-user.png">
				</div>
				<div class="name">
					<p>Girls Brigade Assistant</p>
				</div>
			</div>
			<div class="chat_content">
				<div class="chat_inner">
				</div>
			</div>
			<div class="chat_footer">
				<div class="field">
					<input type="text" class="chat_input" id="user-question" placeholder="Ask me anything about policies or guidelines...">
					<button type="submit" id="submit-question"> 
						<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M20.33 3.66996C20.1408 3.48213 19.9035 3.35008 19.6442 3.28833C19.3849 3.22659 19.1135 3.23753 18.86 3.31996L4.23 8.19996C3.95867 8.28593 3.71891 8.45039 3.54099 8.67255C3.36307 8.89471 3.25498 9.16462 3.23037 9.44818C3.20576 9.73174 3.26573 10.0162 3.40271 10.2657C3.5397 10.5152 3.74754 10.7185 4 10.85L10.07 13.85L13.07 19.94C13.1906 20.1783 13.3751 20.3785 13.6029 20.518C13.8307 20.6575 14.0929 20.7309 14.36 20.73H14.46C14.7461 20.7089 15.0192 20.6023 15.2439 20.4239C15.4686 20.2456 15.6345 20.0038 15.72 19.73L20.67 5.13996C20.7584 4.88789 20.7734 4.6159 20.7132 4.35565C20.653 4.09541 20.5201 3.85762 20.33 3.66996ZM4.85 9.57996L17.62 5.31996L10.53 12.41L4.85 9.57996ZM14.43 19.15L11.59 13.47L18.68 6.37996L14.43 19.15Z" fill="#000000"/>
						</svg>
					</button>
				<div>
			</div>
		</div>
    </div>
    <script>
        const toggleChatbot = () => {
            const modal = document.getElementById('chatbot-modal');
            modal.style.display = modal.style.display === 'block' ? 'none' : 'block';
        };
        const showMessage = (msg, sender) => {
            const chatInner = document.querySelector('.chat_inner');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add(sender === 'bot' ? 'sender_message' : 'user_message');
            messageDiv.innerHTML = `<div class="message">${msg}</div>`;
            chatInner.appendChild(messageDiv);
            chatInner.scrollTop = chatInner.scrollHeight;
        };
        const getToken = async () => {
            try {
                const res = await fetch('http://localhost:4000/v1/question/startChat', { method: 'POST', headers: { 'Content-Type': 'application/json' } });
                const data = await res.json();
                return res.ok ? data.data.token : null;
            } catch (err) { console.error(err); return null; }
        };
        const askQuestion = async () => {
            const input = document.getElementById('user-question');
            const question = input.value.trim();
            if (!question) return showMessage('Please ask a question.', 'bot');
            showMessage(question, 'user'); showMessage('Loading...', 'bot');
			input.value = '';
            const token = await getToken();
            if (!token) return showMessage('Failed to fetch token.', 'bot');
            try {
                const res = await fetch('http://localhost:4000/v1/question/askQuestion', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                    body: JSON.stringify({ question })
                });
                const data = await res.json();
                document.querySelector('.chat_inner .sender_message:last-child').remove();
                showMessage(res.ok ? data.data.answer : 'Failed to get an answer.', 'bot');
            } catch (err) { console.error(err); showMessage('Error occurred.', 'bot'); }
            input.value = '';
        };
        document.getElementById('chatbot-icon').addEventListener('click', toggleChatbot);
        document.getElementById('submit-question').addEventListener('click', askQuestion);
        document.getElementById('user-question').addEventListener('keydown', (e) => { if (e.key === 'Enter') askQuestion(); });
    </script>
    <?php
}






