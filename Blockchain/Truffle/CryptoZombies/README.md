# Crypto Zombies

To initialize truffle project
```truffle init```

Made the required project structure and placed the code in the appropriate place 

Installed following dependencies
```
npm install --save-dev chai
npm install --save-dev @openzeppelin/test-helpers
npm install truffle-hdwallet-provider
```

To compile the project, following command is used
```truffle compile```

This will create a build directory which will contain the JSON atrifacts

## Testing

After following the above steps, testing can be performed using following command
```truffle test```

![Executed Tests Screenshot](https://github.com/BassamMonib/Projects/tree/main/Blockchain/Truffle/CryptoZombies/test.png)

## Deployment

Before deployement, we need to configure the **truffle-config.js** file

As we will be deploying on *Rinkeby* testnet, so we need wallet for interaction and configured rinkeby network

Following lines must be added for wallet functionality
```
const HDWalletProvider = require("truffle-hdwallet-provider");
const mnemonic = "YOUR MNEMONIC (12 word seed phrase)";
```

*Note: The walled whose seed is being provided must be funded using faucet*

Configuration for testnet
```
rinkeby: {
    // Special function to setup the provider
    provider: function () {
    // Setting the provider with the Infura Rinkeby address and Token
    return new HDWalletProvider(mnemonic, "https://rinkeby.infura.io/v3/INFURA_KEY")
    },
    network_id: 4
}
```

Created a migration file named **2_crypto_zombie.js** which helps truffle to deploy code to Ethereum

Now for deployment, following command is used (--netowrk flag is used to tell on what network it will deploy e.g. Mainnet, Kovan, Rinkeby etc.)
```truffle migrate --network rinkeby```

![Deployment Screenshot](https://github.com/BassamMonib/Projects/tree/main/Blockchain/Truffle/CryptoZombies/deploy.png)