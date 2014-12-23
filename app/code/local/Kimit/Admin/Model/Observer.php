<?php
/**
 * Kimit Admin Dashboard
 *
 * @author John Tranter
 */
class Kimit_Admin_Model_Observer
{

    public function injectDashboardTabs(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ( $block instanceof Mage_Adminhtml_Block_Dashboard_Diagrams )
        {
            $block->addTab('welcome', array(
                'label'     => $block->__('Current Orders'),
                'content'   => $block->getLayout()->createBlock('kimitadmin/dashboard_tab_welcome')->toHtml(),
                'active'    => true
            ));
        }

    }

}