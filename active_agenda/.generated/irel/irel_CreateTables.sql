CREATE TABLE `irel` (
   LetterID int unsigned auto_increment not null,
   IncidentReportID int,
   ContactTypeID int,
   LetterOrganizationID int,
   LetterDate date,
   PostDate date,
   OrganizationID int,
   LetterSummary text,
   LetterAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LetterID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `irel_l` (
   _RecordID int unsigned not null auto_increment,
   LetterID int unsigned  not null,
   IncidentReportID int,
   ContactTypeID int,
   LetterOrganizationID int,
   LetterDate date,
   PostDate date,
   OrganizationID int,
   LetterSummary text,
   LetterAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LetterID
   )
) TYPE=InnoDB;
