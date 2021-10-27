//SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

import "./Token.sol";

contract FractionManager {
    mapping(bytes32 => address[]) fractionHolders;
    mapping(address => uint256) fractionHoldersAmount;

    function getFractionHolders(string memory name, string memory symbol)
        public
        view
        returns (address[] memory)
    {
        bytes32 key = keccak256(abi.encodePacked(name, symbol));
        return fractionHolders[key];
    }

    function getFractionHoldersAmount(address fractionee)
        public
        view
        returns (uint256)
    {
        return fractionHoldersAmount[fractionee];
    }

    function addFractionHolder(
        string memory name,
        string memory symbol,
        address shareHolder
    ) internal {
        fractionHolders[keccak256(abi.encodePacked(name, symbol))].push(
            shareHolder
        );
        fractionHoldersAmount[shareHolder]++;
    }

    function distributeInFractions(bytes32 key, uint256 price) internal {
        uint256 sharePrice = price / fractionHolders[key].length;
        for (uint256 i = 0; i < fractionHolders[key].length; i++) {
            payable(fractionHolders[key][i]).transfer(sharePrice);
            delete fractionHoldersAmount[fractionHolders[key][i]];
        }
        delete fractionHolders[key];
    }
}
