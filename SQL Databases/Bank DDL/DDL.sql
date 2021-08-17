show databases;
use bankschema;

create table employee(
    eid varchar(10) PRIMARY KEY,
    branch_id varchar(15),
    name varchar(25),
    address varchar(50),
    age float,
    FOREIGN KEY (branch_id) references branch (branch_id) ON DELETE CASCADE ON UPDATE CASCADE 
);

show columns from employee;
show columns from loanaccounts;

alter table employee add designation varchar(25);

alter table loanaccounts alter max_credit set default 1000;

alter table employee modify age int;

alter table transaction add constraint FOREIGN KEY (eid) REFERENCES employee (eid) on delete cascade on update cascade;

insert into employee (eid, branch_id, name, address, age) values 
(1, 32, 'Bob', '1318 Walt Nuzum Farm Road', 21),
(2, 43, 'Alice', '4479 Commerce Boulevard', 25),
(3, 56, 'Joe', '1873 Bridge Avenue', 39);