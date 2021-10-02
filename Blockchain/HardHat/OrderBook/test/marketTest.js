/**
 *  @title  MarketPlace Test Cases
 *  @author Bassam Monib
 */
const { expect } = require('chai');

describe('Marketplace Test Cases', () => {
    /**
     * @dev will be called before each test
     */
    beforeEach(async () => {        
        /**
         *  @dev Getting contract using required contract name
         */
         MarketPlace = await ethers.getContractFactory('Marketplace');
        /** 
         * @dev Deploying the contract on default hardhat development network
         * 'instance' will be our interface to communicate with contract
         */
        instance = await MarketPlace.deploy();
        /**
         * @dev Getting the accounts for performing transactions
         * Default deployment account is linked with first account, 'BOB' in our case
         */
        [BOB, ALICE] = await ethers.getSigners();
    })

    describe('Storing of new demand and requests in order book', () => {

        it('Storing new demand', async () => {
            /**
             * @dev Checking any present demand, should be 0
             */
            expect(await instance.getDemandsCount()).to.equal(0);
            /**
             *  @dev Calling contract function to put up a new demand
             */
            await instance.putDemand("Crypto Punk", 1500);
            /**
             * @dev A Demand is created, should be 1 now
             */
            expect(await instance.getDemandsCount()).to.equal(1);
        })

        it('Storing new request', async () => {
            /**
             * @dev Checking any present request, should be 0
             */
            expect(await instance.getRequestsCount()).to.equal(0);
            /**
             *  @dev Calling contract function to put up a new request
             */
            await instance.putRequest("Crypto Kitty", 800);
            /**
             * @dev A Request is created, should be 1 now
             */
            expect(await instance.getRequestsCount()).to.equal(1);
        })
    })
    
    describe('Cannot store already present demand and requests in order book', () => {

        it('Should not store already present demand', async () => {
            /**
             * @dev Checking any present demand, should be 0
             */
            expect(await instance.getDemandsCount()).to.equal(0);
            /**
             *  @dev Calling contract function to put up a new demand
             */
            await instance.putDemand("Crypto Punk", 1500);
            try{
                /**
                 * @dev Here it will fire up the require in put demand function
                 * it will fail here as already present demand cannot be stored again
                 * so using try catch
                 */
                await instance.putDemand("Crypto Punk", 1500);
                /**
                 * @dev If this gets invoke, it will show error which means our require statement is not working
                 */
                expect(true, 'This should not run, as you cannot store already present demand').to.be.false;
            } catch(err){
                /** 
                 * @dev Flow should go through from here so that the test can finish gracefully
                 */
            }
        })

        it('Should not store already present request', async () => {
            /**
             * @dev Checking any present request, should be 0
             */
             expect(await instance.getRequestsCount()).to.equal(0);
             /**
              *  @dev Calling contract function to put up a new request
              */
             await instance.putRequest("Crypto Kitty", 800);
             try{
                 /**
                  * @dev Here it will fire up the require in put request function
                  * it will fail here as already present request cannot be stored again
                  * so using try catch
                  */
                 await instance.putRequest("Crypto Kitty", 800);
 
                 /**
                  * @dev If this gets invoke, it will show error which means our require statement is not working
                  */
                 expect(true, 'This should not run, as you cannot store already present request').to.be.false;
 
             } catch(err){
                 /** 
                  * @dev Flow should go through from here so that the test can finish gracefully
                  */
             }
        })
    })

    describe('Clearing the present records after order gets finished', () => {

        it('Clearing present buying request on a new sell demand', async () => {
            /**
             * @dev Checking any present demand, should be 0
             */
            expect(await instance.getRequestsCount()).to.equal(0);
            /**
             *  @dev Calling contract function to put up a new request
             */
            await instance.putRequest("Crypto Punk", 1500);
            /**
             * @dev A Demand is created, should be 1 now
             */
            expect(await instance.getRequestsCount()).to.equal(1);
            /**
             *  @dev Calling contract function to put up a new request
             *  it will perform transaction on already present demand 
             */
             await instance.putDemand("Crypto Punk", 1500);
            /**
             * @dev Checking present demands again, should be 0 again
             */
            expect(await instance.getRequestsCount()).to.equal(0);
        })

        it('Clearing present selling demand on a new buy request', async () => {
            /**
             * @dev Checking any present request, should be 0
             */
            expect(await instance.getDemandsCount()).to.equal(0);
            /**
             *  @dev Calling contract function to put up a new request
             */
            await instance.putDemand("Crypto Kitty", 800);
            /**
             * @dev A Request is created, should be 1 now
             */
            expect(await instance.getDemandsCount()).to.equal(1);
            /**
             *  @dev Calling contract function to put up a new demand
             *  it will perform transaction on already present request 
             */
             await instance.putRequest("Crypto Kitty", 800);
            /**
             * @dev Checking present requests again, should be 0 again
             */
            expect(await instance.getDemandsCount()).to.equal(0);
        })
    })
})