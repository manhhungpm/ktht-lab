<?php

define('CODE_SUCCESS', 0);
define('CODE_ERROR', 1);

define('MEDIA_PENDING', 0);

//lỗi vô hiệu đơn vị quản lý khi có alias đang hoạt động
define('CODE_ERROR_DISABLE_MANAGER_WHEN_BLACKWHITELIST_ACTIVE',3);


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
//define('DELETE',-1);

define("TYPE_WHITELIST",1);
define("TYPE_BLACKLIST",0);

//Action_log constant
define('LOGIN', 'Login');
define('LOGOUT', 'Logout');
define('ADD', 'Add');
define('UPDATE', 'Update');
define('DELETE', 'Delete');
define('STATISTIC','Statistic');

//Rent status
define("PAY",0);
define("BORROW",1);
define("WAIT_APPROVED",2);
define("DENY",3);
define("APPROVED",4);
