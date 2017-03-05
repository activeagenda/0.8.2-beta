CREATE TABLE `bcq` (
   BusinessConsequenceID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   BusinessConsequenceTypeID int,
   NatureofConsequenceID int,
   ConsequenceTitle varchar(128),
   ConsequenceDesc text,
   ConsequenceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bcq_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessConsequenceID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   BusinessConsequenceTypeID int,
   NatureofConsequenceID int,
   ConsequenceTitle varchar(128),
   ConsequenceDesc text,
   ConsequenceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessConsequenceID
   )
) TYPE=InnoDB;
