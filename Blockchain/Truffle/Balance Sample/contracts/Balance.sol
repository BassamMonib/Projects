// SPDX-License-Identifier: MIT
pragma solidity >= 0.4.2;

contract Balance {

    mapping (address => uint256) public balances;

    constructor() public {
      balances[msg.sender] = 100;
    }
}
