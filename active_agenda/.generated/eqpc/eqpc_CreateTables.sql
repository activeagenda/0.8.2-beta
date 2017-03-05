CREATE TABLE `eqpc` (
   EquipmentConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   EquipmentConsiderationTitle varchar(128),
   EquipmentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpc_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   EquipmentConsiderationTitle varchar(128),
   EquipmentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentConsiderationID
   )
) TYPE=InnoDB;
