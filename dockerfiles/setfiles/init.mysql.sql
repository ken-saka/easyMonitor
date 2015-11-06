create table tbl_account (
  login_id  int NOT NULL AUTO_INCREMENT,
  login     varchar(20),
  passwd    varchar(20),
  authority tinyint,
  PRIMARY KEY(login_id),
  INDEX(login)
);
INSERT INTO tbl_account
 VALUES (login_id, login="root", passwd="root", authority=0);

create table tbl_session (
  session   varchar(40),
  login_id  int NOT NULL,
  created   datetime,
  data      text,
  PRIMARY KEY(session),
  INDEX(session, login_id, created)
);

create table tbl_contact (
  contact_id  int NOT NULL AUTO_INCREMENT,
  login_id    int NOT NULL,
  contactType varchar(20),
  contactText varchar(256),
  notice      tinyint,
  PRIMARY KEY(contact_id),
  INDEX(login_id, contactText)
);

create table tbl_host (
  host_id    int NOT NULL AUTO_INCREMENT,
  hostname   varchar(255) UNIQUE,
  ipaddress  varchar(40),
  PRIMARY KEY(host_id),
  INDEX(hostname)
);
INSERT INTO tbl_host(hostname, ipaddress)
  VALUES('localhost', '127.0.0.1');
INSERT INTO tbl_host(hostname, ipaddress)
  VALUES('http-service01', '127.0.0.1');

create table tbl_monitor (
  monitor_id  int NOT NULL AUTO_INCREMENT,
  monitorName varchar(64) NOT NULL,
  monitorType varchar(20) NOT NULL,
  host_id     int NOT NULL,
  argument    text,
  timeout     tinyint,
  PRIMARY KEY(monitor_id),
  INDEX(monitorName, monitorType, host_id)
);
INSERT INTO tbl_monitor(monitorName, monitorType, host_id)
  VALUES ("local-PING", "PING", 1);
INSERT INTO tbl_monitor(monitorName, monitorType, host_id)
  VALUES ("local-HTTP", "HTTP", 1);
INSERT INTO tbl_monitor(monitorName, monitorType, host_id)
  VALUES ("serive01-PING", "PING", 2);
INSERT INTO tbl_monitor(monitorName, monitorType, host_id)
  VALUES ("service01-HTTP", "HTTP", 2);
  

create table tbl_alert (
  alert_id      int NOT NULL AUTO_INCREMENT,
  hostname      varchar(255) NOT NULL,
  occurDate     datetime NOT NULL,
  alertLevel_id tinyint NOT NULL,
  alertContent  varchar(255),
  checked       tinyint,
  PRIMARY KEY(alert_id),
  INDEX(occurDate, hostname, alertLevel_id, alertContent)
);

create table tbl_alertLevel (
  alertLevel_id tinyint NOT NULL,
  alertLevel    varchar(20),
  INDEX(alertLevel_id, alertLevel)
);

create table tbl_statistics (
  statistics_id  int NOT NULL AUTO_INCREMENT,
  monitor_id     int,
  data           int,
  dateTime       datetime,
  PRIMARY KEY(statistics_id),
  INDEX(monitor_id, dateTime)
);

