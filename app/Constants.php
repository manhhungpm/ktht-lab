<?php

define('CODE_SUCCESS', 0);
define('CODE_ERROR', 1);

//lỗi kích hoạt cấu hình khi loại tin đang vô hiệu
define('CODE_ERROR_ACTIVE_CONFIG_WHEN_SMS_TYPE_DISABLE', 2);

//lỗi vô hiệu loại tin khi cấu hình đang kích hoạt
define('CODE_ERROR_DISABLE_SMS_TYPE_WHEN_CONFIG_ACTIVE',3);

//lôi vô hiệu nhóm tin khi loại tin đang kích hoạt
define('CODE_ERROR_DISABLE_SMS_GROUP_WHEN_SMS_TYPE_ACTIVE',4);

define('SYSTEM_DOB', '2016-01-01 00:00:00');

define('MESSAGE_SUCCESS', 'success');
define('MESSAGE_ERROR', 'error');

define('SSO_WSDL', 'http://10.60.7.126:8660/passportv3/passportWS?wsdl');
define('DOMAIN_CODE', 'KGM_ANTISPAM_SMS');

define('WARNING_TOTAL_COUNT_CALLED', 5);
define('WARNING_TOTAL_COUNT_CONTENT', 5);

define('SELECT_All', -1);

define('INACTIVE', 0);

define('ACTIVE',1);
