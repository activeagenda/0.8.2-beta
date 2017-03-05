CREATE TABLE `act` (
   ActionID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   Title varchar(128),
   ActionRequired text,
   OrganizationID int,
   PersonAccountableID int,
   BeginDate date,
   ActionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ActionID
   ),
   INDEX act_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `act_l` (
   _RecordID int unsigned not null auto_increment,
   ActionID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   Title varchar(128),
   ActionRequired text,
   OrganizationID int,
   PersonAccountableID int,
   BeginDate date,
   ActionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ActionID
   ),
   INDEX act_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;
