//SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract Token {
    string _name;
    string _symbol;
    uint256 _shares;
    uint256 _amount;
    uint256 _price;
    uint256 _time;

    constructor(
        string memory name_,
        string memory symbol_,
        uint256 shares_,
        uint256 amount_,
        uint256 price_
    ) {
        _name = name_;
        _symbol = symbol_;
        _shares = shares_;
        _amount = amount_;
        _time = block.timestamp + 86400;
        _price = price_;
    }

    function name() public view returns (string memory) {
        return _name;
    }

    function symbol() public view returns (string memory) {
        return _symbol;
    }

    function shares() public view returns (uint256) {
        return _shares;
    }

    function setShares(uint256 newShare) public {
        _shares = newShare;
    }

    function time() public view returns (uint256) {
        return _time;
    }

    function setTime(uint256 time_) public {
        _time = time_;
    }

    function amount() public view returns (uint256) {
        return _amount;
    }

    function price() public view returns (uint256) {
        return _price;
    }

    function setPrice(uint256 newPrice) public {
        _price = newPrice;
    }
}
