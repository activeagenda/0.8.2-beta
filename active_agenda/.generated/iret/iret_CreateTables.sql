CREATE TABLE `iret` (
   TelephoneCallID int unsigned auto_increment not null,
   IncidentReportID int,
   ContactTypeID int,
   PhoningOrganizationID int,
   CallTime time,
   OrganizationID int,
   CallSummary text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TelephoneCallID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `iret_l` (
   _RecordID int unsigned not null auto_increment,
   TelephoneCallID int unsigned  not null,
   IncidentReportID int,
   ContactTypeID int,
   PhoningOrganizationID int,
   CallTime time,
   OrganizationID int,
   CallSummary text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TelephoneCallID
   )
) TYPE=InnoDB;
