use crate::erc721::{token_cfg, Sender, Token};
use casper_types::
{
    account::AccountHash, U256//, PublicKey, AsymmetricType
};

// End to End Testing
#[test]
fn test_erc721_deploy() 
{
    let t = Token::deployed();
    assert_eq!(t.name(), token_cfg::NAME);
    assert_eq!(t.symbol(), token_cfg::SYMBOL);

    println!("Account of Ali: {}", t.ali);
    println!("Account of Bob: {}", t.bob);
    println!("Account of Joe: {}", t.joe);
}


#[test]
fn mint_token()
{
    let mut t = Token::deployed();
    let token_id:U256 = 1.into();
    let zero_addr:AccountHash = AccountHash::from_formatted_str("account-hash-0000000000000000000000000000000000000000000000000000000000000000").unwrap_or_default();

    t.mint_token(t.bob, token_id, Sender(t.ali));

    assert_eq!(t.owner_of(token_id), t.bob);                  // Bob should be the owner of the new minted token
    assert_eq!(t.owner_of(2.into()), zero_addr);               // owner of non-existent token id, should be equal to null address, because it doesnot exist
}

#[test]
fn approve_and_transferfrom_invalidtoken()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.ali));
    t.mint_token(t.ali, 2.into(), Sender(t.ali));
    assert_eq!(t.balance_of(t.ali), 2.into());                  // should pass, ali now has two token

    // Approving invalid token
    t.approve(t.bob, 3.into(), Sender(t.ali));                  // token 3 doesnot exist
    assert_ne!(t.owner_of(3.into()), t.bob);                    // Not Equal should pass, because id 3 is a non extent token and its owner should not be bob

    // TransferFrom invalid token
    t.transfer_from(t.ali, t.joe, 3.into() ,Sender(t.bob));
    assert_eq!(t.balance_of(t.joe), 0.into());                  // joe's balance should still be zero, because the transfer above should not have gone through
    assert_eq!(t.balance_of(t.ali), 2.into());                  // Ali's balances should remain same
}

#[test]
fn transferfrom_non_approved_token()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.ali));
    t.mint_token(t.ali, 2.into(), Sender(t.ali));
    assert_eq!(t.balance_of(t.ali), 2.into());

    // TransferFrom non approved token 
    t.transfer_from(t.ali, t.joe, 1.into(), Sender(t.bob));     // Transfering non-approved token

    assert_eq!(t.balance_of(t.joe), 0.into());                  // joe's balance should still be zero, because the transfer above should not have gone through
    assert_eq!(t.balance_of(t.ali), 2.into());                  // Ali's balances should remain same
}

#[test]
fn transfer_from_success_case()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.ali));
    t.mint_token(t.ali, 2.into(), Sender(t.ali));
    assert_eq!(t.balance_of(t.ali), 2.into());

    t.approve(t.bob, 2.into(), Sender(t.ali));                // should be a successful approve
    assert_eq!(t.get_approved(2.into()), t.bob);              // should be true, bob is approved for tokenId: 2
    t.transfer_from(t.ali, t.joe, 2.into(), Sender(t.bob));   // should be successful.
    assert_eq!(t.balance_of(t.ali), 1.into());                // ali's balance should be 1 now
    assert_eq!(t.balance_of(t.joe), 1.into());                // joe's balance should also be 1 now
    assert_ne!(t.owner_of(2.into()), t.ali);                  // ali shouldn't be the owner of tokenId: 2
    assert_eq!(t.owner_of(2.into()), t.joe);                  // joe should be the new owner of tokenId: 2
}


#[test]
fn mint_already_existing_token()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.ali));    
    t.mint_token(t.bob, 1.into(), Sender(t.ali));              // shouldnot go through

    assert_eq!(t.balance_of(t.ali), 1.into());
    assert_eq!(t.balance_of(t.bob), 0.into());                // Bob's balance should be 0 because mintToken for bob above should fail
}

#[test]
fn transfer_from_non_owner_token()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.bob));
    t.mint_token(t.joe, 2.into(), Sender(t.bob));

    t.approve(t.bob, 2.into(), Sender(t.ali));                  // ali is approving tokenId 2 to bob, although ali is not owner of tokenId 2. Shouldn't go through
    t.transfer_from(t.ali, t.bob, 2.into(), Sender(t.bob));     // bob is then trying to transfer that token to himself

    assert_ne!(t.owner_of(2.into()), t.ali);                    // ali shouldn't be the owner of tokenId: 2
    assert_ne!(t.owner_of(2.into()), t.bob);                    // bob should 't be the owner of tokenId: 2
    assert_eq!(t.owner_of(2.into()), t.joe);                    // joe should remain the owner of tokenId: 2    
}

#[test]
fn burn_token_success()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.bob));
    t.mint_token(t.ali, 2.into(), Sender(t.bob));

    assert_eq!(t.balance_of(t.ali), 2.into());
    t.burn_token(1.into(), Sender(t.ali));
    assert_eq!(t.balance_of(t.ali), 1.into());
}

#[test]
fn set_approval_for_all_test()
{
    let mut t = Token::deployed();
    t.mint_token(t.ali, 1.into(), Sender(t.bob));
    t.mint_token(t.ali, 2.into(), Sender(t.bob));

    t.set_approval_for_all(t.bob, true, Sender(t.ali));
    assert_eq!(t.is_approved_for_all(t.ali, t.bob), true);

    t.transfer_from(t.ali, t.joe, 2.into(), Sender(t.bob));
    assert_eq!(t.balance_of(t.ali), 1.into());
    assert_eq!(t.balance_of(t.joe), 1.into());
}