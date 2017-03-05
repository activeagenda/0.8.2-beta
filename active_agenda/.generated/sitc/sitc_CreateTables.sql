CREATE TABLE `sitc` (
   SituationContactID int unsigned auto_increment not null,
   SituationID int,
   ContactImmediacy float,
   ContactImmediacyUnitsID int,
   SeverityID int,
   SpecialFactors text,
   LastUpdate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationContactID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitc_l` (
   _RecordID int unsigned not null auto_increment,
   SituationContactID int unsigned  not null,
   SituationID int,
   ContactImmediacy float,
   ContactImmediacyUnitsID int,
   SeverityID int,
   SpecialFactors text,
   LastUpdate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationContactID
   )
) TYPE=InnoDB;
