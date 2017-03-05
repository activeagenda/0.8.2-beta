CREATE TABLE `linex` (
   InjuryExposureID int unsigned auto_increment not null,
   InjuryExposureTypeID int,
   InjuryExposureTitle varchar(128),
   InjuryExposureDivision varchar(10),
   InjuryExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryExposureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linex_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryExposureID int unsigned  not null,
   InjuryExposureTypeID int,
   InjuryExposureTitle varchar(128),
   InjuryExposureDivision varchar(10),
   InjuryExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryExposureID
   )
) TYPE=InnoDB;
