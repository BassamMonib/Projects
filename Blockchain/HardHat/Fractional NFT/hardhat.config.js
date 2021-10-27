/**
 * @type import('hardhat/config').HardhatUserConfig
 */
require("@nomiclabs/hardhat-waffle");
require("@nomiclabs/hardhat-etherscan");
require('dotenv').config();

const { INFURA_API_KEY, RINKEBY_PRIVATE_KEY } = process.env;

module.exports = {
  solidity: {
    version: "0.8.0",
    settings: {
      optimizer: {
        enabled: true,
        runs: 200
      }
    }
  },
  networks: {
    rinkeby: {
      url: `https://rinkeby.infura.io/v3/${INFURA_API_KEY}`,
      accounts: [`0x${RINKEBY_PRIVATE_KEY}`],
    },
  },
  etherscan: {
    apiKey: "MK12WSKG9RM523GATHTPD1IFBPQC64F5BF"
  },
};