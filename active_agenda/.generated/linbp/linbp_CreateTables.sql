CREATE TABLE `linbp` (
   BodyPartID int unsigned auto_increment not null,
   BodyPartTypeID int,
   BodyPartTitle varchar(128),
   BodyPartDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BodyPartID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linbp_l` (
   _RecordID int unsigned not null auto_increment,
   BodyPartID int unsigned  not null,
   BodyPartTypeID int,
   BodyPartTitle varchar(128),
   BodyPartDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BodyPartID
   )
) TYPE=InnoDB;
