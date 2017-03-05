CREATE TABLE `linet` (
   InjuryExposureTypeID int unsigned auto_increment not null,
   InjuryExposureCategoryID int,
   ExposureType varchar(128),
   InjuryExposureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryExposureTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linet_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryExposureTypeID int unsigned  not null,
   InjuryExposureCategoryID int,
   ExposureType varchar(128),
   InjuryExposureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryExposureTypeID
   )
) TYPE=InnoDB;
