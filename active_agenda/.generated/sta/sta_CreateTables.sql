CREATE TABLE `sta` (
   StateID int unsigned auto_increment not null,
   CountryID int unsigned,
   StateName varchar(50),
   StateAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      StateID
   ),
   INDEX sta_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sta_l` (
   _RecordID int unsigned not null auto_increment,
   StateID int unsigned  not null,
   CountryID int unsigned,
   StateName varchar(50),
   StateAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      StateID
   ),
   INDEX sta_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
