CREATE TABLE `opprr` (
   PermitRptReqID int unsigned auto_increment not null,
   OppPermitID int,
   ReportReqID int,
   Requirement varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitRptReqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opprr_l` (
   _RecordID int unsigned not null auto_increment,
   PermitRptReqID int unsigned  not null,
   OppPermitID int,
   ReportReqID int,
   Requirement varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitRptReqID
   )
) TYPE=InnoDB;
