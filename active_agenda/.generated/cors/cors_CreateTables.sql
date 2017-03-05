CREATE TABLE `cors` (
   SituationID int unsigned auto_increment not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   GeneralDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cors_l` (
   _RecordID int unsigned not null auto_increment,
   SituationID int unsigned  not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   GeneralDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationID
   )
) TYPE=InnoDB;
