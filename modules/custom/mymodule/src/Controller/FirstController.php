<?php

/**
 * @file
 * Contains \Drupal\mymodule\Controller\MyModuleController
 * 
 * This controller creates a page content that will be accessed via the path set in routing.yml
 * It will then return an array that will be used to display content
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {
    public function content() {
        return array(
            '#type' => 'markup',
            '#markup' => t('This is my menu linked custom page'),
        );
    }
}