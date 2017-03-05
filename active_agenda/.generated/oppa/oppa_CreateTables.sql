CREATE TABLE `oppa` (
   OperatingPermitAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OppPermitID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OperatingPermitAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppa_l` (
   _RecordID int unsigned not null auto_increment,
   OperatingPermitAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OppPermitID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OperatingPermitAssocID
   )
) TYPE=InnoDB;
