use chess;

select *
from players
where fname like '__s%';

select *
from sponsors
where sponsor_name != 'azhar' and sponsor_name != 'galib';

select fname
from players natural join player_participated natural join tornaments
where tornament_name = 'awami';

select *
from tornaments
where organizer_id in ( select organizer_id
						from tornament_organizer
						where organizer_name = 'alinawaz' or organizer_name = 'shabana azmi');
                        
select fname, lname
from players
where player_id in ( select player_id
					 from player_participated
					 group by player_id
                     having count(player_id) > 1);
                     
select count(club_id)
from chessclub
where adress_id in ( select address_id
					 from address 
                     where city = 'lahore' or city = 'faisalabad');
                     
select fname, lname
from ((((players natural join player_participated)
			   natural join tornaments)
               natural join tornament_organizer)
               natural join chessclub cl)
               join address a on cl.adress_id = a.address_id
where organizer_name = 'Rahat fateh' and city = 'lahore';