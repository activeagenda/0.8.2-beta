CREATE TABLE `opppc` (
   OppPermitCategoryID int unsigned auto_increment not null,
   OppPermitID int,
   PermitCategoryID int,
   PermitCategoryName varchar(128),
   PermitCategoryDesc varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OppPermitCategoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opppc_l` (
   _RecordID int unsigned not null auto_increment,
   OppPermitCategoryID int unsigned  not null,
   OppPermitID int,
   PermitCategoryID int,
   PermitCategoryName varchar(128),
   PermitCategoryDesc varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OppPermitCategoryID
   )
) TYPE=InnoDB;
