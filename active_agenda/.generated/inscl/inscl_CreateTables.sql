CREATE TABLE `inscl` (
   CoverageLimitID int unsigned auto_increment not null,
   PolicyID int,
   CoverageLimitTypeID int unsigned,
   LimitAmount decimal(12,4),
   LimitDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CoverageLimitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inscl_l` (
   _RecordID int unsigned not null auto_increment,
   CoverageLimitID int unsigned  not null,
   PolicyID int,
   CoverageLimitTypeID int unsigned,
   LimitAmount decimal(12,4),
   LimitDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CoverageLimitID
   )
) TYPE=InnoDB;
