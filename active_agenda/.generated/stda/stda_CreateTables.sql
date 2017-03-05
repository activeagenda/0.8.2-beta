CREATE TABLE `stda` (
   StandardAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   StandardID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      StandardAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `stda_l` (
   _RecordID int unsigned not null auto_increment,
   StandardAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   StandardID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      StandardAssocID
   )
) TYPE=InnoDB;
