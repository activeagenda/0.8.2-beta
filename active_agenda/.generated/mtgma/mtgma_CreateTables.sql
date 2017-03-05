CREATE TABLE `mtgma` (
   MasterAssignID int unsigned auto_increment not null,
   MasterMeetingID int,
   OrganizationID int,
   AssignmentDetails text,
   MasterLeaderObservations text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MasterAssignID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtgma_l` (
   _RecordID int unsigned not null auto_increment,
   MasterAssignID int unsigned  not null,
   MasterMeetingID int,
   OrganizationID int,
   AssignmentDetails text,
   MasterLeaderObservations text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MasterAssignID
   )
) TYPE=InnoDB;
