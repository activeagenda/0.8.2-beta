CREATE TABLE `usrl` (
   EntryID int unsigned auto_increment not null,
   PersonID int unsigned not null,
   EventTypeID int unsigned not null,
   EventDescription text not null,
   EventURL text default null,
   RemoteIP varchar(15),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EntryID
   ),
   INDEX usrl_PersonID_EventTypeID_ModDate (
      PersonID,
      EventTypeID,
      _ModDate
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrl_l` (
   _RecordID int unsigned not null auto_increment,
   EntryID int unsigned  not null,
   PersonID int unsigned not null,
   EventTypeID int unsigned not null,
   EventDescription text not null,
   EventURL text default null,
   RemoteIP varchar(15),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EntryID
   ),
   INDEX usrl_l_PersonID_EventTypeID_ModDate (
      PersonID,
      EventTypeID,
      _ModDate
   )
) TYPE=InnoDB;
