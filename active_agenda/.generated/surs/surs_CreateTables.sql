CREATE TABLE `surs` (
   SurveyScaleID int unsigned auto_increment not null,
   Name varchar(50) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyScaleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surs_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyScaleID int unsigned  not null,
   Name varchar(50) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyScaleID
   )
) TYPE=InnoDB;
