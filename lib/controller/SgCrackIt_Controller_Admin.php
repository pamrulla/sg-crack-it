<?php

class SgCrackIt_Controller_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_page'));
    }

    public function register_page()
    {
        $pages = array();

        $pages[] = add_menu_page(
            'Sg-Crack-It',
            'Sg-Crack-It',
            'sgCrackIt_show',
            'sgCrackIt',
            array($this, 'route'));

        $pages[] = add_submenu_page(
            'sgCrackIt',
            __('Global settings', 'sg-crack-it'),
            __('Global settings', 'sg-crack-it'),
            'sgCrackIt_change_settings',
            'sgCrackIt_glSettings',
            array($this, 'route'));

        $pages[] = add_submenu_page(
            'sgCrackIt',
            __('Support & More', 'sg-crack-it'),
            __('Support & More', 'sg-crack-it'),
            'sgCrackIt_show',
            'sgCrackIt_wpq_support',
            array($this, 'route'));

        foreach ($pages as $p) {
            add_action('admin_print_scripts-' . $p, array($this, 'enqueueScript'));
            add_action('load-' . $p, array($this, 'routeLoadAction'));
        }
    }

    public function routeLoadAction()
    {
        $screen = get_current_screen();

        if (!empty($screen)) {
            // Workaround for wp_ajax_hidden_columns() with sanitize_key()
            $name = strtolower($screen->id);

            if (!empty($_GET['module'])) {
                $name .= '_' . strtolower($_GET['module']);
            }

            set_current_screen($name);

            $screen = get_current_screen();
        }

        $helperView = new WpProQuiz_View_GlobalHelperTabs();

        $screen->add_help_tab($helperView->getHelperTab());
        $screen->set_help_sidebar($helperView->getHelperSidebar());

        $this->_route(true);
    }

    public function route()
    {
        $this->_route();
    }

    private function _route($routeAction = false)
    {
        $module = isset($_GET['module']) ? $_GET['module'] : 'overallView';

        if (isset($_GET['page'])) {
            if (preg_match('#wpProQuiz_(.+)#', trim($_GET['page']), $matches)) {
                $module = $matches[1];
            }
        }

        $c = null;

        switch ($module) {
            case 'overallView':
                $c = new WpProQuiz_Controller_Quiz();
                break;
            case 'question':
                $c = new WpProQuiz_Controller_Question();
                break;
            case 'preview':
                $c = new WpProQuiz_Controller_Preview();
                break;
            case 'statistics':
                $c = new WpProQuiz_Controller_Statistics();
                break;
            case 'importExport':
                $c = new WpProQuiz_Controller_ImportExport();
                break;
            case 'glSettings':
                $c = new WpProQuiz_Controller_GlobalSettings();
                break;
            case 'styleManager':
                $c = new WpProQuiz_Controller_StyleManager();
                break;
            case 'toplist':
                $c = new WpProQuiz_Controller_Toplist();
                break;
            case 'wpq_support':
                $c = new WpProQuiz_Controller_WpqSupport();
                break;
            case 'info_adaptation':
                $c = new WpProQuiz_Controller_InfoAdaptation();
                break;
        }

        if ($c !== null) {
            if ($routeAction) {
                if (method_exists($c, 'routeAction')) {
                    $c->routeAction();
                }
            } else {
                $c->route();
            }
        }
    }
}