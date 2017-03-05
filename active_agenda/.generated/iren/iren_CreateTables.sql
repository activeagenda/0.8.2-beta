CREATE TABLE `iren` (
   IRNumberID int unsigned auto_increment not null,
   IncidentReportID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IRNumberID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `iren_l` (
   _RecordID int unsigned not null auto_increment,
   IRNumberID int unsigned  not null,
   IncidentReportID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IRNumberID
   )
) TYPE=InnoDB;
