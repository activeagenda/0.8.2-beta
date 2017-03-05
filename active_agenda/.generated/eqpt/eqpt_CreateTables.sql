CREATE TABLE `eqpt` (
   EquipmentTypeID int unsigned auto_increment not null,
   EquipmentCategoryID int,
   EquipmentTypeTitle varchar(128),
   EquipmentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpt_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentTypeID int unsigned  not null,
   EquipmentCategoryID int,
   EquipmentTypeTitle varchar(128),
   EquipmentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentTypeID
   )
) TYPE=InnoDB;
