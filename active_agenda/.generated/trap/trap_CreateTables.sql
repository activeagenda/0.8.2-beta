CREATE TABLE `trap` (
   PrereqID int unsigned auto_increment not null,
   CourseID int,
   PrereqCourseID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PrereqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trap_l` (
   _RecordID int unsigned not null auto_increment,
   PrereqID int unsigned  not null,
   CourseID int,
   PrereqCourseID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PrereqID
   )
) TYPE=InnoDB;
