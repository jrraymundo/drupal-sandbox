<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Controller\ReportController
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Controller for RSVP List Report
 */
 class ReportController extends ControllerBase {

    /**
     * Gets all RSVPs for all nodes
     * 
     * @return array
     */
    protected function load() {
        // Get the rsvplist table, and use 'r' as its alias
        $select = Database::getConnection()->select('rsvplist', 'r');

        // Join the users table with alias 'u' to the rsvplist table, so we can get the entry creator's username
        $select->join('users_field_data', 'u', 'r.uid = u.uid');

        // Join the node table with alias 'n' to the rsvplist table, so we can get the event's name
        $select->join('node_field_data', 'n', 'r.nid = n.nid');

        // Select these specific fields for the output, note that we are using the table aliases for each field
        $select->addField('u', 'name', 'username');
        $select->addField('n', 'title');
        $select->addField('r', 'mail');

        $entries =$select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }

    /**
     * Creates the report page
     * 
     * @return array 
     * Render array for report output.
     */
    public function report() {
        // This will be displayed in the custom report page above the table
        $content = array();
        $content['message'] = array(
            '#markup' => $this->t('Below is a list of all Event RSVPs including username, email address and the name of the event they will be attending.')
        );
        
        /**
         * These will be the table headers displayed
         * Note that the order of this array will matter
         * It should follow the same order of the fields that were added in load(),
         * in order to display them under the correct header
         */
        $headers = array(
            t('Name'),
            t('Event'),
            t('Email')
        );

        /**
         * An array called $rows is created to store the data for each row of the table
         * 
         * Each entry is sanitized. 
         * We use the SafeMarkup::checkPlain class/method from Drupal to do this'
         */
        $rows = array();
        foreach($entries = $this->load() as $entry) {
            // 
            $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
        }

        // The data are put together to form the table
        $content['table'] = array(
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#empty' => t('No entries available.')
        );

        // Disable cache for this page
        $content['#cache']['max-age'] = 0;
        
        return $content;
    }
 }