CREATE TABLE `surp` (
   SurveyPartID int unsigned auto_increment not null,
   SurveyID int,
   Anonymous bool default 0,
   ParticipationTime time,
   ParticipationReasonID int,
   ResultID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyPartID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surp_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyPartID int unsigned  not null,
   SurveyID int,
   Anonymous bool default 0,
   ParticipationTime time,
   ParticipationReasonID int,
   ResultID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyPartID
   )
) TYPE=InnoDB;
