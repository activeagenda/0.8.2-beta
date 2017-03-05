CREATE TABLE `pkg` (
   PackagingUnitEquivalentID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FirstUnitValue float,
   FirstUnitID int,
   SecondUnitValue float,
   SecondUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PackagingUnitEquivalentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pkg_l` (
   _RecordID int unsigned not null auto_increment,
   PackagingUnitEquivalentID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FirstUnitValue float,
   FirstUnitID int,
   SecondUnitValue float,
   SecondUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PackagingUnitEquivalentID
   )
) TYPE=InnoDB;
