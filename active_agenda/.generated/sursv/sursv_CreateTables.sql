CREATE TABLE `sursv` (
   SurveyScaleValueID int unsigned auto_increment not null,
   SurveyScaleTitleID int,
   ScaleValue float,
   ScaleValueDesc varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyScaleValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sursv_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyScaleValueID int unsigned  not null,
   SurveyScaleTitleID int,
   ScaleValue float,
   ScaleValueDesc varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyScaleValueID
   )
) TYPE=InnoDB;
