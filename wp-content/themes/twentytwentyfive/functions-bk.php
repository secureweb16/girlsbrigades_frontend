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

function csv_upload_menu() {
    add_menu_page(
        'CSV Upload', // Page title
        'CSV Upload', // Menu title
        'manage_options', // Capability
        'csv-upload', // Menu slug
        'csv_upload_page', // Callback function
        'dashicons-upload', // Icon
        20 // Position
    );
}
add_action('admin_menu', 'csv_upload_menu');




function csv_upload_page() {
    ?>
    <div class="wrap">
        <h1>Upload CSV or DOC File</h1>
        <form method="post" enctype="multipart/form-data" id="file-upload-form">
            <?php wp_nonce_field('csv_doc_upload_action', 'csv_doc_upload_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="file">Select File</label></th>
                    <td>
                        <input type="file" name="file" id="file" accept=".csv,.doc,.docx" required>
                        <p class="description">Allowed file types: CSV, DOC, DOCX</p>
                    </td>
                </tr>
            </table>
            <button type="button" class="button button-primary" id="upload-button">Upload File</button>
        </form>

        <div id="upload-status"></div>

        <h2>Uploaded Document</h2>
        <div id="document-link"></div> 
        <h2>Document Content:</h2>
        <div id="document-content"></div> 

    </div>

    <script>
        document.getElementById('upload-button').addEventListener('click', async () => {
            const form = document.getElementById('file-upload-form');
            const formData = new FormData(form);
            const statusDiv = document.getElementById('upload-status');
            const documentLinkDiv = document.getElementById('document-link');
            const documentContentDiv = document.getElementById('document-content');

            statusDiv.innerHTML = 'Uploading...';

            try {
                const response = await fetch('http://localhost:4000/v1/documents/uploadFile', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (response.ok) {
                    statusDiv.innerHTML = `<p style="color:green;">${result.message}</p>`;
                    
                    // Display the document link or content
                    if (result.data && result.data.document) {
                        const docPath = result.data.document.fullPath;					
                        // Create a link to the uploaded document
                        documentLinkDiv.innerHTML = `<a href="${docPath}" target="_blank" download>Click here to view the document</a>`;
                    }
                } else {
                    statusDiv.innerHTML = `<p style="color:red;">${result.message || 'File upload failed.'}</p>`;
                }
            } catch (error) {
                statusDiv.innerHTML = `<p style="color:red;">Error: ${error.message}</p>`;
            }
        });

        // Fetch the document data from API and display its content
		fetch('http://localhost:4000/v1/documents/getDocument')
    .then(response => response.json())
    .then(data => {
        // Get the fullPath from the response
        const rawFilePath = data?.data?.document?.fullPath;


		console.log(rawFilePath, "-rawFilePath-----------");


        const cleanedFilePath = rawFilePath.replace('http://localhost/', '');
        const documentLinkDiv = document.getElementById('document-link');
        documentLinkDiv.innerHTML = `<a href="${rawFilePath}" target="_blank">Click here to view the document</a>`;

    }).catch(function(error) {
            // Handle error fetching the document metadata
        document.getElementById('document-content').innerHTML = `<p style="color:red;">Error fetching document metadata: ${error.message}</p>`;
    });
    </script>

    <!-- Include Mammoth.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
	
    <?php
}


add_action('wp_footer', 'addChatBot');
function addChatBot() {
    ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div id="chatbot-icon" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; cursor: pointer;">
    <i class="fa fa-comment" style="font-size: 60px; color: #007bff;"></i>
    </div>

    <div id="chatbot-modal" style="display: none; position: fixed; bottom: 80px; right: 20px; z-index: 1000; background: white; border: 1px solid #ccc; border-radius: 10px; width: 300px; height: 400px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); overflow-y: scroll;">
        <div id="chat-history" style="padding: 10px;">
            <!-- Questions and answers will be appended here -->
        </div>
        <div id="chatbot-ask-question" style="background: white; padding: 10px; border-top: 1px solid #ccc; border-radius: 5px; width: 100%;">
            <input type="text" id="user-question" placeholder="Ask a question..." style="width: 100%; padding: 5px; margin-bottom: 10px;">
            <button type="button" id="submit-question" class="button button-primary">Ask</button>
        </div>
    </div>

    <script>
        // Function to get the token from startChat API
        async function getToken() {
            try {
                const response = await fetch('http://localhost:4000/v1/question/startChat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const data = await response.json();
                if (response.ok) {
                    return data.data.token;
                } else {
                    showChatBotMessage('Failed to generate token: ' + data.message, 'error');
                    return null;
                }
            } catch (error) {
                console.error('Error getting token:', error);
                showChatBotMessage('Error getting token.', 'error');
                return null;
            }
        }

        // Function to show a message in the chatbot
        function showChatBotMessage(message, type = 'normal') {
            const chatHistory = document.getElementById('chat-history');
            const messageDiv = document.createElement('div');
            messageDiv.style.marginBottom = '10px';
            messageDiv.innerHTML = `<strong>Bot:</strong> ${message}`;

            if (type === 'error') {
                messageDiv.style.color = 'red';
            }

            chatHistory.appendChild(messageDiv);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        // Submit the question to askQuestion API
        document.getElementById('submit-question').addEventListener('click', async function() {
            const question = document.getElementById('user-question').value;
            const chatHistory = document.getElementById('chat-history');

            if (!question) {
                showChatBotMessage('Please ask a question.', 'error');
                return;
            }

            // Append the question to the chat history
            chatHistory.innerHTML += `<div style="margin-bottom: 10px;"><strong>You:</strong> ${question}</div>`;
            const loadingText = `<div style="color: grey; font-style: italic;">Loading...</div>`;
            chatHistory.innerHTML += loadingText;

            // Scroll to the bottom of the chat history
            chatHistory.scrollTop = chatHistory.scrollHeight;

            const token = await getToken(); // Get the token from the startChat API
            if (!token) {
                return;
            }

            try {
                // Send the question with the token to askQuestion API
                const response = await fetch('http://localhost:4000/v1/question/askQuestion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}` // Attach the token in the Authorization header
                    },
                    body: JSON.stringify({ question })
                });

                const data = await response.json();

                // Remove the "Loading..." text once the response is received
                const loadingElement = chatHistory.querySelector('div[style="color: grey; font-style: italic;"]');
                if (loadingElement) {
                    loadingElement.remove();
                }

                // Show the answer from the API
                if (response.ok) {
                    chatHistory.innerHTML += `<div style="margin-bottom: 10px;"><strong>Bot:</strong> ${data.data.answer}</div>`;
                } else {
                    // Show error message if no answer is returned
                    showChatBotMessage(data.message || 'Failed to get an answer.', 'error');
                }

                // Scroll to the bottom of the chat history
                chatHistory.scrollTop = chatHistory.scrollHeight;
            } catch (error) {
                // Remove the "Loading..." text
                const loadingElement = chatHistory.querySelector('div[style="color: grey; font-style: italic;"]');
                if (loadingElement) {
                    loadingElement.remove();
                }

                showChatBotMessage(`Error: ${error.message}`, 'error');
            }

            // Clear the input field
            document.getElementById('user-question').value = '';
        });

        // Toggle chatbot modal visibility when the icon is clicked
        document.getElementById('chatbot-icon').addEventListener('click', function() {
            const chatbotModal = document.getElementById('chatbot-modal');
            chatbotModal.style.display = chatbotModal.style.display === 'none' ? 'block' : 'none';
        });
    </script>
    <?php
}

