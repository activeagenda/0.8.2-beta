CREATE TABLE `linsc` (
   InjurySourceID int unsigned auto_increment not null,
   InjurySourceTypeID int,
   InjurySourceTitle varchar(128),
   InjurySourceDivision varchar(10),
   InjurySourceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjurySourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linsc_l` (
   _RecordID int unsigned not null auto_increment,
   InjurySourceID int unsigned  not null,
   InjurySourceTypeID int,
   InjurySourceTitle varchar(128),
   InjurySourceDivision varchar(10),
   InjurySourceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjurySourceID
   )
) TYPE=InnoDB;
