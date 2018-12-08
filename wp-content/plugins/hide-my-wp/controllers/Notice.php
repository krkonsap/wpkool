<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class HMW_Controllers_Notice extends HMW_Classes_FrontController {

    protected $notice_spam = 0;

    public function __construct() {
        parent::__construct();
        add_action('admin_notices', array($this, 'hmw_admin_notices'));

        //check if notice is disabled
        $this->action();

    }

    /**
     * Show the notifications for review
     * @param $notices
     */
    public function hmw_admin_notices($notices) {
        if (!isset($notices) || !is_array($notices)) {
            $notices = array();
        }

        $ignore = add_query_arg(array('hmw_admin_notice' => 'two_week_review', 'hmw_admin_notice_action' => 'later'), HMW_Classes_Tools::getSettingsUrl());
        $disable = add_query_arg(array('hmw_admin_notice' => 'two_week_review', 'hmw_admin_notice_action' => 'disable'), HMW_Classes_Tools::getSettingsUrl(), HMW_Classes_Tools::getSettingsUrl());
        if (is_string($disable) && $disable <> '') {
            $notices['two_week_review'] = array(
                'title' => __('Love Hide My WP?', _HMW_PLUGIN_NAME_),
                'msg' => '<a href="https://wordpress.org/support/plugin/hide-my-wp/reviews/?rate=5#new-post" target="_blank" style="font-weight: bold; display: inline-block;"><img src="' . _HMW_THEME_URL_ . 'img/support_hide_my_wp.png" style="width: 100%; max-width: 550px"></a>',
                'link' => '<li><span class="dashicons dashicons-external"></span><a href="https://wordpress.org/support/plugin/hide-my-wp/reviews/?rate=5#new-post" target="_blank" style="font-weight: bold">' . __("Leave a review!", _HMW_PLUGIN_NAME_) . '</a></li>
					<li> <span class="dashicons dashicons-smiley"></span><a href="' . $ignore . '"> ' . __("I've already left a review", _HMW_PLUGIN_NAME_) . '</a></li>
                     <li><span class="dashicons dashicons-dismiss"></span><a href="' . $disable . '">' . __("Never show again", _HMW_PLUGIN_NAME_) . '</a></li>',

                'later_link' => $ignore,
                'int' => 14
            );
        }
        HMW_Classes_ObjController::getClass('HMW_Classes_Error')->hookNotices();
        $this->showMessage($notices);

    }

    /**
     * Primary notice function that can be called from an outside function sending necessary variables
     *
     * @param $notices
     * @return bool|void
     */
    public function showMessage($notices) {
        foreach ($notices as $slug => $notice) {
            // Check for required fields
            if (!$this->required_fields($notice)) {
                // Call for spam protection
                if ($this->anti_notice_spam()) {
                    return;
                }

                // Get the current date then set start date to either passed value or current date value and add interval
                $current_date = current_time("n/j/Y");
                $start = (isset($notice['start']) ? $notice['start'] : $current_date);
                $interval = (isset($notice['int']) ? $notice['int'] : 0);
                $start = date("n/j/Y", strtotime("+$interval DAY", strtotime($start)));

                // This is the main notices storage option
                $notices_option = HMW_Classes_Tools::getOption('admin_notice');

                // Check if the message is already stored and if so just grab the key otherwise store the message and its associated date information
                if (!is_array($notices_option)) {
                    $notices_option = array();
                }
                if (!array_key_exists($slug, $notices_option)) {

                    $notices_option[$slug]['start'] = $start;
                    $notices_option[$slug]['int'] = $interval;
                    HMW_Classes_Tools::saveOptions('admin_notice', $notices_option);
                }


                // Sanity check to ensure we have accurate information
                // New date information will not overwrite old date information
                $admin_display_check = (isset($notices_option[$slug]['dismissed']) ? $notices_option[$slug]['dismissed'] : 0);
                $admin_display_start = (isset($notices_option[$slug]['start']) ? $notices_option[$slug]['start'] : $start);
                $admin_display_msg = (isset($notice['msg']) ? $notice['msg'] : '');
                $admin_display_title = (isset($notice['title']) ? $notice['title'] : '');
                $admin_display_link = (isset($notice['link']) ? $notice['link'] : '');
                $output_css = false;

                // Ensure the notice hasn't been hidden and that the current date is after the start date
                if ($admin_display_check == 0 && strtotime($admin_display_start) <= strtotime($current_date)) {

                    // Get remaining query string
                    $query_str = (isset($notice['later_link']) ? $notice['later_link'] : esc_url(add_query_arg('hmw_admin_notice', $slug)));
                    // Admin notice display output
                    echo '<div class="update-nag hmw-admin-notice">
                            <div class="' . 'hmw-notice-logo"></div>
                            <p class="hmw-notice-body">' . $admin_display_msg . '</p>
                            <ul class="hmw-notice-body hmw-blue" style="margin-left: 15px;">' . $admin_display_link . '</ul>
                            <a href="' . $query_str . '" class="dashicons dashicons-dismiss"></a>
                          </div>';

                    $this->notice_spam += 1;
                    $output_css = true;
                }

                if ($output_css) {
                    HMW_Classes_ObjController::getClass('HMW_Classes_DisplayController')->loadMedia('notice');
                }
            }
        }
    }

    /**
     * Called when an action is triggered
     *
     * @return void
     */
    public function action() {

        if (HMW_Classes_Tools::getValue('hmw_admin_notice', '') <> '' &&
            HMW_Classes_Tools::getValue('hmw_admin_notice_action', '') <> ''
        ) {

            switch (HMW_Classes_Tools::getValue('hmw_admin_notice_action')) {

                case 'disable':
                    $notices_option = HMW_Classes_Tools::getOption('admin_notice');
                    if (is_array($notices_option)) {
                        $notices_option[HMW_Classes_Tools::getValue('hmw_admin_notice')]['dismissed'] = 1;
                        HMW_Classes_Tools::saveOptions('admin_notice', $notices_option);
                        $query_str = remove_query_arg('hmw_admin_notice');
                        $query_str = remove_query_arg('hmw_admin_notice_action', $query_str);
                        wp_validate_redirect($query_str);
                        wp_redirect($query_str);
                    }
                    break;
                case 'later':
                    $notices_option = HMW_Classes_Tools::getOption('admin_notice');
                    $new_start = date("n/j/Y", strtotime("+14 DAY"));

                    $notices_option[HMW_Classes_Tools::getValue('hmw_admin_notice')]['start'] = $new_start;
                    $notices_option[HMW_Classes_Tools::getValue('hmw_admin_notice')]['dismissed'] = 0;
                    HMW_Classes_Tools::saveOptions('admin_notice', $notices_option);
                    $query_str = remove_query_arg('hmw_admin_notice');
                    $query_str = remove_query_arg('hmw_admin_notice_action', $query_str);
                    wp_validate_redirect($query_str);
                    wp_redirect($query_str);
                    break;
            }

            exit();
        }
    }


    /**
     * Spam protection check
     * @return bool
     */
    public function anti_notice_spam() {
        if ($this->notice_spam >= 1) {
            return true;
        }
        return false;
    }

    /**
     * Required fields check
     * @param $fields
     * @return bool
     */
    public function required_fields($fields) {
        if (!isset($fields['msg']) || (isset($fields['msg']) && empty($fields['msg']))) {
            return true;
        }
        if (!isset($fields['title']) || (isset($fields['title']) && empty($fields['title']))) {
            return true;
        }
        return false;
    }

}
