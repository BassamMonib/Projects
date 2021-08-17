show databases;
use bankschema;

/*DDL*/

/* Q1 */
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

/* Q2 */
alter table employee add designation varchar(25);

/* Q3 */
alter table loanaccounts alter max_credit set default 1000;

/* Q4 */
alter table employee modify age int;

/* Q5 */
alter table transaction add constraint FOREIGN KEY (eid) REFERENCES employee (eid) on delete cascade on update cascade;


/* DML */
use chess;

/* Q6 */
select *
from players
where fname like '__s%';

/* Q7 */
select *
from sponsors
where sponsor_name != 'azhar' and sponsor_name != 'galib';

/* Q8 */
select fname
from players natural join player_participated natural join tornaments
where tornament_name = 'awami';

/* Q9 */
select *
from tornaments
where organizer_id in ( select organizer_id
						from tornament_organizer
						where organizer_name = 'alinawaz' or organizer_name = 'shabana azmi');
                        
/* Q10 */
select fname, lname
from players
where player_id in ( select player_id
					 from player_participated
					 group by player_id
                     having count(player_id) > 1);
                     
/* Q11 */
select count(club_id)
from chessclub
where adress_id in ( select address_id
					 from address 
                     where city = 'lahore' or city = 'faisalabad');
                     
/* Q12 */
select fname, lname
from ((((players natural join player_participated)
			   natural join tornaments)
               natural join tornament_organizer)
               natural join chessclub cl)
               join address a on cl.adress_id = a.address_id
where organizer_name = 'Rahat fateh' and city = 'lahore';