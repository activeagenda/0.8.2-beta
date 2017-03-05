CREATE TABLE `mtg` (
   MeetingID int unsigned auto_increment not null,
   AssignedMeeting bool default 0,
   MasterAssignID int,
   OrganizationID int,
   MeetingTitle varchar(128),
   MeetingTypeID int,
   MeetingStartTime datetime,
   MeetingEndTime datetime,
   SeriesEndDate date,
   MeetingStatusID int,
   Agenda text,
   IssuesDiscussed text,
   DepartmentMeeting bool default 0,
   DepartmentID int,
   RegulatoryRequired bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MeetingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtg_l` (
   _RecordID int unsigned not null auto_increment,
   MeetingID int unsigned  not null,
   AssignedMeeting bool default 0,
   MasterAssignID int,
   OrganizationID int,
   MeetingTitle varchar(128),
   MeetingTypeID int,
   MeetingStartTime datetime,
   MeetingEndTime datetime,
   SeriesEndDate date,
   MeetingStatusID int,
   Agenda text,
   IssuesDiscussed text,
   DepartmentMeeting bool default 0,
   DepartmentID int,
   RegulatoryRequired bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MeetingID
   )
) TYPE=InnoDB;
