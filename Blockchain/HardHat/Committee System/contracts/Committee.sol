//  SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.0;

import "./Token.sol";

contract CommitteePool {
    Token[] pools;
    uint256 indx;

    event isLock(bool lock);
    event isParticipating(bool status);

    modifier checkPool(string memory _name, string memory _symbol) {
        require(_isPoolPresent(_name, _symbol), "Pool not present");
        _;
    }

    function getPoolLength() public view returns (uint256) {
        return pools.length;
    }

    function exchangePoolTokens(string memory _name, string memory _symbol)
        external
        payable
        checkPool(_name, _symbol)
    {
        pools[indx].mint(msg.sender, msg.value * 3);
    }

    function createPool(
        string memory _name,
        string memory _symbol,
        uint256 _fixedAmount
    ) external {
        require(!_isPoolPresent(_name, _symbol), "Pool already present");
        Token newToken = new Token(_name, _symbol, _fixedAmount, address(this));
        pools.push(newToken);
    }

    function payCommittee() public {
        // Getting approvals so that we can perform transferFrom, as it requires approval
        // All the amount approved is transfered so no token approval left

        int256 p_indx = pools[indx]._isParticipantPresent(msg.sender);
        require(p_indx != -1, "Participant is not present");
        require(pools[indx].getParticipation(p_indx), "Unknown Participation");

        pools[indx].getApprovalForToken(
            msg.sender,
            address(this),
            pools[indx].getfixedAmount()
        );
        pools[indx].transferFrom(
            msg.sender,
            address(this),
            pools[indx].getfixedAmount()
        );

        pools[indx].setTotalAmount(
            pools[indx].getTotalAmount() + pools[indx].getfixedAmount()
        );
    }

    function participateInPool(string memory _name, string memory _symbol)
        external
        checkPool(_name, _symbol)
    {
        require(!pools[indx].getLock(), "Pool is locked");
        pools[indx].addMe(msg.sender);

        // Security Amount
        pools[indx].getApprovalForToken(
            msg.sender,
            address(this),
            pools[indx].getfixedAmount() / 2
        );
        payCommittee();
    }

    function triggerPaymentInterval(string memory _name, string memory _symbol)
        external
        checkPool(_name, _symbol)
    {
        if (block.timestamp >= pools[indx].getExpiry()) {
            pools[indx].paymentInterval();
            pools[indx].setTotalAmount(0);
            pools[indx].setLock(true);
        }
    }

    function exitCommittee(string memory _name, string memory _symbol)
        external
        checkPool(_name, _symbol)
    {
        // Getting Security
        pools[indx].transferFrom(
            msg.sender,
            address(this),
            pools[indx].getfixedAmount() / 2
        );

        // Should also deposit 4th part of the fixed amount
        pools[indx].getApprovalForToken(
            msg.sender,
            address(this),
            pools[indx].getfixedAmount() / 4
        );
        pools[indx].transferFrom(
            msg.sender,
            address(this),
            pools[indx].getfixedAmount() / 4
        );

        int256 p_indx = pools[indx]._isParticipantPresent(msg.sender);
        pools[indx].exitParticipant(p_indx);
        pools[indx].exitHolder(msg.sender);
    }

    function checkPoolStatus(string memory _name, string memory _symbol)
        external
        checkPool(_name, _symbol)
    {
        emit isLock(pools[indx].getLock());
    }

    function checkParticipantStatus(string memory _name, string memory _symbol)
        external
        checkPool(_name, _symbol)
    {
        int256 p_indx = pools[indx]._isParticipantPresent(msg.sender);
        if (p_indx != -1) emit isParticipating(true);
        else emit isParticipating(false);
    }

    function _isPoolPresent(string memory _name, string memory _symbol)
        internal
        returns (bool)
    {
        for (uint256 i = 0; i < pools.length; i++)
            if (
                keccak256(bytes(pools[i].symbol())) ==
                keccak256(bytes(_symbol)) &&
                keccak256(bytes(pools[i].name())) == keccak256(bytes(_name))
            ) {
                indx = i;
                return true;
            }
        return false;
    }
}
