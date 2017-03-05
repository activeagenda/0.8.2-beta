CREATE TABLE `eqpa` (
   EquipAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EquipmentInventoryID int unsigned,
   UseConditions text,
   EquipAssocStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpa_l` (
   _RecordID int unsigned not null auto_increment,
   EquipAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EquipmentInventoryID int unsigned,
   UseConditions text,
   EquipAssocStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipAssocID
   )
) TYPE=InnoDB;
