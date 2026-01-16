<?php

namespace ProductShowcase\Admin;

/**
 * Class Admin_Manager
 *
 * Bootstraps admin functionality.
 */
class Admin_Manager {

    /**
     * Initialize admin hooks.
     */
    public function init() {
        $meta_box = new Meta_Box();
        $meta_box->init();

        $settings_page = new Settings_Page();
        $settings_page->init();
    }
}
