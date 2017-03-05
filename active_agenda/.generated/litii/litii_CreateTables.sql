CREATE TABLE `litii` (
   ITIncidentIndicatorID int unsigned auto_increment not null,
   IndicatorCategoryID int,
   IndicatorType varchar(128),
   IndicatorDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ITIncidentIndicatorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `litii_l` (
   _RecordID int unsigned not null auto_increment,
   ITIncidentIndicatorID int unsigned  not null,
   IndicatorCategoryID int,
   IndicatorType varchar(128),
   IndicatorDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ITIncidentIndicatorID
   )
) TYPE=InnoDB;
