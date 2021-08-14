var Balance = artifacts.require("Balance");

contract("Sample Balance Contract Test", function(accounts){

    it("Check an address balance, should be 100", function(){
        return Balance.deployed().then(function(instance){
            return instance.balances.call(accounts[0])
        }).then(function(balance){
            assert.equal(balance.valueOf(), 100, "BALANCE DOSEN'T MATCH")
        })
    })

})