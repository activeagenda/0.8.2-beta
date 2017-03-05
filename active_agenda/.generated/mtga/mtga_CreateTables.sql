CREATE TABLE `mtga` (
   AttendeeID int unsigned auto_increment not null,
   MeetingID int,
   PunctualnessID int,
   AttentivenessID int,
   AttendanceID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AttendeeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtga_l` (
   _RecordID int unsigned not null auto_increment,
   AttendeeID int unsigned  not null,
   MeetingID int,
   PunctualnessID int,
   AttentivenessID int,
   AttendanceID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AttendeeID
   )
) TYPE=InnoDB;
