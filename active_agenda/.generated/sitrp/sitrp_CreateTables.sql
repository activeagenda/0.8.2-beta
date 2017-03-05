CREATE TABLE `sitrp` (
   SituationResponseID int unsigned auto_increment not null,
   SituationID int,
   ResourceTypeID int,
   LocalRoleDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationResponseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitrp_l` (
   _RecordID int unsigned not null auto_increment,
   SituationResponseID int unsigned  not null,
   SituationID int,
   ResourceTypeID int,
   LocalRoleDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationResponseID
   )
) TYPE=InnoDB;
