CREATE TABLE `orgla` (
   LocationAssocID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LocationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationAssocID
   ),
   INDEX orgla_LocationID (
      LocationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgla_l` (
   _RecordID int unsigned not null auto_increment,
   LocationAssocID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LocationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocationAssocID
   ),
   INDEX orgla_l_LocationID (
      LocationID
   )
) TYPE=InnoDB;
