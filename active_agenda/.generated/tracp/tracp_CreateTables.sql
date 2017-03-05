CREATE TABLE `tracp` (
   CertPrereqID int unsigned auto_increment not null,
   CertificationID int,
   CourseID int,
   CourseSatisfactionLevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CertPrereqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tracp_l` (
   _RecordID int unsigned not null auto_increment,
   CertPrereqID int unsigned  not null,
   CertificationID int,
   CourseID int,
   CourseSatisfactionLevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CertPrereqID
   )
) TYPE=InnoDB;
