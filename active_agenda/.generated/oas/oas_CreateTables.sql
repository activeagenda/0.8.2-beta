CREATE TABLE `oas` (
   OtherAssetID int unsigned auto_increment not null,
   OrganizationID int,
   OtherAssetTitle varchar(128),
   OtherAssetTypeID int,
   OtherAssetDesc text,
   BaseUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherAssetID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oas_l` (
   _RecordID int unsigned not null auto_increment,
   OtherAssetID int unsigned  not null,
   OrganizationID int,
   OtherAssetTitle varchar(128),
   OtherAssetTypeID int,
   OtherAssetDesc text,
   BaseUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherAssetID
   )
) TYPE=InnoDB;
