<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<div class="wrap clustdoc-client-portal-wrap">
    <h1><?php _e('Clustdoc Client Portal - Settings page', 'clustdoc-client-portal'); ?></h1>
    <div class="form">
        <form method="post" action="options.php">
            <?php settings_fields('clustdoc_client_portal_optsgroup'); ?>
            <?php do_settings_sections('clustdoc_client_portal_optsgroup'); ?>
            <div class="row">
                <div class="col col-md-12">
                    <div class="card">
                        <div class="card-block text-center">
                            <div class="message">
                                <?php if ($this->api_error_message) { ?>
                                    <?php if ($this->api_error_message !== ERROR_MSG_EN && $this->api_error_message !== ERROR_MSG_FR) { ?>
                                        <div class="messageError">
                                            <div><?php echo ($this->api_error_message) ?></div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <!-- <div class="messageSuccess">
										<div><?php echo ($this->api_success_message) ?></div>
									</div> -->

                                    <script>
                                        document.getElementById('dispay-portals').style.display = "block";
                                    </script>
                                <?php } ?>
                            </div>
                            <?php if ($this->api_error_message) { ?>
                                <h1 style="font-size:30px; margin-top:30px"><?php _e(TXT_WELCOME, 'clustdoc-client-portal'); ?> </h1>
                                <h4><?php _e(TXT_INFO_1, 'clustdoc-client-portal'); ?>
                                    <a style="font-weight:bold;" target="_blank" href="<?php echo URL_TO_EMBED_PORTAL ?>"><?php _e(TXT_NEED_HELP, 'clustdoc-client-portal'); ?></a>
                                </h4>
                            <?php } else { ?>
                                <h1 style="font-size:30px;"><?php _e(TXT_WELCOME, 'clustdoc-client-portal'); ?></h1>
                                <div class="logout-section">
                                    <?php if ($this->api_success_message) { ?>
                                        <button onclick="disconnect()" style="margin-bottom:15px; margin-top:15px;" id="clustdoc_client_portal_ib" class="button button-primary generate-code-btn">
                                            <?php _e(BTN_LOGOUT, 'clustdoc-client-portal'); ?>
                                        </button>
                                        <script>
                                            var connect = document.getElementById("connect").style.display = 'none';
                                        </script>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="pic-header">
                                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/settings-screenshot.png'; ?>">
                            </div>
                            <div id="connect">
                                <?php if ($this->api_error_message) { ?>
                                    <h2 style="margin-top:30px;"><?php _e(TXT_TOKEN_CONF, 'clustdoc-client-portal'); ?></h2>
                                    <h4>
                                        <?php _e(TXT_INFO_2, 'clustdoc-client-portal'); ?>
                                        <a style="font-weight:bold;" target="_blank" href="<?php echo URL_TO_CREATE_TOKEN ?>"><?php _e(TXT_NEED_HELP, 'clustdoc-client-portal'); ?></a>
                                    </h4>
                                    <input iclass="form-control" type="text" id="clustdoc_api_token" name="clustdoc_client_portal_options[api_token]" placeholder="<?php echo PLACEHOLDER ?>" />
                                    <input style="margin-bottom: 200px;" class="form-control" type="submit" name="submit" id="submit" value="<?php echo INPUT_VALUE ?>">
                                <?php } else { ?>
                                    <div id="dispay-portals" class="dispay-portals">
                                        <h2 style="margin-top: 30px; margin-bottom:30px;"><?php _e(TXT_PORTAL, 'clustdoc-client-portal'); ?></h2>
                                        <div class="portal-text">
                                            <p style="margin-bottom: 50px;"><b><?php _e(TXT_OPTION_1, 'clustdoc-client-portal'); ?></b><br><?php _e(TXT_OPTION_1_T, 'clustdoc-client-portal'); ?></p>
                                            <p style="margin-bottom: 30px;"><b><?php _e(TXT_OPTION_2, 'clustdoc-client-portal'); ?></b><br><?php _e(TXT_OPTION_2_T, 'clustdoc-client-portal'); ?></p>
                                            <label><?php _e(TXT_SELECT_PORTAL, 'clustdoc-client-portal'); ?></label>
                                            <div style="margin-bottom:15px; margin-top:10px;">
                                                <select style="height:30px;width:300px;" id="clustdoc_client_portal_select">
                                                    <?php echo $this->get_portals(); ?>
                                                </select>
                                            </div>
                                            <p><a onclick="display()" id="clustdoc_client_portal_ib" class="button button-primary generate-code-btn"><?php _e(BTN_GENERATE_CODE, 'clustdoc-client-portal'); ?></a></p>
                                            <?php

                                            ?>
                                            <script type="text/javascript">
                                                function disconnect() {
                                                    var connect = document.getElementById("clustdoc_api_token").value = "";
                                                }
                                                // Display the Portal iframe selected
                                                function display() {
                                                    var url = document.getElementById("clustdoc_client_portal_select").value;
                                                    var res = document.getElementById("res");
                                                    var iframe = null;
                                                    if (url !== "0") {
                                                        iframe = "&lt;iframe src=\"" + url + "?embedded=1\" frameborder=\"0\" height=\"800\" width=\"100%\"&gt;&lt;/iframe&gt;";
                                                        res.style.display = "block"
                                                        res.style.display = "inline-flex"
                                                        res.style.alignItems = "center"
                                                        res.style.justifyContent = "center"
                                                        res.style.fontSize = "14px"
                                                        res.style.height = "65px"
                                                        res.style.alignItems = "center"
                                                        res.innerHTML = iframe;
                                                        copy_button.style.display = "block";
                                                        copy_button.style.marginLeft = "10px";
                                                        copy_button.style.alignContent = "center";
                                                        copy_button.style.width = "70px";
                                                        container.style.display = "flex";
                                                        container.style.flexDirection = "row";
                                                        container.style.justifyContent = "center";
                                                        container.style.alignItems = "stretch";
                                                    }
                                                }

                                                var copy_button_text = "<?php _e(TXT_COPY, 'clustdoc-client-portal'); ?>";
                                                var copied_button_text = "<?php _e(TXT_COPIED, 'clustdoc-client-portal'); ?>";

                                                // Copy the content of the 'res' element to the clipboard
                                                function copyResToClipboard() {
                                                    var res = document.getElementById("res");
                                                    var copyButton = document.getElementById("copy_button");

                                                    var text = res.innerText;
                                                    navigator.clipboard.writeText(text).then(function() {
                                                        copyButton.innerText = copied_button_text;
                                                        copyButton.disabled = true;
                                                        setTimeout(function() {
                                                            copyButton.disabled = false;
                                                            copyButton.innerText = copy_button_text;
                                                        }, 2000);
                                                    }).catch(function(err) {
                                                        console.error('Could not copy text: ', err);
                                                    });
                                                }
                                            </script>
                                            <h3 id="text"><?php _e(TXT_CODE_TO_INSERT_PAGES, 'clustdoc-client-portal'); ?></h3>
                                            <div id="container">
                                                <div id="res" style="background-color:#f8f9fa;border:#0085ba solid 1px;display:none;width:40%;"></div>
                                                <a onclick="copyResToClipboard()" id="copy_button" class="button button-primary generate-code-btn" style="display: none;">
                                                    <?php _e(TXT_COPY, 'clustdoc-client-portal'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>