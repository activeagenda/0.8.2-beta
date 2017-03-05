CREATE TABLE `ppla` (
   ChangeID int unsigned auto_increment not null,
   ChangeDate date,
   PersonID int unsigned,
   OldHomeAddress1 varchar(128),
   OldHomeAddress2 varchar(128),
   OldHomeCountyID int unsigned,
   OldHomeCity varchar(50),
   OldHomePostalCode varchar(50),
   OldHomePhone varchar(50),
   OldHomeFax varchar(50),
   OldHomeEmail varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChangeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppla_l` (
   _RecordID int unsigned not null auto_increment,
   ChangeID int unsigned  not null,
   ChangeDate date,
   PersonID int unsigned,
   OldHomeAddress1 varchar(128),
   OldHomeAddress2 varchar(128),
   OldHomeCountyID int unsigned,
   OldHomeCity varchar(50),
   OldHomePostalCode varchar(50),
   OldHomePhone varchar(50),
   OldHomeFax varchar(50),
   OldHomeEmail varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChangeID
   )
) TYPE=InnoDB;
