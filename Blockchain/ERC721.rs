/*
#![cfg_attr(
    not(target_arch = "wasm32"),
    crate_type = "target arch should be wasm32"
)]
*/

#![no_main]

extern crate alloc;

use alloc::{
    string::String,
};

use contract::{
    contract_api::{runtime, storage},
};

use types::{
    account::AccountHash,
    bytesrepr::{Bytes},
    contracts::{EntryPoint, EntryPointAccess, EntryPointType, EntryPoints, NamedKeys},
    CLType, CLTyped, Parameter, U256 
};

// Bringing files in sub-folders into scope
mod utils;

use utils::mappings::*;
use utils::helper_methods::*;



#[no_mangle]
/// Gets the name of the token
pub extern "C" fn name() {
    let val: String = get_key("_name");
    ret(val)
}

#[no_mangle]
/// Gets the symbol of the token
pub extern "C" fn symbol() {
    let val: String = get_key("_symbol");
    ret(val)
}

#[no_mangle]
/// Gets the balance of account
pub extern "C" fn balance_of() {
    let account: AccountHash = runtime::get_named_arg("account");
    let val: U256 = get_key(&balance_key(&account));
    ret(val)
}

#[no_mangle]
/// Given a token_id, this will return the owner of that token
///
/// Parameters-> token_id:U256
pub extern "C" fn owner_of() {
    let token_id: U256 = runtime::get_named_arg("token_id");
    let val: AccountHash = get_key(&owner_key(token_id));
    ret(val)
}

#[no_mangle]
/// Gets the token URI
/// 
/// Parameters-> token_id:U256
pub extern "C" fn token_uri() {
    let token_id: U256 = runtime::get_named_arg("token_id");
    if !_exists(token_id) {             // URI query for nonexistent token - Returning
        ret("");
    }
    let base_uri = _base_uri();
    if base_uri.len() > 0 {
        ret(_encode_packed(base_uri, token_id))
    }
    else{
        ret("");
    }
}

#[no_mangle]
/// This function approves a token to an account.
/// It first makes sure that the caller of this function is indeed the owner of the token that is being approved.
/// It also makes sure that the owner is not equal to the approval address.
/// 
/// Parameters-> token_id:U256, to:AccountHash
pub extern "C" fn approve() {

    let token_id:U256 = runtime::get_named_arg("token_id");
    let to:AccountHash = runtime::get_named_arg("to");
    let owner:AccountHash = get_key(&owner_key(token_id));
    let mut error_flag:bool = false;
   
    if owner == to {
        error_flag = true;
    }

    if !(owner == runtime::get_caller()) {
        error_flag = true;
    }

    if !error_flag {
        _approve(to, token_id);
    }
}

#[no_mangle]
/// Returns the approval address of a token.
/// 
/// Parameters-> token_id:U256
pub extern "C" fn get_approved() 
{
    let token_id:U256 = runtime::get_named_arg("token_id");
    let val: AccountHash = get_key(&token_approval_key(token_id));
    ret(val);
}

#[no_mangle]
/// This function approves an address for all the tokens of the caller
/// 
/// Parameters-> operator:AccountHash, approved:bool
pub extern "C" fn set_approval_for_all() 
{
    // Approve or remove `operator` as an operator for the caller.
    let caller:AccountHash = runtime::get_caller();
    let operator:AccountHash = runtime::get_named_arg("operator");
    let approved:bool = runtime::get_named_arg("approved");

    if operator != caller
    {
        _set_approval_for_all(caller, operator, approved)
    }
}

#[no_mangle]
/// This function returns whether an Account is approved to spend all the tokens of some owner account
/// 
/// Parameters-> owner: AccountHash, operator: AccountHash
pub extern "C" fn is_approved_for_all(){

    // Returns if the `operator` is allowed to manage all of the assets of `owner`.

    let owner:AccountHash = runtime::get_named_arg("owner");
    let operator:AccountHash = runtime::get_named_arg("operator");

    ret (_is_approved_for_all(owner, operator));
}

#[no_mangle]
/// Transfer token from owner to recepient. Can be called either by the owner of the token, or the token approved address.
/// 
/// Parameters-> from: AccountHash, to: AccountHash, token_id: U256
pub extern "C" fn transfer_from(){

    let from:AccountHash = runtime::get_named_arg("from");
    let to:AccountHash = runtime::get_named_arg("to");
    let token_id: U256 = runtime::get_named_arg("token_id");

    if _is_approved_or_owner(runtime::get_caller(), token_id)            // transfer caller is not owner nor approved
    {
        _transfer(from, to, token_id);
    }
}

#[no_mangle]
/// Parameters-> from: AccountHash, to: AccountHash, token_id: U256, _data: Bytes
pub extern "C" fn safe_transfer_from()
{
    let from:AccountHash = runtime::get_named_arg("from");
    let to:AccountHash = runtime::get_named_arg("to");
    let token_id: U256 = runtime::get_named_arg("token_id");
    let _data:Bytes = runtime::get_named_arg("_data");

    if _is_approved_or_owner(runtime::get_caller(), token_id)            // transfer caller is not owner nor approved
    {
        _safe_transfer(from, to, token_id, _data);
    }
}

#[no_mangle]
/// Mint token to an Address
/// 
/// Parameters-> to: AccountHash, token_id: U256
pub extern "C" fn mint(){

    let to:AccountHash = runtime::get_named_arg("to");
    let token_id:U256 = runtime::get_named_arg("token_id");

    _mint(to, token_id)
}

#[no_mangle]
/// Burn a token
/// 
/// Parameters: token_id
pub extern "C" fn burn() {
    let token_id:U256 = runtime::get_named_arg("token_id");
    let owner:AccountHash = get_key(&owner_key(token_id));      // if token doesnot exist, it will return all-zero (null) address.
    let zero_addr:AccountHash = AccountHash::from_formatted_str("account-hash-0000000000000000000000000000000000000000000000000000000000000000").unwrap_or_default();

    if owner != zero_addr {                     // if token doesnot exist, this check will fail
        _burn(token_id, owner, zero_addr);
    }
}


/// All session code must have a `call` entrypoint.
#[no_mangle]
pub extern "C" fn call() 
{
    let token_name: String = runtime::get_named_arg("token_name");
    let token_symbol: String = runtime::get_named_arg("token_symbol");

    let mut entry_points = EntryPoints::new();
    entry_points.add_entry_point(EntryPoint::new(
        String::from("name"),
        vec![],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("symbol"),
        vec![],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("token_uri"),
        vec![
            Parameter::new("token_id", CLType::U256)
        ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("balance_of"),
        vec![Parameter::new("account", AccountHash::cl_type())],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("owner_of"),
        vec![Parameter::new("token_id", CLType::U256)],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("approve"),
        vec![
            Parameter::new("to", AccountHash::cl_type()),
            Parameter::new("token_id", CLType::U256)
            ],
            CLType::Unit,
            EntryPointAccess::Public,
            EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("get_approved"), 
        vec![Parameter::new("token_id", CLType::U256)],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("set_approval_for_all"), 
        vec![
            Parameter::new("operator", AccountHash::cl_type()),
            Parameter::new("approved", bool::cl_type()),
            ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("is_approved_for_all"), 
        vec![
            Parameter::new("owner", AccountHash::cl_type()),
            Parameter::new("operator", AccountHash::cl_type()),
            ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("transfer_from"), 
        vec![
            Parameter::new("from", AccountHash::cl_type()),
            Parameter::new("to", AccountHash::cl_type()),
            Parameter::new("token_id", CLType::U256),
            ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("safe_transfer_from"), 
        vec![
            Parameter::new("from", AccountHash::cl_type()),
            Parameter::new("to", AccountHash::cl_type()),
            Parameter::new("token_id", CLType::U256),
            Parameter::new("_data", Bytes::cl_type()),
            ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("mint"), 
        vec![
            Parameter::new("to", AccountHash::cl_type()),
            Parameter::new("token_id", CLType::U256),
            ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    entry_points.add_entry_point(EntryPoint::new(
        String::from("burn"), 
        vec![
            Parameter::new("token_id", CLType::U256)
            ],
        CLType::Unit,
        EntryPointAccess::Public,
        EntryPointType::Contract,
    ));
    let mut named_keys = NamedKeys::new();
    named_keys.insert("_name".to_string(), storage::new_uref(token_name).into());
    named_keys.insert("_symbol".to_string(), storage::new_uref(token_symbol).into());

    let (contract_hash, _) =
        storage::new_locked_contract(entry_points, Some(named_keys), None, None);
    runtime::put_key("ERC721", contract_hash.into());
    runtime::put_key("ERC721_hash", storage::new_uref(contract_hash).into());
}