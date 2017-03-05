CREATE TABLE `trama` (
   MaterialAssocID int unsigned auto_increment not null,
   CourseID int,
   MaterialID int,
   MaterialQuantity float,
   MaterialQuantityUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MaterialAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trama_l` (
   _RecordID int unsigned not null auto_increment,
   MaterialAssocID int unsigned  not null,
   CourseID int,
   MaterialID int,
   MaterialQuantity float,
   MaterialQuantityUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MaterialAssocID
   )
) TYPE=InnoDB;
