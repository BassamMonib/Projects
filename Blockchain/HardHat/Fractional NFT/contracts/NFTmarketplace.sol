//SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

import "./FractionManager.sol";
import "./Auction.sol";

contract NFTmarketplace is FractionManager, Auction {
    Token[] NFTs;

    function _TEST_TIME(string memory name, string memory symbol) public {
        uint256 indx = _getIndx(keccak256(abi.encodePacked(name, symbol)));
        require(indx != NFTs.length, "NFT invalid");
        NFTs[indx].setTime(block.timestamp);
    }

    modifier isNFTpresent(bytes32 _key, bool flag) {
        uint256 indx = _getIndx(_key);
        if (indx != NFTs.length) flag = !flag;
        require(flag, "NFT invalid state");
        _;
    }

    modifier isSharesPresent(bytes32 _key) {
        uint256 indx = _getIndx(_key);
        require(indx != NFTs.length, "NFT invalid");
        require(NFTs[indx].shares() > 0, "NFT shares are not present");
        _;
    }

    constructor() Auction(address(this)) {}

    function getNFTsList() public view returns (Token[] memory) {
        return NFTs;
    }

    function enlistNFT(
        string memory name,
        string memory symbol,
        uint256 shares,
        uint256 amount,
        uint256 price
    )
        external
        payable
        isNFTpresent(keccak256(abi.encodePacked(name, symbol)), true)
    {
        Token NFT = new Token(name, symbol, shares, amount, price);
        NFTs.push(NFT);
    }

    function buyFraction(string memory name, string memory symbol)
        external
        payable
        isNFTpresent(keccak256(abi.encodePacked(name, symbol)), false)
        isSharesPresent(keccak256(abi.encodePacked(name, symbol)))
    {
        uint256 indx = _getIndx(keccak256(abi.encodePacked(name, symbol)));
        require(indx != NFTs.length, "NFT invalid");
        require(msg.value == NFTs[indx].amount());
        addFractionHolder(name, symbol, msg.sender);
        _reduceShare(keccak256(abi.encodePacked(name, symbol)));
    }

    function auction(string memory name, string memory symbol)
        external
        payable
        isNFTpresent(keccak256(abi.encodePacked(name, symbol)), false)
    {
        uint256 indx = _getIndx(keccak256(abi.encodePacked(name, symbol)));
        require(indx != NFTs.length, "NFT invalid");
        require(block.timestamp < NFTs[indx].time(), "Auction time expired");
        require(NFTs[indx].price() < msg.value, "Invalid acution price");
        addAuctioneer(
            keccak256(abi.encodePacked(name, symbol)),
            msg.sender,
            msg.value
        );
    }

    function endAuction(string memory name, string memory symbol)
        external
        payable
        isNFTpresent(keccak256(abi.encodePacked(name, symbol)), false)
    {
        uint256 indx = _getIndx(keccak256(abi.encodePacked(name, symbol)));
        require(indx != NFTs.length, "NFT invalid");
        require(
            block.timestamp > NFTs[indx].time(),
            "Auction time not expired"
        );
        removeAuctioneers(keccak256(abi.encodePacked(name, symbol)));
        distributeInFractions(
            keccak256(abi.encodePacked(NFTs[indx].name(), NFTs[indx].symbol())),
            NFTs[indx].price()
        );
        delete NFTs[indx];
    }

    function _reduceShare(bytes32 _key) internal {
        uint256 indx = _getIndx(_key);
        require(indx != NFTs.length, "NFT invalid");
        NFTs[indx].setShares(NFTs[indx].shares() - 1);
    }

    function _getIndx(bytes32 _key) internal view returns (uint256) {
        uint256 i = 0;
        for (; i < NFTs.length; i++) {
            bytes32 key = keccak256(
                abi.encodePacked(NFTs[i].name(), NFTs[i].symbol())
            );
            if (key == _key) break;
        }
        return i;
    }
}
