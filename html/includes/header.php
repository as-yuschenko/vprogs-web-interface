<?php

define    ("ACT_ARM",             1);
define    ("ACT_DISARM",           2);
define    ("ACT_ON",               3);
define    ("ACT_OFF",              4);
define    ("ACT_AUTO_ON",          5);
define    ("ACT_AUTO_OFF",         6);
define    ("ACT_ASTP_RESET",       7);
define    ("ACT_ASTP_START",       8);
define    ("ACT_LOOP_CNTRL_OFF",   9);
define    ("ACT_LOOP_CNTRL_ON",    10);
define    ("ACT_READ_STATE",       11);
define    ("ACT_READ_CNTR",        12);
define    ("ACT_READ_VOLT",        13);
define    ("ACT_READ_TEMP",        14);

define    ("ETYPE_ZONE",           1);
define    ("ETYPE_PART",           2);
define    ("ETYPE_RELAY",          3);
define    ("ETYPE_COUNTER",        4);
define    ("ETYPE_VOLTAGE",        5);
define    ("ETYPE_TEMP",           6);

//echo ACT_READ_STATE;

//settings
define    ("PORT_ADD",              1);
define    ("PPDEV_ADD",              2);
define    ("PPDEV_SHOW_SETTINGS",    3);




define    ("PPDEV_UPDATE_PARTS",    101);
define    ("PPDEV_UPDATE_ZONES",    102);
define    ("PPDEV_UPDATE_RELAYS",   103);
define    ("PPDEV_UPDATE_USERS",    104);



//telegram
define    ("BOT_ADD",              1);
define    ("BOT_RM",          	   2);
define    ("BOT_EDIT",             3);
define    ("BOT_USER_ADD",         4);
define    ("BOT_USER_RM",          5);
define    ("BOT_USER_EDIT",        6);

?>
