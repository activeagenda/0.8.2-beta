CREATE TABLE `oppea` (
   PermitEqptID int unsigned auto_increment not null,
   OppPermitID int,
   EquipmentInventoryID int,
   PermitEqptAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitEqptID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppea_l` (
   _RecordID int unsigned not null auto_increment,
   PermitEqptID int unsigned  not null,
   OppPermitID int,
   EquipmentInventoryID int,
   PermitEqptAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitEqptID
   )
) TYPE=InnoDB;
