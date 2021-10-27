//SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

import "./Token.sol";

contract Auction {
    address marketContract;

    struct Auctioneer {
        address auctioneer;
        uint256 amount;
    }
    mapping(bytes32 => Auctioneer[]) auctioneers;
    mapping(bytes32 => Auctioneer) toBeWinner;

    constructor(address _marketContract) {
        marketContract = _marketContract;
    }

    function getAuctioneers(string memory name, string memory symbol)
        public
        view
        returns (Auctioneer[] memory)
    {
        bytes32 key = keccak256(abi.encodePacked(name, symbol));
        return auctioneers[key];
    }

    function addAuctioneer(
        bytes32 key,
        address auctioneer,
        uint256 amount
    ) internal {
        toBeWinner[key] = Auctioneer(auctioneer, amount);
        auctioneers[key].push(toBeWinner[key]);
    }

    function removeAuctioneers(bytes32 key) public payable {
        // require(msg.sender == marketContract, "Invalid Caller");
        require(auctioneers[key].length != 0, "Empty Auctioniers");
        for (uint256 i = 0; i < auctioneers[key].length; i++) {
            if (auctioneers[key][i].auctioneer != toBeWinner[key].auctioneer) {
                payable(auctioneers[key][i].auctioneer).transfer(
                    auctioneers[key][i].amount
                );
            }
        }
        delete auctioneers[key];
    }
}
