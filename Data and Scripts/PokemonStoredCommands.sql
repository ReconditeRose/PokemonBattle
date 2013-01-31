use pokemonBattle
go

Alter Procedure CreateParty (@userName varchar(15))
AS 
Insert into UserParty ([Username])
Values(@userName)
Select Max(PartyID) from UserParty where Username = @userName Group by Username 
Go

Alter Procedure LearnMove (@pokemonID int, @moveName varchar(20))
AS
if(not exists(select * from [Move] where Name = @moveName))
return 0

Select Move1,Move2,Move3,Move4 INTO #tempTable from UserPokemon where PokemonID = @pokemonID 
Select Move1 from #tempTable

if( exists (Select Move1 from #tempTable where Move1 =@moveName) or exists (Select Move2 from #tempTable where Move2 =@moveName)
or exists (Select Move3 from #tempTable where Move3 =@moveName) or exists (Select Move4 from #tempTable where Move4 =@moveName))
return 0

if (exists (Select Move1 from #tempTable where Move1 is NULL))
Begin
print 'Yea'
update UserPokemon
Set Move1 = @moveName where PokemonID = @pokeMonID
Drop Table #tempTable
return 1
End

if (exists (Select Move2 from #tempTable where Move2 is NULL))
Begin
update UserPokemon
Set Move2 = @moveName where PokemonID = @pokeMonID
Drop Table #tempTable
return 1
End

if (exists (Select Move3 from #tempTable where Move3 is NULL))
Begin
update UserPokemon
Set Move3 = @moveName where PokemonID = @pokeMonID
Drop Table #tempTable
return 1
End

update UserPokemon
Set Move4 = @moveName where PokemonID = @pokeMonID
Drop Table #tempTable
return 1
Go

exec LearnMove 56, "Horn Attack"

Alter Procedure AddPokemon (@PokemonName nvarchar(10), @PartyNo int)
As
if(Select COUNT(PokemonID) from UserPokemon where PartyNo = @PartyNo) >5
return -1

insert into UserPokemon (PartyNo,pokemonName,Nature,Attack,Defense,Speed,[Sp Attack], [Sp Defense],HP,evasion)
Values(@PartyNo,@PokemonName,'Whatever', 0,0,0,0,0,0,0)
Go

Alter Procedure DeleteTeam (@TeamID int)
as
Delete from UserPokemon
where partyNo = @TeamID
go

Alter Procedure RegisterParty (@username varchar(15) ,@password varchar(50), @email varchar(15))
As
Insert into [User] (Username,[Password],Email)
values (@username,@password,@email)
go

Alter Procedure Authenticate (@username varchar(15) ,@password varchar(50))
As
SELECT Username FROM [User] WHERE Username=@username and Password=@password
go

Alter Procedure AuthProd
AS
declare @name varchar(50)
declare @Grant_Permission cursor
set @Grant_Permission = Cursor for select name from sys.procedures
open @Grant_Permission
Fetch Next from @Grant_Permission into @name

while @@FETCH_STATUS =0
BEGIN
exec('grant execute on '+@name+' to [333PokemonBattle]')
print('grant execute on '+@name+' to [333PokemonBattle]')
Fetch NEXT from @Grant_Permission into @name
END
close @Grant_Permission
go

Alter Procedure DeletePoke(@pokemonID int)
As
delete from UserPokemon
where PokemonID = @pokemonID
go

Alter Procedure getMoves
As
select [Name],[Type],[Power],[Accuracy],PP from [Move]
go

Create Procedure getPokemon(@var1 int)
AS
Select pokemonName, Attack,Defense,[Sp Attack],[Sp Defense],Speed,HP,Move1,Move2,Move3,Move4,PokemonID
from UserPokemon where PartyNO= @var1
GO

Alter Procedure dropMove(@var1 int, @var2 int)
AS
if(@var2>0 and @var2<5)
exec('update UserPokemon set Move'+@var2+' = NULL where PokemonID='+@var1);
go

exec AuthProd
exec dropMove 40,1

