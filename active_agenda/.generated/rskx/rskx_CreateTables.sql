CREATE TABLE `rskx` (
   RiskIndexID int unsigned auto_increment not null,
   IndexValue int,
   RiskRecommendation varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RiskIndexID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskx_l` (
   _RecordID int unsigned not null auto_increment,
   RiskIndexID int unsigned  not null,
   IndexValue int,
   RiskRecommendation varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RiskIndexID
   )
) TYPE=InnoDB;
