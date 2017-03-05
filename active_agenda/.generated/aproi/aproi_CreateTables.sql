CREATE TABLE `aproi` (
   OtherItemID int unsigned auto_increment not null,
   OrganizationID int,
   OtherItemTypeID int,
   OtherItemTitle varchar(128),
   OtherItemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aproi_l` (
   _RecordID int unsigned not null auto_increment,
   OtherItemID int unsigned  not null,
   OrganizationID int,
   OtherItemTypeID int,
   OtherItemTitle varchar(128),
   OtherItemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherItemID
   )
) TYPE=InnoDB;
