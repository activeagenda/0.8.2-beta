CREATE TABLE `vala` (
   ValuesAssociationID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SharedValueID int,
   ValuesAssociationTitle varchar(128),
   Manner text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ValuesAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vala_l` (
   _RecordID int unsigned not null auto_increment,
   ValuesAssociationID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SharedValueID int,
   ValuesAssociationTitle varchar(128),
   Manner text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ValuesAssociationID
   )
) TYPE=InnoDB;
