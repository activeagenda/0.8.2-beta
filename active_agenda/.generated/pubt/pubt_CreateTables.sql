CREATE TABLE `pubt` (
   PublicityTypeID int unsigned auto_increment not null,
   PublicityExposureCategoryID int,
   PublicityExposureType varchar(128),
   PublicityExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pubt_l` (
   _RecordID int unsigned not null auto_increment,
   PublicityTypeID int unsigned  not null,
   PublicityExposureCategoryID int,
   PublicityExposureType varchar(128),
   PublicityExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PublicityTypeID
   )
) TYPE=InnoDB;
