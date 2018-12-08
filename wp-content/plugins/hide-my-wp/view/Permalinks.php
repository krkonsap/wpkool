<?php

if (defined('HMW_DISABLE') && HMW_DISABLE) {
    //don't run if disable
} elseif (HMW_Classes_Tools::isPermalinkStructure() && !HMW_Classes_Tools::isPHPPermalink()) {
    if (is_multisite() && !is_plugin_active_for_network(_HMW_PLUGIN_NAME_ . '/index.php')) {
        ?>
        <div class="error notice">
            <p><?php echo __("Hide My WordPress requires to be activated on the entire network to prevent login issues!", _HMW_PLUGIN_NAME_); ?></p>
        </div>
        <?php
        return;
    }
    ?>

    <div id="hmw_wrap" class="d-flex flex-row my-3 bg-light">
        <?php echo $view->getAdminTabs(HMW_Classes_Tools::getValue('tab', 'hmw_permalinks')); ?>
        <div class="hmw_row d-flex flex-row bg-white px-3">
            <?php do_action('hmw_notices'); ?>
            <div class="hmw_col flex-grow-1 mr-3">
                <form method="POST">
                    <?php wp_nonce_field('hmw_settings', 'hmw_nonce'); ?>
                    <input type="hidden" name="action" value="hmw_settings"/>
                    <input type="hidden" name="hmw_mode" value="<?php echo HMW_Classes_Tools::getOption('hmw_mode') ?>"/>

                    <?php do_action('hmw_form_notices'); ?>

                    <div class="card col-sm-12">
                        <div class="card-body py-2 px-0">
                            <h3 class="card-title"><?php _e('Levels of security', _HMW_PLUGIN_NAME_); ?>:</h3>
                            <div class="group_autoload d-flex justify-content-center btn-group mt-3" role="group" data-toggle="button">
                                <button type="button" class="btn btn-lg btn-outline-info default_autoload m-1 py-3 px-4 <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'active' : '') ?>"><?php echo __("Default (unsafe)", _HMW_PLUGIN_NAME_) ?></button>
                                <button type="button" class="btn btn-lg btn-outline-info lite_autoload m-1 py-3 px-4 <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'lite') ? 'active' : '') ?>"><?php echo __("Lite mode", _HMW_PLUGIN_NAME_) ?></button>

                                <div style="position: relative; margin: .23rem!important;">
                                    <div class="box" style="border: none" data-toggle="popover" data-html="true" data-placement="top" data-content="<?php echo sprintf(__('Upgrade Your Website Security Protection. Hide WordPress Completely. %sUnlock this feature%s', _HMW_PLUGIN_NAME_), "<br /><a href='https://hidemywp.co/wordpress_update' target='_blank'>", "</a>") ?>">
                                        <div class="ribbon"><span><?php echo __('PRO', _HMW_PLUGIN_NAME_) ?></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-lg btn-outline-info m-1 py-3 px-4" style="opacity: 0.7;"><?php echo __("Ghost mode", _HMW_PLUGIN_NAME_) ?></button>
                                </div>
                            </div>
                            <div class="wp-admin_warning col-sm-12 my-2 text-danger p-0 text-center" <?php echo((HMW_Classes_Tools::getOption('hmw_hide_admin')) ? '' : 'style="display: none;"') ?> >
                                <div class="my-2 small"><?php echo sprintf(__("%sWARNING:%s The admin path is hidden from visitors. Use the custom login URL to login to admin", _HMW_PLUGIN_NAME_), '<span class="font-weight-bold">', '</span>'); ?></div>
                                <div class="my-3 small"><?php echo sprintf(__("If you can't login, use this URL: %s and all your changes are roll back to default", _HMW_PLUGIN_NAME_), "<strong>" . site_url() . "/wp-login.php?" . HMW_Classes_Tools::getOption('hmw_disable_name') . "=" . HMW_Classes_Tools::getOption('hmw_disable') . "</strong><br />"); ?></div>
                            </div>

                            <script>
                                (function ($) {
                                    $(document).ready(function () {
                                        $(".default_autoload").on('click', function () {
                                            $('input[name=hmw_mode]').val('default');
                                            $('.group_autoload button').removeClass('active');
                                            <?php
                                            foreach (HMW_Classes_Tools::$default as $name => $value) {
                                                if (is_string($value) && $value <> "0" && $value <> "1") {
                                                    echo '$("input[type=text][name=' . $name . ']").val("' . str_replace('"', '\\"', $value) . '");' . "\n";
                                                } elseif ($value == "0" || $value == "1") {
                                                    echo '$("input[name=' . $name . ']").prop("checked", ' . (int)$value . '); $("input[name=' . $name . ']").trigger("change");';
                                                }
                                            }
                                            ?>
                                            $('input[name=hmw_admin_url]').trigger('keyup');
                                            $('.tab-panel').hide();
                                            $('.tab-panel_tutorial').show();
                                        });
                                        $(".lite_autoload").on('click', function () {
                                            $('input[name=hmw_mode]').val('lite');
                                            $('.group_autoload button').removeClass('active');
                                            <?php
                                            $lite = @array_merge(HMW_Classes_Tools::$default, HMW_Classes_Tools::$lite);
                                            foreach ($lite as $name => $value) {
                                                if (is_string($value) && $value <> "0" && $value <> "1") {
                                                    echo '$("input[type=text][name=' . $name . ']").val("' . str_replace('"', '\\"', $value) . '");' . "\n";
                                                } elseif ($value == "0" || $value == "1") {
                                                    echo '$("input[name=' . $name . ']").prop("checked", ' . (int)$value . '); $("input[name=' . $name . ']").trigger("change");';

                                                }
                                            }
                                            ?>
                                            $('input[name=hmw_admin_url]').trigger('keyup');
                                            $('.tab-panel').show();
                                            $('.tab-panel_tutorial').hide();

                                        });
                                        $(".ninja_autoload").on('click', function () {
                                            $('input[name=hmw_mode]').val('ninja');
                                            $('.group_autoload button').removeClass('active');
                                            <?php
                                            $ninja = @array_merge(HMW_Classes_Tools::$default, HMW_Classes_Tools::$ninja);
                                            foreach ($ninja as $name => $value) {
                                                if (is_string($value) && $value <> "0" && $value <> "1") {
                                                    echo '$("input[type=text][name=' . $name . ']").val("' . str_replace('"', '\\"', $value) . '");' . "\n";
                                                } elseif ($value == "0" || $value == "1") {
                                                    echo '$("input[name=' . $name . ']").prop("checked", ' . (int)$value . '); $("input[name=' . $name . ']").trigger("change");';

                                                }
                                            }
                                            ?>
                                            $('input[name=hmw_admin_url]').trigger('keyup');
                                            $('.tab-panel').show();
                                            $('.tab-panel_tutorial').hide();

                                        });


                                    });
                                })(jQuery);
                            </script>

                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel_tutorial embed-responsive embed-responsive-16by9 text-center" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'lite') ? 'style="display:none"' : '') ?>>
                        <iframe width="853" height="480" style="max-width: 100%" src="https://www.youtube.com/embed/VGUs1dL611I?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('Admin Settings', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <?php if (defined('HMW_DEFAULT_ADMIN') && HMW_DEFAULT_ADMIN) {
                                echo ' <div class="text-danger">' . sprintf(__('Your admin URL is changed by another plugin/theme in %s. To prevent errors, disable the other plugin who changes the admin path.', _HMW_PLUGIN_NAME_), '<strong>' . HMW_DEFAULT_ADMIN . '</strong>') . '</div>';
                            } else {
                                ?>
                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom admin URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. adm, back', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg">
                                        <input type="text" class="form-control bg-input" name="hmw_admin_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_admin_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_admin_url'] ?>"/>
                                    </div>
                                </div>

                                <div class="col-sm-12 row mb-1 ml-1">
                                    <div class="checker col-sm-12 row my-2 py-1">
                                        <div class="col-sm-12 p-0 switch switch-sm">
                                            <input type="hidden" name="hmw_hide_admin" value="0"/>
                                            <input type="checkbox" id="hmw_hide_admin" name="hmw_hide_admin" class="switch" <?php echo(HMW_Classes_Tools::getOption('hmw_hide_admin') ? 'checked="checked"' : '') ?> value="1"/>
                                            <label for="hmw_hide_admin"><?php _e('Hide "wp-admin"', _HMW_PLUGIN_NAME_); ?></label>
                                            <div class="offset-1 text-black-50"><?php _e('Show 404 Not Found Error for /wp-admin', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 row mb-1 ml-1 hmw_hide_newadmin_div" <?php echo(HMW_Classes_Tools::getOption('hmw_admin_url') == HMW_Classes_Tools::$default['hmw_admin_url'] ? 'style="display:none;"' : '') ?>>
                                    <div class="checker col-sm-12 row my-2 py-1">
                                        <div class="col-sm-12 p-0 switch switch-sm">
                                            <input type="hidden" name="hmw_hide_newadmin" value="0"/>
                                            <input type="checkbox" id="hmw_hide_newadmin" name="hmw_hide_newadmin" class="switch" <?php echo(HMW_Classes_Tools::getOption('hmw_hide_newadmin') ? 'checked="checked"' : '') ?> value="1"/>
                                            <label for="hmw_hide_newadmin"><?php _e('Hide the new admin path', _HMW_PLUGIN_NAME_); ?></label>
                                            <div class="offset-1 text-black-50"><?php _e('Let only the new login be accessible and redirect me to admin after logging in', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="admin_warning col-sm-12 my-3 text-danger p-0 text-center small" style="display: none">
                                    <?php echo sprintf(__("Some Themes don't work with custom Admin and Ajax paths. In case of ajax errors, switch back to wp-admin and admin-ajax.php.", _HMW_PLUGIN_NAME_)); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('Login Settings', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <?php if (defined('HMW_DEFAULT_LOGIN') && HMW_DEFAULT_LOGIN) {
                                echo ' <div class="text-danger">' . sprintf(__('Your login URL is changed by another plugin/theme in %s. To prevent errors, disable the other plugin who changes the login path.', _HMW_PLUGIN_NAME_), '<strong>' . HMW_DEFAULT_LOGIN . '</strong>') . '</div>';
                            } else {
                                ?>
                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom login URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. login or signin', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg">
                                        <input type="text" class="form-control bg-input" name="hmw_login_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_login_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_login_url'] ?>"/>
                                    </div>
                                </div>

                                <div class="col-sm-12 row mb-1 ml-1 hmw_hide_login_div">
                                    <div class="checker col-sm-12 row my-2 py-1">
                                        <div class="col-sm-12 p-0 switch switch-sm">
                                            <input type="hidden" name="hmw_hide_login" value="0"/>
                                            <input type="checkbox" id="hmw_hide_login" name="hmw_hide_login" class="switch" <?php echo(HMW_Classes_Tools::getOption('hmw_hide_login') ? 'checked="checked"' : '') ?> value="1"/>
                                            <label for="hmw_hide_login"><?php _e('Hide "wp-login.php"', _HMW_PLUGIN_NAME_); ?></label>
                                            <div class="offset-1 text-black-50"><?php _e('Show 404 Not Found Error for /wp-login.php', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-bottom border-gray"></div>

                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom Lost Password URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. lostpass or forgotpass', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg">
                                        <input type="text" class="form-control bg-input" name="hmw_lostpassword_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_lostpassword_url') ?>" placeholder="?action=lostpassword"/>
                                    </div>
                                </div>

                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom Register URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. newuser or register', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg">
                                        <input type="text" class="form-control bg-input" name="hmw_register_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_register_url') ?>" placeholder="?action=register"/>
                                    </div>
                                </div>

                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom Logout URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. logout or disconnect', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg">
                                        <input type="text" class="form-control bg-input" name="hmw_logout_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_logout_url') ?>" placeholder="?action=logout"/>
                                    </div>
                                </div>
                                <?php if (is_multisite()) { ?>
                                    <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                        <div class="col-sm-4 p-0 font-weight-bold">
                                            <?php _e('Custom Activation URL', _HMW_PLUGIN_NAME_); ?>:
                                            <div class="small text-black-50"><?php _e('eg. multisite activation link', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                        <div class="col-sm-8 p-0 input-group input-group-lg">
                                            <input type="text" class="form-control bg-input" name="hmw_activate_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_activate_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_activate_url'] ?>"/>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('Common Paths', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom admin-ajax URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. ajax, json', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_admin-ajax_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_admin-ajax_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_admin-ajax_url'] ?>"/>
                                </div>
                            </div>

                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom wp-content URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. core, inc, include', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_wp-content_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_wp-content_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_wp-content_url'] ?>"/>
                                </div>
                            </div>

                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom wp-includes URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. lib, library', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_wp-includes_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_wp-includes_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_wp-includes_url'] ?>"/>
                                </div>
                            </div>


                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom uploads URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. images, files', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_upload_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_upload_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_upload_url'] ?>"/>
                                </div>
                            </div>
                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom comment URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. comments, discussion', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_wp-comments-post" value="<?php echo HMW_Classes_Tools::getOption('hmw_wp-comments-post') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_wp-comments-post'] ?>"/>
                                </div>
                            </div>

                            <?php if (!HMW_Classes_Tools::isMultisites() && !HMW_Classes_Tools::isNginx() && !HMW_Classes_Tools::isWpengine()) { ?>
                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom author URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. profile, usr, writer', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg">
                                        <input type="text" class="form-control bg-input" name="hmw_author_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_author_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_author_url'] ?>"/>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="hmw_author_url" value="<?php echo HMW_Classes_Tools::$default['hmw_author_url'] ?>"/>
                            <?php } ?>
                            <div class="col-sm-12 row mb-1 ml-1">
                                <div class="box" data-toggle="popover" data-html="true" data-placement="top" data-content="<?php echo sprintf(__('This feature requires %sHide My WP Ghost%s.', _HMW_PLUGIN_NAME_), "<a href='http://hidemywp.co/' target='_blank'>", "</a>") ?>">
                                    <div class="ribbon"><span><?php echo __('PRO', _HMW_PLUGIN_NAME_) ?></span></div>
                                </div>
                                <div class="checker col-sm-12 row my-2 py-1" style="opacity: 0.3">
                                    <div class="col-sm-12 p-0 switch switch-sm">
                                        <div class="hmw_pro"><img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>">
                                        </div>
                                        <label for="hmw_hide_authors"><?php _e('Hide Author ID URL', _HMW_PLUGIN_NAME_); ?></label>
                                        <div class="offset-1 text-black-50"><?php _e("Don't let URLs like domain.com?author=1 show the user login name", _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('Plugin Settings', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom plugins path URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. modules', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_plugin_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_plugin_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_plugin_url'] ?>"/>
                                </div>
                            </div>
                            <div class="col-sm-12 row mb-1 ml-1">
                                <div class="box" data-toggle="popover" data-html="true" data-placement="top" data-content="<?php echo sprintf(__('This feature requires %sHide My WP Ghost%s.', _HMW_PLUGIN_NAME_), "<a href='http://hidemywp.co/' target='_blank'>", "</a>") ?>">
                                    <div class="ribbon"><span><?php echo __('PRO', _HMW_PLUGIN_NAME_) ?></span></div>
                                </div>
                                <div class="checker col-sm-12 row my-2 py-1" style="opacity: 0.3">
                                    <div class="col-sm-12 p-0 switch switch-sm">
                                        <div class="hmw_pro"><img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>">
                                        </div>
                                        <label for="hmw_hide_plugins"><?php _e('Hide plugin names', _HMW_PLUGIN_NAME_); ?></label>
                                        <div class="offset-1 text-black-50"><?php _e('Give random names to each plugin', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('Theme Settings', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom themes path URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. assets, templates, styles', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_themes_url" value="<?php echo HMW_Classes_Tools::getOption('hmw_themes_url') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_themes_url'] ?>"/>
                                </div>
                            </div>

                            <div style="position: relative">
                                <div class="box" data-toggle="popover" data-html="true" data-placement="top" data-content="<?php echo sprintf(__('This feature requires %sHide My WP Ghost%s.', _HMW_PLUGIN_NAME_), "<a href='http://hidemywp.co/' target='_blank'>", "</a>") ?>">
                                    <div class="ribbon"><span><?php echo __('PRO', _HMW_PLUGIN_NAME_) ?></span></div>
                                </div>
                                <div style="opacity: 0.3">
                                    <div class="col-sm-12 row mb-1 ml-1">
                                        <div class="checker col-sm-12 row my-2 py-1">
                                            <div class="col-sm-12 p-0 switch switch-sm">
                                                <div class="hmw_pro">
                                                    <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>"></div>
                                                <label for="hmw_hide_themes"><?php _e('Hide theme names', _HMW_PLUGIN_NAME_); ?></label>
                                                <div class="offset-1 text-black-50"><?php _e('Give random names to each theme (works in WP multisite)', _HMW_PLUGIN_NAME_); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                        <div class="col-sm-4 p-0 font-weight-bold">
                                            <?php _e('Custom theme style name', _HMW_PLUGIN_NAME_); ?>:
                                            <div class="small text-black-50"><?php _e('eg. main.css,  theme.css, design.css', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                        <div class="col-sm-8 p-0 input-group input-group-lg" style="opacity: 0.3">
                                            <input type="text" class="form-control bg-input"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 row mb-1 ml-1">
                                        <div class="checker col-sm-12 row my-2 py-1">
                                            <div class="col-sm-12 p-0 switch switch-sm">
                                                <div class="hmw_pro">
                                                    <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>">
                                                </div>
                                                <label for="hmw_hide_styleids"><?php _e('Hide IDs from Stylesheets', _HMW_PLUGIN_NAME_); ?></label>
                                                <div class="offset-1 text-black-50"><?php _e('(may slow down the website)', _HMW_PLUGIN_NAME_); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('REST API Settings', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <div class="box" data-toggle="popover" data-html="true" data-placement="top" data-content="<?php echo sprintf(__('This feature requires %sHide My WP Ghost%s.', _HMW_PLUGIN_NAME_), "<a href='http://hidemywp.co/' target='_blank'>", "</a>") ?>">
                                <div class="ribbon"><span><?php echo __('PRO', _HMW_PLUGIN_NAME_) ?></span></div>
                            </div>
                            <div style="opacity: 0.3">
                                <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                    <div class="col-sm-4 p-0 font-weight-bold">
                                        <?php _e('Custom API path URL', _HMW_PLUGIN_NAME_); ?>:
                                        <div class="small text-black-50"><?php _e('eg. json, api, call', _HMW_PLUGIN_NAME_); ?></div>
                                    </div>
                                    <div class="col-sm-8 p-0 input-group input-group-lg" style="opacity: 0.3">
                                        <input type="text" class="form-control bg-input"/>
                                    </div>
                                </div>

                                <div class="col-sm-12 row mb-1 ml-1">
                                    <div class="checker col-sm-12 row my-2 py-1">
                                        <div class="col-sm-12 p-0 switch switch-sm">
                                            <div class="hmw_pro">
                                                <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>">
                                            </div>
                                            <label for="hmw_disable_rest_api"><?php _e('Disable Rest API access', _HMW_PLUGIN_NAME_); ?></label>
                                            <div class="offset-1 text-black-50"><?php _e("Disable Rest API access if you don't use your website for API calls", _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <h3 class="card-title bg-brown text-white p-2"><?php _e('Security Settings', _HMW_PLUGIN_NAME_); ?>:</h3>
                        <div class="card-body">
                            <div class="box" data-toggle="popover" data-html="true" data-placement="top" data-content="<?php echo sprintf(__('This feature requires %sHide My WP Ghost%s.', _HMW_PLUGIN_NAME_), "<a href='http://hidemywp.co/' target='_blank'>", "</a>") ?>">
                                <div class="ribbon"><span><?php echo __('PRO', _HMW_PLUGIN_NAME_) ?></span></div>
                            </div>
                            <div style="opacity: 0.3">
                                <div class="col-sm-12 row mb-1 ml-1">
                                    <div class="checker col-sm-12 row my-2 py-1">
                                        <div class="col-sm-12 p-0 switch switch-sm">
                                            <div class="hmw_pro">
                                                <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>">
                                            </div>
                                            <label for="hmw_hide_oldpaths"><?php _e('Hide WordPress Common Paths', _HMW_PLUGIN_NAME_); ?></label>
                                            <div class="offset-1 text-black-50"><?php _e('Hide /wp-content, /wp-include, /plugins, /themes paths', _HMW_PLUGIN_NAME_); ?></div>
                                            <div class="offset-1 text-black-50"><?php _e('Hide upgrade.php and install.php for visitors', _HMW_PLUGIN_NAME_); ?></div>
                                            <div class="offset-1 text-black-50"><?php _e('(this may affect the fonts and images loaded through CSS)', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 row mb-1 ml-1">
                                    <div class="checker col-sm-12 row my-2 py-1">
                                        <div class="col-sm-12 p-0 switch switch-sm">
                                            <div class="hmw_pro">
                                                <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>">
                                            </div>
                                            <label for="hmw_hide_commonfiles"><?php _e('Hide WordPress Common Files', _HMW_PLUGIN_NAME_); ?></label>
                                            <div class="offset-1 text-black-50"><?php _e('Hide wp-config.php , wp-config-sample.php, readme.html, license.txt files', _HMW_PLUGIN_NAME_); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (HMW_Classes_Tools::isNginx() || HMW_Classes_Tools::isApache() || HMW_Classes_Tools::isLitespeed()) { ?>

                                    <div class="col-sm-12 row mb-1 ml-1">
                                        <div class="checker col-sm-12 row my-2 py-1">
                                            <div class="col-sm-12 p-0 switch switch-sm">
                                                <div class="hmw_pro">
                                                    <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>"></div>
                                                <label for="hmw_sqlinjection"><?php _e('Firewall Against Script Injection', _HMW_PLUGIN_NAME_); ?></label>
                                                <div class="offset-1 text-black-50"><?php echo __('Most WordPress installations are hosted on the popular Apache, Nginx and IIS web servers.', _HMW_PLUGIN_NAME_); ?></div>
                                                <div class="offset-1 text-black-50"><?php echo __('A thorough set of rules can prevent many types of SQL Injection and URL hacks from being interpreted.', _HMW_PLUGIN_NAME_); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 row mb-1 ml-1">
                                        <?php $uploads = wp_upload_dir(); ?>
                                        <div class="checker col-sm-12 row my-2 py-1">
                                            <div class="col-sm-12 p-0 switch switch-sm">
                                                <div class="hmw_pro">
                                                    <img src="<?php echo _HMW_THEME_URL_ . 'img/pro.png' ?>"></div>
                                                <label for="hmw_disable_browsing"><?php _e('Disable Directory Browsing', _HMW_PLUGIN_NAME_); ?></label>
                                                <div class="offset-1 text-black-50"><?php echo sprintf(__("Don't let hackers see any directory content. See %sUploads Directory%s", _HMW_PLUGIN_NAME_), '<a href="' . $uploads['baseurl'] . '" target="_blank">', '</a>'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card col-sm-12 p-0 tab-panel" <?php echo((HMW_Classes_Tools::getOption('hmw_mode') == 'default') ? 'style="display:none"' : '') ?>>
                        <div class="card-body">
                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom category URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. cat, dir, list', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control bg-input" name="hmw_category_base" value="<?php echo HMW_Classes_Tools::getOption('hmw_category_base') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_category_base'] ?>"/>
                                </div>
                            </div>

                            <div class="col-sm-12 row border-bottom border-light py-3 mx-1 my-3">
                                <div class="col-sm-4 p-0 font-weight-bold">
                                    <?php _e('Custom tags URL', _HMW_PLUGIN_NAME_); ?>:
                                    <div class="small text-black-50"><?php _e('eg. keyword, topic', _HMW_PLUGIN_NAME_); ?></div>
                                </div>
                                <div class="col-sm-8 p-0 input-group input-group-lg">
                                    <input type="text" class="form-control" name="hmw_tag_base" value="<?php echo HMW_Classes_Tools::getOption('hmw_tag_base') ?>" placeholder="<?php echo HMW_Classes_Tools::$default['hmw_tag_base'] ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 my-3 p-0">
                        <button type="submit" class="btn rounded-0 btn-success btn-lg px-5 save"><?php _e('Save', _HMW_PLUGIN_NAME_); ?></button>
                        <a href="https://wordpress.org/support/plugin/hide-my-wp/reviews/?rate=5#new-post" target="_blank" class="btn rounded-0 btn-link btn-sm "><img src="<?php echo _HMW_THEME_URL_ ?>img/support_hide_my_wp.png" style="width: 100%; max-width: 550px"></a>
                    </div>
                </form>
            </div>
            <div class="hmw_col hmw_col_side">
                <div class="card col-sm-12 p-0">
                    <div class="card-body f-gray-dark text-center">
                        <h3 class="card-title"><?php _e('Check Your Website', _HMW_PLUGIN_NAME_); ?></h3>
                        <div class="card-text text-muted">
                            <?php echo __('Check if your website is secured with the current settings.', _HMW_PLUGIN_NAME_) ?>
                        </div>
                        <div class="card-text text-info m-3">
                            <a href="<?php echo HMW_Classes_Tools::getSettingsUrl('hmw_securitycheck') ?>" class="btn rounded-0 btn-warning btn-lg text-white px-5 securitycheck"><?php _e('Security Check', _HMW_PLUGIN_NAME_); ?></a>
                        </div>
                        <div class="card-text text-muted small">
                            <?php echo __('Make sure you save the settings and empty the cache before checking your website with our tool.', _HMW_PLUGIN_NAME_) ?>
                        </div>

                        <div class="card-text m-3 ">
                            <a class="bigbutton text-center" href="http://hidemywp.co/knowledge-base/" target="_blank"><?php echo __("Learn more about Hide My WP", _HMW_PLUGIN_NAME_); ?></a>
                        </div>
                    </div>
                </div>

                <div class="card col-sm-12 p-0">
                    <div class="card-body f-gray-dark text-center">
                        <h3 class="card-title"><?php echo __('Love Hide My WP?', _HMW_PLUGIN_NAME_); ?></h3>
                        <div class="card-text text-muted">
                            <h1>
                                <a href="https://wordpress.org/support/plugin/hide-my-wp/reviews/?rate=5#new-post" target="_blank" style="font-size: 80px"><i class="fa fa-heart text-danger"></i></a>
                            </h1>
                            <?php echo __('Please help us and support our plugin on WordPress.org', _HMW_PLUGIN_NAME_) ?>
                        </div>
                        <div class="card-text text-info m-3">
                            <a href="https://wordpress.org/support/plugin/hide-my-wp/reviews/?rate=5#new-post" target="_blank" class="btn rounded-0 btn-success btn-lg px-4"><?php echo __('Rate Hide My WP', _HMW_PLUGIN_NAME_); ?></a>
                        </div>
                        <div class="card-text text-muted">
                            <?php echo __('Contact us after you left the review cause we have a surprise for you.', _HMW_PLUGIN_NAME_) ?>
                            <h1>
                                <a href="https://hidemywp.co/contact/" target="_blank" style="font-size: 80px"><i class="fa fa-gift text-info"></i></a>
                            </h1>
                        </div>
                    </div>
                </div>

                <?php echo $view->getView('Support') ?>

            </div>
        </div>
    </div>
<?php }