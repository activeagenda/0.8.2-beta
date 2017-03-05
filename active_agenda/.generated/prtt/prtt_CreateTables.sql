CREATE TABLE `prtt` (
   ParticipationTypeID int auto_increment not null,
   ParticipationPurposeID int,
   ParticipationTitle varchar(128),
   ParticipationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ParticipationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `prtt_l` (
   _RecordID int unsigned not null auto_increment,
   ParticipationTypeID int  not null,
   ParticipationPurposeID int,
   ParticipationTitle varchar(128),
   ParticipationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ParticipationTypeID
   )
) TYPE=InnoDB;
