CREATE TABLE `ires` (
   IncidentReportSituationID int unsigned auto_increment not null,
   IncidentReportID int,
   SituationTypeID int unsigned not null,
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncidentReportSituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ires_l` (
   _RecordID int unsigned not null auto_increment,
   IncidentReportSituationID int unsigned  not null,
   IncidentReportID int,
   SituationTypeID int unsigned not null,
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncidentReportSituationID
   )
) TYPE=InnoDB;
