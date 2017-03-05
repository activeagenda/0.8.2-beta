CREATE TABLE `lppb` (
   LossBuildingID int unsigned auto_increment not null,
   OrganizationID int,
   BuildingID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   PropertyAvailable bool default 0,
   PropertyAvailableDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossBuildingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppb_l` (
   _RecordID int unsigned not null auto_increment,
   LossBuildingID int unsigned  not null,
   OrganizationID int,
   BuildingID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   PropertyAvailable bool default 0,
   PropertyAvailableDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossBuildingID
   )
) TYPE=InnoDB;
