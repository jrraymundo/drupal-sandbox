<?php
/**
 * @file
 * contains \Drupal\rsvplist\Plugin\Block\RSVPBlock
 */

namespace Drupal\rsvplist\Plugin\Block;

/**
 * BlockBase is used to extend our class
* AccountInterface and AccessResult is used to control permissions
* See Drupal docs for more info
*/
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides an 'RSVP' List Block
 * @Block(
 *  id = "rsvp_block",
 *  admin_label = @Translation("RSVP Block")
 * )
 */
 class RSVPBlock extends BlockBase {
    /**
     * {@inheritdoc}
    */

    /**
     * What ever is created in build() will be rendered and displayed by this block
     * 
     * In this case we pull the RSVPForm that was created in this module,
     * and use it to be displayed whenever this block is used
     * 
     * Note that this block may not be visible in home page,
     * It will only appear in a 'node' so create a content first 
     */
    public function build() {
        return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
    }

    public function blockAccess(AccountInterface $account) {
        /**
         * This comment is just to tell that we're accesing $node from the given namespace/path 
         * @var \Drupal\node\Entity\Node $node 
         * */
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;

        /**
         * @var \Drupal\rsvplist\EnablerService $enabler 
         * 
         * This will pull the rsvplist.enabler created from EnablerService.php
        */
        $enabler = \Drupal::service('rsvplist.enabler');

        /** 
         * If the $nid is valid/exists and the block is enabled via rsvplist.enabler,
         * then we allow access, else do not show the block
         * 
         * 'view rsvplist' refers to what was created in the permissions file
         */
        if (is_numeric($nid)) { // If $nid is valid
            if ($enabler->isEnabled($node)) { // If block is enabled for this $node
                return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
            }
        }
        return AccessResult::forbidden();
    }
 }