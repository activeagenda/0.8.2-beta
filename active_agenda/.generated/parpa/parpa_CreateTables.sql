CREATE TABLE `parpa` (
   PartnershipAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   PartnershipID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parpa_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   PartnershipID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipAssocID
   )
) TYPE=InnoDB;
