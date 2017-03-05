CREATE TABLE `pplat` (
   AvailabilityModificationTypeID int unsigned auto_increment not null,
   ModificationCategoryID int,
   ModificationType varchar(128),
   ModificationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AvailabilityModificationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplat_l` (
   _RecordID int unsigned not null auto_increment,
   AvailabilityModificationTypeID int unsigned  not null,
   ModificationCategoryID int,
   ModificationType varchar(128),
   ModificationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AvailabilityModificationTypeID
   )
) TYPE=InnoDB;
