CREATE TABLE `ire` (
   IncidentReportID int unsigned auto_increment not null,
   OrganizationID int,
   MannerReceivedID int,
   ReportSourceID int,
   RepPersonTypeID int,
   OnCoPropID int,
   RepEvent text,
   SubObserv text,
   IncidentName varchar(128),
   CodeName varchar(50),
   PotVioLaw bool default 0,
   GovtRept bool default 0,
   InvRptDftComp bool default 0,
   InvRptDftCompDate date,
   InvRptDftSent bool default 0,
   InvRptDftSentDate datetime,
   InvRptDftReceived bool default 0,
   InvRptDftRecDate date,
   InvRptFinalized bool default 0,
   InvRptFinalDate datetime,
   IncidentStatusID int,
   KeyLearning text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncidentReportID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ire_l` (
   _RecordID int unsigned not null auto_increment,
   IncidentReportID int unsigned  not null,
   OrganizationID int,
   MannerReceivedID int,
   ReportSourceID int,
   RepPersonTypeID int,
   OnCoPropID int,
   RepEvent text,
   SubObserv text,
   IncidentName varchar(128),
   CodeName varchar(50),
   PotVioLaw bool default 0,
   GovtRept bool default 0,
   InvRptDftComp bool default 0,
   InvRptDftCompDate date,
   InvRptDftSent bool default 0,
   InvRptDftSentDate datetime,
   InvRptDftReceived bool default 0,
   InvRptDftRecDate date,
   InvRptFinalized bool default 0,
   InvRptFinalDate datetime,
   IncidentStatusID int,
   KeyLearning text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncidentReportID
   )
) TYPE=InnoDB;
