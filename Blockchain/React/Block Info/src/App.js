import './App.css';

import Web3 from 'web3';
import React, { useState } from 'react';
import { EthereumAbi } from './abis';
const web3 = new Web3(Web3.givenProvider);
const EthereumContractAddress = '0x2E66C011b2760BA7180324bbdc88d4d94CA758B8';
const EthereumContract = new web3.eth.Contract(EthereumAbi, EthereumContractAddress);

function App() {
  const [blockNumber, setBlockNumber] = useState(null);
  const [miner, setMiner] = useState(null);
  const [totalDifficuly, setTotalDifficuly] = useState(null);
  const [transactions, setTransactions] = useState([]);
  const [amounts, setAmounts] = useState([]);
  const [ID, setID] = useState(null);
  return (
    <div className="App">
      <header className="App-header">
        <label style={{ color: 'red' }}> Note: 'Wait 25-30 seconds for response after starting/resuming requests!'</label>
        <button
          onClick={
            async (e) => {
              e.preventDefault();
              if (ID == null) {
                setID(setInterval(
                  async () => {
                    let block = await web3.eth.getBlock('latest');
                    setBlockNumber(block.number);
                    // setMiner(block.miner);
                    setMiner(await EthereumContract.methods.get_miner().call());
                    setTotalDifficuly(block.difficulty);
                    let txs = block.transactions;
                    setTransactions(txs);
                    let amnts = [];
                    let b = await web3.eth.getTransaction(txs[0]);
                    amnts[0] = b.value;
                    for (let i = 1; i < txs.length; i++) {
                      let b = await web3.eth.getTransaction(txs[i]);
                      amnts[i] = b.value;
                    }
                    setAmounts(amnts.sort(function (a, b) { return b - a }));
                  }
                  , 25000));
              }
            }
          }
          type="button" >
          Start/Resume Requests
        </button>
        <button
          onClick={
            async (e) => {
              e.preventDefault();
              if (ID != null) {
                clearInterval(ID);
                setID(null);
              }
            }
          }
          type="button" >
          Pause Requests
        </button>
        <h1>Block Number</h1><h5>{blockNumber}</h5>
        <h1>Miner Address</h1><h5>{miner}</h5>
        <h1>Total Difficulty</h1><h5>{totalDifficuly}</h5>
        <h1>Transactions</h1>
        <table>
          <tbody>
            {transactions.map((obj) => (
              <tr>
                <td>{obj}</td>
              </tr>
            ))}
          </tbody>
        </table>
        <h1>Transactions Amount</h1>
        <table border="1">
          <tbody>
            <tr>
              <td>Amounts</td>
            </tr>
            {amounts.map((obj) => (
              <tr>
                <td>{obj}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </header>
    </div>
  );
}

export default App;
