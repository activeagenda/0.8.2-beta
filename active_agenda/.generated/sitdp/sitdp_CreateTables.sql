CREATE TABLE `sitdp` (
   DrillParticipantID int unsigned auto_increment not null,
   SituationDrillID int unsigned not null,
   ParticipantDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DrillParticipantID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitdp_l` (
   _RecordID int unsigned not null auto_increment,
   DrillParticipantID int unsigned  not null,
   SituationDrillID int unsigned not null,
   ParticipantDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DrillParticipantID
   )
) TYPE=InnoDB;
