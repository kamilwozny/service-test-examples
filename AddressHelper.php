<?php

namespace \Service;

use \Entity\Address;
use \Entity\User;

class AddressHelper
{
    /**
     * This case tells, the address can be added ONLY by administration, agency can't modify it
     * Also there is only one main address, not more
     */
    const CASE_1 = 1;

    /**
     * In this case, agency can add a second delivery address
     */
    const CASE_1_2 = 2;

    /**
     * In this case, admin can add multiple address, agency see the drop down list with
     * those addresses and can choose it
     * User cant modify those
     */
    const CASE_1_2_3 = 4;

    /**
     * @var string
     */
    private $addr_handling_case;

    public function __construct($addr_handling_case)
    {
        $this->addr_handling_case = $addr_handling_case;
    }

    /**
     * CHeck if user have rights to use this address in order creation
     * @param Address $address
     * @param User $user
     *
     * @return bool true if user can use that address at order creation
     */
    public function validateAddress( Address $address, User $user )
    {
        // check if it is global delivery point and then check if this option is enabled in admin
        if($address->getIsGlobalDeliveryPoint()) {
            return $this->addr_handling_case === AddressHelper::CASE_1_2_3;
        }

        if($address->getUser() === $user) {
            if(!$address->getIsMainAddress()) {
                return $this->addr_handling_case === AddressHelper::CASE_1_2;
            }

            return true;
        }

        return false;
    }
}
