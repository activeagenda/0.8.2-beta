CREATE TABLE `llit` (
   LossLegitimacyTypeID int unsigned auto_increment not null,
   LegitimacyCategoryID int,
   LegitimacyType varchar(128),
   LegitimacyCode varchar(10),
   LegitimacyDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossLegitimacyTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `llit_l` (
   _RecordID int unsigned not null auto_increment,
   LossLegitimacyTypeID int unsigned  not null,
   LegitimacyCategoryID int,
   LegitimacyType varchar(128),
   LegitimacyCode varchar(10),
   LegitimacyDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossLegitimacyTypeID
   )
) TYPE=InnoDB;
