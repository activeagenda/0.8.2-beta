CREATE TABLE `posa` (
   PostingAssignmentID int unsigned auto_increment not null,
   PostingID int,
   PostingMethodDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PostingAssignmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `posa_l` (
   _RecordID int unsigned not null auto_increment,
   PostingAssignmentID int unsigned  not null,
   PostingID int,
   PostingMethodDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PostingAssignmentID
   )
) TYPE=InnoDB;
