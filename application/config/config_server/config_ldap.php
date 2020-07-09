<?php

/**
 * Active Directory Configuration Page
 * @date 23-11-2017
 * @version 1.0
 * @copyright 2017, CODEPOINT Softwares Pvt. Ltd.
 */

################################################################################
# Active Directory Error Mail Settings

/**
 * [CONSTANT VAR] Whether to send error emails in case of failures
 * @global boolean
 */
define("_LDAP_SEND_ERROR_EMAIL", FALSE);

/**
 * [CONSTANT VAR] The email address to which error emails of ldap sync shall be send
 * @global string
 */
define("_LDAP_ERROR_EMAIL", "itisjoby@gmail.com");


################################################################################
# Active Directory Server Settings

/**
 * [CONSTANT VAR] The name or IP address of Active Directory Host Server
 * @global string
 */
define("_LDAP_HOST","host address here");

/**
 * [CONSTANT VAR] The username authorized to retrieve information from Active Directory Host Server
 * @global string
 */
define("_LDAP_USERNAME","server");

/**
 * [CONSTANT VAR] The password of the user connecting to Active Directory
 * @global string
 */
define("_LDAP_PASSWORD","123456");

/**
 * [CONSTANT VAR] Set this to true if you need to by-pass the active directory authentication (not secure)
 * @global boolean
 */
define("_LDAP_BYPASS_AUTHENTICATION",false);

//	To store the tree structure setup in Active Directory from top-level to bottom-level order till the parent of Branches
$branch_tree_structure	=	array(	'DC=local',
                                        'DC=handshake'
                                );

/**
 * [CONSTANT VAR] The domain name of Active Directory
 * @global string
 */
define("_LDAP_DOMAIN_NAME",'handshake');

/**
 * [CONSTANT VAR] Set the mode of AD to be synced.
 * 'structured' - traversing and fetching of user will be done only thru assigned tree structure
 * 'plain' - all users under the final node in 'branch structure' will be fetched
 * @global string
 */
define('_LDAP_TREE_TYPE','plain');

/**
 * [CONSTANT VAR] The key type that holds the branches list
 * @global string
 */
define('_LDAP_BRANCH_TYPE','OU');

/**
 * [CONSTANT VAR] If to sync branch in system. 
 * If true, then the tree structure should be ending just above the branch nodes.
 * Works only if LDAP_TREE_TYPE is set to structured
 * @global string
 */
define('_LDAP_SYNC_BRANCHES',FALSE);

//	To store the tree structure setup in Active Directory from branch-level to bottom-level order till the parent of User
//	Works only if LDAP_TREE_TYPE is set to structured
$user_tree_structure	=	array(
							);

/**
 * [CONSTANT VAR] The key type that holds the branches list
 * @global string
 */
define('_LDAP_USER_TYPE','CN');

/**
 * [CONSTANT VAR] To filter only the users from specific member groups (Comma separated list to check in multiple groups)
 * @global string
 */
define('_LDAP_USER_MEMBER_GROUP','');

/**
 * [CONSTANT VAR] The key type that holds the member group
 * @global string
 */
define('_LDAP_USER_MEMBER_GROUP_TYPE','CN');

/**
 * [CONSTANT VAR] If set to a value other than '', the group will be checked by prefixing either the parent name or by prefixing the branch name
 * Possible values can be '','branch', or 'parent'
 * @global string
 */
define('_LDAP_PREFIX_TYPE_FOR_USER_MEMBER_GROUP','');


//**************************	Do not edit beyond this	************************

################################################################################
# Active Directory System Settings

/**
 * [CONSTANT VAR] The comma separated tree list for Branches
 * @global string
 */
define("_LDAP_BRANCH_TREE", implode(',', array_reverse($branch_tree_structure)));

/**
 * [CONSTANT VAR] The comma separated tree list from Branches to User
 * @global string
 */
define("_LDAP_USER_TREE", implode(',', $user_tree_structure));

################################################################################
# Active Directory Microsoft Settings

//	To store the list of account types that are for enabled accounts in Active Directory
$enabled_account_list	=	array(	512,	//	NORMAL_ACCOUNT
									544,	//	Enabled, Password Not Required
									66048,	//	Enabled, Password Doesn’t Expire
									262656,	//	Enabled, Smartcard Required
									262688,	//	Enabled, Smartcard Required, Password Not Required
									328192,	//	Enabled, Smartcard Required, Password Doesn't Expire
									328224	//	Enabled, Smartcard Required, Password Doesn't Expire & Not Required
							);

/**
 * [CONSTANT VAR] The list of values for AD account enabled status
 * @global string
 */
define('_LDAP_ENABLED_ACCOUNT_STATUS_NUMBERS', implode(',', $enabled_account_list));
