CREATE TABLE `mtgm` (
   MasterMeetingID int unsigned auto_increment not null,
   OrganizationID int,
   MeetingTypeID int,
   MeetingTitle varchar(128),
   Agenda text,
   RecurringMeeting bool default 0,
   SchedFreq float,
   SchedFreqUnitsID int,
   SeriesEndDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MasterMeetingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtgm_l` (
   _RecordID int unsigned not null auto_increment,
   MasterMeetingID int unsigned  not null,
   OrganizationID int,
   MeetingTypeID int,
   MeetingTitle varchar(128),
   Agenda text,
   RecurringMeeting bool default 0,
   SchedFreq float,
   SchedFreqUnitsID int,
   SeriesEndDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MasterMeetingID
   )
) TYPE=InnoDB;
