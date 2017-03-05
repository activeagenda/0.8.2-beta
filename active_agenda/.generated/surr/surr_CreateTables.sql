CREATE TABLE `surr` (
   ResultID int unsigned auto_increment not null,
   SurveyPartID int,
   QuestionID int,
   SurveyScaleValueID int,
   SurveyScore float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResultID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surr_l` (
   _RecordID int unsigned not null auto_increment,
   ResultID int unsigned  not null,
   SurveyPartID int,
   QuestionID int,
   SurveyScaleValueID int,
   SurveyScore float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResultID
   )
) TYPE=InnoDB;
