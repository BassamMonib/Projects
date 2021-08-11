use casper_engine_test_support::{Code, Hash, SessionBuilder, TestContext, TestContextBuilder};
use casper_types::{
    account::AccountHash, bytesrepr::FromBytes, runtime_args, AsymmetricType, CLTyped, PublicKey,
    RuntimeArgs, U256, U512,
};

pub mod token_cfg {
    use super::*;
    pub const NAME: &str = "ERC721";
    pub const SYMBOL: &str = "ERC";
    pub const DECIMALS: u8 = 18;
    
    pub fn total_supply() -> U256 {
        5.into()
    }

    pub fn token_id() -> Vec<U256> 
    {
        let mut token_ids: Vec<U256> = Vec::with_capacity(total_supply().as_usize());
        for i in 0..total_supply().as_usize()
        {
            token_ids.push(i.into());
        }
        token_ids
    }
}

pub struct Sender(pub AccountHash);

pub struct Token {
    context: TestContext,
    pub ali: AccountHash,
    pub bob: AccountHash,
    pub joe: AccountHash,
}

impl Token {
    pub fn deployed() -> Token {
        let ali = PublicKey::ed25519_from_bytes([3u8; 32]).unwrap();
        let bob = PublicKey::ed25519_from_bytes([6u8; 32]).unwrap();
        let joe = PublicKey::ed25519_from_bytes([9u8; 32]).unwrap();

        let mut context = TestContextBuilder::new()
            .with_public_key(ali, U512::from(500_000_000_000_000_000u64))
            .with_public_key(bob, U512::from(500_000_000_000_000_000u64))
            .build();
            
        let session_code = Code::from("../../target/wasm32-unknown-unknown/release/contract.wasm");
        let session_args = runtime_args! {
            "token_name" => token_cfg::NAME,
            "token_symbol" => token_cfg::SYMBOL,
            //"tokenTotalSupply" => token_cfg::total_supply(),
            //"tokenIds" => token_cfg::token_id(),
        };
        let session = SessionBuilder::new(session_code, session_args)
            .with_address(ali.to_account_hash())
            .with_authorization_keys(&[ali.to_account_hash()])
            .build();
        context.run(session);
        Token {
            context,
            ali: ali.to_account_hash(),
            bob: bob.to_account_hash(),
            joe: joe.to_account_hash(),
        }
    }

    fn contract_hash(&self) -> Hash {
        self.context
            .query(self.ali, &[format!("{}_hash", token_cfg::NAME)])
            .unwrap_or_else(|_| panic!("{} contract not found", token_cfg::NAME))
            .into_t()
            .unwrap_or_else(|_| panic!("{} has wrong type", token_cfg::NAME))
    }

    fn query_contract<T: CLTyped + FromBytes>(&self, name: &str) -> Option<T> {
        match self
            .context
            .query(self.ali, &[token_cfg::NAME.to_string(), name.to_string()])
        {
            Err(_) => None,
            Ok(maybe_value) => {
                let value = maybe_value
                    .into_t()
                    .unwrap_or_else(|_| panic!("{} is not expected type.", name));
                Some(value)
            }
        }
    }

    fn call(&mut self, sender: Sender, method: &str, args: RuntimeArgs) {
        let Sender(address) = sender;
        let code = Code::Hash(self.contract_hash(), method.to_string());
        let session = SessionBuilder::new(code, args)
            .with_address(address)
            .with_authorization_keys(&[address])
            .build();
        self.context.run(session);
    }

    pub fn name(&self) -> String {
        self.query_contract("_name").unwrap()
    }

    pub fn symbol(&self) -> String {
        self.query_contract("_symbol").unwrap()
    }

    pub fn balance_of(&self, account: AccountHash) -> U256 {
        let key = format!("_balance_{}", account);
        self.query_contract(&key).unwrap_or_default()
    }

    pub fn owner_of(&self, token_id: U256) -> AccountHash {
        let key = format!("_owner_{}", token_id);
        self.query_contract(&key).unwrap_or_default()
    }
    
    pub fn approve(&mut self, to: AccountHash, token_id: U256, owner: Sender) {
        self.call(
            owner,
            "approve",
            runtime_args! {
                "to" => to,
                "token_id" => token_id
            },
        );
    }

    pub fn get_approved(&self, token_id: U256) -> AccountHash {
        let key = format!("_token_approvals_{}", token_id);
        self.query_contract(&key).unwrap_or_default()
    }
    
    pub fn set_approval_for_all(&mut self, operator:AccountHash, approved:bool, owner: Sender) {
        self.call(
            owner,
            "set_approval_for_all",
            runtime_args! {
                "operator" => operator,
                "approved" => approved
            },
        );
    }

    pub fn is_approved_for_all(&self, owner:AccountHash, operator:AccountHash) -> bool {
        let key = format!("_operator_approvals_{}_{}", owner, operator);
        self.query_contract(&key).unwrap_or_default()
    }

    pub fn transfer_from(
        &mut self,
        from: AccountHash,
        to: AccountHash,
        token_id: U256,
        sender: Sender,
    ) {
        self.call(
            sender,
            "transfer_from",
            runtime_args! {
                "from" => from,
                "to" => to,
                "token_id" => token_id
            },
        );
    }

    pub fn mint_token(&mut self, to:AccountHash, token_id: U256, owner:Sender)
    {
        self.call(
            owner,
            "mint",
            runtime_args! {
                "to" => to,
                "token_id" => token_id,
            },
        );
    }

    pub fn burn_token(&mut self, token_id: U256, owner:Sender)
    {
        self.call(
            owner,
            "burn",
            runtime_args! {
                "token_id" => token_id,
            },
        );
    }

}