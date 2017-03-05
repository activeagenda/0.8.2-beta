CREATE TABLE `sitt` (
   SituationTypeID int unsigned auto_increment not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitt_l` (
   _RecordID int unsigned not null auto_increment,
   SituationTypeID int unsigned  not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationTypeID
   )
) TYPE=InnoDB;
