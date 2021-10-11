//  SPDX-License-Identifier: UNLICENSED
pragma solidity ^0.8.0;

import "./ERC20/ERC20.sol";
import "./ERC20/Ownable.sol";

contract Token is ERC20, Ownable {
    struct PaymentHolder {
        Participant holder;
        uint256 allowanceRate;
    }

    struct Participant {
        address participant;
        bool isParticipanting;
    }

    uint256 fixedAmount;
    Participant[] participants;
    PaymentHolder[] paymentHolders;
    address committee;
    uint256 expiry;
    uint256 totalAmount;
    bool lock;

    modifier onlyCommittee() {
        require(msg.sender == committee);
        _;
    }

    constructor(
        string memory _name,
        string memory _symbol,
        uint256 _fixedAmount,
        address _committee
    ) ERC20(_name, _symbol) {
        fixedAmount = _fixedAmount;
        committee = _committee;
        expiry = block.timestamp + 15 days;
    }

    function getTotalAmount() public view onlyCommittee returns (uint256) {
        return totalAmount;
    }

    function setTotalAmount(uint256 _totalAmount) public onlyCommittee {
        totalAmount = _totalAmount;
    }

    function getLock() public view onlyCommittee returns (bool) {
        return lock;
    }

    function setLock(bool _lock) public onlyCommittee {
        lock = _lock;
    }

    function getExpiry() public view onlyCommittee returns (uint256) {
        return expiry;
    }

    function getfixedAmount() public view onlyCommittee returns (uint256) {
        return fixedAmount;
    }

    function getParticipation(int256 indx)
        public
        view
        onlyCommittee
        returns (bool)
    {
        return participants[uint256(indx)].isParticipanting;
    }

    function getApprovalForToken(
        address _owner,
        address _spender,
        uint256 _security
    ) public onlyCommittee {
        _approve(_owner, _spender, fixedAmount + _security);
        int256 indx = _isParticipantPresent(_owner);
        participants[uint256(indx)].isParticipanting = true;
    }

    function mint(address account, uint256 amount) public onlyCommittee {
        _mint(account, amount);
    }

    function addMe(address participant) public onlyCommittee {
        participants.push(Participant(participant, true));
    }

    function paymentInterval() public onlyCommittee {
        // Cheking participations
        _checkParticiaptions();

        // Increasing the allowance of every participant as committee goes on
        _increasePaymentHoldersAllowances();

        // Selecting any random committee winner
        uint256 rand = block.timestamp % participants.length;
        uint256 allowedPayment = _calculateAllowanceAmount(totalAmount);
        if (participants.length == 1) allowedPayment = totalAmount;

        // Approving the winner a secure calculated amount to spend
        _approve(committee, participants[rand].participant, allowedPayment);

        // Resetting the expriy of committee
        expiry = block.timestamp + 15 days;

        // Storing winner so that it can be tracked and forced to participate furthur
        PaymentHolder memory newHolder = PaymentHolder(
            participants[rand],
            fixedAmount / participants.length
        );
        paymentHolders.push(newHolder);

        // Removing winner from participant
        delete participants[rand];
    }

    // Any allowance logic can be set here, currently it is half allowance
    function _calculateAllowanceAmount(uint256 payment)
        private
        pure
        returns (uint256)
    {
        return payment / 2;
    }

    function _increasePaymentHoldersAllowances() private {
        for (uint256 i = 0; i < paymentHolders.length; i++) {
            increaseAllowance(
                paymentHolders[i].holder.participant,
                paymentHolders[i].allowanceRate
            );
        }
    }

    function _clearStatus() private {
        for (uint256 i = 0; i < participants.length; i++) {
            participants[i].isParticipanting = false;
        }
    }

    function _checkParticiaptions() private {
        // Deleting non participants
        for (uint256 i = 0; i < participants.length; i++) {
            if (!participants[i].isParticipanting) {
                delete paymentHolders[i];
            }
        }

        // Deleting non payment holders
        for (uint256 i = 0; i < paymentHolders.length; i++) {
            if (!paymentHolders[i].holder.isParticipanting) {
                // Removing their allowances
                decreaseAllowance(
                    paymentHolders[i].holder.participant,
                    getAllowance(paymentHolders[i].holder.participant)
                );
                delete paymentHolders[i];
            }
        }
    }

    function _isParticipantPresent(address _participant)
        public
        view
        onlyCommittee
        returns (int256)
    {
        for (uint256 i = 0; i < participants.length; i++)
            if (participants[i].participant == _participant) return int256(i);
        return -1;
    }
}
