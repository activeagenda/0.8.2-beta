CREATE TABLE `prti` (
   ParticipantInvID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   InvolvementTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ParticipantInvID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `prti_l` (
   _RecordID int unsigned not null auto_increment,
   ParticipantInvID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   InvolvementTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ParticipantInvID
   )
) TYPE=InnoDB;
