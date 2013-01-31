Create Procedure MobileUser (@username varchar(15) ,@password varchar(50), @UserID varchar(15))
As
if exists ( select * from UserParty, [User] where UserParty.Username = [User].username and PartyID = @UserID)
select * from UserParty, [User] where UserParty.Username = [User].Username and PartyID = @UserID

go

use pokemonBattle
go

grant execute on MobileUser to [333PokemonBattle]