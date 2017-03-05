CREATE TABLE `traas` (
   AttendeeSchedID int unsigned auto_increment not null,
   TrainingClassID int unsigned not null,
   Completed bool default 1,
   PunctualnessID int default 2,
   AttentivenessID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AttendeeSchedID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `traas_l` (
   _RecordID int unsigned not null auto_increment,
   AttendeeSchedID int unsigned  not null,
   TrainingClassID int unsigned not null,
   Completed bool default 1,
   PunctualnessID int default 2,
   AttentivenessID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AttendeeSchedID
   )
) TYPE=InnoDB;
