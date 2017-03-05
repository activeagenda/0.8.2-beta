CREATE TABLE `ctr` (
   CountryID int unsigned auto_increment not null,
   CountryName varchar(50),
   NativeCountryName varchar(50),
   CountryAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CountryID
   ),
   INDEX ctr_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ctr_l` (
   _RecordID int unsigned not null auto_increment,
   CountryID int unsigned  not null,
   CountryName varchar(50),
   NativeCountryName varchar(50),
   CountryAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CountryID
   ),
   INDEX ctr_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
