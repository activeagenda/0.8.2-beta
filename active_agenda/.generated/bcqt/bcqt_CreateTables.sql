CREATE TABLE `bcqt` (
   BusinessConsequenceTypeID int unsigned auto_increment not null,
   ConsequenceCategoryID int,
   ConsequenceType varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bcqt_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessConsequenceTypeID int unsigned  not null,
   ConsequenceCategoryID int,
   ConsequenceType varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessConsequenceTypeID
   )
) TYPE=InnoDB;
