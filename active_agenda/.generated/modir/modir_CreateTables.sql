CREATE TABLE `modir` (
   IssueReportID int unsigned auto_increment not null,
   OrganizationID int,
   ModuleID varchar(5),
   IssueCategoryID int unsigned,
   IssueTypeID int unsigned,
   IssueBrowserID int unsigned,
   IssueBrowserVersion varchar(25),
   IssueOperatingSystemID int unsigned,
   IssueOperatingSystemVersion varchar(25),
   IssueTitle varchar(255),
   IssueDesc text,
   PersonReportingID int unsigned,
   ReportedPriorityID int unsigned,
   PosttoActiveAgenda bool default 1,
   PersonAccountableID int unsigned,
   AccountablePriorityID int unsigned,
   ResolutionComplexityID int unsigned,
   HoursEstimate float,
   IssueStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IssueReportID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modir_l` (
   _RecordID int unsigned not null auto_increment,
   IssueReportID int unsigned  not null,
   OrganizationID int,
   ModuleID varchar(5),
   IssueCategoryID int unsigned,
   IssueTypeID int unsigned,
   IssueBrowserID int unsigned,
   IssueBrowserVersion varchar(25),
   IssueOperatingSystemID int unsigned,
   IssueOperatingSystemVersion varchar(25),
   IssueTitle varchar(255),
   IssueDesc text,
   PersonReportingID int unsigned,
   ReportedPriorityID int unsigned,
   PosttoActiveAgenda bool default 1,
   PersonAccountableID int unsigned,
   AccountablePriorityID int unsigned,
   ResolutionComplexityID int unsigned,
   HoursEstimate float,
   IssueStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IssueReportID
   )
) TYPE=InnoDB;
