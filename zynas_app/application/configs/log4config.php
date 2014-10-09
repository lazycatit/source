<?php
Logger::configure(
array(
'appenders' => array(
'default' => array(
'class' => 'LoggerAppenderDailyFile',
'layout' => array(
'class' => 'LoggerLayoutPattern',
'params' => array(
'conversionPattern' => '%date{Y-m-d H:i:s,u} [%t] %p %c %m %n'
)
),
'params' => array(
'datePattern' => 'Y-m-d',
'file' => APPLICATION_PATH.'/var/log/daily-%s.log',
),
),
),
'rootLogger' => array(
'appenders' => array('default'),
),
)
);