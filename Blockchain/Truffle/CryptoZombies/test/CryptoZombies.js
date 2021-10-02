const CryptoZombies = artifacts.require("CryptoZombies");
const utils = require("./helpers/utils");
const { time } = require('@openzeppelin/test-helpers');
var expect = require('chai').expect;
const zombieNames = ["Zombie 1", "Zombie 2"];
contract("CryptoZombies", (accounts) => {
    // Accounts for interaction    
    let [alice, bob] = accounts;
    let contractInstance;
    // Will be called before each test
    beforeEach(async () => {
        // Taking the instance of smart contract to interact with
        contractInstance = await CryptoZombies.new();
    });
    it("should be able to create a new zombie", async () => {
        // Called the smart contract function to create zombies  
        const result = await contractInstance.createRandomZombie(zombieNames[0], {from: alice});
        // Checking deployment
        expect(result.receipt.status).to.equal(true);
        // Checking is the zombie is created with the name provided
        expect(result.logs[0].args.name).to.equal(zombieNames[0]);
    })
    it("should not allow two zombies", async () => {
        await contractInstance.createRandomZombie(zombieNames[0], {from: alice});
        // As in cryptozombies, one account can create only one zombie
        await utils.shouldThrow(contractInstance.createRandomZombie(zombieNames[1], {from: alice}));
    })
    context("with the single-step transfer scenario", async () => {
        it("should transfer a zombie", async () => {
            const result = await contractInstance.createRandomZombie(zombieNames[0], {from: alice});
            // Taking the zombie id to transfer
            const zombieId = result.logs[0].args.zombieId.toNumber();
            // Alice transfering the zombie using zombieID to Bob
            await contractInstance.transferFrom(alice, bob, zombieId, {from: alice});
            // Getting and checking value of new owner, Bob
            const newOwner = await contractInstance.ownerOf(zombieId);
            expect(newOwner).to.equal(bob);
        })
    })
    context("with the two-step transfer scenario", async () => {
        it("should approve and then transfer a zombie when the approved address calls transferFrom", async () => {
            const result = await contractInstance.createRandomZombie(zombieNames[0], {from: alice});
            const zombieId = result.logs[0].args.zombieId.toNumber();
            // Approving BOB so that it can perform the transfer operation
            await contractInstance.approve(bob, zombieId, {from: alice});
            // NOTE: BOB is doing transfer from because BOB is approved to do transfered
            await contractInstance.transferFrom(alice, bob, zombieId, {from: bob});
            const newOwner = await contractInstance.ownerOf(zombieId);
            expect(newOwner).to.equal(bob);
        })
        it("should approve and then transfer a zombie when the owner calls transferFrom", async () => {
            const result = await contractInstance.createRandomZombie(zombieNames[0], {from: alice});
            const zombieId = result.logs[0].args.zombieId.toNumber();
            // Approving BOB so that it can perform the transfer operation
            await contractInstance.approve(bob, zombieId, {from: alice});
            // NOTE: ALICE is doing transfer to BOB
            await contractInstance.transferFrom(alice, bob, zombieId, {from: alice});
            const newOwner = await contractInstance.ownerOf(zombieId);
            expect(newOwner).to.equal(bob);
         })
    })
    it("zombies should be able to attack another zombie", async () => {
        let result;
        // Creating zombies for bob and alice
        result = await contractInstance.createRandomZombie(zombieNames[0], {from: alice});
        const firstZombieId = result.logs[0].args.zombieId.toNumber();
        result = await contractInstance.createRandomZombie(zombieNames[1], {from: bob});
        const secondZombieId = result.logs[0].args.zombieId.toNumber();
        // Time travelling using openzeppelin given library so that the zombie can perform attack
        // As there is time constraint so we need to increase time in testing
        await time.increase(86400);
        await contractInstance.attack(firstZombieId, secondZombieId, {from: alice});
        // Checking the perfomed status of attack function
        expect(result.receipt.status).to.equal(true);
    })
})