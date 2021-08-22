const Balances = artifacts.require("Balance");

module.exports = function (deployer) {
  deployer.deploy(Balances);
};
