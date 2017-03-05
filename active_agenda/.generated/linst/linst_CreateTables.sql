CREATE TABLE `linst` (
   InjurySourceTypeID int unsigned auto_increment not null,
   InjurySourceCategoryID int,
   SourceType varchar(128),
   InjurySourceTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjurySourceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linst_l` (
   _RecordID int unsigned not null auto_increment,
   InjurySourceTypeID int unsigned  not null,
   InjurySourceCategoryID int,
   SourceType varchar(128),
   InjurySourceTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjurySourceTypeID
   )
) TYPE=InnoDB;
