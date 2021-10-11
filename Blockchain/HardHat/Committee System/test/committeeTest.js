/**
 *  @title  Committee Test Cases
 *  @author Bassam Monib
 */
const { expect } = require('chai');

describe('Committee Test Cases', () => {

    //Will be called before each test
    beforeEach(async () => {

        // Getting contract using required contract name
        MarketPlace = await ethers.getContractFactory('CommitteePool');

        // @dev Deploying the contract on default hardhat development network
        // 'instance' will be our interface to communicate with contract
        instance = await MarketPlace.deploy();

        // Getting the accounts for performing transactions
        // Default deployment account is linked with first account, 'BOB' in our case
        [BOB, ALICE, JON] = await ethers.getSigners();
    })

    describe('Token Pool Creation', () => {

        it('Allow creation of a new pool with unique name and symbol', async () => {

            // Checking existence of a pool
            expect(await instance.getPoolLength()).to.equal(0);

            // Calling contract function to create new pool
            await instance.createPool("Bassam", "BAZ", 20);

            //Pool created
            expect(await instance.getPoolLength()).to.equal(1);
        })

        it('Dont allow creation of a new pool with existing name and symbol', async () => {

            // Checking existence of a pool
            expect(await instance.getPoolLength()).to.equal(0);

            // Calling contract function twice for checking limitaion of new pool creation
            await instance.createPool("Bassam", "BAZ", 20);
            try {
                // Here it will fire up the require create pool
                // it will fail here as more than one pool with same name/symbol cant be created
                // so using try catch
                await instance.createPool("Bassam", "BAZ", 40);
            } catch (err) {
                // Flow should go through from here so that the test can finish gracefully
                return;
            }

            // If this gets invoke, it will show error which means our creation check is not working
            expect(true, 'This should not run, as you cannot create more than one pool with same name/symbol').to.be.false;
        })
    })

    describe('Token Pool Paticipation', () => {

        it('Allow participation in existing token pool', async () => {

            // Calling contract function to create new pool
            await instance.createPool("Bassam", "BAZ", 10);

            // Getting the pool tokens from ethers
            await instance.exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });

            // Paticipation in pool
            await instance.participateInPool("Bassam", "BAZ");
        })

        it('Invalid participation amount', async () => {

            // Calling contract function to create new pool
            await instance.createPool("Bassam", "BAZ", 10);

            // Getting the pool tokens from wei
            await instance.exchangePoolTokens("Bassam", "BAZ", { value: 1 });

            try {
                // Here it will fire up the invalid amount require 
                // so using try catch
                await instance.participateInPool("Bassam", "BAZ");
            } catch (err) {
                // Flow should go through from here so that the test can finish gracefully
                return;
            }

            // If this gets invoke, it will show error which means our creation check is not working
            expect(true, 'This should not run, as you participation amount is invalid').to.be.false;
        })
    })

    describe('Triggering Payment Interval', () => {

        it('Pool locked after one committee payment interval gets triggered', async () => {

            // Calling contract function to create new pool
            await instance.createPool("Bassam", "BAZ", 10);

            // Getting the pool tokens from ethers
            await instance.exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });
            await instance.connect(ALICE).exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });
            await instance.connect(JON).exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });

            // Paticipation in pool
            await instance.participateInPool("Bassam", "BAZ");
            await instance.connect(ALICE).participateInPool("Bassam", "BAZ");
            await instance.connect(JON).participateInPool("Bassam", "BAZ");

            // Time Travelling :)            
            await network.provider.send("evm_increaseTime", [86400 * 20]);
            await network.provider.send("evm_mine");

            // Triggering Payment Interval
            await instance.triggerPaymentInterval("Bassam", "BAZ");

            await expect(await instance.checkPoolStatus("Bassam", "BAZ")).to.emit(instance, 'isLock').withArgs(true);
        })

        it('Pool should not be perform interval before time', async () => {

            // Calling contract function to create new pool
            await instance.createPool("Bassam", "BAZ", 10);

            // Getting the pool tokens from ethers
            await instance.exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });
            await instance.connect(ALICE).exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });
            await instance.connect(JON).exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });

            // Paticipation in pool
            await instance.participateInPool("Bassam", "BAZ");
            await instance.connect(ALICE).participateInPool("Bassam", "BAZ");
            await instance.connect(JON).participateInPool("Bassam", "BAZ");

            // Triggering Payment Interval
            await instance.triggerPaymentInterval("Bassam", "BAZ");

            await expect(await instance.checkPoolStatus("Bassam", "BAZ")).to.emit(instance, 'isLock').withArgs(false);
        })
    })

    describe('Exiting policy of committee', () => {

        it('Participant exiting from committee', async () => {

            // Calling contract function to create new pool
            await instance.createPool("Bassam", "BAZ", 10);

            // Getting the pool tokens from ethers
            await instance.exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });
            await instance.connect(ALICE).exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });
            await instance.connect(JON).exchangePoolTokens("Bassam", "BAZ", { value: ethers.utils.parseEther("10") });

            // Paticipation in pool
            await instance.participateInPool("Bassam", "BAZ");
            await instance.connect(ALICE).participateInPool("Bassam", "BAZ");
            await instance.connect(JON).participateInPool("Bassam", "BAZ");

            // Triggering Payment Interval
            await instance.exitCommittee("Bassam", "BAZ");

            await expect(await instance.checkParticipantStatus("Bassam", "BAZ")).to.emit(instance, 'isParticipating').withArgs(false);
        })
    })
})