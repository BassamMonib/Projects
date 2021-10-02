// SPDX-License-Identifier: UNLICENSED
pragma solidity >=0.7.3;

contract Marketplace {
    // Other typr of assets and more attributes can be used
    // This is a simple Asset struct used for convenience
    struct Asset {
        string name;
        uint256 price;
    }

    // Order Book
    Asset[] private sellingDemands;
    Asset[] private buyingRequests;

    function getDemandsCount() external view returns (uint256) {
        return sellingDemands.length;
    }

    function getRequestsCount() external view returns (uint256) {
        return buyingRequests.length;
    }

    function _checkDemands(string memory name, uint256 price)
        internal
        view
        returns (bool)
    {
        for (uint256 indx = 0; indx < sellingDemands.length; indx++)
            // Strings are limited and can't be directly compared, so using their hash for comparing
            if (
                keccak256(abi.encodePacked((sellingDemands[indx].name))) ==
                keccak256(abi.encodePacked((name))) &&
                sellingDemands[indx].price == price
            ) return true;
        return false;
    }

    function _checkRequests(string memory name, uint256 price)
        internal
        view
        returns (bool)
    {
        for (uint256 indx = 0; indx < buyingRequests.length; indx++)
            // Strings are limited and can't be directly compared, so using their hash for comparing
            if (
                keccak256(abi.encodePacked((buyingRequests[indx].name))) ==
                keccak256(abi.encodePacked((name))) &&
                buyingRequests[indx].price == price
            ) return true;
        return false;
    }

    function _clearRecords(string memory name, uint256 price) internal {
        for (uint256 indx = 0; indx < sellingDemands.length; indx++)
            // Strings are limited and can't be directly compared, so using their hash for comparing
            if (
                keccak256(abi.encodePacked((sellingDemands[indx].name))) ==
                keccak256(abi.encodePacked((name))) &&
                sellingDemands[indx].price == price
            ) {
                // Removign the stored demand record
                sellingDemands[indx] = sellingDemands[
                    sellingDemands.length - 1
                ];
                sellingDemands.pop();
                break;
            }
        for (uint256 indx = 0; indx < buyingRequests.length; indx++)
            // Strings are limited and can't be directly compared, so using their hash for comparing
            if (
                keccak256(abi.encodePacked((buyingRequests[indx].name))) ==
                keccak256(abi.encodePacked((name))) &&
                buyingRequests[indx].price == price
            ) {
                // Removing the stored request record
                buyingRequests[indx] = buyingRequests[
                    buyingRequests.length - 1
                ];
                buyingRequests.pop();
                break;
            }
    }

    function putDemand(string memory name, uint256 price) external {
        // Check Present Demands
        require(!_checkDemands(name, price), "Demand already present");

        // Match and sell asset directly
        if (_checkRequests(name, price)) {
            // Clear records after order/transaction finished
            _clearRecords(name, price);
        } else {
            // Store Demand
            Asset memory newDemand = Asset(name, price);
            sellingDemands.push(newDemand);
        }
    }

    function putRequest(string memory name, uint256 price) external {
        // Check Present Requests
        require(!_checkRequests(name, price), "Request already present");

        // Match and buy asset directly
        if (_checkDemands(name, price)) {
            // Clear records after order/transaction finished
            _clearRecords(name, price);
        } else {
            // Store Request
            Asset memory newRequest = Asset(name, price);
            buyingRequests.push(newRequest);
        }
    }
}
