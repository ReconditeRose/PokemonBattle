use pokemonBattle
go

create procedure sendBattleRequest(@var1 int,@var2 int)
As
if not exists(select * from Battle where (PartyOne = @var2 or PartyTwo =@var2))
insert into BattleRequests
values(@var1,@var2)
go

create procedure acceptBattleRequest(@var1 int, @var2 int)
As
if(exists(select * from BattleRequests where TeamID = @var2)
BEGIN


if not exists(select * from Battle where (PartyOne = @var2 or PartyTwo =@var2))
insert into Battle
Values(0,GETDATE(),@var1,@var2,1)

Delete from BattleRequests
Where TeamID = @var1 and TeamID = @var2

END
go
