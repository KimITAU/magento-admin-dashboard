<?php
/**
 * Kimit Admin Dashboard
 *
 * @author John Tranter
 */


/**
 * Adminhtml dashboard orders diagram
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */

class Kimit_Admin_Block_Dashboard_Tab_Welcome extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    
    protected $_orders;
    
    public function __construct()
    {
        parent::__construct();
        $this->setId('welcome_tab');
        $this->setDestElementId('diagram_tab_content');
        $this->setTemplate('widget/tabshoriz.phtml');
    }
    
    public function getTabLabel() {
        return Mage::helper('kimitadmin')->__('Current Orders');
    }

    public function getTabTitle() {
        return Mage::helper('kimitadmin')->__('Current Orders');
    }

    public function canShowTab() {
        return true;
    }
    
    public function isHidden()
    {
        return false;
    }
    
    protected function _toHtml() {
        $returnhtml = array();
        
        foreach($this->getOrders() as $_order)
        {
            $filter="status=".$_order->getStatus();
            $filter = base64_encode($filter);
            $url = Mage::helper('adminhtml')->getUrl('adminhtml/sales_order', array('filter'=>$filter));
            $returnhtml[] = "<tr><td class=".$_order->getStatusLabel()."><a href='$url'>".$_order->getStatusLabel() . '</td><td>'.$_order->getQty()."</a></td></tr>";
        }
        asort($returnhtml);
        array_unshift($returnhtml,"<table class='welcome_orders'><tr><th>Current Orders</td><td>Count</th></tr>");
        $returnhtml[] = "</table><style>table.welcome_orders{margin:30px;font-size:2em;border:1px solid #ccc;}table.welcome_orders td,table.welcome_orders th{padding:8px 10px;border-style:solid; border-color: #ccc;border-width:0 1px 1px 1px }table.welcome_orders td a{text-decoration: none;}table.welcome_orders td:last-child{text-align:center;}table.welcome_orders tr:first-child{background-color:rgb(111, 137, 146);color:#fff;}table.welcome_orders tr:nth-child(2n){background-color:rgb(218,223,224);}</style>";
        return implode('', $returnhtml) ;
    }
    
    protected function getOrders()
    {
        if(!isset($this->_orders))
        {
            $this->_orders = Mage::getModel('sales/order')->getCollection()
            //->addAttributeToSelect('state')
            ->addAttributeToSelect('status')
            ->addFieldToFilter('state', array('neq'=>'complete'))
            ->addFieldToFilter('state', array('neq'=>'canceled'))
            ->addFieldToFilter('status', array('neq'=>'canceled'))
            ->addFieldToFilter('status', array('neq'=>'complete'))
            ->addFieldToFilter('state', array('neq'=>'closed'));
            
            //$this->_orders->getSelect()-> ('qty', 'SUM({{status}})', 'status');
            $this->_orders->getSelect()->columns('count(*) as qty');
            $this->_orders->getSelect()->group('status');
        }
        
        return $this->_orders;
    }
    
}