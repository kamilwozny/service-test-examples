<?php

namespace \Tests\Service;

use \Entity\Address;
use \Entity\User;
use \Service\AddressHelper;

/**
 * @author Kamil Woźny
 */
class AddressHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGlobalDeliveryPoint()
    {
        $address = $this->getMockBuilder(Address::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $address->expects($this->exactly(3))
                ->method('getIsGlobalDeliveryPoint')
                ->will($this->returnValue(true));

        $user = $this->getMock(User::class);

        //handling = global delivery point so this test has to be true
        $this->assertTrue($this->isValidAddress(AddressHelper::CASE_1_2_3, $address, $user));
        // different case, not true
        $this->assertNotTrue($this->isValidAddress(AddressHelper::CASE_1_2, $address, $user));
        // different case, not true
        $this->assertNotTrue($this->isValidAddress(AddressHelper::CASE_1, $address, $user));
    }

    /**
     * Test behaviour when user which not own this address want to use it
     */
    public function testAddressAccess()
    {
        $user1 = $this->getMock(User::class);
        $user2 = $this->getMock(User::class);

        $address = $this->getMockBuilder(Address::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $address->expects($this->exactly(4))
                ->method('getIsGlobalDeliveryPoint')
                ->will($this->returnValue(false));

        $address->expects($this->exactly(4))
            ->method('getUser')
            ->will($this->returnValue($user1));

        $this->assertNotTrue($this->isValidAddress(AddressHelper::CASE_1_2, $address, $user2)); // user2 cant use user1 address
        $this->assertTrue($this->isValidAddress(AddressHelper::CASE_1_2, $address, $user1)); // user1 can use his second address

        $address->expects($this->exactly(1))->method('getIsMainAddress')->will($this->returnValue(true));
        $this->assertTrue($this->isValidAddress(AddressHelper::CASE_1, $address, $user1)); // user1 can use his main address

        $this->assertNotTrue($this->isValidAddress(AddressHelper::CASE_1, $address, $user2)); // user2 cant use user1 address
    }

    /**
     * @param $addr_handling_case int address handling case
     * @param Address $address
     * @param User $user
     *
     * @return bool
     */
    private function isValidAddress($addr_handling_case, Address $address, User $user)
    {
        $addressHelper = new AddressHelper($addr_handling_case);
        return $addressHelper->validateAddress($address, $user);
    }
}
