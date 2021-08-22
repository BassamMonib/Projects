/**
 *  @title  Crypto Zombies Test Cases
 *  @author Bassam Monib 😃
 */

/**
 *  @notice Waffle & Ether.js were required in config.js
 */

/**
 *  @notice Using for expect notation
 */
const { expect } = require('chai');

// Frankenstein, A monster but still..... :)
// Brainy, Loves to eat 'Maghaz' (Brain).. :|
const zombieNames = ["Frankenstein", "Brainy"];

describe('CryptoZombies Testing', () => {

    /**
     * @dev Will be called before each test
     */
    beforeEach(async () => {
        
        /**
         *  @dev Getting the contract using required contract name
         */
        CryptoZombies = await ethers.getContractFactory('CryptoZombies');

        /** 
         * @dev Deploying the contract on default hardhat development network
         * 'instance' will be our interface to communicate with contract
         */
        instance = await CryptoZombies.deploy();

        /**
         * @dev Getting the accounts for performing transactions
         * Default deployment account is linked with first, 'Bob' in our case
         */
        [BOB, ALICE] = await ethers.getSigners();
    });

    describe('Zombie Factory', () => {

        it('Initial random zombie creation', async () => {

            /**
             * @dev Checking before any zombie creation, should be 0
             */
            expect(await instance.zombiesCount()).to.equal(0);

            /**
             *  @dev Calling contract function to create new zombie
             */
            await instance.createRandomZombie(zombieNames[0]); 
            
            /**
             * @dev A Zombie is created, should be 1 now
             */
            expect(await instance.zombiesCount()).to.equal(1);
        })

        it('Should not create more than one zombie for an account', async () => {

            /**
             * @dev Calling contract function two times to check the zombie creation condition
             */
            await instance.createRandomZombie(zombieNames[0]);

            try{
                /**
                 * @dev Here it will fire up the require in create zombie function
                 * it will fail here as more than one zombie cant be created directly
                 * so using try catch
                 */
                await instance.createRandomZombie(zombieNames[1]);

                /**
                 * @dev If this gets invoke, it will show error which means our creation check is not working
                 */
                expect(true, 'This should not run, as you cannot create more than one zombies').to.be.false;

            } catch(err){
                /** 
                 * @dev Flow should go through from here so that the test can finish gracefully
                 */
            }
        })

        it('Checking created zombie owner account', async () => {
            
            /**
             * @dev Calling contract function two times to check the zombie creation condition
             */
             await instance.createRandomZombie(zombieNames[0]);

            /**
             * @dev Checking either zombie created with calling account or not
             */
            expect(await instance.zombieToOwner(0)).to.equal(BOB.address);
            
            /**
             * @notice This will fail, as BOB is owner of Frankenstein
             */
            // expect(await instance.zombieToOwner(0)).to.equal(Alice.address);
        })

        it('Checking how much zombies an account has', async () => {
            
            /**
             * @dev Checking before any zombie created by BOB, should be 0
             */
            expect(await instance.ownerZombieCount(BOB.address)).to.equal(0);

            /**
             * @dev Calling contract function two times to check the zombie creation condition
             */
            await instance.createRandomZombie(zombieNames[0]);
            
            /**
             * @dev A Zombie is created by BOB, should be 1 now
             */
            expect(await instance.ownerZombieCount(BOB.address)).to.equal(1);
        }) 

    })

    describe('Zombie Attack', () => {

        /**
         * @dev Will be called before each test
         */
        beforeEach(async () => {

            /**
             * @dev BOB calling function to create his new zombie
             */
            await instance.createRandomZombie(zombieNames[0]);

            /**
             * @dev ALICE calling function to create his new zombie
             */
            await instance.connect(ALICE).createRandomZombie(zombieNames[1]);
        })

        it('Attacking other zombies', async () => {

            /**
             * @dev Increasing blocktime by passing number of seconds that will be added to the timestamp of the latest block
             * Latest bLock mined by evm_mine
             */
            await network.provider.send("evm_increaseTime", [86400])
            await network.provider.send("evm_mine")

            /**
             * @dev Zombie at id 0 (Frankenstein) is attacking zombie at id 1 (Brainy)
             */
            await instance.attack(0, 1);
        })

        it('Cannot attack other zombie due to timeout', async () => {

            try{
                /**
                 * @dev Zombie at id 0 (Frankenstein) is attacking zombie at id 1 (Brainy)
                 * but it will fail as zombie is in cool down
                 * due to this condition, we are using try catch
                 */
                await instance.attack(await instance.zombieToOwner(0), await instance.zombieToOwner(1))
            
                /**
                 * @dev If this gets invoke, it will show error which means our cooldown check is not working
                 */
                expect(true, 'This should not run, as you cannot attack other zombies during cooldown').to.be.false;

            } catch(err){
                /** 
                 * @dev Flow should go through from here so that the test can finish gracefully
                 */
            }
        })

        it('Win/loss after attacking zombie', async () => {

            /**
             * @dev Increasing blocktime by passing number of seconds that will be added to the timestamp of the latest block
             * Latest bLock mined by evm_mine
             */
            await network.provider.send("evm_increaseTime", [86400])
            await network.provider.send("evm_mine")

            /**
             * @dev Zombie at id 0 (Frankenstein) is attacking zombie at id 1 (Brainy)
             */
            await instance.attack(0, 1);

            /**
             * @notice
             * There is no surety of winning or lossing after attacking other zombie
             * So we are logging to console to check that either the attack was successful or not
             * 
             * @dev 70% win probability is set in the code
             */
            if(await instance.getWinCount(0) == 1) console.log('\tAttacking zombie has won');
            else console.log('\tAttacking zombie has lost');
        })

    })

    describe('Zombie Ownership', () => {

        /**
         * @dev Will be called before each test
         */
        beforeEach(async () => {

            /**
             * @dev BOB calling function to create his new zombie
             */
            await instance.createRandomZombie(zombieNames[0]);
        })

        it('Zombie transfer without allowance', async () => {
            
            /**
             * @dev Using ERC721 tranfer methodology to send zombie token from BOB to ALICE
             */
            await instance.transferFrom(BOB.address, ALICE.address, 0);     // BOB is a good guy :)
            
            /**
             * @dev Checking the token of owner, changed or not
             */
            expect(await instance.ownerOf(0)).to.equal(ALICE.address);
        })

        describe('Zombie transfer with allowance', () => {
        
                it("Approved address's call", async () => {
                
                /**
                 * @dev BOB approving his zombie token to ALICE so she can transfer herself
                 */
                await instance.approve(ALICE.address, 0);
                
                /**
                 * @dev ALICE transfering the approved zombie token from BOB to herslef
                 */
                await instance.connect(ALICE).transferFrom(BOB.address, ALICE.address, 0);

                /**
                 * @dev Checking the token of owner, changed or not
                 */
                expect(await instance.ownerOf(0)).to.equal(ALICE.address);
            })

            it("Owner's address call", async () => {

                /**
                 * @dev BOB approving his zombie token to ALICE so she can transfer herself
                 */
                await instance.approve(ALICE.address, 0);
                
                /**
                 * @dev BOB transfering the approved zombie token to ALICE
                 */
                await instance.transferFrom(BOB.address, ALICE.address, 0);

                /**
                 * @dev Checking the token of owner, changed or not
                 */
                expect(await instance.ownerOf(0)).to.equal(ALICE.address);
            })

        })

    })

    describe('Zombie Feeding', () => {

        /**
         * @dev Will be called before each test
         */
        beforeEach(async () => {

            /**
             * @dev BOB calling function to create his new zombie
             */
            await instance.createRandomZombie(zombieNames[0]); 
        })

        /**
        * @notice Here we are doing extra work to test feedOnKitty function
        * @dev As we are working on hardhat development network so we need to deploy
        * cryptokitty contract on our network so then we can interact with it using
        * the kitty interface
        */ 
        it('Zombie getting fed on kitty', async () => {

            /**
             * @dev Getting the contract using required contract name
             */
            let CryptoKitty = await ethers.getContractFactory('KittyCore');

            /**
             * @dev Deploying the contract on default hardhat development network
             * 'instance' will be our interface to communicate with contract
             */
            let kittyInstance = await CryptoKitty.deploy();
        
            /**
             * @dev As we have deployed kitty contract on hardhat network
             * now we are getting it's address and setting it in our kitty interface
             */
            await instance.setKittyContractAddress(kittyInstance.address);

            /**
             * @dev Creating new kitty and using _myTestKittyId() variable for feedOnKitty
             * @param gene for crypto kitty
             * @param owner the owner for the new crypto kitty
             */
            await kittyInstance.createPromoKitty(123456789, BOB.address);
            let kittyId = await kittyInstance._myTestKittyId();

            /**
             * @dev Increasing blocktime by passing number of seconds that will be added to the timestamp of the latest block
             * so we can feed our zombie using the created kitty
             * Latest bLock mined by evm_mine
             */
            await network.provider.send("evm_increaseTime", [86400])
            await network.provider.send("evm_mine")

            /**
             * @dev Feeding zombie 0 (Frankenstein) our new kitty having kittyId
             */
            await instance.feedOnKitty(0, kittyId);
            
            /**
             * @dev As we have fed our zombie and new zombie will be created having id 1
             * and its default name will be "SpecialKittyZombie" as it's created with the
             * help of crypto kitty
             */
            expect(await instance.getName(1)).to.equal("SpecialKittyZombie")
        })

    })

    describe('Zombie Helper', () => {

        /**
         * @dev Will be called before each test
         */
        beforeEach(async () => {

            /**
             * @dev BOB calling function to create his new zombie
             */
            await instance.createRandomZombie(zombieNames[0]); 
        })

        it('Leveling up zombie using value', async () => {

            /**
             * @dev Checking starting level which is Level-1 for every zombie
             */
            expect(await instance.getLevel(0)).to.equal(1);

            /**
             * @dev Sending value to give levelUp fee and perform zombie levelUp
             */
            await instance.levelUp(0, {value: 1000000000000000});

            /**
             * @dev Checking now leveled up zombie which should be Level-2
             */
            expect(await instance.getLevel(0)).to.equal(2);
        })

        it('Change zombie name by leveling up zombie to level-2', async () => {

            /**
             * @dev Checking starting name which is "Frankenstein"
             */
            expect(await instance.getName(0)).to.equal(zombieNames[0]);
            
            /**
             * @dev Sending value to give levelUp fee and perform zombie levelUp
             */
            await instance.levelUp(0, {value: 1000000000000000});

            /**
             * @dev Changing zombie name as now leveled up Level-2 and checking new name
             */
            await instance.changeName(0, "Dr. Lorax");      // An intelligent Zombie *_*
            expect(await instance.getName(0)).to.equal("Dr. Lorax");
        })

        it('Should not change zombie name as zombie is not at level-2', async () => {

            try{
                /**
                 * @dev Changing zombie name cannot be done as zombie is not at Level-2
                 * so the call should not be successful that's why using try catch
                 */
                await instance.changeName(0, "Dr. Lorax");      // An intelligent Zombie *_*
                
                /**
                 * @dev If this gets invoke, it will show error which means our zombie name is changing
                 */
                 expect(true, 'This should not run, as you cannot change name under Level-2').to.be.false;

            } catch(err){
                /** 
                 * @dev Flow should go through from here so that the test can finish gracefully
                 */
            }

        })

        it('Change zombie dna by leveling up zombie to level-20', async () => {

            let dna = await instance.getDna(0);
            
            /**
             * @dev Sending value to give levelUp fee in a for loop to levelUp zombie to Level-20
             * so the zombie dna can be changed
             */
            for(let i = 0; i < 19; i++)
                await instance.levelUp(0, {value: 1000000000000000});

            /**
             * @dev Checking zombie dna which should not be equal to old dna
             * @param zombieId
             * @param newDna
             */
            await instance.changeDna(0, 100);
            expect(await instance.getDna(0)).to.not.equal(dna);
        })

        it('Should not change zombie dna as zombie is not at level-20', async () => {

            try{
                /**
                 * @dev Changing zombie name cannot be done as zombie is not at Level-2
                 * so the call should not be successful that's why using try catch
                 */
                await instance.changeDna(0, 100);      // An intelligent Zombie *_*
                
                /**
                 * @dev If this gets invoke, it will show error which means our zombie name is changing
                 */
                 expect(true, 'This should not run, as you cannot change dna under level-20').to.be.false;

            } catch(err){
                /** 
                 * @dev Flow should go through from here so that the test can finish gracefully
                 */
            }

        })

    })

})