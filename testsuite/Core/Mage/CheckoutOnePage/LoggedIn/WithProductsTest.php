<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    tests
 * @package     selenium
 * @subpackage  tests
 * @author      Magento Core Team <core@magentocommerce.com>
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * One page Checkout tests with different products
 *
 * @package     selenium
 * @subpackage  tests
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Core_Mage_CheckoutOnePage_LoggedIn_WithProductsTest extends Mage_Selenium_TestCase
{
    /**
     * <p>Creating Simple and Virtual products</p>
     *
     * @return array
     * @test
     */
    public function preconditionsForTests()
    {
        //Data
        $simple = $this->loadDataSet('Product', 'simple_product_visible');
        $virtual = $this->loadDataSet('Product', 'virtual_product_visible');
        $userData = $this->loadDataSet('Customers', 'generic_customer_account');
        //Steps and Verification
        $this->loginAdminUser();
        $this->navigate('manage_products');
        $this->productHelper()->createProduct($simple);
        $this->assertMessagePresent('success', 'success_saved_product');
        $this->productHelper()->createProduct($virtual, 'virtual');
        $this->assertMessagePresent('success', 'success_saved_product');
        $this->navigate('manage_customers');
        $this->customerHelper()->createCustomer($userData);
        $this->assertMessagePresent('success', 'success_saved_customer');

        return array('simple' => $simple['general_name'], 'virtual' => $virtual['general_name'],
                     'user'   => array('email' => $userData['email'], 'password' => $userData['password']));
    }

    /**
     * <p>Checkout with simple product.</p>
     * <p>Preconditions:</p>
     * <p>1.Product is created.</p>
     * <p>2.Customer without address is created.</p>
     * <p>3.Customer signed in at the frontend.</p>
     * <p>Steps:</p>
     * <p>1. Open product page.</p>
     * <p>2. Add product to Shopping Cart.</p>
     * <p>3. Click "Proceed to Checkout".</p>
     * <p>4. Fill in Billing Information tab.</p>
     * <p>5. Select "Ship to this address" option.</p>
     * <p>6. Click 'Continue' button.</p>
     * <p>7. Select Shipping Method.</p>
     * <p>8. Click 'Continue' button.</p>
     * <p>9. Select Payment Method.</p>
     * <p>10. Click 'Continue' button.</p>
     * <p>11. Verify information into "Order Review" tab</p>
     * <p>12. Place order.</p>
     * <p>Expected result:</p>
     * <p>Checkout is successful.</p>
     *
     * @param array $data
     *
     * @test
     * @depends preconditionsForTests
     */
    public function withSimpleProductAndCustomerWithoutAddress($data)
    {
        $checkoutData = $this->loadDataSet('OnePageCheckout', 'signedin_flatrate_checkmoney',
                                           array('general_name' => $data['simple']));
        //Steps
        $this->customerHelper()->frontLoginCustomer($data['user']);
        $this->checkoutOnePageHelper()->frontCreateCheckout($checkoutData);
        //Verification
        $this->assertMessagePresent('success', 'success_checkout');
    }

    /**
     * <p>Checkout with virtual product.</p>
     * <p>Preconditions:</p>
     * <p>1.Product is created.</p>
     * <p>2.Customer without address is created.</p>
     * <p>3.Customer signed in at the frontend.</p>
     * <p>Steps:</p>
     * <p>1. Open product page.</p>
     * <p>2. Add product to Shopping Cart.</p>
     * <p>3. Click "Proceed to Checkout".</p>
     * <p>4. Fill in Billing Information tab.</p>
     * <p>5. Click 'Continue' button.</p>
     * <p>6. Select Payment Method.</p>
     * <p>7. Click 'Continue' button.</p>
     * <p>8. Verify information into "Order Review" tab</p>
     * <p>9. Place order.</p>
     * <p>Expected result:</p>
     * <p>Checkout is successful.</p>
     *
     * @param array $data
     *
     * @test
     * @depends preconditionsForTests
     */
    public function withVirtualProductAndCustomerWithoutAddress($data)
    {
        //Data
        $checkoutData = $this->loadDataSet('OnePageCheckout', 'signedin_flatrate_checkmoney_virtual',
                                           array('general_name' => $data['virtual']));
        //Steps
        $this->customerHelper()->frontLoginCustomer($data['user']);
        $this->checkoutOnePageHelper()->frontCreateCheckout($checkoutData);
        //Verification
        $this->assertMessagePresent('success', 'success_checkout');
    }
}